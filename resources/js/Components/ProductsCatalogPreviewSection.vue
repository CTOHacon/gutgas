<template>
	<div>
		<ProductsCatalogSectionBanner
			class="g-col-12 banner"
			:image="image"
			:title="title"
			:text="text"
			:featured-product="featuredProduct"
		>
		</ProductsCatalogSectionBanner>
		<div class="g-layout gap-0">
			<ProductCatalogCard
				v-for="(product, index) in products"
				:key="index"
				:product="product"
				class="g-col-4 g-large-mobile-col-12 product-card"
			/>
		</div>
	</div>
</template>

<script setup lang="ts">
import { TProductsCatalogPreviewSectionProps } from '@/types/TProductsCatalogPreviewSectionProps';
import ProductsCatalogSectionBanner from './ProductsCatalogSectionBanner.vue';
import ProductCatalogCard from './ProductCatalogCard.vue';

const props = defineProps<TProductsCatalogPreviewSectionProps>();
</script>

<style scoped lang="scss">
.banner {
	margin-right: -1px;
	border: 1px solid #000;
}
.product-card {
	margin-top: -1px;
	margin-right: -1px;
}
</style>
