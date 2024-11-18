<template>
	<div class="range-slider-field" ref="fieldRef">
		<button
			class="thumb thumb-from"
			ref="fromThumb"
			@mousedown="onThumbMouseDown($event, 'from')"
			@touchstart="onThumbTouchStart($event, 'from')"
			:style="{
				left: `${fromThumbLeftPosition}%`
			}"
		/>
		<div class="track"></div>
		<div
			class="range"
			:style="{
				left: fromThumbLeftPosition + '%',
				right: toThumbRightPosition + '%'
			}"
		></div>
		<button
			class="thumb thumb-to"
			ref="toThumb"
			@mousedown="onThumbMouseDown($event, 'to')"
			@touchstart="onThumbTouchStart($event, 'to')"
			:style="{
				right: `${toThumbRightPosition}%`
			}"
		/>
	</div>
</template>

<script setup lang="ts">
import { computed, ref, watch } from 'vue';
interface ImodelValue {
	from: number;
	to: number;
}
const props = defineProps<{
	min: number;
	max: number;
	modelValue: ImodelValue;
	step: number;
}>();
const valueFrom = ref(props.modelValue.from);
const valueTo = ref(props.modelValue.to);

watch(
	() => props.modelValue,
	() => {
		valueFrom.value = props.modelValue.from;
		valueTo.value = props.modelValue.to;
	},
	{ immediate: true }
);

const fieldRef = ref<HTMLDivElement | null>(null);

const fromThumb = ref<HTMLButtonElement | null>(null);
const toThumb = ref<HTMLButtonElement | null>(null);

const fromThumbLeftPosition = computed(() => {
	const fromThumbLeftPosition =
		((valueFrom.value - props.min) / (props.max - props.min)) * 100;

	return fromThumbLeftPosition;
});
const toThumbRightPosition = computed(() => {
	const toThumbRightPosition =
		((props.max - valueTo.value) / (props.max - props.min)) * 100;
	return toThumbRightPosition;
});

const onThumbMouseDown = (event: MouseEvent, type: string) => {
	const onMouseMoveListner = (event: MouseEvent) => {
		const x = event.clientX;
		const fieldRect = fieldRef.value?.getBoundingClientRect();
		if (!fieldRect) return;

		const fieldWidth = fieldRect.width;
		const fieldLeft = fieldRect.left;
		const position = (x - fieldLeft) / fieldWidth;
		const value =
			Math.round(
				(props.min + (props.max - props.min) * position) / props.step
			) * props.step;

		if (type === 'from') {
			if (value >= valueTo.value) valueFrom.value = valueTo.value;
			else if (value < props.min) valueFrom.value = props.min;
			else valueFrom.value = value;
		} else {
			if (value <= valueFrom.value) valueTo.value = valueFrom.value;
			else if (value > props.max) valueTo.value = props.max;
			else valueTo.value = value;
		}
	};
	const onMouseUpListner = (event: MouseEvent) => {
		document.removeEventListener('mousemove', onMouseMoveListner);
		document.removeEventListener('mouseup', onMouseUpListner);
		emit('update:modelValue', { from: valueFrom.value, to: valueTo.value });
	};
	document.addEventListener('mousemove', onMouseMoveListner);
	document.addEventListener('mouseup', onMouseUpListner);
};
const onThumbTouchStart = (event: TouchEvent, type: string) => {
	const onTouchMoveListner = (event: TouchEvent) => {
		const x = event.touches[0].clientX;
		const fieldRect = fieldRef.value?.getBoundingClientRect();
		if (!fieldRect) return;

		const fieldWidth = fieldRect.width;
		const fieldLeft = fieldRect.left;
		const position = (x - fieldLeft) / fieldWidth;
		const value =
			Math.round(
				(props.min + (props.max - props.min) * position) / props.step
			) * props.step;

		if (type === 'from') {
			if (value >= valueTo.value) valueFrom.value = valueTo.value;
			else if (value < props.min) valueFrom.value = props.min;
			else valueFrom.value = value;
		} else {
			if (value <= valueFrom.value) valueTo.value = valueFrom.value;
			else if (value > props.max) valueTo.value = props.max;
			else valueTo.value = value;
		}
	};
	const onTouchEndListner = (event: TouchEvent) => {
		document.removeEventListener('touchmove', onTouchMoveListner);
		document.removeEventListener('touchend', onTouchEndListner);
		emit('update:modelValue', { from: valueFrom.value, to: valueTo.value });
	};
	document.addEventListener('touchmove', onTouchMoveListner);
	document.addEventListener('touchend', onTouchEndListner);
};

const emit = defineEmits({
	'update:modelValue': ({ from, to }: ImodelValue) => true
});
</script>

<style scoped lang="scss">
$height: 16px;
.range-slider-field {
	display: flex;
	align-items: center;
	position: relative;
	border-radius: 13px;
	width: 100%;
	height: $height;
}
.track {
	border-radius: 13px;
	background: #444444;
	position: relative;
	z-index: 0;
	height: 4px;
	width: 100%;
}
.range {
	border-radius: 13px;
	background: #f24942;
	height: 4px;
	position: absolute;
	z-index: 1;
}
.thumb {
	width: $height;
	height: $height;
	border-radius: 50%;
	background: #f24942;
	border: 2px solid #202020;
	position: absolute;
	z-index: 3;
	top: 0;
	transform-origin: center center;
	transition: transform 0.2s ease-in-out;
}
.thumb-from {
	transform: translateX(-50%);
	&:hover {
		transform: scale(1.2) translateX(-50%);
	}
}
.thumb-to {
	transform: translateX(50%);
	&:hover {
		transform: scale(1.2) translateX(50%);
	}
}
</style>
