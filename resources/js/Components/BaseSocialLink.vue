<template>
	<a class="social-link" :href="href || ''" target="_blank">
		<span class="popup">
			{{ name }}
		</span>
		<img :src="iconUrl" :alt="name" />
	</a>
</template>

<script setup lang="ts">
const props = defineProps<{
	href: string;
	icon: string;
	name: string;
}>();

const iconUrl = new URL(props.icon, import.meta.url).href;
</script>

<style scoped lang="scss">
.social-link {
	display: block;
	position: relative;
	width: 1em;
	height: 1em;
	display: flex;
	justify-content: center;
	align-items: center;
	opacity: 0.4;
	transition: opacity 0.3s ease-in-out;
	img {
		width: 100%;
	}

	&:hover {
		opacity: 1;
		.popup {
			opacity: 1;
			transform: translateY(0);
		}
	}
}
.popup {
	position: absolute;
	bottom: calc(100% + 8px);
	border-radius: 31px;
	padding: 9px 12px;
	background: radial-gradient(
			87.5% 87.5% at 50% 12.5%,
			#ff5267 0%,
			rgba(255, 109, 103, 0) 100%
		),
		#f24942;
	box-shadow: 0px 0px 4px 0px rgba(0, 0, 0, 0.48) inset;

	color: #430001;
	font-size: 0.875rem;
	font-weight: 800;
	line-height: 0.75rem;
	&::before {
		content: '';
		position: absolute;
		top: 100%;
		width: 12px;
		height: 6px;
		left: calc(50% - 6px);
		background: url('@/assets/social-popup-carret.svg') no-repeat center;
		background-size: cover;
	}
	pointer-events: none;
	opacity: 0;
	transform: translateY(0.5rem);
	transition: opacity 0.3s ease-in-out, transform 0.3s ease-in-out;
}
</style>
