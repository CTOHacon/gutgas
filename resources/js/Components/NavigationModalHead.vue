<template>
	<div class="navigation-modal-head background-semi-black">
		<div class="f-row align-center p-16">
			<button class="close-button mr-8" @click="toggleNavigationModal">
				<svg width="24" height="25" viewBox="0 0 24 25">
					<rect
						x="2.64645"
						y="20.9705"
						width="24.5"
						height="1.5"
						transform="rotate(-45 2.64645 20.9705)"
						fill="#FFFFFF"
						stroke="#FFFFFF"
						stroke-width="0.5"
					/>
					<rect
						x="0.353553"
						width="24.5"
						height="1.5"
						transform="matrix(-0.707107 -0.707107 -0.707107 0.707107 21.2813 21.2205)"
						fill="#FFFFFF"
						stroke="#FFFFFF"
						stroke-width="0.5"
					/>
				</svg>
			</button>
			<h4 class="title fs-semi-large">Меню</h4>
			<LocaleSelect class="lang-switch fs-semi-large" />
		</div>
	</div>
</template>

<script setup lang="ts">
import useNavigationModal from '@/composables/navigationModal';
import LocaleSelect from '@/Components/LocaleSelect.vue';

const { toggleNavigationModal } = useNavigationModal();
</script>

<style scoped lang="scss">
.navigation-modal-head {
	border-bottom-right-radius: 4px;
}
.close-button {
	display: flex;
	align-items: center;
	padding: 4px;
	background: #343434;
	border-radius: 0 4px 4px 4px;
}
.title {
	font-weight: 700;
	line-height: 1.2;
	color: #ffffff;
	text-transform: uppercase;
}
.lang-switch {
	margin-left: auto;
}
</style>
