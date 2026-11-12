<template>
	<label class="phone-country-select" :title="countryName(modelValue)">
		<span class="flag">{{ modelValue.flag }}</span>
		<span class="dial">+{{ modelValue.dial }}</span>
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
			class="native-select"
			:value="modelValue.code"
			:aria-label="countryName(modelValue)"
			@change="onChange"
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
</template>

<script setup lang="ts">
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import {
	PHONE_COUNTRIES,
	TPhoneCountry,
	findPhoneCountry
} from '@/utils/phoneCountries';

/**
 * Flag + dial code trigger over a native <select> (native picker on phones).
 * Country names are localized with Intl.DisplayNames.
 */
const props = defineProps<{
	modelValue: TPhoneCountry;
	locale?: string;
}>();

const emit = defineEmits<{
	(e: 'update:modelValue', value: TPhoneCountry): void;
}>();

const intlLocale: Record<string, string> = { ua: 'uk' };
const displayNames = computed(() => {
	const locale = props.locale ?? (usePage().props.locale as string) ?? 'ua';
	try {
		return new Intl.DisplayNames([intlLocale[locale] ?? locale, 'en'], {
			type: 'region'
		});
	} catch {
		return null;
	}
});
const countryName = (option: TPhoneCountry) =>
	displayNames.value?.of(option.code) ?? option.code;

const onChange = (event: Event) =>
	emit(
		'update:modelValue',
		findPhoneCountry((event.target as HTMLSelectElement).value)
	);
</script>

<style scoped lang="scss">
.phone-country-select {
	position: relative;
	display: flex;
	align-items: center;
	gap: 0.375rem;
	cursor: pointer;
	white-space: nowrap;
}
.flag {
	font-size: 1.125rem;
	line-height: 1;
}
.chevron {
	opacity: 0.6;
	flex-shrink: 0;
}
.native-select {
	position: absolute;
	inset: 0;
	width: 100%;
	opacity: 0;
	cursor: pointer;
	font-size: 1rem; // no iOS zoom on focus
	color: #000;
}
</style>
