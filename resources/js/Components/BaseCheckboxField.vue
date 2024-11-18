<template>
	<div
		class="checkbox-field"
		:class="{
			checked: modelValue || checked
		}"
		@click="
			emit('update:modelValue', !modelValue);
			emit('check', !modelValue);
		"
	>
		<input
			type="checkbox"
			:name="name"
			:checked="modelValue"
			class="input"
		/>
		<div
			class="box"
			:class="{
				checked: modelValue || checked
			}"
		></div>
		<label class="label" :for="name">
			{{ label }}
		</label>
		<!-- <span  -->
	</div>
</template>

<script setup lang="ts">
const props = defineProps<{
	modelValue?: boolean;
	checked?: boolean;
	name?: string;
	label: string;
}>();

const emit = defineEmits({
	'update:modelValue': (value: boolean) => true,
	check: (value: boolean) => true
});
</script>

<style scoped lang="scss">
.input {
	display: none;
}
.checkbox-field {
	display: flex;
	color: #828282;
	align-items: center;
	transition: color 0.2s ease-in-out;
	cursor: pointer;
	&:hover {
		color: rgba(255, 255, 255, 0.56);
	}
	&.checked:hover {
		color: #fff;
	}
}
.box {
	position: relative;
	border-radius: 4px;
	border: 1px solid currentColor;
	background: #202020;
	transition: border 0.2s ease-in-out, background 0.2s ease-in-out;
	width: 20px;
	height: 20px;
	cursor: pointer;
	margin-right: 0.4em;
	&::before {
		content: '';
		position: absolute;
		top: 3px;
		left: 3px;
		right: 3px;
		bottom: 3px;
		transform: scale(0);
		background: #f24942;
		border-radius: 2px;
		transition: transform 0.2s ease-in-out;
	}
	&.checked {
		&::before {
			transform: scale(1);
		}
	}
}
.label {
	cursor: pointer;
}
.checkbox-field:hover {
}
</style>
