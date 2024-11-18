<template>
	<AdminLayout>
		<div class="row">
			<div class="col-9">
				<ProductForm
					:form="form"
					:attributes="attributes"
					:categories="categories"
					:productsGroups="productsGroups"
					:requiredProductsGroups="requiredProductsGroups"
					:productFilters="productFilters"
				/>
			</div>
			<div class="col-3">
				<div class="sticky-top">
					<button
						class="btn btn-success w-100 mt-3"
						@click="onSubmitForm"
					>
						Створити Продукт
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
import { TProductsGroup } from '@/types/TProductsGroup';
import { useSaveShortcut } from '@/modules/useSaveShortcut';
import { TRequiredProductsGroup } from '@/types/TRequiredProductsGroup';
import { TProductFilter } from '@/types/TProductFilter';

const props = defineProps<{
	attributes: TAttribute[];
	categories: ICategoryTree[];
	productsGroups: TProductsGroup[];
	productFilters: TProductFilter[];
	requiredProductsGroups: TRequiredProductsGroup[];
}>();

const form = useForm<TProductForm>({
	name: '',
	sku: '',
	price: null,
	stock: null,
	description: '',
	media_file: null,
	category_id: null,
	sorting_index: 0,
	attributes: [],
	product_filter_values: [],
	products_groups: [],
	required_products_groups: []
});

const { addMessage } = useMessages();

const onSubmitForm = () => {
	form.clearErrors();
	form.post(route('admin.product.store'), {
		preserveScroll: true,
		onSuccess: () => {},
		onError: () => {
			Object.entries(form.errors).forEach(([key, value]) => {
				addMessage({
					type: 'error',
					text: value
				});
			});
		}
	});
};

useSaveShortcut(onSubmitForm);
</script>

<style scoped></style>
