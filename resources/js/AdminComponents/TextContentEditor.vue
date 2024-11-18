<template>
	<QuillEditor
		class="plain-content-ql-editor"
		content-type="html"
		@update:content="value => emit('update:modelValue', value)"
		:content="modelValue"
		:placeholder="placeholder"
		:toolbar="{
			container: [
				[{ header: [1, 2, 3, 4, 5, 6, false] }],
				['bold'],
				[{ list: 'ordered' }, { list: 'bullet' }],
				['clean'],
				[
					{
						mbClass: [
							'mb-20',
							'mb-24',
							'mb-32',
							'mb-40',
							'mb-48',
							false
						]
					}
				]
			]
		}"
	/>
</template>

<script setup lang="ts">
import { Quill, QuillEditor } from '@vueup/vue-quill';

const Parchment = Quill.import('parchment');
const MarginBottom = new Parchment.Attributor.Attribute('mbClass', 'class', {
	scope: Parchment.Scope.BLOCK
});
Quill.register(MarginBottom, true);

const props = defineProps<{
	modelValue: string;
	placeholder?: string;
}>();

const emit = defineEmits({
	'update:modelValue': (value: string) => true
});
</script>

<style lang="scss">
.ql-mbClass.ql-picker {
	.ql-picker-label {
		&::before {
			content: 'Margin bottom ' attr(data-value);
			margin-right: 1rem;
		}
	}
	.ql-picker-item {
		&::before {
			content: attr(data-value);
		}
		&:last-child::before {
			content: 'No margin bottom';
		}
	}
}
</style>
