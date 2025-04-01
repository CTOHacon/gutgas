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
import { onMounted } from 'vue';

const { setupCart } = useCart();
setupCart();

onMounted(() => {
	if (typeof window !== 'undefined') {
		const html = document.querySelector('html');
		if (html) {
			html.classList.add('enable-transitions');
		}
	}
});
</script>

<style global lang="scss">
html:not(.enable-transitions) * {
	transition: none !important;
}
</style>
