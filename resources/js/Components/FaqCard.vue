<template>
	<div
		class="faq-card"
		:class="{
			active: isActive
		}"
		itemprop="mainEntity"
		itemscope
		itemtype="https://schema.org/Question"
	>
		<div class="qustion-wrapper" @click="emit('click', true)">
			<h4 class="lh-160 fs-semi-large" itemprop="name">
				{{ question }}
			</h4>
			<button class="view-answer">
				<span class="view-answer__text">Відповідь</span>
			</button>
		</div>
		<div
			class="answer-wrapper"
			itemprop="acceptedAnswer"
			itemscope
			itemtype="https://schema.org/Answer"
		>
			<div
				class="answer-text color-typography-secondary typography-content"
				itemprop="text"
			>
				<slot />
			</div>
			<svg
				class="close-answer"
				width="32"
				height="32"
				viewBox="0 0 32 32"
				fill="none"
				@click="emit('click', false)"
			>
				<g class="close-answer__cross" opacity="0.64">
					<path
						d="M20 12L12 20"
						stroke="#F24942"
						stroke-width="1.5"
						stroke-linecap="round"
						stroke-linejoin="round"
					/>
					<path
						d="M12 12L20 20"
						stroke="#F24942"
						stroke-width="1.5"
						stroke-linecap="round"
						stroke-linejoin="round"
					/>
				</g>
				<rect
					class="close-answer__rect"
					x="0.5"
					y="0.5"
					width="31"
					height="31"
					rx="15.5"
					stroke="#F24942"
					stroke-opacity="0.56"
				/>
			</svg>
		</div>
	</div>
</template>

<script setup lang="ts">
const props = defineProps<{
	question: string;
	isActive: boolean;
}>();

const emit = defineEmits({
	click: (value: boolean) => true
});
</script>

<style scoped lang="scss">
.faq-card {
	border-radius: 2px;
	border-radius: 2px;
	min-height: 244px;

	background: rgba(21, 21, 21, 0.5);
	border: 1px solid #000;
	position: relative;
	transition: background 0.3s ease-in-out, border 0.3s ease-in-out;
}
.faq-card:hover {
	border: 1px solid rgba(149, 149, 150, 0.16);
	background: #1a1a1a;
}
.faq-card.active {
	border: 1px solid #000;
	background: #202020;
}
.qustion-wrapper {
	height: 100%;
	padding: 56px 50px 16px 50px;
	display: flex;
	flex-direction: column;
	justify-content: space-between;
	align-items: center;
	text-align: center;
	cursor: pointer;
	transition: transform 0.3s ease-in-out, opacity 0.3s ease-in-out;
}
.faq-card.active .qustion-wrapper {
	transform: translateY(-30px) translateX(-30px);
	opacity: 0;
}

.view-answer {
	color: #fff;
	line-height: 100%;
	position: relative;
	display: flex;
	flex-direction: column;
	align-items: center;
	&__text {
		opacity: 0.4;
		transition: opacity 0.2s ease-in-out;
	}
	transition: color 0.2s ease-in-out, transform 0.2s ease-in-out;
	&::after {
		content: '';
		position: relative;
		width: 16px;
		height: 16px;
		margin-top: 4px;
		background: url('@/assets/chevron-down.svg') center / cover no-repeat;
	}
}
.faq-card:hover .view-answer {
	transform: translateY(6px);
	&__text {
		opacity: 1;
	}
	color: #f24942;
}

.answer-wrapper {
	display: flex;
	flex-direction: column;
	justify-content: center;
	padding: 16px 24px;
	opacity: 0;
	transform: translateY(30px) translateX(30px);
	transition: opacity 0.3s ease-in-out, transform 0.3s ease-in-out;
	position: absolute;
	inset: 0;
	pointer-events: none;
}
.close-answer {
	position: absolute;
	top: 14px;
	right: 14px;
	cursor: pointer;
	&__cross,
	&__rect {
		transition: opacity 0.2s ease-in-out;
	}
	&:hover .close-answer__cross {
		opacity: 1;
	}
	&:hover .close-answer__rect {
		opacity: 0.3;
	}
}
.active .answer-wrapper {
	opacity: 1;
	pointer-events: auto;
	transform: translateY(0) translateX(0);
}
</style>
