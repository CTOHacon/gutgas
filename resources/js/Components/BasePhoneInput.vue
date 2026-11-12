<template>
	<BaseFormField
		class="base-phone-input"
		:error-message="errorMessage"
		:is-focused="isFocused"
		:is-valid="isValid ?? isComplete"
		:placeholder="placeholder"
		:value="national"
	>
		<div class="row">
			<label class="country" :title="countryName(country)">
				<span class="flag">{{ country.flag }}</span>
				<span class="dial">+{{ country.dial }}</span>
				<svg class="chevron" width="10" height="6" viewBox="0 0 10 6">
					<path
						d="M1 1l4 4 4-4"
						fill="none"
						stroke="currentColor"
						stroke-width="1.5"
						stroke-linecap="round"
						stroke-linejoin="round"
					/>
				</svg>
				<select
					class="country-select"
					:value="country.code"
					:aria-label="countryName(country)"
					@change="onCountryChange"
				>
					<option
						v-for="option in PHONE_COUNTRIES"
						:key="option.code"
						:value="option.code"
					>
						{{ option.flag }} {{ countryName(option) }} +{{ option.dial }}
					</option>
				</select>
			</label>
			<input
				ref="inputRef"
				class="input"
				type="tel"
				inputmode="tel"
				autocomplete="tel-national"
				:value="national"
				@input="onInput"
				@focus="isFocused = true"
				@blur="isFocused = false"
			/>
		</div>
	</BaseFormField>
</template>

<script setup lang="ts">
import { computed, ref, watch } from 'vue';
import { usePage } from '@inertiajs/vue3';
import BaseFormField from './BaseFormField.vue';
import {
	PHONE_COUNTRIES,
	TPhoneCountry,
	applyPhoneMask,
	defaultPhoneCountry,
	findPhoneCountry,
	matchDialCode,
	maxDigits,
	parsePhone
} from '@/utils/phoneCountries';

/**
 * Phone field with a flag/country-code picker. Emits "+<dial> <national>",
 * e.g. "+380 67 123 45 67"; the country defaults to the site language.
 */
const props = defineProps<{
	modelValue: string;
	placeholder: string;
	errorMessage?: string;
	/** Overrides the built-in "number looks complete" check. */
	isValid?: boolean;
	locale?: string;
}>();

const emit = defineEmits<{
	(e: 'update:modelValue', value: string): void;
}>();

const siteLocale = computed(
	() => props.locale ?? (usePage().props.locale as string) ?? 'ua'
);

const initial = parsePhone(
	props.modelValue ?? '',
	defaultPhoneCountry(siteLocale.value)
);
const country = ref<TPhoneCountry>(initial.country);
const digits = ref(initial.digits);
const isFocused = ref(false);
const inputRef = ref<HTMLInputElement | null>(null);

const national = computed(() => applyPhoneMask(digits.value, country.value.mask));
const isComplete = computed(() => digits.value.length >= country.value.min);

const composed = () =>
	digits.value ? `+${country.value.dial} ${national.value}` : '';

// external changes (form reset, prefill)
watch(
	() => props.modelValue,
	value => {
		if (value === composed()) return;
		const parsed = parsePhone(value ?? '', country.value);
		country.value = parsed.country;
		digits.value = parsed.digits.slice(0, maxDigits(parsed.country));
	}
);

const intlLocale: Record<string, string> = { ua: 'uk' };
const displayNames = computed(() => {
	try {
		return new Intl.DisplayNames(
			[intlLocale[siteLocale.value] ?? siteLocale.value, 'en'],
			{ type: 'region' }
		);
	} catch {
		return null;
	}
});
const countryName = (option: TPhoneCountry) =>
	displayNames.value?.of(option.code) ?? option.code;

const normalize = (raw: string) => {
	let next = raw.replace(/\D/g, '');

	// pasted "+48 ..." switches the country
	if (raw.trim().startsWith('+')) {
		const match = next.startsWith(country.value.dial)
			? country.value
			: matchDialCode(next);
		if (match) {
			country.value = match;
			next = next.slice(match.dial.length);
		}
	} else if (
		next.startsWith(country.value.dial) &&
		next.length > maxDigits(country.value)
	) {
		// "380671234567" typed without the plus
		next = next.slice(country.value.dial.length);
	}

	const trunk = country.value.trunk;
	if (trunk && next.startsWith(trunk)) next = next.slice(trunk.length);

	return next.slice(0, maxDigits(country.value));
};

const onInput = (event: Event) => {
	const target = event.target as HTMLInputElement;
	digits.value = normalize(target.value);
	// keep the DOM in sync when the formatted value is unchanged (e.g. a letter typed)
	target.value = national.value;
	emit('update:modelValue', composed());
};

const onCountryChange = (event: Event) => {
	country.value = findPhoneCountry((event.target as HTMLSelectElement).value);
	digits.value = digits.value.slice(0, maxDigits(country.value));
	emit('update:modelValue', composed());
	inputRef.value?.focus();
};
</script>

<style scoped lang="scss">
@import '@/styles/variables.scss';

$country-width: 8rem;
$input-gap: 0.25rem;

.base-phone-input :deep(.placeholder) {
	left: #{$country-width + $input-gap};
}
.row {
	display: flex;
	align-items: stretch;
}
.country {
	position: relative;
	flex: 0 0 $country-width;
	display: flex;
	align-items: center;
	gap: 0.375rem;
	padding-left: $form-field-padding-horizontal;
	box-sizing: border-box;
	cursor: pointer;
	white-space: nowrap;
	&::after {
		content: '';
		position: absolute;
		right: 0.5rem;
		top: 25%;
		bottom: 25%;
		width: 1px;
		background: rgba(131, 141, 151, 0.4);
	}
}
.flag {
	font-size: 1.125rem;
	line-height: 1;
}
.chevron {
	opacity: 0.6;
	flex-shrink: 0;
}
.country-select {
	position: absolute;
	inset: 0;
	width: 100%;
	opacity: 0;
	cursor: pointer;
	font-size: 1rem; // no iOS zoom on focus
	color: #000;
}
.input {
	flex: 1;
	min-width: 0;
	padding: $form-field-padding-vertical $form-field-padding-horizontal
		$form-field-padding-vertical $input-gap;
	display: block;
}
</style>
