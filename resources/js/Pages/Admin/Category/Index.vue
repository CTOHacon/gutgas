<template>
	<AdminLayout>
		<!-- categories table -->
		<div class="mb-3">
			<Link
				class="btn btn-primary"
				:href="route('admin.category.create') || ''"
			>
				Створити Категорію
			</Link>
		</div>
		<div class="table-responsive">
			<table class="table table-striped table-hover">
				<thead>
					<tr>
						<th>Назва</th>
						<th>Товари В Категорії</th>
						<th>Дії</th>
					</tr>
				</thead>
				<tbody>
					<CategoryTableCard
						v-for="category in categories"
						:category="category"
						:key="category.id"
					/>
				</tbody>
			</table>
		</div>
	</AdminLayout>
</template>

<script setup lang="ts">
import AdminLayout from '@/Layouts/AdminLayout.vue';
import useAuth from '@/modules/useAuth';
import CategoryTableCard from '@/AdminComponents/Category/CategoryTableCard.vue';
import { ICategoryTree } from '@/types/ICategoryTree';
import { toRefs, ref } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps<{
	categories: ICategoryTree[];
}>();
const { categories } = toRefs(props);
// sort categories by 'path' property
categories.value.sort((a, b) => a.path.localeCompare(b.path));
const { hasPermissions } = useAuth();

const search = ref('');
const onSearch = () => {};
</script>

<style scoped></style>
