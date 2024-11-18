<template>
	<div class="use-case-card">
		<div class="head">
			<img class="image" :src="image" :alt="title" />
		</div>
		<div class="body">
			<h3 class="fs-large fw-800 lh-100 mb-40">{{ title }}</h3>
			<p class="fs-semi-large description">{{ description }}</p>
		</div>
	</div>
</template>

<script setup lang="ts">
const props = defineProps<{
	image: string;
	title: string;
	description: string;
}>();
</script>

<style scoped lang="scss">
@import '@/styles/variables.scss';
.use-case-card {
	background: rgba(21, 21, 21, 0.5);
	border-radius: 2px;
	display: flex;
	@media (max-width: $mobile-width) {
		flex-direction: column;
		text-align: center;
	}
}
.head {
	flex-basis: 295px;
	overflow: hidden;
	position: relative;
	@media (max-width: $large-mobile-width) {
		flex-basis: 150px;
	}
	@media (max-width: $mobile-width) {
		flex-basis: 100%;
		margin: 0 auto 24px auto;
		max-width: 200px;
	}
}
.image {
	width: 100%;
	height: 100%;
	object-fit: cover;
}
.body {
	flex: 1;
	align-self: center;
	padding: 14px 14px 14px 8px;
}
.description {
	font-weight: 300;
	font-size: 1.25rem;
	line-height: 140%;
	color: #838d97;
}
</style>
