<template>
	<div class="product-gallery-slider">
		<swiper
			:loop="true"
			:spaceBetween="0"
			:modules="[Thumbs, Navigation, Pagination]"
			:navigation="{
				enabled: true,
				nextEl: nextRef,
				prevEl: prevRef
			}"
			:pagination="{ clickable: true, el: paginationRef }"
			:thumbs="{ swiper: thumbsSwiper, autoScrollOffset: 1 }"
			class="main-slider mb-48"
		>
			<swiper-slide
				class="main-slider-slide"
				v-for="(image, index) in images"
				:key="index"
			>
				<div class="main-slider-slide__image-container">
					<img
						:alt="
							imageBaseAlt +
							' | ' +
							__('photo') +
							' ' +
							(index + 1)
						"
						:src="image"
					/>
				</div>
			</swiper-slide>
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
			<div class="pagination" ref="paginationRef"></div>
		</swiper>
		<swiper
			@swiper="thumbsSwiper = $event"
			:loop="true"
			:spaceBetween="8"
			:slidesPerView="3"
			:freeMode="true"
			:watchSlidesProgress="true"
			class="thumbs"
		>
			<swiper-slide
				v-for="(image, index) in images"
				:key="index"
				class="thumb"
			>
				<img
					:src="image"
					:alt="
						imageBaseAlt +
						' | ' +
						__('photoThumbnail') +
						' ' +
						(index + 1)
					"
				/>
			</swiper-slide>
		</swiper>
	</div>
</template>

<script setup lang="ts">
import { Swiper, SwiperSlide } from 'swiper/vue';
import { Navigation, Thumbs, Pagination } from 'swiper/modules';
import ISwiper from 'swiper';
import 'swiper/css';
import 'swiper/css/free-mode';
import 'swiper/css/navigation';
import 'swiper/css/thumbs';

import { ref } from 'vue';

const { __ } = useTranslations();

const props = defineProps<{
	images: string[];
	imageBaseAlt: string;
}>();

const thumbsSwiper = ref<null | ISwiper>(null);
const paginationRef = ref<null | HTMLElement>(null);
const nextRef = ref<null | HTMLElement>(null);
const prevRef = ref<null | HTMLElement>(null);
</script>

<style scoped lang="scss">
@import '@/styles/variables.scss';
.main-slider-slider {
	position: relative;
}
.pagination {
	display: flex;
}
.main-slider-slide {
	position: relative;
}
.main-slider-slide__image-container {
	overflow: hidden;
	display: flex;
	justify-content: center;
	align-items: center;
	height: 490px;
	margin: 0 auto;
	border-radius: 24px;
	img {
		height: 100%;
		width: 100%;
		object-fit: contain;
		object-position: bottom;
	}
	@media (max-width: $tablet-width) {
		height: 400px;
	}
	@media (max-width: $mobile-width) {
		height: 300px;
	}
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
	background: #0f0f10;
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
.thumb {
	border-radius: 8px;
	border: 1px solid var(--card-background, #1a1a1a);
	background: radial-gradient(
		71.02% 100% at 50.2% 100%,
		#000 0%,
		rgba(0, 0, 0, 0) 64.14%
	);
	height: 200px;
	display: flex;
	align-items: center;
	justify-content: center;
	img {
		height: 100%;
		width: 100%;
		object-fit: cover;
	}
}
</style>
