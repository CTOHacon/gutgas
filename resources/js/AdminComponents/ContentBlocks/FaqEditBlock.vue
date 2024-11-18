<template>
	<div>
		<div class="row">
			<div class="col-4 mb-3" v-for="(faqItem, index) in items">
				<div class="card-body" :key="index">
					<div class="mb-1">
						<input
							class="form-control mb-1"
							v-model="faqItem.question"
							placeholder="Питання"
						/>
						<textarea
							class="form-control"
							v-model="faqItem.answer"
							placeholder="Відповідь"
						/>
						<button
							class="btn btn-danger mt-1"
							@click="items.splice(index, 1)"
						>
							Видалити
						</button>
					</div>
				</div>
			</div>
		</div>
		<button
			class="btn btn-primary"
			@click="items.push({ question: '', answer: '' })"
		>
			Додати Елемент
		</button>
	</div>
</template>

<script setup lang="ts">
import { TFaqItemProps } from '@/types/TFaqItemProps';
import { toRef, watch } from 'vue';

const props = defineProps<{
	modelValue: TFaqItemProps[];
}>();

const items = toRef(props, 'modelValue');

watch(
	() => items.value,
	value => {
		emit('update:modelValue', value);
	},
	{ deep: true }
);

const emit = defineEmits({
	'update:modelValue': (value: TFaqItemProps[]) => true
});
</script>

<style scoped></style>
