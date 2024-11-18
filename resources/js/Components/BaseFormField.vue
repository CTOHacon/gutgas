<template>
	<div
		class="base-form-field-wrapper"
		:class="{
			'is-focused': isFocused,
			'has-error': hasError,
			'is-valid': isValid && !hasError
			// required: required
		}"
	>
		<span class="placeholder" v-if="placeholder && !value.length">
			{{ placeholder }}
		</span>
		<div class="field-wrapper">
			<slot />
		</div>
		<Transition name="fade">
			<span class="error color-danger" v-if="hasError">
				{{ errorMessage }}
				<img class="error-icon" src="@/assets/field-error.svg" />
			</span>
		</Transition>
		<Transition name="fade">
			<img
				class="is-valid-icon"
				v-if="isValid && !hasError"
				src="@/assets/field-valid.svg"
			/>
		</Transition>
	</div>
</template>

<script setup lang="ts">
import { ref, watchEffect } from 'vue';

const props = defineProps<{
	value: string;
	placeholder: string;
	isFocused?: boolean;
	errorMessage?: string;
	isValid?: boolean;
	// required?: boolean;
}>();

const hasError = ref(false);
watchEffect(() => {
	if (props.errorMessage?.length && !props.isValid) hasError.value = true;
	else hasError.value = false;
});
</script>

<style scoped lang="scss">
@import '@/styles/variables.scss';
.base-form-field-wrapper {
	// border-bottom: 1px solid rgba(183, 62, 56, 0.16);
	border-bottom: 1px solid rgba(183, 62, 56, 0.4);
	position: relative;
	width: 100%;
	transition: border-bottom 0.3s ease-in-out;
	&.required {
	}
	&.is-focused,
	&.is-valid {
		border-bottom: 1px solid #f24942;
	}
	&::before {
		content: '';
		position: absolute;
		left: 0;
		bottom: 0;
		right: 0;
		top: 0;
		opacity: 0;
		background: linear-gradient(
			180deg,
			rgba(241, 87, 66, 0) 0%,
			rgba(241, 73, 66, 0.16) 100%
		);
		transition: opacity 0.3s ease-in-out;
		z-index: 0;
	}
	&.has-error::before {
		opacity: 1;
	}
}
.field-wrapper {
	position: relative;
	z-index: 2;
	height: 100%;
}
.placeholder {
	position: absolute;
	top: $form-field-padding-vertical;
	left: $form-field-padding-horizontal;
	line-height: 100%;
	transition: all 0.2s ease-in-out;
	pointer-events: none;
	opacity: 0.6000000238418579;
	z-index: 1;
}
.is-focused .placeholder {
	transform-origin: left center;
	transform: scale(0.85) translateY(20%);
	opacity: 0.4000000059604645;
}
.error {
	display: flex;
	align-items: center;
	padding: 3px 4px;
	position: absolute;
	z-index: 2;
	right: 0;
	top: -6px;
	font-size: 12px;
	line-height: 100%;
	text-align: right;

	pointer-events: none;
}
.error-icon {
	margin-left: 4px;
	pointer-events: none;
}
.is-valid-icon {
	position: absolute;
	top: 0;
	right: 0;
	width: 1rem;
	height: 1rem;
}
.fade-enter-from,
.fade-leave-to {
	opacity: 0;
	transform: translateY(-10px);
}
.fade-enter-active,
.fade-leave-active {
	transition: opacity 0.3s ease-in-out, transform 0.3s ease-in-out;
	transform-origin: center center;
}
.fade-enter-to,
.fade-leave-from {
	opacity: 1;
	transform: translateY(0);
}
</style>
