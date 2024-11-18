<template>
	<nav class="products-groups-menu">
		<Swiper
			:loop="false"
			:space-between="0"
			:slides-per-view="1"
			:modules="[Navigation]"
			wrapperTag="ul"
			@active-index-change="onSlideChange"
			:speed="0"
			@after-init="onSliderInit"
			:navigation="{
				enabled: true,
				nextEl: nextRef,
				prevEl: prevRef
			}"
			:breakpoints="{
				320: {
					slidesPerView: 2,
					spaceBetween: 0
				},
				500: {
					slidesPerView: 3,
					spaceBetween: 0
				},
				768: {
					slidesPerView: 4,
					spaceBetween: 0
				},
				1024: {
					slidesPerView: 4.5,
					spaceBetween: 0
				}
			}"
			class="main-slider"
		>
			<SwiperSlide tag="li">
				<ProductsGroupCard
					:name="__('all')"
					:icon="defaultIcon"
					:iconHover="defaultIconHover"
					:iconActive="defaultIconActive"
					:disableLink="reactiveFilterMode"
					:active="
						reactiveFilterMode
							? !currentProductsGroup
							: currentPath ===
							  ('/' + getGlobalSetting('productsCatalogSlug') ||
									'catalog')
					"
					:link="
						route('seo-entity', [
							usePage().props.locale,
							getGlobalSetting('productsCatalogSlug') || 'catalog'
						])
					"
					@click="
						e =>
							reactiveFilterMode
								? onProductsGroupSelect(e, null)
								: router.get(
										route('seo-entity', [
											usePage().props.locale,
											getGlobalSetting(
												'productsCatalogSlug'
											) || 'catalog'
										])
								  )
					"
				/>
			</SwiperSlide>
			<SwiperSlide
				tag="li"
				v-for="productsGroup in productsGroups"
				:key="productsGroup.id"
				class="slide"
			>
				<ProductsGroupCard
					:icon="productsGroup.icon"
					:iconHover="productsGroup.icon_hover"
					:iconActive="productsGroup.icon_active"
					:name="_t(productsGroup.name)"
					:disableLink="reactiveFilterMode"
					:active="
						+currentProductsGroup === productsGroup.id ||
						currentPath.includes(productsGroup.seo_entity.slug)
					"
					:link="
						route('seo-entity', [
							usePage().props.locale,
							productsGroup.seo_entity.slug
						])
					"
					@click="
						e =>
							reactiveFilterMode
								? onProductsGroupSelect(e, productsGroup.id)
								: router.get(
										route('seo-entity', [
											usePage().props.locale,
											productsGroup.seo_entity.slug
										])
								  )
					"
					class="products-group-card"
				/>
			</SwiperSlide>
		</Swiper>
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
	</nav>
</template>

<script setup lang="ts">
import ProductsGroupCard from './ProductsGroupCard.vue';
import { TProductsGroup } from '@/types/TProductsGroup';

import defaultIcon from '@/assets/product-categories/all-balons.svg';
import defaultIconActive from '@/assets/product-categories/all-balons-active.svg';
import defaultIconHover from '@/assets/product-categories/all-balons-hover.svg';
import { getGlobalSetting } from '@/utils/getGlobalSetting';
import 'swiper/css';
import 'swiper/css/free-mode';
import { router, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
import { Swiper, SwiperSlide } from 'swiper/vue';
import { Navigation } from 'swiper/modules';

const nextRef = ref<null | HTMLElement>(null);
const prevRef = ref<null | HTMLElement>(null);
const { _t, __ } = useTranslations();

const props = defineProps<{
	productsGroups: TProductsGroup[];
	reactiveFilterMode?: boolean;
	selectedProductsGroup?: number | null;
}>();

const currentProductsGroup = (usePage().props as any).productsCatalogData
	.productsGroup;

const currentPath = ref<string>(
	hasWindow()
		? window.location.pathname.replace(usePage().props.locale + '/', '')
		: ''
);

const onProductsGroupSelect = (e: Event, id: number | null) => {
	if (!props.reactiveFilterMode) return;
	emit('productsGroupSelect', id);
};

const emit = defineEmits({
	productsGroupSelect: (id: number | null) => true
});

const onSlideChange = (swiper: any) => {
	localStorage.setItem(
		'productsGroupMenuSliderIndex',
		`${swiper.activeIndex}`
	);
};

const onSliderInit = (swiper: any) => {
	console.log(swiper, localStorage.getItem('productsGroupMenuSliderIndex'));
	const index = localStorage.getItem('productsGroupMenuSliderIndex');
	if (index) swiper.slideTo(+index);
	swiper.params.speed = 300;
};
</script>

<style scoped lang="scss">
@import '@/styles/variables.scss';
.f-row {
	--col-gap: 24px;
	--row-gap: 24px;
	@media (max-width: $tablet-width) {
		--col-gap: 16px;
		--row-gap: 16px;
	}
}
.products-groups-menu {
	padding: 0 64px;
	position: relative;
	@media (max-width: 768px) {
		padding: 0 44px;
	}
}
.main-slider {
	overflow: hidden;
	position: relative;
	// before after element to create shadow for a smooth transition
	&::before,
	&::after {
		content: '';
		position: absolute;
		top: 0;
		width: 20px;
		height: 100%;
		background: linear-gradient(
			to right,
			rgba(15, 15, 16, 1) 0%,
			rgba(15, 15, 16, 0) 100%
		);
		z-index: 5;
	}
	&::before {
		left: 0;
	}
	&::after {
		right: 0;
		transform: rotateY(180deg);
	}
}
.products-group-card {
	flex-basis: 160px;
	position: relative;
	@media (max-width: $tablet-width) {
		flex-basis: 100px;
	}
}
.card-link {
	position: absolute;
	top: 0;
	left: 0;
	width: 100%;
	height: 100%;
	z-index: 10;
	opacity: 0;
}

.navigation-button {
	position: absolute;
	z-index: 5;
	width: 44px;
	height: 44px;
	display: flex;
	justify-content: center;
	align-items: center;
	top: calc(52px - 22px);
	border: 1px solid #b73e38;
	border-radius: 50%;
	transition: border-color 0.3s;
	&.prev {
		left: 8px;
	}
	&.next {
		right: 8px;
	}
	&.swiper-button-disabled {
		opacity: 0.5;
		pointer-events: none;
	}
	&:hover {
		border-color: #f24942;
	}
	@media (max-width: 768px) {
		top: calc(40px - 22px);
		&.prev {
			left: 0px;
		}
		&.next {
			right: 0px;
		}
	}
}
</style>
