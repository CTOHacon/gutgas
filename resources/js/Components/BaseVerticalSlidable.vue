<template>
	<div class="vertical-slidable" ref="mainRef">
		<div class="vertical-slidable__inner" ref="innerRef">
			<slot />
		</div>
	</div>
</template>

<script setup lang="ts">
import { ref, watch } from 'vue';

const props = defineProps<{
	isOpened: boolean;
}>();

watch(
	() => props.isOpened,
	() => {
		if (props.isOpened) {
			mainRef.value!.style.height = `${innerRef.value!.offsetHeight}px`;
		} else {
			mainRef.value!.style.height = '0px';
		}
	}
);

const innerRef = ref<HTMLElement | null>(null);
const mainRef = ref<HTMLElement | null>(null);
</script>

<style scoped lang="scss">
.vertical-slidable {
	overflow: hidden;
	transition: height 0.3s ease-in-out, transform 0.3s ease-in-out;
	height: 0;
	&__inner {
		position: relative;
		display: block;
	}
}
</style>
