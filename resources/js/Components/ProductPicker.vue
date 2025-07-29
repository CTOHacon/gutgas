<template>
	<div class="product-picker">
		<p class="color-success fs-medium p-16 mb-8" v-if="+product.stock">
			{{ __(`inStock`) }}
		</p>
		<p class="color-danger fs-medium p-16 mb-8" v-else>
			{{ __(`outOfStock`) }}
		</p>
		<p class="fs-typography-content lh-160 pl-16 pr-40 pb-24">
			{{ _t(product.description) }}
		</p>
		<div class="ph-16 mb-16" v-if="+product.stock">
			<div
				class="f- justify-between align-center price-quantity pv-12 pl-16"
			>
				<ProductSpecialStatusBar
					v-if="_t(product.price_label)"
					class="price-quantity__special-status-bar"
				>
					{{ _t(product.price_label) }}
				</ProductSpecialStatusBar>
				<template v-if="productInCart">
					<BaseQuantityField
						:max="999"
						:min="1"
						:model-value="productInCart.quantity"
						@update:model-value="
							newQuantity =>
								setProductQuantity(product, newQuantity)
						"
					/>
				</template>
				<template v-else>
					<BaseQuantityField :max="999" :min="1" v-model="quantity" />
				</template>
				<p class="price">
					<span class="old-price price__item" v-if="hasDiscount">
						<span class="price__value">
							{{ product.old_price }}
						</span>
						<span class="price__currency">
							{{ __('uah') }}
						</span>
					</span>
					<span
						class="current-price price__item"
						:class="{ 'color-danger': hasDiscount }"
					>
						<span class="price__value">
							{{ product.price }}
						</span>
						<span class="price__currency">
							{{ __('uah') }}
						</span>
					</span>
				</p>
			</div>
		</div>
		<div class="ph-40 pb-24" v-if="+product.stock">
			<BaseButton
				class="pv-24 ph-44 w-100 fs-medium"
				@click="onAddToCart"
			>
				<template v-if="!isInCart(product)">
					{{ __('addToCart') }}
				</template>
				<template v-else>
					{{ __('inCart') }}
					<svg
						class="ml-16"
						width="18"
						height="13"
						viewBox="0 0 18 13"
						fill="none"
					>
						<path
							d="M17 1L6 12L1 7"
							stroke="#F24942"
							stroke-width="2"
							stroke-linecap="round"
							stroke-linejoin="round"
						/>
					</svg>
				</template>
			</BaseButton>
			<!-- <BaseButton
				variation="primary-bordered"
				class="pv-12 mt-8 w-100 fs-medium"
			>
				{{ __('buyInOneClick') }}
			</BaseButton> -->
		</div>
	</div>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import BaseQuantityField from './BaseQuantityField.vue';
import BaseButton from './BaseButton.vue';
import { TProduct } from '@/types/TProduct';
import useCart from '@/composables/cart';
import { computed } from 'vue';
import ProductSpecialStatusBar from './ProductSpecialStatusBar.vue';

const { __, _t } = useTranslations();
const { isCartModalOpened } = useCart();

const props = defineProps<{
	product: TProduct;
}>();

const { addProductToCart, isInCart, setProductQuantity, findInCart } =
	useCart();

const productInCart = computed(() => {
	return findInCart(props.product);
});

const quantity = ref(1);
const onAddToCart = () => {
	if (!isInCart(props.product)) {
		addProductToCart(props.product, quantity.value);
		isCartModalOpened.value = true;
	}
};

const hasDiscount = computed(() => {
	return props.product.old_price && props.product.old_price > 0;
});
</script>

<style scoped lang="scss">
.product-picker {
	border-radius: 8px;
	background: rgba(21, 21, 21, 0.5);
}
.price-quantity {
	border: 1px solid #202020;
	border-left: none;
	border-right: none;
	position: relative;
	&__special-status-bar {
		position: absolute;
		right: 0;
		top: -9px;
	}
}

.price {
	display: flex;
	align-items: center;
	gap: 1.5rem;
	margin-left: auto;
	color: #fff;
	&__currency {
		font-family: 'Mulish';
		font-weight: 800;
		font-size: 0.875rem;
		line-height: 100%;
		opacity: 0.4;
		margin-top: -0.125rem;
	}
	&__item {
		display: flex;
		flex-direction: column;
		align-items: end;
	}
	&__value {
		position: relative;
	}
}
.old-price {
	position: relative;
	font-weight: 700;
	font-size: 1.125rem;
	line-height: 100%;
	text-align: center;
	color: #838d97;
}
.old-price .price__value {
	&::before {
		content: '';
		position: absolute;
		height: 0.125rem;
		left: -0.125rem;
		right: -0.125rem;
		top: calc(50% - 0.0625rem);
		background: #fff;
	}
}
.current-price {
	font-weight: 700;
	font-size: 1.25rem;
	line-height: 124%;
}
</style>
