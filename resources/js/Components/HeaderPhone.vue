<template>
	<a class="header-phone" :href="`tel:${phoneHref}`">{{ phone }}</a>
</template>

<script setup lang="ts">
import { getGlobalSetting } from '@/utils/getGlobalSetting';

const phone = getGlobalSetting('phone') ?? '';
const phoneHref = phone.replace(/[^\d+]/g, '');
</script>

<style scoped lang="scss">
@import '@/styles/variables.scss';
.header-phone {
	display: inline-block;
	position: relative;
	font-size: calc(1em + 2px);
	font-weight: 700;
	line-height: 1.2;
	white-space: nowrap;
	@media (max-width: $small-desktop-width) {
		font-size: 18px;
	}
	&::after {
		content: '';
		position: absolute;
		left: 0;
		bottom: -6px;
		width: 100%;
		height: 2px;
		border-radius: 2px;
		background-color: #f24942;
		pointer-events: none;
	}
}
</style>
