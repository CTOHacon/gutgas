<template>
	<div
		class="quantity-field"
		:class="{
			disabled: disabled
		}"
	>
		<button
			class="control-button"
			@click="emit('update:modelValue', modelValue - 1)"
			:disabled="modelValue <= min"
		>
			<svg width="32" height="32" viewBox="0 0 32 32">
				<path
					d="M20 15L16 19L12 15L20 15Z"
					stroke="#838D97"
					fill="#838D97"
					stroke-linecap="round"
					stroke-linejoin="round"
				/>
			</svg>
		</button>
		<input class="input" type="text" :value="modelValue" @input="onInput" />
		<button
			class="control-button"
			@click="emit('update:modelValue', modelValue + 1)"
			:disabled="modelValue >= max"
		>
			<svg width="32" height="32" viewBox="0 0 32 32">
				<path
					d="M20 17L16 13L12 17L20 17Z"
					stroke="#838D97"
					fill="#838D97"
					stroke-linecap="round"
					stroke-linejoin="round"
				/>
			</svg>
		</button>
	</div>
</template>

<script setup lang="ts">
import { ref } from 'vue';

const props = defineProps<{
	disabled?: boolean;
	modelValue: number;
	max?: number;
	min?: number;
}>();
const max = ref(props.max ?? Infinity);
const min = ref(props.min ?? 0);

const onInput = (event: Event) => {
	const target = event.target as HTMLInputElement;
	target.value = target.value.replace(/\D/g, '');
	const value = Number(target.value);
	if (target.value.length === 0) return;
	if (value > max.value) {
		target.value = max.value.toString();
		emit('update:modelValue', max.value);
	} else if (value < min.value) {
		target.value = min.value.toString();
		emit('update:modelValue', min.value);
	} else {
		emit('update:modelValue', value);
	}
};

const emit = defineEmits({
	'update:modelValue': (value: number) => true
});
</script>

<style scoped lang="scss">
.quantity-field {
	display: flex;
	align-items: center;
	transition: opacity 0.2s ease-in-out;
	&.disabled {
		opacity: 0.5;
		pointer-events: none;
	}
}
.control-button {
	border-radius: 54px;
	border: 1px solid rgba(149, 149, 150, 0.24);
	line-height: 0;
	transition: border 0.2s ease-in-out, opacity 0.2s ease-in-out;
	path {
		transition: 0.3s;
	}
	&:hover {
		border: 1px solid #f24942;
		background: rgba($color: #f24942, $alpha: 0.14);
		path {
			fill: #f24942;
			stroke: #f24942;
		}
	}
	&:active {
		border: 1px solid rgba($color: #f24942, $alpha: 0.32);
	}
	&:disabled {
		border: 1px solid rgba(149, 149, 150, 0.24);
		opacity: 0.5;
	}
}
.input {
	margin: 0 10px;
	border-radius: 106px;
	border: 1px solid #202020;
	padding: 8px;
	width: 76px;
	text-align: center;

	color: currentColor;
	font-size: 1.25rem;
	font-weight: 800;
	line-height: 100%;
	-webkit-appearance: none;
	-moz-appearance: none;
	appearance: none;
	@media (max-width: 768px) {
		width: 60px;
		font-size: 1.125rem;
		margin: 0 4px;
	}
}
</style>
