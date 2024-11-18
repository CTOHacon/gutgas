<template>
	<button class="desktop-header-cart-button" :class="{ active: isActive }">
		<span class="fs-medium color-danger fw-500" v-if="isCartModalOpened">{{
			cartTotalQuantity
		}}</span>
		<svg viewBox="0 0 58 58" fill="none" v-else>
			<path
				fill-rule="evenodd"
				clip-rule="evenodd"
				d="M12.75 15C12.3358 15 12 15.3358 12 15.75C12 16.1642 12.3358 16.5 12.75 16.5H17.1352L18.2578 22.1088C18.2617 22.1353 18.2669 22.1613 18.2734 22.1868L20.3644 32.6336L20.3644 32.634C20.5131 33.382 20.9201 34.0539 21.5141 34.5321C22.1063 35.0088 22.8468 35.2628 23.6067 35.25H35.7433C36.5032 35.2628 37.2437 35.0088 37.8359 34.5321C38.43 34.0538 38.837 33.3818 38.9856 32.6336L38.9857 32.6336L38.9867 32.628L40.9867 22.1405C41.0286 21.9209 40.9704 21.6941 40.8279 21.5219C40.6854 21.3497 40.4735 21.25 40.25 21.25H19.6157L18.4854 15.6028C18.4153 15.2523 18.1075 15 17.75 15H12.75ZM21.8354 32.3403L19.9159 22.75H39.3435L37.5144 32.3414L37.5139 32.3439C37.4334 32.7457 37.2145 33.1066 36.8953 33.3636C36.5753 33.6212 36.175 33.758 35.7644 33.7501L35.75 33.75H23.6L23.5856 33.7501C23.175 33.758 22.7747 33.6212 22.4547 33.3636C22.1348 33.1061 21.9156 32.7442 21.8356 32.3414L21.8354 32.3403ZM20.25 40.75C20.25 39.3693 21.3693 38.25 22.75 38.25C24.1307 38.25 25.25 39.3693 25.25 40.75C25.25 42.1307 24.1307 43.25 22.75 43.25C21.3693 43.25 20.25 42.1307 20.25 40.75ZM34 40.75C34 39.3693 35.1193 38.25 36.5 38.25C37.8807 38.25 39 39.3693 39 40.75C39 42.1307 37.8807 43.25 36.5 43.25C35.1193 43.25 34 42.1307 34 40.75Z"
				fill="#F24942"
			/>
		</svg>
		<span class="products-count" v-if="!isCartModalOpened">
			{{ cartTotalQuantity }}
		</span>
		<svg
			width="28"
			height="28"
			viewBox="0 0 28 28"
			v-if="isCartModalOpened"
			class="tip-decoration"
		>
			<circle
				cx="14"
				cy="14"
				r="14"
				transform="matrix(-1 0 0 1 28 0)"
				fill="#0F0F10"
			/>
			<path
				d="M22 9L11 20L6 15"
				stroke="#F24942"
				stroke-width="2"
				stroke-linecap="round"
				stroke-linejoin="round"
			/>
		</svg>
	</button>
</template>

<script setup lang="ts">
import useCart from '@/composables/cart';

const props = defineProps<{
	isActive: boolean;
}>();

const { isCartModalOpened, cartTotalQuantity } = useCart();
</script>

<style scoped lang="scss">
@import '@/styles/variables.scss';
.desktop-header-cart-button {
	width: 56px;
	height: 56px;
	line-height: 1;
	position: relative;
	border: 1px solid #ff5c55;
	border-radius: 50%;
	&:hover {
		background: rgba($color: #f24942, $alpha: 0.1);
	}
	@media (max-width: $small-desktop-width) {
		width: 44px;
		height: 44px;
	}
}
svg {
	width: 100%;
	height: 100%;
	transition: 0.2s ease-in-out;

	circle,
	path {
		transition: 0.2s ease-in-out;
	}
}
.products-count {
	position: absolute;
	width: 20px;
	height: 20px;
	border-radius: 50%;
	z-index: 1;
	bottom: -3px;
	right: -3px;
	background: #f24942;
	color: #000;
	display: flex;
	justify-content: center;
	align-items: center;
	line-height: 0;
	font-size: 10px;
}

.tip-decoration {
	position: absolute;
	width: 30px;
	height: 30px;
	bottom: -6px;
	right: -3px;
}
</style>
