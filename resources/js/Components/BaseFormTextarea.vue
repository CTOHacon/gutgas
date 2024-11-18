<template>
	<BaseFormField
		:error-message="errorMessage"
		:is-focused="isFocused"
		:is-valid="isValid"
		:placeholder="placeholder"
		:value="modelValue"
		class="wrapper"
	>
		<textarea
			class="textarea"
			:value="modelValue"
			:rows="rows"
			@input="(event: any) => 
                emit('update:modelValue', event.target.value)
                "
			@focus="isFocused = true"
			@blur="isFocused = false"
		/>
	</BaseFormField>
</template>

<script setup lang="ts">
import BaseFormField from './BaseFormField.vue';

import { computed, ref } from 'vue';

const props = defineProps<{
	modelValue: string;
	placeholder: string;
	errorMessage?: string;
	isValid?: boolean;
	rows?: number;
}>();

const isFocused = ref(false);

const emit = defineEmits({
	'update:modelValue': (value?: string) => true
});
</script>

<style scoped lang="scss">
@import '@/styles/variables.scss';
.wrapper {
	height: 100%;
}
.textarea {
	padding: $form-field-padding-vertical $form-field-padding-horizontal;
	display: block;
	width: 100%;
	height: 100%;
	resize: none;
}
</style>
