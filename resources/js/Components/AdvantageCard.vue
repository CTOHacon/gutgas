<template>
	<div class="advantage-card">
		<img :src="image" :alt="title" class="image mb-24" />
		<h4 class="fs-large fw-800 lh-100 mb-24">{{ title }}</h4>
		<p class="description fs-semi-large ph-16">{{ description }}</p>
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
.advantage-card {
	display: flex;
	flex-direction: column;
	align-items: center;
}
.image {
	height: 88px;
}
.advantage-card {
	text-align: center;
}
.description {
	color: #838d97;
	text-align: center;
	font-family: Mulish;
	font-weight: 300;
}
</style>
