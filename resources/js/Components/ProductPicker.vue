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
				class="f- justify-between align-center price-quantity pv-12 pl-16 pr-40"
			>
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
					<div class="fw-800 text-right lh-100">
						<p class="fs-semi-large">
							{{ productInCart.price * productInCart.quantity }}
						</p>
						<p class="fs-semi-small color-secondary">
							{{ __('uah') }}
						</p>
					</div>
				</template>
				<template v-else>
					<BaseQuantityField :max="999" :min="1" v-model="quantity" />
					<div class="fw-800 text-right lh-100">
						<p class="fs-semi-large">
							{{ quantity * props.product.price }}
						</p>
						<p class="fs-semi-small color-secondary">
							{{ __('uah') }}
						</p>
					</div>
				</template>
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
}
</style>
