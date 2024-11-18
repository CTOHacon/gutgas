<template>
	<Transition
		name="fade"
		@enter="emit('enter')"
		@leave="emit('leave')"
		@before-leave="emit('beforeLeave')"
	>
		<div class="modal" v-if="isOpened">
			<div class="close-area" @click="emit('close')"></div>
			<slot></slot>
		</div>
	</Transition>
</template>

<script setup lang="ts">
const props = defineProps<{
	isOpened: boolean;
}>();

const emit = defineEmits({
	close: () => true,
	enter: () => true,
	leave: () => true,
	beforeLeave: () => true
});
</script>

<style scoped lang="scss">
.modal {
	position: fixed;
	top: 0;
	left: 0;
	width: 100%;
	height: 100%;
	background: rgba(15, 15, 16, 0.8);
	z-index: 100;
}
.close-area {
	position: fixed;
	top: 0;
	left: 0;
	width: 100%;
	height: 100%;
	z-index: 1;
}
.fade-enter-active,
.fade-leave-active {
	transition: opacity 0.3s ease-in-out;
}
.fade-enter-from,
.fade-leave-to {
	opacity: 0;
}
</style>
