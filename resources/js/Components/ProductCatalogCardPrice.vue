<template>
	<p class="price">
		<span class="old-price" v-if="hasDiscount">
			₴{{ product.old_price }}
		</span>
		<span class="current-price" :class="{ 'color-danger': hasDiscount }">
			₴{{ product.price }}
		</span>
	</p>
</template>

<script setup lang="ts">
import { TProduct } from '@/types/TProduct';
import { computed } from 'vue';

const props = defineProps<{
	product: TProduct;
}>();

const hasDiscount = computed(() => {
	return props.product.old_price && props.product.old_price > 0;
});
</script>

<style scoped lang="scss">
.price {
	font-size: 1rem;
	line-height: 1.5rem;
	letter-spacing: 0.05em;
	font-weight: 700;
	text-align: center;
	display: flex;
	flex-direction: column;
	align-items: center;
}
.old-price {
	position: relative;
	display: block;
	font-size: 0.875rem;
	line-height: 100%;
	color: #838d97;
	width: fit-content;
	&::before {
		content: '';
		background: #fff;
		height: max(0.125rem, 2px);
		position: absolute;
		left: -0.1875rem;
		right: -0.1875rem;
		top: calc(50% - max(0.125rem, 2px) / 2);
	}
}
</style>
