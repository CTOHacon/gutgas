<template>
	<section class="contact-us-section">
		<div class="inner-wrapper">
			<div class="background-decoration-text">
				<img src="@/assets/background-decoration-text.svg" />
			</div>
			<div class="container g-layout">
				<div class="background-decoration-image">
					<img
						src="@/assets/contact-us-background-decoration-image.png"
					/>
				</div>
				<div class="g-col-6 g-tablet-col-12 pv-88">
					<h2 class="fs-h2 mb-12" v-if="title" v-html="title" />
					<small
						class="fs-small subtitle"
						v-if="subtitle"
						v-html="subtitle"
					/>
					<ContactUsForm class="pv-48" />
				</div>
				<div
					class="g-col-4 g-tablet-col-12 g-col-end-12 g-tablet-col-end-13 socials-wrapper"
				>
					<div class="socials-text mb-24" v-if="text" v-html="text" />
					<SocialLinksGroup class="social-links f-centered" />
				</div>
			</div>
		</div>
	</section>
</template>

<script setup lang="ts">
import ContactUsForm from './ContactUsForm.vue';
import SocialLinksGroup from './SocialLinksGroup.vue';

const props = defineProps<{
	title?: string;
	subtitle?: string;
	text?: string;
}>();
</script>

<style scoped lang="scss">
@import '@/styles/variables.scss';
.contact-us-section {
	position: relative;
	background: linear-gradient(
		146.9deg,
		rgba(255, 75, 68, 0.22) 0%,
		rgba(255, 75, 68, 0.23) 100%
	);
	padding: 1px;
	overflow: hidden;
}

.inner-wrapper {
	overflow: hidden;
	background: linear-gradient(
			147.2deg,
			rgba(236, 35, 71, 0.12) 0%,
			rgba(224, 46, 38, 0) 100.69%
		),
		#0f0f10;
	margin: 1px;
	position: relative;
	z-index: 2;
}
.background-decoration-text {
	position: absolute;
	bottom: -65px;
	left: -83px;
	right: -32px;
	max-width: 1873.341px;

	img {
		width: 100%;
	}
	@media (min-width: 1900px) {
		max-width: 1873.341px;
		margin: 0 auto;
		left: 0;
		right: 0;
	}
}
.background-decoration-image {
	position: absolute;
	right: -370px;
	bottom: 0;
	max-height: 480px;
	@media (max-width: $small-desktop-width) {
		max-height: 300px;
	}
	@media (max-width: $tablet-width) {
		z-index: -1;
		opacity: 0.5;
	}
}
.container {
	position: relative;
	z-index: 2;
}
.subtitle {
	opacity: 0.7;
	font-weight: 300;
	line-height: 100%;
}

.socials-text {
	color: rgba(172, 115, 124, 1);
	font-weight: 300;
	text-align: center;
	:deep(strong) {
		font-weight: 300;
		color: rgba(255, 204, 202, 1) !important;
	}
}
.socials-wrapper {
	margin-top: 150px;
	@media (max-width: $tablet-width) {
		margin-top: 50px;
		margin-bottom: 24px;
		background: rgba(15, 15, 16, 0.8);
		padding: 16px;
		border-radius: 8px;
	}
}

.social-links {
	font-size: 36px;
}
</style>
