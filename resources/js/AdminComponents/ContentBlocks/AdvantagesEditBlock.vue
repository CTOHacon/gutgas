<template>
	<div class="row">
		<div class="col-4 mb-3" v-for="(advantageItem, index) in items">
			<div class="card-body" :key="index">
				<div class="mb-1">
					<MediaFileUrlSelect
						class="mb-1"
						v-model="advantageItem.image"
					/>
					<input
						class="form-control mb-1"
						v-model="advantageItem.title"
						placeholder="Заголовок"
					/>
					<textarea
						class="form-control"
						v-model="advantageItem.description"
						placeholder="Опис"
					/>
				</div>
				<button
					class="btn btn-danger mt-1"
					@click="items.splice(index, 1)"
				>
					Видалити
				</button>
			</div>
		</div>
	</div>
	<button
		class="btn btn-primary"
		@click="
			items.push({
				title: '',
				description: '',
				image: ''
			})
		"
	>
		Додати Елемент
	</button>
</template>

<script setup lang="ts">
import { TAdvantageCardProps } from '@/types/TAdvantageCardProps';
import { toRef, watch } from 'vue';
import MediaFileUrlSelect from '../Media/MediaFileUrlSelect.vue';

const props = defineProps<{
	modelValue: TAdvantageCardProps[];
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
	'update:modelValue': (value: TAdvantageCardProps[]) => true
});
</script>

<style scoped></style>
