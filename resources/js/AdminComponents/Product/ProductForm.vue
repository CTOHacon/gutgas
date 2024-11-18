<template>
	<div class="row g-1 mb-3">
		<div class="col-8">
			<FormFieldWrapper class="h-100" label="Про Товар">
				<div class="row g-2">
					<div class="col-4">
						<label class="form-label">Зображення Каталога</label>
						<MediaFileSelect v-model="form.media_file" />
					</div>
					<div class="col-8">
						<div class="mb-2">
							<label class="form-label">Назва У Каталозі</label>
							<input
								class="form-control fs-4"
								v-model="form.name"
								placeholder="Назва"
							/>
							<FormError :error="form.errors.name" />
						</div>
						<label class="form-label">Короткий Опис</label>
						<textarea
							class="form-control"
							v-model="form.description"
							placeholder="Опис"
						></textarea>
					</div>
				</div>
			</FormFieldWrapper>
		</div>
		<div class="col-4">
			<FormFieldWrapper class="h-100" label="Менеджмент">
				<div class="mb-2">
					<label class="form-label">SKU(Артикул)</label>
					<input
						class="form-control fs-4"
						v-model="form.sku"
						placeholder="SKU(Артикул)"
					/>
					<FormError :error="form.errors.sku" />
				</div>
				<div class="mb-2">
					<label class="form-label">Ціна (грн)</label>
					<input
						class="form-control"
						v-model="form.price"
						placeholder="Ціна"
					/>
					<FormError :error="form.errors.price" />
				</div>
				<div class="">
					<label class="form-label">Кількість У Наявності</label>
					<input
						class="form-control"
						v-model="form.stock"
						placeholder="0"
					/>
					<FormError :error="form.errors.stock" />
				</div>
			</FormFieldWrapper>
		</div>
	</div>
	<!-- <FormFieldWrapper label="Категорія" class="mb-3">
		<CategorySelect
			class="mb-4"
			:categories="categories"
			v-model="form.category_id"
		/>
	</FormFieldWrapper> -->
	<FormFieldWrapper label="Атрибути" class="mb-3">
		<AttributeMultiSelect
			:attributes="attributes"
			v-model="form.attributes"
		/>
	</FormFieldWrapper>
	<FormFieldWrapper label="Фільтри" class="mb-3">
		<div class="mb-3">
			<label class="form-label">Піоритет Відображення у Каталозі</label>
			<input
				class="form-control"
				v-model="form.sorting_index"
				placeholder="Піорітет Відображення у Каталозі"
			/>
			<FormError :error="form.errors.sorting_index" />
		</div>
		<ProductFiltersSelect
			:productFilters="productFilters"
			v-model="form.product_filter_values"
		/>
		<FormError :error="form.errors.product_filter_values" />
	</FormFieldWrapper>
	<FormFieldWrapper
		label="Категорії"
		class="mb-3"
		v-if="productsGroups.length"
	>
		<ProductsGroupMultiSelect
			:productsGroups="productsGroups"
			v-model="form.products_groups"
		/>
	</FormFieldWrapper>
	<FormFieldWrapper
		label="Рекомендовані Комплектації"
		class="mb-3"
		v-if="requiredProductsGroups.length"
	>
		<RequiredProductsGroupMultiSelect
			:requiredProductsGroups="requiredProductsGroups"
			v-model="form.required_products_groups"
		/>
	</FormFieldWrapper>
</template>

<script setup lang="ts">
import { TAttribute } from '@/types/TAttribute';
import { ICategoryTree } from '@/types/ICategoryTree';
import { InertiaForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import AttributeMultiSelect from '../Attribute/AttributeMultiSelect.vue';
import MediaFileSelect from '../Media/MediaFileSelect.vue';
import FormError from '../FormError.vue';
import { TProductForm } from '@/types/TProduct';
import FormFieldWrapper from '../FormFieldWrapper.vue';
import { TProductsGroup } from '@/types/TProductsGroup';
import ProductsGroupMultiSelect from '../ProductsGroup/ProductsGroupMultiSelect.vue';
import { TRequiredProductsGroup } from '@/types/TRequiredProductsGroup';
import RequiredProductsGroupMultiSelect from '../RequiredProductsGroup/RequiredProductsGroupMultiSelect.vue';
import { TProductFilter } from '@/types/TProductFilter';
import ProductFiltersSelect from '../ProductFilter/ProductFiltersSelect.vue';

const editMode = ref<'general' | 'page' | 'seo'>('general');
const props = defineProps<{
	form: InertiaForm<TProductForm>;
	attributes: TAttribute[];
	categories: ICategoryTree[];
	productFilters: TProductFilter[];
	productsGroups: TProductsGroup[];
	requiredProductsGroups: TRequiredProductsGroup[];
}>();
</script>

<style scoped lang="scss"></style>
