<template>
	<tr>
		<td
			:style="{
				paddingLeft: category.depth * 2 + 1 + 'rem'
			}"
		>
			{{ category.name }}
		</td>
		<td>
			{{ category.products_count }}
		</td>
		<td>
			<div
				class="btn-group"
				v-if="hasPermissions('admin.category.manage')"
			>
				<Link
					class="btn btn-primary"
					:href="route('admin.category.edit', category.id) || ''"
				>
					Редагувати
				</Link>
				<Link
					class="btn btn-danger"
					:href="route('admin.category.destroy', category.id) || ''"
					method="delete"
					as="button"
					>Видалити
				</Link>
			</div>
		</td>
	</tr>
	<CategoryTableCard
		v-for="childrenCategory in category.children"
		:key="childrenCategory.id"
		:category="childrenCategory"
	/>
</template>

<script setup lang="ts">
import { ICategoryTree } from '@/types/ICategoryTree';
import useAuth from '@/modules/useAuth';
import { Link } from '@inertiajs/vue3';

const props = defineProps<{
	category: ICategoryTree;
}>();

const { hasPermissions } = useAuth();
</script>

<style scoped lang="scss"></style>
