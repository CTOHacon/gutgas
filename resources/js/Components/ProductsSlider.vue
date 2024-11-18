<template>
	<div class="products-slider">
		<swiper
			:loop="false"
			:space-between="0"
			:slides-per-view="3"
			:centered-slides="true"
			:modules="[Navigation, Pagination]"
			:navigation="{
				enabled: true,
				nextEl: nextRef,
				prevEl: prevRef
			}"
			:breakpoints="{
				320: {
					slidesPerView: 1,
					spaceBetween: 0
				},
				768: {
					slidesPerView: 2,
					spaceBetween: 0
				},
				1024: {
					slidesPerView: 3,
					spaceBetween: 0
				}
			}"
			:pagination="{ el: paginationRef }"
			class="main-slider"
		>
			<swiper-slide
				v-for="(product, index) in props.products"
				:key="index"
				class="slide"
			>
				<ProductCatalogCard
					class="slide-product-card"
					:product="product"
					type="simplified"
			/></swiper-slide>
			<button class="navigation-button prev" ref="prevRef">
				<svg width="9" height="16" viewBox="0 0 9 16" fill="none">
					<path
						d="M7.75 15L0.75 8L7.75 1"
						fill="#B73E38"
						stroke="#B73E38"
						stroke-linecap="round"
						stroke-linejoin="round"
					/>
				</svg>
			</button>
			<button class="navigation-button next" ref="nextRef">
				<svg width="9" height="16" viewBox="0 0 9 16" fill="none">
					<path
						d="M1.25 15L8.25 8L1.25 1"
						fill="#B73E38"
						stroke="#B73E38"
						stroke-linecap="round"
						stroke-linejoin="round"
					/>
				</svg>
			</button>
		</swiper>
		<div class="pagination mt-24" ref="paginationRef"></div>
	</div>
</template>

<script setup lang="ts">
import { Swiper, SwiperSlide } from 'swiper/vue';
import { Navigation, Pagination, FreeMode } from 'swiper/modules';
import ISwiper from 'swiper';
import 'swiper/css';
import 'swiper/css/free-mode';
import { TProduct } from '@/types/TProduct';
import { ref } from 'vue';
import ProductCatalogCard from './ProductCatalogCard.vue';

const props = defineProps<{
	products: TProduct[];
}>();

const thumbsSwiper = ref<null | ISwiper>(null);
const paginationRef = ref<null | HTMLElement>(null);
const nextRef = ref<null | HTMLElement>(null);
const prevRef = ref<null | HTMLElement>(null);
</script>

<style scoped lang="scss">
.main-slider {
	position: relative;
	&::before,
	&::after {
		content: '';
		background: linear-gradient(
			90deg,
			rgba(15, 15, 16, 0) 0%,
			#0f0f10 100%
		);
		position: absolute;
		top: 0;
		bottom: 0;
		width: 88px;
		z-index: 2;
	}
	&::before {
		left: 0;
		transform: rotateY(180deg);
	}
	&::after {
		right: 0;
	}
}
.slide {
	height: auto;
}
.slide-product-card {
	height: 100%;
}
:deep(.swiper-pagination-bullet) {
	flex: 1;
	height: 3px;
	background: #1a1a1a;
	position: relative;
	transition: 0.3s;
	&.swiper-pagination-bullet-active {
		background: #f24942;
	}
}
.navigation-button {
	position: absolute;
	z-index: 5;
	width: 56px;
	height: 56px;
	display: flex;
	justify-content: center;
	align-items: center;
	top: 50%;
	transform: translateY(-50%);
	border: 1px solid #b73e38;
	border-radius: 50%;
	transition: border-color 0.3s;
	&.prev {
		left: 0;
	}
	&.next {
		right: 0;
	}
	&:hover {
		border-color: #f24942;
	}
}
.pagination {
	display: flex;
}
</style>
