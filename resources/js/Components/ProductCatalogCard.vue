<template>
	<div
		class="product-catalog-card background-semi-black"
		:class="[
			{
				active: inCart
			},
			type
		]"
	>
		<Link
			:href="
				product.seo_entity
					? route('seo-entity', [
							usePage().props.locale,
							product.seo_entity.slug
					  ])
					: ''
			"
			class="head"
		>
			<img class="image" :src="imageUrl" :alt="_t(product.name)" />
		</Link>
		<div class="body">
			<template v-if="+product.stock">
				<div class="default-content">
					<h3 class="name fs-medium mb-8">{{ _t(product.name) }}</h3>
					<p class="price">₴{{ product.price }}</p>
				</div>
				<div class="hover-content">
					<p class="price mb-8">₴{{ product.price }}</p>
					<ProductCatalogCardCartButton
						:active="inCart"
						@click="
							inCart
								? removeProductFromCart(product)
								: addProductToCart(product)
						"
					/>
				</div>
			</template>
			<template v-else>
				<div class="ph-20 pv-20">
					<h3 class="name fs-medium mb-8">{{ _t(product.name) }}</h3>
					<p class="price">₴{{ product.price }}</p>
					<p class="color-danger fs-semi-small mt-12">
						{{ __(`outOfStock`) }}
					</p>
				</div>
			</template>
		</div>
	</div>
</template>

<script setup lang="ts">
import { TProduct } from '@/types/TProduct';
import ProductCatalogCardCartButton from './ProductCatalogCardCartButton.vue';
import useCart from '@/composables/cart';
import { computed } from 'vue';
import getMediaFileUrl from '@/modules/helpers/getMediaFileUrl';
import { Link, usePage } from '@inertiajs/vue3';

const { _t, __ } = useTranslations();

const props = defineProps<{
	product: TProduct;
	type?: 'simplified' | 'featured';
}>();

const imageUrl = props.product.media_file
	? getMediaFileUrl(props.product.media_file, 'thumbnail')
	: '';

const { addProductToCart, removeProductFromCart, isInCart } = useCart();
const inCart = computed(() => isInCart(props.product));
</script>

<style scoped lang="scss">
@import '@/styles/variables.scss';
.product-catalog-card {
	border: 1px solid #000;
	display: flex;
	flex-direction: column;
	padding: 16px 10px 0 10px;
	text-align: center;
	&.featured {
		background: linear-gradient(180deg, #570705 0%, rgba(0, 0, 0, 0) 100%);
	}
}
.head {
	background: radial-gradient(
		45.83% 105.48% at 50% 100%,
		#000 0%,
		rgba(0, 0, 0, 0.43) 58.85%,
		rgba(0, 0, 0, 0) 100%
	);
	height: 228px;
	position: relative;
	overflow: hidden;
	&::before {
		content: '';
		position: absolute;
		bottom: 0;
		left: 28px;
		right: 28px;
		background: #f24942;
		height: 2px;
		border-radius: 2px;
		transform: scaleX(0.5);
		opacity: 0;
		transition: transform 0.3s ease-in-out, opacity 0.2s ease-in-out;
		z-index: 2;
	}

	@media (max-width: $tablet-width) {
		height: 200px;
	}
}
.simplified {
	.head {
		height: 173px;
		background: none !important;
	}
}
.image {
	max-height: 100%;
	height: min-content;
	width: auto;
	opacity: 0.95;
	transition: opacity 0.3s ease-in-out, transform 0.3s ease-in-out;
	position: relative;
	z-index: 1;
	border-bottom: 1px solid #000;
}
.product-catalog-card:hover .image {
	transform: scale(1.03);
}
.body {
	flex: 1;
	position: relative;
}
.hover-content {
	position: absolute;
	top: 0;
	bottom: 0;
	left: 0;
	right: 0;
	opacity: 0;
	transform: translateY(20%);
	align-items: center;
	justify-content: center;
}
.default-content {
	padding: 20px 12px 16px 12px;
	height: 100%;
	.price {
		margin-top: auto;
	}
}
.simplified .default-content {
	padding: 14px 9px 12px 9px;
}
.hover-content,
.default-content {
	display: flex;
	flex-direction: column;
	transition: transform 0.3s ease-in-out, opacity 0.3s ease-in-out;
}
.price {
	line-height: 1.5rem;
	letter-spacing: 0.05em;
	font-weight: 700;
}
.name {
	color: #bdbdbd;
}

.product-catalog-card:hover {
	.head::before {
		transform: scaleX(1);
		opacity: 1;
	}
	.image {
		opacity: 1;
	}
	.default-content {
		transform: translateY(-20%);
		opacity: 0;
	}
	.hover-content {
		opacity: 1;
		transform: translateY(0);
	}
}
.product-catalog-card.active {
	.default-content {
		transform: translateY(-20%);
		opacity: 0;
	}
	.hover-content {
		opacity: 1;
		transform: translateY(0);
	}
}
</style>
