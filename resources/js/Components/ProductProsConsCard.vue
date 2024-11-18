<template>
	<div
		class="product-pros-cons-card background-semi-black"
		:class="{
			recommended: recommended,
			discouraged: discouraged
		}"
	>
		<div class="head">
			<h3 class="fs-semi-large ph-16 title">{{ title }}</h3>
			<img :src="image" :alt="title" class="image" />
			<img v-if="logo" :src="logo" class="logo" />
		</div>
		<div class="body ph-24 pv-32 ls-5">
			<div class="advantages mb-24" v-if="advantages">
				<h5 class="fs-semi-large fw-700 color-success mb-16">
					{{ __('advantages') }}
				</h5>
				<ul class="list">
					<li class="list-item" v-for="advantage in advantages">
						{{ advantage }}
					</li>
				</ul>
			</div>
			<div class="disadvantages" v-if="disadvantages">
				<h5 class="fs-semi-large fw-700 color-danger mb-16">
					{{ __('disadvantages') }}
				</h5>
				<ul class="list">
					<li class="list-item" v-for="disadvantage in disadvantages">
						{{ disadvantage }}
					</li>
				</ul>
			</div>
		</div>
	</div>
</template>

<script setup lang="ts">
import { TProductProsConsCardProps } from '@/types/TProductProsConsCardProps';

const props = defineProps<TProductProsConsCardProps>();

const { _t, __ } = useTranslations();
</script>

<style scoped lang="scss">
.product-pros-cons-card {
	position: relative;
	display: flex;
	flex-direction: column;
	border: 1px solid #000000;
	&.discouraged {
		background: #131111;
	}
}

// HEAD

.head {
	height: 356px;
	display: flex;
	justify-content: center;
	align-items: flex-end;
	border-bottom: 1px solid #000000;
	overflow: hidden;
	position: relative;
	&::before {
		content: '';
		position: absolute;
		z-index: 0;
		left: 8px;
		right: 8px;
		top: 70px;
		bottom: 0;
		background: radial-gradient(
			50% 105.48% at 50% 100%,
			#000000 0%,
			rgba(0, 0, 0, 0.63) 56.42%,
			rgba(0, 0, 0, 0) 100%
		);
	}
}
.recommended .head {
	background: linear-gradient(180deg, #570705 0%, #000000 100%);
}
.title {
	font-weight: 700;
	position: absolute;
	line-height: 100%;
	color: #838d97;
	top: 31px;
	text-align: center;
	max-width: 250px;
}

.image {
	max-width: 100%;
	max-height: 100%;
	width: auto;
	height: auto;
	transition: opacity 0.3s ease-in-out;
	position: relative;
}
.logo {
	position: absolute;
	left: 16px;
	bottom: 16px;
	filter: drop-shadow(0px 34.8187px 55px rgba(244, 63, 63, 0.23));
}

// BODY
.body {
	flex: 1;
	display: flex;
	flex-direction: column;
	& > *:last-child {
		margin-top: auto;
	}
}
.list {
	list-style: none;
	li {
		font-weight: 400;
		line-height: 150%;
		font-size: 16px;
	}
}
</style>
