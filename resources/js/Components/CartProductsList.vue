<template>
	<div class="products-list">
		<TransitionGroup name="card-fade">
			<ProductCartCard
				v-for="product in cartProducts"
				:key="product.id"
				:product="product"
			/>
		</TransitionGroup>
	</div>
</template>

<script setup lang="ts">
import useCart from '@/composables/cart';
import ProductCartCard from './ProductCartCard.vue';
const { cartProducts } = useCart();
</script>

<style scoped lang="scss">
.products-list {
	& > *:not(:last-child) {
		margin-bottom: 8px;
	}
	@media (max-width: 768px) {
		& > *:not(:last-child) {
			margin-bottom: 4px;
		}
	}
}
.card-fade {
	&-enter-active,
	&-leave-active {
		transition: transform 0.3s, opacity 0.3s;
	}
	&-leave-active {
		position: absolute;
	}
	&-enter-from,
	&-leave-to {
		opacity: 0;
		transform: scale(0.8);
	}
	&-enter-to,
	&-leave-from {
		opacity: 1;
		transform: scale(1);
	}
	&-move {
		transition: all 0.3s ease-in-out;
	}
}
</style>
