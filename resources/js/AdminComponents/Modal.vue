<template>
	<Transition name="fade">
		<div class="modal-window">
			<div class="toggler" @click="emit('close')"></div>
			<div class="modal-window__inner">
				<div class="d-flex modal-window__head mb-2">
					<button
						class="btn btn-primary ms-auto"
						@click="emit('close')"
					>
						Закрити
					</button>
				</div>
				<div class="modal-window__main">
					<slot></slot>
				</div>
			</div>
		</div>
	</Transition>
</template>

<script setup lang="ts">
const emit = defineEmits({
	close: () => true
});
</script>

<style>
/* Styling for the modal and its content */
.modal-window {
	position: fixed;
	top: 0;
	left: 0;
	width: 100%;
	height: 100%;
	background-color: rgba(0, 0, 0, 0.5);
	display: flex;
	justify-content: center;
	align-items: center;
	padding: 20px;
	z-index: 9999;
}
.toggler {
	position: absolute;
	width: 100%;
	height: 100%;
	z-index: 1;
}
.modal-window__inner {
	background-color: #fff;
	border-radius: 4px;
	max-width: 1400px;
	width: 100%;
	height: 100%;
	padding: 20px;
	overflow: hidden;
	position: relative;
	flex-direction: column;
	display: flex;
	z-index: 2;
}
.modal-window__main {
	overflow: auto;
	height: 100%;
	max-width: 100%;
	/* max-height: calc(100vh - 40px); */
}
.fade-enter-active,
.fade-leave-active {
	transition: opacity 0.2s;
}

.fade-enter-from,
.fade-leave-to {
	opacity: 0;
}

.fade-enter-to,
.fade-leave-from {
	opacity: 1;
}
</style>
