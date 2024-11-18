<template>
	<div
		class="preheader fs-small"
		ref="topRef"
		:style="{
			height: visible ? '32px' : '0'
		}"
	>
		<div class="inner">
			<img class="icon" src="@/assets/ukr-logo.svg" alt="Ukraine" />
			<p class="text">{{ __('togetherToVictory') }}</p>
			<a
				href="https://www.azov-one.com/campaigns"
				target="_blank"
				rel="noopener noreferrer"
				class="link"
				>{{ __('helpArmy') }}</a
			>
		</div>
	</div>
</template>

<script setup lang="ts">
import { onMounted, ref } from 'vue';

const { __ } = useTranslations();

const topRef = ref<HTMLElement | null>(null);
const innerRef = ref<HTMLElement | null>(null);

const visible = ref<boolean>(false);

onMounted(() => {
	visible.value = window.scrollY === 0;
	window.addEventListener('scroll', () => {
		visible.value = window.scrollY === 0;
	});

	setTimeout(() => {
		if (topRef.value) {
			topRef.value.style.transition = 'height 0.2s ease-in-out';
		}
	}, 500);
});

defineExpose({ innerRef });
</script>

<style scoped lang="scss">
@import '@/styles/variables.scss';
.preheader {
	overflow: hidden;
	position: relative;
	z-index: 1;
	@media (max-width: $tablet-width) {
		display: none;
	}
}
.inner {
	width: 100%;
	height: 32px;
	background: #a5111a;

	display: flex;
	align-items: center;
	justify-content: center;
}
.icon {
	width: 1.5rem;
	margin-right: 8px;
	@media (max-width: $tablet-width) {
		margin-right: 4px;
	}
}
.text {
	font-weight: 700;
	line-height: 120%;
	margin-right: 1rem;
	@media (max-width: $tablet-width) {
		margin-right: 0.5rem;
	}
}
.link {
	text-decoration-line: underline;
}
</style>
