<template>
	<nav class="desktop-header-menu">
		<Link
			:href="item.url || ''"
			class="menu-item"
			:class="{
				active:
					(activePath === '/' + locale &&
						item.url === '/' + locale) ||
					(item.url !== '/' + locale && activePath.includes(item.url))
			}"
			v-for="item in items"
		>
			{{ __(item.title) }}
		</Link>
	</nav>
</template>

<script setup lang="ts">
import { THeaderMenuItem } from '@/types/THeaderMenu';
import { getGlobalSetting } from '@/utils/getGlobalSetting';
import { Link, usePage } from '@inertiajs/vue3';
import { onMounted } from 'vue';
import { ref } from 'vue';
const locale = usePage().props.locale;

const { __ } = useTranslations();

const items = getGlobalSetting<THeaderMenuItem[]>('headerMenu') ?? [];
const activePath = ref(hasWindow() ? location.pathname : '');
onMounted(() => {
	activePath.value = location.pathname;
});
</script>

<style scoped lang="scss">
@import '@/styles/variables.scss';
.desktop-header-menu {
	display: flex;
	flex-wrap: nowrap;
}
.menu-item {
	color: #bdbdbd;
	white-space: nowrap;
	position: relative;
	padding-bottom: 8px;
	transition: color 0.2s ease-in-out;
	margin-right: 1.5rem;
	// Five items must fit on one row next to the logo and the phone
	@media (max-width: $large-desktop-width) {
		font-size: 16px;
		margin-right: 1rem;
	}
	@media (max-width: $small-desktop-width) {
		font-size: 15px;
		margin-right: 0.75rem;
	}
	@media (max-width: 1150px) {
		font-size: 14px;
		margin-right: 0.6rem;
	}
	&::before {
		content: '';
		width: 100%;
		position: absolute;
		bottom: 0;
		transform: scaleX(0);
		height: 2px;
		border-radius: 2px;
		background-color: #f24942;
		transition: transform 0.2s ease-in-out,
			background-color 0.2s ease-in-out;
	}
	&:hover {
		&::before {
			transform: scaleX(1);
		}
	}
	&.active {
		color: #ffffff;
		&::before {
			transform: scaleX(1);
		}
	}
}
</style>
