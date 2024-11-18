<template>
	<Link class="view-all g-col-12" :href="slug"
		>{{ __('viewAllProducts') }}
		<span class="total-count">({{ totalCount }})</span>
		<svg
			width="16"
			height="16"
			viewBox="0 0 16 16"
			fill="none"
			xmlns="http://www.w3.org/2000/svg"
		>
			<path
				d="M6 13L10 9L6 5"
				stroke="#F24942"
				stroke-linecap="round"
				stroke-linejoin="round"
			/>
		</svg>
	</Link>
</template>

<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

const props = defineProps<{
	slug: string;
	totalCount: number;
}>();

const { __ } = useTranslations();
</script>

<style scoped lang="scss">
.view-all {
	display: block;
	border-radius: 2px;
	border: 1px solid #000;
	position: relative;
	padding: 20px;
	display: flex;
	align-items: center;
	justify-content: center;
	letter-spacing: 0.8px;
	transition: color 0.2s ease-in-out;
	overflow: hidden;
	svg {
		margin-left: 0;
		transform: scale(0);
		transition: transform 0.2s ease-in-out, margin-left 0.2s ease-in-out;
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
		background: radial-gradient(
				640.98% 106.2% at 85.74% 0%,
				rgba(236, 35, 71, 0.08) 0%,
				rgba(224, 46, 38, 0) 100%
			),
			linear-gradient(
				223deg,
				rgba(241, 73, 66, 0.08) 0%,
				rgba(241, 87, 66, 0.08) 100%
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
		color: rgba($color: #b73e38a3, $alpha: 0.7);
		svg {
			margin-left: 12px;
			transform: scale(1);
		}
		&::before {
			opacity: 0;
		}
		&::after {
			opacity: 1;
		}
	}
}
.total-count {
	margin-left: 4px;
	color: #f24942;
}
</style>
