<template>
	<div class="product-cart-card">
		<div class="head">
			<img class="image" :src="imageUrl" :alt="_t(product.name)" />
		</div>
		<div class="body">
			<h5 class="fs-medium mb-8 title">{{ _t(product.name) }}</h5>
			<div class="pricing-quantity-input">
				<BaseQuantityField
					:model-value="product.quantity"
					@update:model-value="setProductQuantity(product, $event)"
					class="quantity-controll color-secondary"
				/>
				<span class="price price-desktop">
					₴{{ product.quantity * product.price }}
				</span>
			</div>
			<div class="f- justify-between align-end">
				<span class="price price-mobile">
					₴{{ product.quantity * product.price }}
				</span>
				<button
					class="delete-button fs-medium color-danger mt-8"
					@click="removeProductFromCart(product)"
				>
					{{ __('delete') }}
				</button>
			</div>
		</div>
	</div>
</template>

<script setup lang="ts">
import useCart from '@/composables/cart';
import ICartProduct from '@/types/ICartProduct';
import BaseQuantityField from './BaseQuantityField.vue';
import getMediaFileUrl from '@/modules/helpers/getMediaFileUrl';
const props = defineProps<{ product: ICartProduct }>();
const imageUrl = props.product.media_file
	? getMediaFileUrl(props.product.media_file, 'thumbnail')
	: '';

const { __, _t } = useTranslations();

const { removeProductFromCart, setProductQuantity } = useCart();
</script>

<style scoped lang="scss">
.product-cart-card {
	border-radius: 8px;
	border: 1px solid rgba(183, 62, 56, 0.24);
	display: flex;
	align-items: center;
	overflow: hidden;
}
.head {
	position: relative;
	overflow: hidden;
	flex-basis: 140px;
	flex-shrink: 0;
	align-self: stretch;
	background: radial-gradient(
		45.83% 105.48% at 50% 100%,
		#000000 0%,
		rgba(0, 0, 0, 0.63) 58.85%,
		rgba(0, 0, 0, 0) 100%
	);
	margin-right: 8px;
	display: flex;
	align-items: flex-end;

	@media (max-width: 768px) {
		flex-basis: 100px;
	}
	@media (max-width: 350px) {
		margin-right: 0;
	}
}
.image {
	width: 100%;
}
.body {
	padding: 5px 12px;
	width: 100%;
}
.title {
	color: #838d97;
}
.price {
	font-weight: 700;
	margin-left: auto;
	display: block;
	@media (max-width: 768px) {
		margin-left: 0;
		margin-top: 4px;
	}
}
.price-desktop {
	@media (max-width: 768px) {
		display: none;
	}
}

.price-mobile {
	@media (min-width: 768px) {
		display: none;
	}
}

.delete-button {
	&:hover {
		text-decoration: underline;
	}
}
.pricing-quantity-input {
	display: flex;
	justify-content: space-between;
	align-items: center;
	@media (max-width: 768px) {
		font-size: 14px;
		flex-direction: column;
		align-items: flex-start;
	}
}
</style>
