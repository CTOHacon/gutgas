<template>
	<div class="simple-cart-products-list fw-600">
		<div
			class="f- align-center product lh-100"
			v-for="cartProduct in cartProducts"
		>
			<div class="price-picker">
				<button
					class="control-button"
					@click="
						setProductQuantity(
							cartProduct,
							cartProduct.quantity - 1
						)
					"
				>
					<svg
						width="24"
						height="24"
						viewBox="0 0 24 24"
						fill="none"
						xmlns="http://www.w3.org/2000/svg"
					>
						<path d="M15 11.25L12 14.25L9 11.25" fill="#838D97" />
						<path
							d="M15 11.25L12 14.25L9 11.25L15 11.25Z"
							stroke="#838D97"
							stroke-linecap="round"
							stroke-linejoin="round"
						/>
					</svg>
				</button>
				<strong class="ml-8 mr-8">{{ cartProduct.quantity }}</strong>
				<button
					class="control-button"
					@click="
						setProductQuantity(
							cartProduct,
							cartProduct.quantity + 1
						)
					"
					:disabled="cartProduct.quantity >= 999"
				>
					<svg
						width="24"
						height="24"
						viewBox="0 0 24 24"
						fill="none"
						xmlns="http://www.w3.org/2000/svg"
					>
						<path d="M15 12.75L12 9.75L9 12.75" fill="#838D97" />
						<path
							d="M15 12.75L12 9.75L9 12.75L15 12.75Z"
							stroke="#838D97"
							stroke-linecap="round"
							stroke-linejoin="round"
						/>
					</svg>
				</button>
			</div>
			<p class="product-name ml-8 mr-8 lh-100">
				{{ _t(cartProduct.name) }}
			</p>
			<span class="color-secondary mr-8"> ₴{{ cartProduct.price }} </span>
			<button class="lh-0" @click="removeProductFromCart(cartProduct)">
				<img
					src="@/assets/cart-trash.svg"
					alt="Видалити"
					class="ml-8 mr-8"
				/>
			</button>
		</div>
	</div>
</template>

<script setup lang="ts">
import useCart from '@/composables/cart';

const { cartProducts, removeProductFromCart, setProductQuantity } = useCart();

const { _t, __ } = useTranslations();
</script>

<style scoped lang="scss">
.product {
	font-size: 15px;
	&:not(:last-child) {
		margin-bottom: 13px;
	}
}
.product-name {
	flex: 1;
	text-overflow: ellipsis;
	padding-right: 20px;
	overflow: hidden;
	text-overflow: ellipsis;
	white-space: nowrap;
	font-family: Mulish;
	font-size: 15px;
}

.control-button {
	border-radius: 54px;
	border: 1px solid rgba(149, 149, 150, 0.24);
	line-height: 0;
	transition: border 0.2s ease-in-out, opacity 0.2s ease-in-out;
	path {
		transition: 0.3s;
	}
	&:hover {
		border: 1px solid #f24942;
		background: rgba($color: #f24942, $alpha: 0.14);
		path {
			fill: #f24942;
			stroke: #f24942;
		}
	}
	&:active {
		border: 1px solid rgba($color: #f24942, $alpha: 0.32);
	}
	&:disabled {
		border: 1px solid rgba(149, 149, 150, 0.24);
		opacity: 0.5;
	}
}
</style>
