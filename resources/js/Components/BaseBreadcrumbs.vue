<template>
	<nav class="container">
		<ol
			class="base-breadcrumbs fw-600 lh-150"
			itemscope
			itemtype="https://schema.org/BreadcrumbList"
		>
			<li
				class="item"
				v-for="(breadcrumb, index) in breadcrumbs"
				:key="breadcrumb.slug"
				itemprop="itemListElement"
				itemscope
				itemtype="https://schema.org/ListItem"
			>
				<Link
					v-if="!isCurrent(breadcrumb.slug)"
					:href="breadcrumb.slug || ''"
					itemprop="item"
					class="link"
				>
					<span itemprop="name">
						{{ breadcrumb.title }}
					</span>
				</Link>
				<span v-else itemprop="name" aria-current="page">
					{{ breadcrumb.title }}
				</span>
				<meta itemprop="position" :content="`${index + 1}`" />
			</li>
		</ol>
	</nav>
</template>

<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();
const breadcrumbs = computed(() => page.props.breadcrumbs ?? []);

// page.url is available during SSR too, so the current item is rendered as
// plain text in the initial HTML (not only after hydration).
const currentPath = computed(() => page.url.split('?')[0].replace(/\/+$/, ''));
const isCurrent = (slug: string) => slug.replace(/\/+$/, '') === currentPath.value;
</script>

<style scoped lang="scss">
.base-breadcrumbs {
	list-style: none;
	padding: 0;
	margin: 0;
	display: flex;
	align-items: center;
	flex-wrap: wrap;
}
.item {
	color: #b73e38;
	display: flex;
	align-items: center;
	position: relative;
}
.item {
	&:hover {
		text-decoration: underline;
	}
	&:not(:last-child)::after {
		content: '';
		display: block;
		position: relative;
		background: url('@/assets/breadcrumbs-separator.svg') no-repeat center;
		width: 16px;
		height: 16px;
		margin: 0 6px 0 4px;
	}
	&:last-child {
		opacity: 0.5;
		pointer-events: none;
	}
}
</style>
