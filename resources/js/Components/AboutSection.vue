<template>
	<div class="home-about-section">
		<div class="container">
			<h2 class="fs-h2 mb-40" v-html="title" />
			<div class="g-layout-12 align-end" ref="innerRef">
				<div class="decorations g-col-6 g-tablet-col-12">
					<div class="first-gradient-circle"></div>
					<div class="second-gradient-circle"></div>
					<div
						class="balon"
						data-index="3"
						:style="{
							transform: `translateX(${balonTranslateX}vw)`
						}"
					>
						<img
							src="@/assets/home-about-balon-3.png"
							:style="{
								transform: `rotate(${balonRotate}deg)`
							}"
							alt="Gas Balon"
						/>
					</div>
					<div
						class="balon"
						data-index="2"
						:style="{
							transform: `translateX(${balonTranslateX}vw)`
						}"
					>
						<img
							src="@/assets/home-about-balon-2.png"
							:style="{
								transform: `rotate(${balonRotate}deg)`
							}"
							alt="Gas Balon"
						/>
					</div>
					<div
						class="balon"
						data-index="1"
						:style="{
							transform: `translateX(${balonTranslateX}vw)`
						}"
					>
						<img
							src="@/assets/home-about-balon-1.png"
							:style="{
								transform: `rotate(${balonRotate}deg)`
							}"
							alt="Gas Balon"
						/>
					</div>
				</div>
				<div class="g-col-6 g-tablet-col-12">
					<div
						class="fs-semi-large lh-160 color-typography-secondary mb-68"
						v-html="content"
					/>
				</div>
			</div>
		</div>
	</div>
</template>

<script setup lang="ts">
import { onMounted, onUnmounted, ref } from 'vue';
import { useViewportPercentage } from '@/composables/isVisible';

const props = defineProps<{
	title?: string;
	content?: string;
}>();

const initialRotation = ref<number>(500);

const innerRef = ref<HTMLElement | null>(null);
const balonTranslateX = ref<number>(-30); // vw
const balonRotate = ref<number>(-initialRotation.value); // deg

const viewportPercentage = useViewportPercentage(innerRef, {
	treshold: 0.8
});

const updatePositions = () => {
	balonTranslateX.value = -30 + 30 * (viewportPercentage.value / 100);
	balonRotate.value =
		-initialRotation.value +
		initialRotation.value * (viewportPercentage.value / 100);
};
onMounted(() => {
	// if match max-width: 768px initialRotation = 300
	if (window.matchMedia('(max-width: 768px)').matches) {
		initialRotation.value = 200;
	}
	updatePositions();
	window.addEventListener('scroll', updatePositions);
});
onUnmounted(() => {
	window.removeEventListener('scroll', updatePositions);
});
</script>

<style scoped lang="scss">
@import '@/styles/variables.scss';
.title {
	max-width: 700px;
}
.inner {
	align-items: flex-end;
}
.main {
	flex: 1;
	margin-left: 0.5rem;
}
.decorations {
	flex: 1;
	margin-right: 0.5rem;
	position: relative;
	height: 438px;
	@media (max-width: $large-mobile-width) {
		// make smaller by 40%
		height: 263px;
	}
	@media (max-width: $mobile-width) {
		transform: translateX(100px);
	}
}
.first-gradient-circle {
	width: 456px;
	height: 456px;
	position: absolute;
	right: -55px;
	top: -60px;

	background: radial-gradient(
			82.62% 57.06% at 17.38% 55%,
			#f24942 38.13%,
			rgba(242, 73, 66, 0) 100%
		)
		no-repeat;
	opacity: 0.36;
	filter: blur(63.6289px);

	border-radius: 50%;

	@media (max-width: $large-mobile-width) {
		// make smaller by 40%
		width: 274px;
		height: 274px;
		right: -27px;
		top: -27px;
	}
}
.second-gradient-circle {
	width: 385px;
	height: 385px;
	position: absolute;
	right: 340px;
	top: 50px;

	background: radial-gradient(
		82.62% 57.06% at 17.38% 55%,
		#f24942 38.13%,
		rgba(242, 73, 66, 0) 100%
	);
	opacity: 0.2;
	filter: blur(63.6289px);

	border-radius: 416.659px;

	@media (max-width: $large-mobile-width) {
		// make smaller by 40%
		width: 231px;
		height: 231px;
		right: 170px;
		top: 25px;
	}
}
.balon {
	position: absolute;
	transform-origin: center center;
	&[data-index='3'] {
		top: 189px;
		right: 639px;
		width: 215px;
		height: 222px;
		filter: drop-shadow(38px -10px 66px rgba(0, 0, 0, 0.58));

		@media (max-width: $large-mobile-width) {
			// make smaller by 40%
			top: 114px;
			right: 384px;
			width: 129px;
			height: 133px;
		}
	}
	&[data-index='2'] {
		top: 155px;
		right: 412px;
		width: 248px;
		height: 256px;
		filter: drop-shadow(38px -10px 66px rgba(0, 0, 0, 0.28));

		@media (max-width: $large-mobile-width) {
			// make smaller by 40%
			top: 93px;
			right: 247px;
			width: 149px;
			height: 153px;
		}
	}
	&[data-index='1'] {
		top: 95px;
		right: 110px;
		width: 334px;
		height: 331px;
		filter: drop-shadow(-18px -10px 66px rgba(0, 0, 0, 0.38));

		@media (max-width: $large-mobile-width) {
			// make smaller by 40%
			top: 57px;
			right: 66px;
			width: 200px;
			height: 199px;
		}
	}
	img {
		max-width: 100%;
	}
	&,
	img {
		transition: transform 0.3s cubic-bezier(0.17, 0.84, 0.42, 0.98);
	}
}
.is-visible .balon {
	transform: translateX(0);
	opacity: 1;
	img {
		transform: rotate(0deg);
	}
}
</style>
