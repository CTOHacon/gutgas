<template>
	<BaseFormField
		:error-message="errorMessage"
		:is-focused="isFocused"
		:is-valid="isValid"
		:placeholder="placeholder"
		:value="modelValue"
		class="select-field"
	>
		<textarea
			class="textarea"
			:value="modelValue"
			@input="(event: any) => 
                emit('update:modelValue', event.target.value)
                "
			@focus="isFocused = true"
			@blur="isFocused = false"
		/>
	</BaseFormField>
</template>

<script setup lang="ts">
import { ref } from 'vue';

const props = defineProps<{
	modelValue: string;
	placeholder: string;
	errorMessage?: string;
	isValid?: boolean;
}>();

const isFocused = ref(false);

const emit = defineEmits({
	'update:modelValue': (value?: string) => true
});
</script>

<style scoped></style>
