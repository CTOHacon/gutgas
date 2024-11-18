<template>
	<button
		class="header-mobile-menu-toggle-button"
		@click="toggleNavigationModal"
	>
		<span class="text">
			<slot />
		</span>
		<!-- hamburger svg -->
		<svg
			width="16"
			height="13"
			viewBox="0 0 16 13"
			fill="none"
			xmlns="http://www.w3.org/2000/svg"
		>
			<line y1="0.5" x2="16" y2="0.5" stroke="white" />
			<line y1="6.5" x2="16" y2="6.5" stroke="white" />
			<line y1="12.5" x2="16" y2="12.5" stroke="white" />
		</svg>
	</button>
</template>

<script setup lang="ts">
import useNavigationModal from '@/composables/navigationModal';

const { toggleNavigationModal } = useNavigationModal();
</script>

<style scoped lang="scss">
.header-mobile-menu-toggle-button {
	display: flex;
	align-items: center;
}
.text {
	margin-right: 16px;
	font-size: 18px;
}
</style>
