<template>
	<section class="home-hero">
		<div class="container">
			<div class="inner">
				<h1 class="fs-h1 lh-100 mb-40" v-html="title" v-if="title"></h1>
				<div
					class="fs-large strong-fw-600"
					v-html="subtitle"
					v-if="subtitle"
				></div>
				<PhoneContactForm class="contact-form mt-88 fs-medium" />
			</div>
			<div class="decorations">
				<img
					class="decoration-image"
					src="@/assets/hero-home-man.png"
				/>
				<img
					class="decoration-image"
					src="@/assets/hero-home-balons.png"
				/>
			</div>
		</div>
	</section>
</template>

<script setup lang="ts">
import useHeaderElement from '@/composables/headerElement';
import PhoneContactForm from './PhoneContactForm.vue';
import { computed } from 'vue';

const { headerHeight } = useHeaderElement();
const paddingTopPx = computed(() => headerHeight.value + 'px');

const props = defineProps<{
	title?: string;
	subtitle?: string;
}>();
</script>

<style scoped lang="scss">
@import '@/styles/variables.scss';
.home-hero {
	background: url('@/assets/hero-home-background.jpg') no-repeat center center;
	background-size: cover;
	position: relative;
}
.container {
	display: flex;
	flex-direction: column;
}
.inner {
	min-height: 100vh;
	display: flex;
	flex-direction: column;
	justify-content: center;
	position: relative;
	z-index: 5;
	max-width: 50%;
	width: 100%;
	padding-top: v-bind('paddingTopPx');
	@media (max-width: $tablet-width) {
		max-width: 700px;
		min-height: unset;
		margin-top: 25px;
	}
}

.decorations {
	position: absolute;
	z-index: 1;
	top: 0;
	left: 0;
	bottom: 78px;
	width: 100%;
	user-select: none;
	&::before {
		content: '';
		display: block;
		height: 171px;
		position: absolute;
		background: linear-gradient(
			180deg,
			rgba(15, 15, 16, 0) 0%,
			#0f0f10 100%
		);
		bottom: -1px;
		left: 0;
		right: 0;
		z-index: 2;
	}
	@media (max-width: $tablet-width) {
		order: -1;
		padding-top: 40px;
		height: 420px;
		display: block;
		position: relative;
	}
}
.decoration-image {
	position: absolute;
	left: 50%;
	bottom: 0;
	max-height: 100%;
	@media (max-width: $tablet-width) {
		left: 50%;
		transform: translateX(-50%);
		max-height: calc(100% - 50px);
		max-width: unset;
		height: auto;
		&:nth-child(2) {
			max-height: 40%;
			transform: translateX(-70%);
		}
	}
}
.contact-form {
	max-width: 440px;
	width: 100%;
}
</style>
