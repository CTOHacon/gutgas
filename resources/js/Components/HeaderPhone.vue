<template>
	<div class="desktop-header-phone">
		<a class="inner" :href="`tel:${phone}`">
			<strong class="phone">{{ phone }}</strong>
			<small class="caption fs-semi-small">{{ __('orderCall') }}</small>
		</a>
	</div>
</template>

<script setup lang="ts">
import { getGlobalSetting } from '@/utils/getGlobalSetting';

const { __ } = useTranslations();
const phone = getGlobalSetting('phone') ?? '';
</script>

<style scoped lang="scss">
@import '@/styles/variables.scss';
.desktop-header-phone {
	text-align: right;
	@media (max-width: $tablet-width) {
		text-align: left;
	}
}
.inner {
	display: block;
	padding-bottom: 10px;
	position: relative;
	&::before {
		content: '';
		width: 100%;
		position: absolute;
		bottom: 0;
		left: 0;
		height: 2px;
		border-radius: 2px;
		background-color: #f24942;
		transition: transform 0.2s ease-in-out,
			background-color 0.2s ease-in-out;
	}
}
.phone {
	font-weight: 700;
	margin-bottom: 2px;
	display: block;
}
.caption {
	font-weight: 500;
	line-height: 100%;
	opacity: 0.4;
	display: block;
}
</style>
