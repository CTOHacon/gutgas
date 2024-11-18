<template>
	<div
		class="cart-overlay"
		:class="{
			active: isActive
		}"
	>
		<div class="head" @click="isActive = !isActive">
			<p class="fs-semi-large mb-4">
				<span class="block">{{ __('cart') }}</span>
				<span class="block color-secondary"
					>{{ cartTotal }}
					<span class="fw-700">{{ __('uah') }}</span></span
				>
			</p>
			<CartToggleButton :isActive="isActive" />
		</div>
		<div class="body">
			<CartProductsList class="products-list" />
		</div>
		<div class="footer">
			<BaseButton
				v-if="cartProducts.length"
				@click="
					() => {
						isActive = false;
						router.get(route('checkout', usePage().props.locale));
					}
				"
				class="block p-16 w-100"
			>
				{{ __('toCheckout') }}
			</BaseButton>
			<button
				class="lh-100 pv-24 underline color-danger fs-large mt-8 text-center w-100"
				@click="isActive = false"
			>
				{{ __('continueShopping') }}
			</button>
		</div>
	</div>
</template>

<script setup lang="ts">
import useCart from '@/composables/cart';
import CartToggleButton from '@/Components/CartToggleButton.vue';
import CartProductsList from '@/Components/CartProductsList.vue';
import { ref } from 'vue';
import BaseButton from './BaseButton.vue';
import { router, usePage } from '@inertiajs/vue3';

const { __ } = useTranslations();

const { cartTotal, cartProducts } = useCart();

const isActive = ref(false);
</script>

<style scoped lang="scss">
.cart-overlay {
	height: calc(100vh - 32px);
	top: 16px;
	border-radius: 24px;
	border: 1px solid #242424;
	background: rgba(19, 19, 19, 0.86);
	box-shadow: 0px -51px 44px -26px #000;
	backdrop-filter: blur(4px);
	position: fixed;
	z-index: 100;
	left: 16px;
	right: 16px;
	transform: translateY(calc(100dvh - 100px));
	transition: transform 0.3s ease-in-out, background 0.3s ease-in-out;

	display: flex;
	flex-direction: column;
	&.active {
		transform: translateY(0);
		background: rgba(19, 19, 19, 0.97);
	}

	padding: 16px;
}

.products-list {
	margin-right: 8px;
}

.head {
	display: flex;
	justify-content: space-between;
	align-items: center;
	padding-bottom: 16px;
}

.body {
	flex: 1;
	overflow: auto;
	border-radius: 8px;
	transition: opacity 0.3s ease-in-out;
	opacity: 0;
}
.active .body {
	opacity: 1;
}

.footer {
	padding-top: 16px;
}
</style>
