<template>
	<AdminLayout>
		<div
			class="d-flex gap-1 flex-wrap p-2 bg-white my-1 sticky-top"
			style="margin-left: -0.5rem; margin-right: -0.5rem"
		>
			<Link
				class="btn btn-primary d-flex gap-2 align-items-center"
				:href="route('admin.product.create') || ''"
			>
				<div class="bi-plus-circle"></div>
				Створити Товар
			</Link>
			<SearchForm
				base-route="admin.product.index"
				search-route="admin.product.search"
			/>
		</div>

		<!-- products table -->
		<div class="table-responsive">
			<table class="table table-striped table-hover">
				<thead>
					<tr>
						<th>Пріоритет</th>
						<th>ID</th>
						<th>Img</th>
						<th>Назва</th>
						<th>SKU</th>
						<th>Price</th>
						<th>Stock</th>
						<th class="text-right">Дії</th>
					</tr>
				</thead>
				<tbody>
					<ProductTableCard
						v-for="product in products.data"
						:product="product"
					/>
				</tbody>
			</table>
		</div>
		<Pagination :data="products" />
	</AdminLayout>
</template>

<script setup lang="ts">
import Pagination from '@/AdminComponents/Pagination.vue';
import ProductTableCard from '@/AdminComponents/Product/ProductTableCard.vue';
import SearchForm from '@/AdminComponents/SearchForm.vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import useAuth from '@/modules/useAuth';
import { IPaginated } from '@/types/IPaginated';
import { TProduct } from '@/types/TProduct';
import { Link, router, useForm } from '@inertiajs/vue3';
import { toRefs, ref } from 'vue';

const props = defineProps<{
	products: IPaginated<TProduct>;
}>();
const { products } = toRefs(props);

const selectedProducts = ref<TProduct[]>([]);
const clearSelection = () => {
	selectedProducts.value = [];
};
</script>

<style scoped></style>
