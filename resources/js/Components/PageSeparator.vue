<template>
	<div class="page-separator"></div>
</template>

<script setup lang="ts">
import { ref } from 'vue';

const props = defineProps<{
	color?: '#26282B' | '#1A1A1A';
}>();
const color = ref(props.color ?? '#1A1A1A');
</script>

<style scoped lang="scss">
.page-separator {
	width: 100%;
	position: relative;
	z-index: 0;
	margin-left: auto;
	margin-right: auto;
	pointer-events: none;
	&::before {
		content: '';
		display: block;
		height: 72px;
		background: radial-gradient(
			49.34% 105.48% at 50% 100%,
			#000000 0%,
			rgba(0, 0, 0, 0.63) 58.85%,
			rgba(0, 0, 0, 0) 100%
		);
		opacity: 0.2;
		position: absolute;
		left: 0;
		right: 0;
		bottom: 0;
	}
	&::after {
		content: '';
		display: block;
		height: 3px;
		max-width: 1376px;
		width: 100%;
		margin-left: auto;
		margin-right: auto;
		background: v-bind('color');
	}
}
</style>
