<template>
	<div>
		<FormFieldWrapper
			class="mb-1"
			:key="index"
			v-for="(item, index) in items"
		>
			<div class="row">
				<div class="col-3">
					<MediaFileUrlSelect
						class="mb-1"
						v-model="item.image"
						placeholder="Зображення"
					/>
				</div>
				<div class="col-9">
					<input
						class="form-control mb-1"
						v-model="item.title"
						placeholder="Заголовок"
					/>
					<QuillEditor
						class="mb-1 h-auto"
						:toolbar="{
							container: [['bold']]
						}"
						content-type="html"
						:content="item.text"
						@update:content="value => (item.text = value)"
					/>
					<label for="">ID Рекомендованого Продукту</label>
					<input
						class="form-control"
						v-model="item.featuredProduct"
						placeholder="ID Рекомендованого Продукту"
					/>
				</div>
			</div>
			<label for="">ID продуктів розділені з ";"</label>
			<input
				class="form-control mb-1"
				:value="item.products.join('; ')"
				@change="(event: Event) => {
                        item.products = 
						(event.target as HTMLInputElement).value
							.split(';')
							.map((id: string) => parseInt(id))
							.filter(id => !isNaN(id));
                    }"
				placeholder="23; 54; 1; 2..."
			/>

			<button class="btn btn-danger mt-1" @click="items.splice(index, 1)">
				Видалити
			</button>
		</FormFieldWrapper>
	</div>
	<button
		class="btn btn-primary mt-1"
		@click="
			items.push({
				title: '',
				text: '',
				image: '',
				products: [],
				featuredProduct: null
			})
		"
	>
		Додати Секцію
	</button>
</template>

<script setup lang="ts">
import { TProductsCatalogPreviewSectionStoreData } from '@/types/TProductsCatalogPreviewSectionProps';
import { QuillEditor } from '@vueup/vue-quill';
import { toRef } from 'vue';
import { watch } from 'vue';
import MediaFileUrlSelect from '../Media/MediaFileUrlSelect.vue';
import FormFieldWrapper from '../FormFieldWrapper.vue';

const props = defineProps<{
	modelValue: TProductsCatalogPreviewSectionStoreData[];
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
	'update:modelValue': (value: TProductsCatalogPreviewSectionStoreData[]) =>
		true
});
</script>

<style scoped></style>
