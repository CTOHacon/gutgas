<template>
	<div class="mobile-overlay">
		<HeaderMobile />
		<CartOverlay />
	</div>
</template>

<script setup lang="ts">
import HeaderMobile from './HeaderMobile.vue';
import CartOverlay from './CartOverlay.vue';
</script>

<style scoped lang="scss">
@import '@/styles/variables.scss';
.mobile-overlay {
	@media (min-width: $tablet-width) {
		display: none;
	}
}
</style>
