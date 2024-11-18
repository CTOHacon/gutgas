<template>
	<div class="tab" v-show="currentTabName === name" ref="tabRef">
		<div class="inner-wrapper">
			<div class="inner" ref="innerRef">
				<slot />
			</div>
		</div>
		<Transition name="fade">
			<button
				class="f- align-center justify-center collapse-toggle fs-medium"
				v-if="collapsed"
				@click="toggleCollapse"
			>
				Розгорнути
				<svg width="33" height="32" viewBox="0 0 33 32" fill="none">
					<path
						opacity="0.56"
						d="M29.5 12L21.5 20L13.5 12"
						stroke="#838D97"
						stroke-width="2.5"
						stroke-linecap="round"
						stroke-linejoin="round"
					/>
				</svg>
			</button>
		</Transition>
	</div>
</template>

<script setup lang="ts">
import { computed, inject, onMounted, ref, watchEffect, watch } from 'vue';

const currentTabName = inject('activeTabName');
const props = defineProps<{
	name: string;
	label: string;
	icon?: string;
}>();

const defaultMaxHeight = 365;

const maxHeight = ref(defaultMaxHeight);
const maxHeightPx = computed(() => {
	if (fullHeight.value !== 0) return `${maxHeight.value}px`;
	return 'auto';
});
const fullHeight = ref(0);
const innerRef = ref<HTMLElement | null>(null);
const tabRef = ref<HTMLElement | null>(null);

const collapsed = ref(false);

watchEffect(() => {
	if (innerRef.value) fullHeight.value = innerRef.value.clientHeight;
	if (maxHeight.value < fullHeight.value && fullHeight.value !== 0)
		collapsed.value = true;
});

const toggleCollapse = () => {
	collapsed.value = !collapsed.value;
	maxHeight.value = collapsed.value ? defaultMaxHeight : fullHeight.value;
};
</script>

<style scoped lang="scss">
.tab {
	position: relative;
}
.inner-wrapper {
	max-height: v-bind('maxHeightPx');
	transition: max-height 0.2s ease-in-out;
	overflow: hidden;
}
.collapse-toggle {
	position: absolute;
	bottom: 0;
	left: 0;
	right: 0;
	width: 100%;
	padding: 16px;
	border-radius: 2px;
	color: #fff;
	background: linear-gradient(
		180deg,
		rgba(21, 21, 21, 0) 0%,
		rgba(21, 21, 21, 0.72) 44.85%,
		rgba(15, 15, 16, 0.72) 100%
	);
	backdrop-filter: blur(2.5px);
	transition: color 0.2s ease-in-out, opacity 0.2s ease-in-out,
		transform 0.2s ease-in-out;
	svg {
		transition: transform 0.2s ease-in-out;
		path {
			transition: stroke 0.2s ease-in-out;
		}
	}
	&:hover {
		color: #f24942;
		svg {
			transform: translateX(5px);
			path {
				stroke: #f24942;
			}
		}
	}
	&.fade-enter-from,
	&.fade-leave-to {
		opacity: 0;
	}
	&.fade-enter-active,
	&.fade-leave-active {
		opacity: 1;
	}
}
</style>
