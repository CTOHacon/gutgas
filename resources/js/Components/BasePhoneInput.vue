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
			<PhoneCountrySelect
				class="country"
				:model-value="country"
				:locale="locale"
				@update:model-value="onCountryChange"
			/>
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
import { ref } from 'vue';
import { usePage } from '@inertiajs/vue3';
import BaseFormField from './BaseFormField.vue';
import PhoneCountrySelect from './PhoneCountrySelect.vue';
import usePhoneNumber from '@/composables/phoneNumber';
import { TPhoneCountry } from '@/utils/phoneCountries';

/**
 * Form phone field with a flag/country-code picker. Emits "+<dial> <national>",
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
	/** ISO code of the picked country; fires on mount and on every change. */
	(e: 'country', code: string): void;
}>();

const { country, national, isComplete, onInput, setCountry } = usePhoneNumber({
	modelValue: () => props.modelValue,
	locale: () => props.locale ?? (usePage().props.locale as string) ?? 'ua',
	emitValue: value => emit('update:modelValue', value),
	emitCountry: code => emit('country', code)
});

const isFocused = ref(false);
const inputRef = ref<HTMLInputElement | null>(null);

const onCountryChange = (next: TPhoneCountry) => {
	setCountry(next);
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
	flex: 0 0 $country-width;
	box-sizing: border-box;
	padding-left: $form-field-padding-horizontal;
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
.input {
	flex: 1;
	min-width: 0;
	padding: $form-field-padding-vertical $form-field-padding-horizontal
		$form-field-padding-vertical $input-gap;
	display: block;
}
</style>
