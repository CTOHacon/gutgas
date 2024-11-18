<template>
	<HeaderDesktop />
	<MobileOverlay class="mobile-overlay" />
	<slot />
	<TheFooter />
</template>

<script setup lang="ts">
import TheFooter from '@/Components/TheFooter.vue';
import useCart from '@/composables/cart';
import '@/styles/style.scss';
import MobileOverlay from '@/Components/MobileOverlay.vue';
import HeaderDesktop from '@/Components/HeaderDesktop.vue';

const { setupCart } = useCart();
setupCart();
</script>

<style scoped lang="scss"></style>
