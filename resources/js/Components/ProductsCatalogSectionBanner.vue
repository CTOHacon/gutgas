<template>
	<div
		class="products-catalog-section-banner background-semi-black g-layout-9"
		v-if="!featuredProduct"
	>
		<div
			class="f- justify-center align-self-end g-col-4 g-large-mobile-col-12"
		>
			<img :src="image" :alt="title" />
		</div>
		<div
			class="g-col-5 g-large-mobile-col-12 align-self-center pv-40 ph-24"
		>
			<h3 class="fs-h5">{{ title }}</h3>
			<div
				class="color-typography-secondary pv-24 lh-150 fw-300"
				v-html="text"
				v-if="text"
			/>
		</div>
	</div>
	<div
		class="products-catalog-section-banner background-semi-black g-layout-9"
		v-else
	>
		<div
			class="f- justify-center align-self-end g-col-3 g-large-mobile-col-12"
		>
			<img :src="image" :alt="title" />
		</div>
		<div
			class="g-col-3 g-large-mobile-col-12 align-self-center text-center pv-40 ph-24"
		>
			<h3 class="fs-h5">{{ title }}</h3>
			<div
				class="color-typography-secondary pv-24 lh-150 fw-300"
				v-html="text"
				v-if="text"
			/>
		</div>
		<div
			class="g-col-3 g-large-mobile-col-12 background-black featured-product-card"
		>
			<ProductCatalogCard
				class="h-100 no-border"
				type="featured"
				:product="featuredProduct"
			/>
		</div>
	</div>
</template>

<script setup lang="ts">
import { TProduct } from '@/types/TProduct';
import ProductCatalogCard from './ProductCatalogCard.vue';

const props = defineProps<{
	image: string;
	title: string;
	featuredProduct?: TProduct | null;
	text?: string;
}>();
</script>

<style scoped lang="scss">
.products-catalog-section-banner {
	gap: 0;
}
.featured-product-card {
	border-left: 1px solid #000;
}
</style>
