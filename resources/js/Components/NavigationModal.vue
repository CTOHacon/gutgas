<template>
	<BaseModal
		:is-opened="isNavigationModalOpened"
		class="navigation-modal"
		@close="toggleNavigationModal"
	>
		<nav class="body">
			<NavigationModalHead />
			<NavigationModalMenu class="menu fs-medium" />
			<NavigationModalFooter />
		</nav>
	</BaseModal>
</template>

<script setup lang="ts">
import useNavigationModal from '@/composables/navigationModal';
import BaseModal from '@/Components/BaseModal.vue';
import NavigationModalFooter from '@/Components/NavigationModalFooter.vue';
import NavigationModalHead from '@/Components/NavigationModalHead.vue';

const { isNavigationModalOpened, toggleNavigationModal } = useNavigationModal();
</script>

<style scoped lang="scss">
.navigation-modal {
	position: fixed;
	top: 0;
	left: 0;
	width: 100%;
	height: 100%;
	background: rgba(15, 15, 16, 0.8);
	z-index: 100;

	&.fade-enter-from .body,
	&.fade-leave-to .body {
		transform: translateX(-100%);
	}
	&.fade-enter-to .body,
	&.fade-leave-from .body {
		transform: translateX(0%);
	}
}
.close-area {
	position: fixed;
	top: 0;
	left: 0;
	width: 100%;
	height: 100%;
	z-index: 1;
}
.body {
	position: fixed;
	top: 0;
	left: 0;
	max-width: 320px;
	width: 100%;
	height: 100%;
	z-index: 2;
	background: #101010;
	box-shadow: 45px 0px 193px -33px #000000;
	transition: transform 0.3s ease-in-out;
	display: flex;
	flex-direction: column;
	@media (max-width: 400px) {
		max-width: 80vw;
	}
}

.menu {
	flex: 1;
}
</style>
