<template>
	<div>
		<Link
			:href="route('admin.productsGroup.edit', productsGroup.id) || ''"
			type="button"
			class="btn w-100 mb-1"
			:class="{
				'btn-primary': route().current('admin.productsGroup.edit'),
				'btn-outline-primary': !route().current(
					'admin.productsGroup.edit'
				)
			}"
		>
			Редагувати Основну Інформацію
		</Link>
		<Link
			:href="
				route(
					'admin.seoEntity.forProductsGroup.edit',
					productsGroup.seo_entity.id
				) || ''
			"
			type="button"
			class="btn w-100"
			:class="{
				'btn-primary': route().current(
					'admin.seoEntity.forProductsGroup.edit'
				),
				'btn-outline-primary': !route().current(
					'admin.seoEntity.forProductsGroup.edit'
				)
			}"
		>
			Редагувати SEO
		</Link>
		<a
			v-if="productsGroup.seo_entity?.slug"
			class="btn btn-outline-primary w-100 mt-1"
			target="_blank"
			:href="`/` + productsGroup.seo_entity?.slug"
		>
			Переглянути
		</a>
		<!-- delete -->
		<button
			type="button"
			class="btn btn-danger w-100 mt-1"
			@click="deleteProductsGroup"
		>
			Видалити
		</button>
	</div>
</template>

<script setup lang="ts">
import { TProductsGroup } from '@/types/TProductsGroup';
import { Link, router } from '@inertiajs/vue3';

const props = defineProps<{
	productsGroup: TProductsGroup;
}>();

const deleteProductsGroup = () => {
	if (confirm('Are you sure you want to delete this?')) {
		router.delete(
			route('admin.productsGroup.destroy', props.productsGroup.id)
		);
	}
};
</script>

<style scoped></style>
