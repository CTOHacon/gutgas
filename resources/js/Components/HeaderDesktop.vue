<template>
	<header class="header" ref="headerElementRef">
		<Preheader />
		<div
			class="desktop-header"
			:class="{ scrolled: isScrolled }"
			ref="headerRef"
		>
			<div class="container fs-medium" :class="{ scrolled: isScrolled }">
				<Link :href="`/${usePage().props.locale}`" class="logo">
					<img src="@/assets/header-logo.svg" alt="Gutgas" />
				</Link>
				<HeaderDesktopMenu class="" />
				<div class="right">
					<HeaderPhone class="right__phone" />
					<CartToggleButton
						:is-active="isCartModalOpened"
						@click="isCartModalOpened = !isCartModalOpened"
					/>
					<Transition name="fade">
						<CartPopup
							class="cart-popup"
							v-if="isCartModalOpened"
							@close="isCartModalOpened = false"
						/>
					</Transition>
					<LocaleSelect class="language-switch" />
				</div>
			</div>
		</div>
		<Transition name="fade">
			<div class="cart-popup-background" v-if="isCartModalOpened"></div>
		</Transition>
	</header>
</template>

<script setup lang="ts">
import HeaderPhone from '@/Components/HeaderPhone.vue';
import CartToggleButton from '@/Components/CartToggleButton.vue';
import HeaderDesktopMenu from '@/Components/HeaderDesktopMenu.vue';
import LocaleSelect from '@/Components/LocaleSelect.vue';
import { onMounted, ref } from 'vue';
import CartPopup from './CartPopup.vue';
import { Link, usePage } from '@inertiajs/vue3';
import Preheader from './Preheader.vue';
import useHeaderElement from '@/composables/headerElement';
import useCart from '@/composables/cart';

const headerRef = ref<HTMLElement | null>(null);
const isScrolled = ref(true);

const handleScroll = () => {
	isScrolled.value = window.scrollY > 0;
};
onMounted(() => {
	handleScroll();
	window.addEventListener('scroll', handleScroll);
});

const { headerElementRef } = useHeaderElement();
const { isCartModalOpened } = useCart();
</script>

<style scoped lang="scss">
@import '@/styles/variables.scss';
.header {
	position: fixed;
	top: 0;
	left: 0;
	width: 100%;
	z-index: 100;
}
.desktop-header {
	transition: background-color 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
	background-color: rgba(15, 15, 16, 0.8);
	backdrop-filter: blur(5px);
	// background: radial-gradient(
	// 	49.34% 105.48% at 50% 100%,
	// 	#000 0%,
	// 	rgba(0, 0, 0, 0.63) 58.85%,
	// 	rgba(0, 0, 0, 0) 100%
	// );
	@media (max-width: $tablet-width) {
		display: none;
	}
}
.container {
	display: flex;
	align-items: flex-end;
	padding-top: 1rem;
	padding-bottom: 1.25rem;
	transition: padding-top 0.3s ease-in-out, padding-bottom 0.3s ease-in-out;
}
.logo {
	width: 200px;
	margin-right: 50px;
	@media (max-width: $small-desktop-width) {
		width: 150px;
		margin-right: 30px;
	}
}

.right {
	margin-left: auto;
	align-self: inherit;
	display: flex;
	align-items: center;
	margin-bottom: -4px;
	position: relative;
	& > *:not(:last-child) {
		margin-right: 24px;
	}
}
.right__phone {
	align-self: flex-end;
	margin-bottom: 4px;
}

.language-switch {
	color: #bdbdbd;
}
.cart-popup {
	position: absolute;
	top: calc(100% + 1.5rem);
	right: 0;
	width: max-content;
	width: 488px;
	&.fade-enter-active,
	&.fade-leave-active {
		transition: opacity 0.3s ease-in-out, transform 0.3s ease-in-out;
	}
	&.fade-enter-from,
	&.fade-leave-to {
		opacity: 0;
		transform: translateY(-1rem);
	}
	&.fade-enter-to,
	&.fade-leave-from {
		opacity: 1;
		transform: translateY(0);
	}
}
.cart-popup-background {
	position: absolute;
	left: 0;
	top: 100%;
	right: 0;
	bottom: 0;
	height: calc(100vh - 100%);
	z-index: -1;
	background: rgba($color: #000000, $alpha: 0.5);
	transition: opacity 0.3s ease-in;
	&.fade-enter-active,
	&.fade-leave-active {
		transition: opacity 0.3s ease-in-out;
	}
	&.fade-enter-from,
	&.fade-leave-to {
		opacity: 0;
	}
	&.fade-enter-to,
	&.fade-leave-from {
		opacity: 1;
	}
}
</style>
