<template>
	<div class="row g-2">
		<div
			class="col-4"
			v-for="(section, sectionIndex) in sections"
			:key="sectionIndex"
		>
			<div class="card mb-1">
				<div class="card-header">
					<div class="d-flex gap-1">
						<div class="flex-grow-1">
							<input
								type="text"
								placeholder="Заголовок"
								class="form-control"
								v-model="section.name"
							/>
						</div>
						<button
							class="btn btn-danger w-auto"
							@click="
								sections = sections.filter(i => i !== section)
							"
						>
							<i class="bi bi-trash"></i>
						</button>
					</div>
				</div>
				<div
					class="card-body"
					v-for="(item, itemIndex) in section.items"
				>
					<div class="d-flex gap-1">
						<div class="flex-grow-1">
							<input
								type="text"
								placeholder="Заголовок"
								class="form-control"
								v-model="item.title"
							/>
							<input
								type="text"
								placeholder="Посилання"
								class="form-control mt-1"
								v-model="item.url"
							/>
						</div>
						<button
							class="btn btn-danger w-auto"
							@click="
								sections[sectionIndex] = {
									...sections[sectionIndex],
									items: sections[sectionIndex].items.filter(
										i => i !== item
									)
								}
							"
						>
							<i class="bi bi-trash"></i>
						</button>
					</div>
				</div>
				<div class="card-footer">
					<button
						class="btn btn-primary mt-1"
						@click="
							sections[sectionIndex] = {
								...sections[sectionIndex],
								items: [
									...sections[sectionIndex].items,
									{
										title: '',
										url: ''
									}
								]
							}
						"
					>
						Додати Елемент
					</button>
				</div>
			</div>
		</div>
	</div>
	<button
		class="btn btn-success mt-1"
		@click="
			sections.push({
				name: '',
				items: [
					{
						title: '',
						url: ''
					}
				]
			})
		"
	>
		Додати Колонку
	</button>
</template>

<script setup lang="ts">
import { TFooterMenuSection } from '@/types/TFooterMenu';
import { toRef } from 'vue';
import { watch } from 'vue';

const props = defineProps<{
	modelValue: TFooterMenuSection[];
}>();

const sections = toRef(props.modelValue);

const emit = defineEmits({
	updateModelValue: (value: TFooterMenuSection[]) => true
});

watch(
	sections,
	value => {
		emit('updateModelValue', value);
	},
	{ deep: true }
);
</script>

<style scoped></style>
