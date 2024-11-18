<template>
	<div>
		<p class="mb-1 px-2 bg-light rounded">
			<small>
				{{ finalTree?.join(' > ') }}
			</small>
		</p>
		<div class="input-group">
			<div class="input-group-prepend">
				<input
					class="form-control"
					placeholder="Пошук"
					type="text"
					v-model="search"
				/>
			</div>
			<select class="form-select" @change="onChange">
				<option :value="null" :selected="modelValue === null">
					Без Батька
				</option>
				<option
					v-for="category in categories"
					:key="category.id"
					:value="category.id"
					:selected="modelValue === category.id"
				>
					{{ ' - '.repeat(category.depth) + category.name }}
				</option>
			</select>
		</div>
	</div>
</template>

<script setup lang="ts">
import { ICategoryTree } from '@/types/ICategoryTree';
import { computed } from 'vue';
import { ref, watch } from 'vue';

const props = defineProps<{
	categories: ICategoryTree[];
	modelValue: number | null;
}>();

const search = ref('');
const categories = computed(() =>
	[...props.categories]
		.sort((a, b) => a.path.localeCompare(b.path))
		.filter(category => {
			if (!search.value) return true;
			return category.name
				.toLowerCase()
				.includes(search.value.toLowerCase());
		})
);

const selectedCategory = computed(() => {
	if (!props.modelValue) return null;
	return props.categories.find(category => category.id === props.modelValue);
});

const finalTree = computed(() => {
	if (!selectedCategory.value) return null;
	const path = selectedCategory.value.path.split('.');
	return path.map(id => {
		const category = props.categories.find(category => category.id === +id);
		return category?.name;
	});
});

const onChange = (event: Event) => {
	const target = event.target as HTMLSelectElement;
	emit('update:modelValue', target.value === '' ? null : +target.value);
};

const emit = defineEmits({
	'update:modelValue': (value: number | null) => true
});
</script>

<style scoped></style>
