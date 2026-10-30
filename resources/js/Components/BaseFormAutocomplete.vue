<template>
	<div class="autocomplete">
		<BaseFormField
			:error-message="errorMessage"
			:is-focused="isFocused"
			:is-valid="isValid"
			:placeholder="placeholder"
			:value="modelValue"
		>
			<input
				class="input"
				:value="modelValue"
				:disabled="disabled"
				autocomplete="off"
				@input="onInput"
				@focus="onFocus"
				@blur="onBlur"
			/>
		</BaseFormField>
		<ul class="options" v-if="isOpened && !disabled">
			<li class="option state" v-if="loading">
				{{ loadingLabel }}
			</li>
			<li
				class="option state"
				v-else-if="options.length === 0 && emptyLabel"
			>
				{{ emptyLabel }}
			</li>
			<li
				v-for="option in options"
				:key="option.key"
				class="option"
				@mousedown.prevent="onSelect(option)"
			>
				{{ option.label }}
			</li>
		</ul>
	</div>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import BaseFormField from './BaseFormField.vue';

export type TAutocompleteOption = {
	key: string;
	label: string;
};

const props = defineProps<{
	modelValue: string;
	placeholder: string;
	options: TAutocompleteOption[];
	loading?: boolean;
	disabled?: boolean;
	errorMessage?: string;
	isValid?: boolean;
	loadingLabel?: string;
	emptyLabel?: string;
}>();

const emit = defineEmits<{
	(e: 'update:modelValue', value: string): void;
	(e: 'select', option: TAutocompleteOption): void;
	(e: 'focus'): void;
}>();

const isFocused = ref(false);
const isOpened = ref(false);

const onInput = (event: Event) => {
	isOpened.value = true;
	emit('update:modelValue', (event.target as HTMLInputElement).value);
};

const onFocus = () => {
	isFocused.value = true;
	isOpened.value = true;
	emit('focus');
};

const onBlur = () => {
	isFocused.value = false;
	isOpened.value = false;
};

const onSelect = (option: TAutocompleteOption) => {
	isOpened.value = false;
	emit('select', option);
};
</script>

<style scoped lang="scss">
@import '@/styles/variables.scss';
.autocomplete {
	position: relative;
}
.input {
	padding: $form-field-padding-vertical $form-field-padding-horizontal;
	display: block;
	width: 100%;
}
.options {
	position: absolute;
	z-index: 10;
	top: 100%;
	left: 0;
	right: 0;
	max-height: 280px;
	overflow-y: auto;
	margin: 0;
	padding: 4px 0;
	list-style: none;
	border-radius: 0 0 8px 8px;
	background: #151515;
	border: 1px solid rgba(131, 141, 151, 0.24);
}
.option {
	padding: 10px $form-field-padding-horizontal;
	cursor: pointer;
	transition: background 0.2s ease-in-out;
	&:hover {
		background: rgba(241, 73, 66, 0.16);
	}
	&.state {
		cursor: default;
		opacity: 0.6;
		&:hover {
			background: none;
		}
	}
}
</style>
