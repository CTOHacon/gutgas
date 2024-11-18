<template>
	<div
		class="header-mobile"
		:style="{
			height: headerHeight + 'px'
		}"
	>
		<div class="f- justify-between align-center top">
			<Link :href="`/${usePage().props.locale}`" class="logo">
				<img src="@/assets/header-logo.svg" alt="Gutgas" />
			</Link>
			<button class="f- align-center" @click="opened = !opened">
				<span class="mr-16 fs-semi-large">
					{{ __('menu') }}
				</span>
				<svg width="16" height="13" viewBox="0 0 16 13" fill="none">
					<line y1="0.5" x2="16" y2="0.5" stroke="white" />
					<line y1="6.5" x2="16" y2="6.5" stroke="white" />
					<line y1="12.5" x2="16" y2="12.5" stroke="white" />
				</svg>
			</button>
		</div>
		<div class="content">
			<HeaderPhone class="fs-semi-large mb-88" />
			<div class="menu-wrapper">
				<ul class="menu">
					<li class="menu-item" v-for="item in items">
						<Link
							:href="item.url"
							class="menu-link"
							:class="{ active: activePath === item.url }"
						>
							{{ item.title }}
						</Link>
					</li>
				</ul>
			</div>
			<LocaleSelect
				:reverse="true"
				class="mt-88 fs-large ml-auto mr-auto"
			/>
		</div>
	</div>
</template>

<script setup lang="ts">
import { THeaderMenuItem } from '@/types/THeaderMenu';
import { getGlobalSetting } from '@/utils/getGlobalSetting';
import { Link, usePage } from '@inertiajs/vue3';
import { onMounted, ref, watch } from 'vue';
import HeaderPhone from './HeaderPhone.vue';
import LocaleSelect from './LocaleSelect.vue';

const { __ } = useTranslations();
const opened = ref(false);

const items = getGlobalSetting<THeaderMenuItem[]>('headerMenu') ?? [];
const activePath = ref(hasWindow() ? location.pathname : '');

const headerHeight = ref(48);

watch(
	() => opened.value,
	value => {
		if (value) {
			headerHeight.value = window.innerHeight - 110;
		} else {
			headerHeight.value = 48;
		}
	}
);

onMounted(() => {
	activePath.value = location.pathname;
});
</script>

<style scoped lang="scss">
@import '@/styles/variables.scss';
.header-mobile {
	display: none;
	border-radius: 32px;
	border: 1px solid #242424;
	box-shadow: 0px -51px 44px -26px #000;
	backdrop-filter: blur(4px);
	background: rgba(19, 19, 19, 0.86);

	@media (max-width: $tablet-width) {
		display: flex;
	}

	flex-direction: column;

	position: fixed;
	z-index: 100;
	top: 16px;
	left: 16px;
	right: 16px;
	width: auto;
	overflow: hidden;
	transition: height 0.3s ease-in-out;
	height: 48px;
}

.top {
	padding: 0 25px 0 18px;
	min-height: 46px;
	height: 46px;
}

.logo {
	width: 137px;
}
.cart-button {
	margin-left: auto;
}
.content {
	padding: 25px;
	flex: 1;
	display: flex;
	flex-direction: column;
	max-height: calc(100% - 48px);
}
.menu-wrapper {
	flex: 1;
	overflow: auto;
}

.menu-item {
	&:not(:last-child) {
		margin-bottom: 16px;
	}
	a {
		display: block;
		padding: 8px;
		font-size: 18px;
		border-bottom: 2px solid rgba(219, 219, 219, 0.25);
		&.active {
			border-bottom: 2px solid #f24942;
		}
	}
}
</style>
