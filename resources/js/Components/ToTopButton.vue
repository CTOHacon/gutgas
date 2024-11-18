<template>
	<button
		class="f- justify-center align-center to-top-button fs-large ls--1"
		@click="onClick"
	>
		{{ __('toTop') }}
		<svg
			width="19"
			height="12"
			viewBox="0 0 19 12"
			fill="none"
			xmlns="http://www.w3.org/2000/svg"
		>
			<path
				opacity="0.56"
				d="M17.5 10L9.5 2L1.5 10"
				stroke="#B73E38"
				stroke-width="2.5"
				stroke-linecap="round"
				stroke-linejoin="round"
			/>
		</svg>
	</button>
</template>

<script setup lang="ts">
const onClick = () => {
	window.scrollTo({
		top: 0,
		behavior: 'smooth'
	});
};

const { __ } = useTranslations();
</script>

<style scoped lang="scss">
.to-top-button {
	border-radius: 4px;
	overflow: hidden;
	position: relative;
	padding: 20px;
	letter-spacing: 0.8px;
	transition: color 0.2s ease-in-out;
	color: #b73e38;
	width: 100%;
	svg {
		margin-left: 17px;
	}
	&::before,
	&::after {
		content: '';
		position: absolute;
		width: 100%;
		height: 100%;
		transition: opacity 0.2s ease-in-out;
	}
	&::before {
		background: linear-gradient(
			180deg,
			rgba(241, 87, 66, 0.04) 0%,
			rgba(241, 73, 66, 0.08) 100%
		);

		opacity: 1;
	}
	&::after {
		background: radial-gradient(
				640.98% 106.2% at 85.74% 0%,
				rgba(236, 35, 71, 0.12) 0%,
				rgba(224, 46, 38, 0) 100%
			),
			linear-gradient(
				223deg,
				rgba(241, 73, 66, 0.12) 0%,
				rgba(241, 87, 66, 0.12) 100%
			);

		opacity: 0;
	}
	&:hover {
		&::before {
			opacity: 0;
		}
		&::after {
			opacity: 1;
		}
	}
}
</style>
