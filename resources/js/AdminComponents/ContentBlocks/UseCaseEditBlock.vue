<template>
	<div>
		<div class="row">
			<div class="col-4 mb-3" v-for="(item, index) in items">
				<div class="card-body" :key="index">
					<div class="mb-1">
						<MediaFileUrlSelect class="mb-1" v-model="item.image" />
						<input
							class="form-control mb-1"
							v-model="item.title"
							placeholder="Заголовок"
						/>
						<textarea
							rows="5"
							class="form-control"
							v-model="item.description"
							placeholder="Опис"
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
			@click="
				items.push({
					image: '',
					title: '',
					description: ''
				})
			"
		>
			Add Item
		</button>
	</div>
</template>

<script setup lang="ts">
import { TUseCaseCardProps } from '@/types/TUseCaseCardProps';
import { toRef, watch } from 'vue';
import MediaFileUrlSelect from '../Media/MediaFileUrlSelect.vue';

const props = defineProps<{
	modelValue: TUseCaseCardProps[];
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
	'update:modelValue': (value: TUseCaseCardProps[]) => true
});
</script>

<style scoped></style>
