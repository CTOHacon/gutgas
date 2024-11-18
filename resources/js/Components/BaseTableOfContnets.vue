<template>
	<nav class="table-of-contents">
		<a
			v-for="item in items"
			:key="item.slug"
			:href="item.slug ? `#${item.slug}` : ''"
			class="item"
			:class="{ active: item.slug === currentActiveItemSlug }"
			@click.prevent="scrollTo(item.slug)"
			>{{ item.title }}</a
		>
	</nav>
</template>

<script setup lang="ts">
import { onMounted, onUnmounted, ref } from 'vue';

const props = defineProps<{
	items: {
		title: string;
		slug: string;
	}[];
	contentRef: HTMLElement | null;
}>();

const currentActiveItemSlug = ref('');

const getCurrentActiveItem = () => {
	if (!props.contentRef) return null;
	const contentHeadings = Array.from(
		props.contentRef.querySelectorAll('h2, h3, h4')
	);
	const currentHeading = contentHeadings.find(heading => {
		const rect = heading.getBoundingClientRect();
		return rect.top > 0 && rect.top < window.innerHeight / 2;
	});
	if (!currentHeading) return null;
	return props.items.find(item => item.slug === currentHeading.id);
};

const onScroll = () => {
	const currentActiveItem = getCurrentActiveItem();
	currentActiveItemSlug.value = currentActiveItem?.slug || '';
};

onMounted(() => {
	onScroll();
	window.addEventListener('scroll', onScroll);
});
onUnmounted(() => {
	window.removeEventListener('scroll', onScroll);
});

const scrollTo = (slug: string) => {
	const element = document.getElementById(slug);
	if (!element) return;
	// scroll with 100px offset
	window.scrollTo({
		top: element.offsetTop - 100,
		behavior: 'smooth'
	});
};
</script>
<style scoped lang="scss">
.table-of-contents {
	border-radius: 2px;
	background: rgba(21, 21, 21, 0.5);
	border: 1px solid #000;
}
.item {
	display: flex;
	align-items: center;
	padding: 20px 32px;
	font-weight: 600;
	transition: color 0.2s ease-in-out, padding-left 0.2s ease-in-out;
	position: relative;
	&:not(:last-child) {
		border-bottom: 1px solid #000000;
	}
	&:hover {
		color: #b73e38;
	}
	&::after {
		content: '';
		position: absolute;
		z-index: 0;
		top: 0;
		left: 0;
		right: 0;
		bottom: 0;
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
		transition: opacity 0.2s ease-in-out;
		opacity: 0;
	}
	&.active,
	&:hover {
		color: #b73e38;
	}
	&:hover::after {
		opacity: 0.3;
	}
	&.active::after {
		opacity: 0.5;
	}

	&::before {
		content: '';
		width: 0;
		height: 16px;
		margin-right: 0;
		background: center / cover no-repeat url('@/assets/chevron-left.svg');
		display: block;
		transform: scale(0);
		transition: transform 0.2s ease-in-out, margin 0.2s ease-in-out,
			width 0.2s ease-in-out;
	}
	&.active {
		padding-left: 16px;
		&::before {
			transform: scale(1);
			width: 16px;
			margin-right: 8px;
		}
	}
}
</style>
