<template>
	<div class="pagination" ref="paginationRef" v-if="links.length > 1">
		<Link
			:href="data.prev_page_url || ''"
			class="item"
			:class="{
				disabled: data.prev_page_url === null
			}"
			preserve-scroll
			aria-label="Previous"
			itemprop="url"
		>
			<svg width="6" height="10" viewBox="0 0 6 10" fill="none">
				<path
					d="M5 9L1 5L5 1V9Z"
					stroke="#838D97"
					fill="#838D97"
					stroke-linecap="round"
					stroke-linejoin="round"
				/>
			</svg>
		</Link>
		<Link
			v-for="link in links"
			:key="link.url"
			:href="link.url || ''"
			class="item"
			:class="{ current: link.active, disabled: link.url === null }"
			preserve-scroll
			itemprop="url"
		>
			{{ link.label }}
		</Link>
		<Link
			:href="data.next_page_url || ''"
			class="item"
			:class="{
				disabled: data.next_page_url === null
			}"
			preserve-scroll
			aria-label="Next"
			itemprop="url"
		>
			<svg width="6" height="10" viewBox="0 0 6 10" fill="none">
				<path
					d="M1 9L5 5L1 1V9Z"
					stroke="#F24942"
					fill="#F24942"
					stroke-linecap="round"
					stroke-linejoin="round"
				/>
			</svg>
		</Link>
	</div>
</template>

<script setup lang="ts">
import { IPaginated } from '@/types/IPaginated';
import { Link } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
const props = defineProps<{
	data: IPaginated<{}>;
}>();
const links = ref(props.data.links.slice(1, -1));

const paginationRef = ref<HTMLElement | undefined>();

const scrollTo = () => {
	if (paginationRef.value) {
		let additionSpacing = 24;
		if (window.matchMedia('(max-width: 767px)').matches) {
			additionSpacing = 100;
		}
		window.scrollTo({
			top:
				paginationRef.value.offsetTop -
				window.innerHeight +
				paginationRef.value.offsetHeight +
				additionSpacing
		});
	}
};

onMounted(() => {
	if (window.location.pathname.includes('page-')) {
		scrollTo();
	}
});
</script>

<style scoped lang="scss">
.pagination {
	display: flex;
	justify-content: center;
	& > *:not(:last-child) {
		margin-right: 10px;
	}
}
.item {
	width: 32px;
	height: 32px;
	display: flex;
	align-items: center;
	justify-content: center;
	font-weight: 600;
	border-radius: 50%;
	border: 1px solid #b73e38;
	color: #b73e38;
	transition: border-color 0.3s ease, color 0.3s ease;
	svg path {
		transition: stroke 0.3s ease, fill 0.3s ease;
		stroke: #f24942;
		fill: #f24942;
	}
	&:hover {
		border-color: #f24942;
		color: #f24942;
	}
	&.disabled {
		pointer-events: none;
		border: 1px solid rgba(149, 149, 150, 0.24);
		svg path {
			stroke: #838d97;
			fill: #838d97;
		}
	}
	&.current {
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
		border: 1px solid #f24942;
		color: #f24942;
	}
}
</style>
