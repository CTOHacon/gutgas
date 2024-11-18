<template>
	<nav class="container">
		<ul
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
					v-if="currentUrl !== breadcrumb.slug"
					:href="breadcrumb.slug || ''"
					itemscope
					itemtype="http://schema.org/Thing"
					itemprop="item"
					:itemid="breadcrumb.slug"
					class="link"
				>
					<span itemprop="name">
						{{ breadcrumb.title }}
					</span>
				</Link>
				<span v-else itemprop="name">
					{{ breadcrumb.title }}
				</span>
				<meta itemprop="position" :content="`${index + 1}`" />
			</li>
		</ul>
	</nav>
</template>

<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import { onMounted } from 'vue';
const {
	props: { breadcrumbs }
} = usePage();

const currentUrl = ref('');
onMounted(() => {
	currentUrl.value = window.location.href.replace(window.location.origin, '');
	console.log(currentUrl.value);
});
</script>

<style scoped lang="scss">
.base-breadcrumbs {
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
