<template>
	<button
		class="cart-button"
		:class="{ active: active }"
		@click="emit('click')"
	>
		<img v-if="!active" class="icon" src="@/assets/add-to-cart.svg" />
		<img v-else class="icon" src="@/assets/yess-tip-accent.svg" />
		<span class="text" v-if="!active">{{ __('addToCart') }}</span>
		<span class="text" v-else>{{ __('inCart') }}</span>
	</button>
</template>

<script setup lang="ts">
defineProps<{ active: boolean }>();
const { __ } = useTranslations();
const emit = defineEmits({
	click: () => true
});
</script>

<style scoped lang="scss">
.cart-button {
	padding: 4px 20px 4px 8px;
	display: flex;
	align-items: center;
	font-weight: 600;
	transition: color 0.3s ease-in-out;
	.icon {
		margin-right: 4px;
	}
	&:hover {
		color: #f24942;
	}
	&.active {
		color: rgba($color: #b73e38, $alpha: 0.4);
	}
}
</style>
