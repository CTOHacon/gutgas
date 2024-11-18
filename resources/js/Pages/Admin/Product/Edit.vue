<template>
	<AdminLayout>
		<div class="row">
			<div class="col-9">
				<ProductForm
					:form="generalForm"
					:attributes="attributes"
					:categories="categories"
					:productsGroups="productsGroups"
					:requiredProductsGroups="requiredProductsGroups"
					:productFilters="productFilters"
				/>
			</div>
			<div class="col-3">
				<div class="sticky-top">
					<div class="p-2 bg-light">
						<ProductManageNavigation :product="product" />
					</div>
					<button
						class="btn btn-success w-100 mt-3"
						@click="onSubmitGeneralForm"
					>
						Зберегти Загальні Дані про Продукт
					</button>
				</div>
			</div>
		</div>
	</AdminLayout>
</template>

<script setup lang="ts">
import ProductForm from '@/AdminComponents/Product/ProductForm.vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import useMessages from '@/modules/useMessages';
import { TAttribute } from '@/types/TAttribute';
import { ICategoryTree } from '@/types/ICategoryTree';
import { TProduct, TProductForm } from '@/types/TProduct';
import { useForm } from '@inertiajs/vue3';
import ProductManageNavigation from '@/AdminComponents/Product/ProductManageNavigation.vue';
import { TProductsGroup } from '@/types/TProductsGroup';
import { useSaveShortcut } from '@/modules/useSaveShortcut';
import { TRequiredProductsGroup } from '@/types/TRequiredProductsGroup';
import { TProductFilter } from '@/types/TProductFilter';
import { TProductFilterValue } from '@/types/TProductFilterValue';

const props = defineProps<{
	product: TProduct;
	productAttributes: TAttribute[];
	attributes: TAttribute[];
	categories: ICategoryTree[];
	productFilters: TProductFilter[];
	selectedProductFilterValues: TProductFilterValue[];
	productsGroups: TProductsGroup[];
	requiredProductsGroups: TRequiredProductsGroup[];
}>();

const { _t } = useTranslations();

const { addMessage } = useMessages();

const generalForm = useForm<TProductForm>({
	name: _t(props.product.name),
	sku: props.product.sku,
	price: props.product.price,
	stock: props.product.stock,
	description: _t(props.product.description),
	media_file: props.product.media_file,
	sorting_index: props.product.sorting_index,
	category_id: props.product.category_id,
	attributes: props.productAttributes.map(attribute => attribute.id),
	product_filter_values: props.selectedProductFilterValues.map(
		productFilterValue => productFilterValue.id
	),
	products_groups: props.product.products_groups
		? props.product.products_groups.map(productsGroup => productsGroup.id)
		: [],
	required_products_groups: props.product.required_products_groups
		? props.product.required_products_groups.map(
				requiredProductsGroup => requiredProductsGroup.id
		  )
		: []
});

const onSubmitGeneralForm = () => {
	generalForm.clearErrors();
	generalForm.put(route('admin.product.update', props.product.id), {
		preserveScroll: true,
		onSuccess: () => {},
		onError: () => {
			Object.entries(generalForm.errors).forEach(([key, value]) => {
				addMessage({
					type: 'error',
					text: value
				});
			});
		}
	});
};

useSaveShortcut(onSubmitGeneralForm);
</script>

<style scoped></style>
