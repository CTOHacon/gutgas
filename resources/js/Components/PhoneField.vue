<template>
	<div class="phone-field" @click.self="inputRef?.focus()">
		<PhoneCountrySelect
			class="country"
			:model-value="country"
			@update:model-value="onCountryChange"
		/>
		<input
			ref="inputRef"
			class="input"
			type="tel"
			inputmode="tel"
			autocomplete="tel-national"
			:placeholder="example"
			:value="national"
			@input="onInput"
		/>
	</div>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { usePage } from '@inertiajs/vue3';
import PhoneCountrySelect from './PhoneCountrySelect.vue';
import usePhoneNumber from '@/composables/phoneNumber';
import { TPhoneCountry } from '@/utils/phoneCountries';

/** Compact phone input for the hero "call me back" pill. Same value format as BasePhoneInput. */
const props = defineProps<{
	modelValue: string;
}>();

const emit = defineEmits<{
	(e: 'update:modelValue', value: string): void;
}>();

const { country, national, example, onInput, setCountry } = usePhoneNumber({
	modelValue: () => props.modelValue,
	locale: () => (usePage().props.locale as string) ?? 'ua',
	emitValue: value => emit('update:modelValue', value)
});

const inputRef = ref<HTMLInputElement | null>(null);

const onCountryChange = (next: TPhoneCountry) => {
	setCountry(next);
	inputRef.value?.focus();
};
</script>

<style scoped lang="scss">
@import '@/styles/variables.scss';
.phone-field {
	padding: 0.61em 0.5em 0.61em 1.1em;
	display: flex;
	align-items: center;
	gap: 0.75em;
	cursor: text;
	min-width: 0;
	@media (max-width: $large-mobile-width) {
		gap: 0.5em;
	}
}
.country {
	flex-shrink: 0;
	padding-right: 0.75em;
	border-right: 1px solid rgba(131, 141, 151, 0.4);
	@media (max-width: $large-mobile-width) {
		padding-right: 0.5em;
	}
}
.input {
	flex: 1;
	min-width: 0;
	width: 100%;
	background: transparent;
	letter-spacing: 0.04em;
	&::placeholder {
		color: currentColor;
		opacity: 0.35;
	}
}
</style>
