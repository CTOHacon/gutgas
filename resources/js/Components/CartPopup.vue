<template>
	<div class="cart-popup" :class="{ empty: !cartProducts.length }">
		<div class="inner">
			<template v-if="cartProducts.length">
				<div class="f-row justify-between align-end mb-20">
					<h5 class="fs-h5">{{ __('cart') }}</h5>
					<span class="fs-small"
						>{{ cartProducts.length }} {{ __('products') }}</span
					>
				</div>
				<CartProductsList class="products-list">
					<TransitionGroup name="card-fade">
						<ProductCartCard
							v-for="product in cartProducts"
							:key="product.id"
							:product="product"
						/>
					</TransitionGroup>
				</CartProductsList>
				<div class="footer mt-24">
					<div class="">
						<small class="total-title fs-small">{{
							__('total')
						}}</small>
						<p class="fs-medium">
							<span class="total-ammount"> {{ cartTotal }} </span>
							<small class="total-currency">грн</small>
						</p>
						<button
							class="lh-100 underline color-danger fs-small mt-12"
							@click="emit('close')"
						>
							{{ __('continueShopping') }}
						</button>
					</div>
					<BaseButton
						@click="
							() => {
								emit('close');
								router.get(
									route('checkout', usePage().props.locale)
								);
							}
						"
						class="checkout-button"
					>
						{{ __('toCheckout') }}
					</BaseButton>
				</div>
			</template>
			<template v-else>
				<h2 class="fs-h5">{{ __('yourCartIsEmpty') }}</h2>
				<button
					class="lh-100 underline color-danger fs-small mt-12"
					@click="emit('close')"
				>
					{{ __('continueShopping') }}
				</button>
			</template>
		</div>
	</div>
</template>

<script setup lang="ts">
import useCart from '@/composables/cart';
import ProductCartCard from './ProductCartCard.vue';
import BaseButton from './BaseButton.vue';
import { router, usePage } from '@inertiajs/vue3';
import CartProductsList from './CartProductsList.vue';
const { cartProducts, cartTotal } = useCart();

const { __, _t } = useTranslations();

const emit = defineEmits({
	close: () => true
});
</script>

<style scoped lang="scss">
.cart-popup {
	padding: 30px 24px 40px 32px;
	border-radius: 8px;
	background: linear-gradient(
			135deg,
			rgba(236, 35, 71, 0.12) 0%,
			rgba(224, 46, 38, 0) 100%
		),
		#0f0f10;
	&.empty {
		padding: 24px;
		border-radius: 8px;
		text-align: center;
	}
	.inner {
		position: relative;
		z-index: 11;
	}
	z-index: 10;
}
.products-list {
	max-height: 295px;
	overflow-y: auto;
	padding-right: 4px;
	position: relative;
}
.total-title {
	margin-bottom: 4px;
	color: #838d97;
	font-weight: 600;
}
.total-ammount {
	font-weight: 600;
}
.total-currency {
	font-weight: 700;
	margin-left: 4px;
}
.footer {
	display: flex;
	justify-content: space-between;
	align-items: flex-end;
}
.checkout-button {
	padding: 1rem 2.75rem;
}
</style>
