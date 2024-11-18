<template>
	<div>
		<Link
			:href="route('admin.product.edit', product.id) || ''"
			type="button"
			class="btn w-100 mb-1"
			:class="{
				'btn-primary': route().current('admin.product.edit'),
				'btn-outline-primary': !route().current('admin.product.edit')
			}"
		>
			Про Товар
		</Link>
		<Link
			:href="route('admin.productPage.edit', product.id) || ''"
			type="button"
			class="btn w-100 mb-1"
			:class="{
				'btn-primary': route().current('admin.productPage.edit'),
				'btn-outline-primary': !route().current(
					'admin.productPage.edit'
				)
			}"
		>
			Сторінка Товару
		</Link>
		<Link
			:href="
				route(
					'admin.seoEntity.forProductPage.edit',
					product.seo_entity?.id
				) || ''
			"
			type="button"
			class="btn w-100"
			:class="{
				'btn-primary': route().current(
					'admin.seoEntity.forProductPage.edit'
				),
				'btn-outline-primary': !route().current(
					'admin.seoEntity.forProductPage.edit'
				)
			}"
		>
			SEO Налаштування
		</Link>
		<a
			class="btn btn-outline-primary w-100 mt-1"
			target="_blank"
			:href="`/` + product.seo_entity?.slug"
		>
			Переглянути на сайті
		</a>
		<!-- delete -->
		<button
			type="button"
			class="btn btn-danger w-100 mt-1"
			@click="deleteProduct"
		>
			Видалити
		</button>
	</div>
</template>

<script setup lang="ts">
import { TProduct } from '@/types/TProduct';
import { Link, router } from '@inertiajs/vue3';

const props = defineProps<{
	product: TProduct;
}>();

const deleteProduct = () => {
	if (confirm('Are you sure you want to delete this product?')) {
		router.delete(route('admin.product.destroy', props.product.id));
	}
};
</script>

<style scoped></style>
