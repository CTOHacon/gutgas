<template>
	<section class="products-compair-section pt-116">
		<div class="container">
			<h2 class="fs-h2 mb-32" v-html="title" v-if="title" />
			<div class="fs-large description mb-72" v-html="subtitle" />
			<div class="g-layout">
				<ProductProsConsCard
					class="g-col-3 g-tablet-col-6 g-large-mobile-col-12"
					v-for="(item, index) in items"
					:key="index"
					:title="item.title"
					:image="item.image"
					:logo="item.logo"
					:advantages="item.advantages"
					:disadvantages="item.disadvantages"
					:recommended="item.recommended"
					:discouraged="item.discouraged"
				/>
			</div>
		</div>
	</section>
</template>

<script setup lang="ts">
import { TProductProsConsCardProps } from '@/types/TProductProsConsCardProps';
import ProductProsConsCard from './ProductProsConsCard.vue';

const props = defineProps<{
	title?: string;
	subtitle?: string;
	items?: TProductProsConsCardProps[];
}>();
</script>

<style scoped lang="scss">
.g-layout {
	gap: 0;
	width: calc(100% + 1px);
	& > * {
		margin-right: -1px;
		margin-bottom: -1px;
	}
}
.products-compair-section {
	position: relative;
	&::before {
		content: '';
		position: absolute;
		top: 0;
		left: 0;
		right: 0;
		height: 59.6875rem;
		background-image: url('@/assets/products-compair/background.png');
		background-size: cover;
		background-position: center;
		// opacity: 0.8;
		// mix-blend-mode: difference;
		z-index: 0;
	}
}
.container {
	position: relative;
}
.description {
	max-width: 712px;
}
</style>
