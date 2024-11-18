<template>
	<BaseFormField
		:error-message="errorMessage"
		:is-focused="isFocused"
		:is-valid="isValid"
		:placeholder="placeholder"
		:value="modelValue"
	>
		<input
			v-if="!mask"
			class="input"
			:value="modelValue"
			@input="(event: Event) => {
				emit('update:modelValue', (event.target as HTMLInputElement).value);
			}"
			@focus="isFocused = true"
			@blur="isFocused = false"
			:type="type ? type : 'text'"
		/>
		<input
			v-else
			class="input"
			v-maska
			:data-maska="mask"
			data-maska-eager
			:value="modelValue"
			@input="(event: Event) => {
				emit('update:modelValue', (event.target as HTMLInputElement).value);
			}"
			@focus="isFocused = true"
			@blur="isFocused = false"
			:type="type ? type : 'text'"
		/>
	</BaseFormField>
</template>

<script setup lang="ts">
import BaseFormField from './BaseFormField.vue';

import { ref } from 'vue';
import { vMaska } from 'maska';

const props = defineProps<{
	modelValue: string;
	placeholder: string;
	errorMessage?: string;
	type?: string;
	mask?: string;
	isValid?: boolean;
}>();

const isFocused = ref(false);

const emit = defineEmits({
	'update:modelValue': (value?: string) => true
});
</script>

<style scoped lang="scss">
@import '@/styles/variables.scss';
.input {
	padding: $form-field-padding-vertical $form-field-padding-horizontal;
	display: block;
	width: 100%;
}
</style>
