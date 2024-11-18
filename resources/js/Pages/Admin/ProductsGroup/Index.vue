<template>
	<AdminLayout>
		<!-- categories table -->
		<div class="mb-3">
			<Link
				class="btn btn-primary"
				:href="route('admin.productsGroup.create') || ''"
			>
				Створити Категорію
			</Link>
		</div>
		<div class="table-responsive">
			<table class="table table-striped table-hover">
				<thead>
					<tr>
						<th>Назва</th>
						<th>Відображати в меню</th>
						<th class="text-right">Дії</th>
					</tr>
				</thead>
				<tbody>
					<ProductsGroupTableCard
						v-for="productsGroup in productsGroups"
						:productsGroup="productsGroup"
						:key="productsGroup.id"
					/>
				</tbody>
			</table>
		</div>
	</AdminLayout>
</template>

<script setup lang="ts">
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { toRefs, ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import { TProductsGroup } from '@/types/TProductsGroup';
import ProductsGroupTableCard from '@/AdminComponents/ProductsGroup/ProductsGroupTableCard.vue';

const props = defineProps<{
	productsGroups: TProductsGroup[];
}>();
const { productsGroups } = toRefs(props);
</script>

<style scoped></style>
