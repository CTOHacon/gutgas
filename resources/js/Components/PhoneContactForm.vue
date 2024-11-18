<template>
	<div class="phone-form" :class="{ disabled: isSubmiting }">
		<p class="fs-medium mb-16">{{ __('balonInOneClick') }}</p>
		<div class="phone-field-wrapper">
			<PhoneField class="phone-field" v-model="form.phone" />
			<BaseButton @click="onSubmit" class="button">
				{{ __('order') }}
			</BaseButton>
		</div>
		<Transition name="appeare">
			<p class="error-message fs-small" v-if="form.errors.phone">
				{{ form.errors.phone }}
			</p>
		</Transition>
	</div>
</template>

<script setup lang="ts">
import { ref, watch } from 'vue';
import BaseButton from '@/Components/BaseButton.vue';
import PhoneField from '@/Components/PhoneField.vue';
import { useForm, usePage } from '@inertiajs/vue3';

const { __ } = useTranslations();

const form = useForm({
	phone: ''
});

const isSubmiting = ref(false);
const onSubmit = () => {
	isSubmiting.value = true;
	form.post(route('requestCallMail'), {
		onSuccess: () => {},
		onError: () => {
			isSubmiting.value = false;
		},
		preserveScroll: true
	});
};

// const emit = defineEmits({
// 	submit: (value: string) => true
// });
</script>

<style scoped lang="scss">
@import '@/styles/variables.scss';
.phone-form {
	transition: opacity 0.3s ease-in-out;
	user-select: none;
	position: relative;
	&.disabled {
		opacity: 0.6;
		pointer-events: none;
	}
}
.phone-field {
	flex: 1;
}
.phone-field-wrapper {
	background: linear-gradient(
			266.55deg,
			rgba(241, 73, 66, 0.04) 0%,
			rgba(241, 87, 66, 0.04) 84.62%
		),
		rgba(17, 17, 17, 0.88);
	border: 1px solid rgba(255, 75, 68, 0.16);
	box-shadow: 0px 28px 77px -23px rgba(244, 63, 63, 0.51);
	border-radius: 106px;
	display: flex;
}

.button {
	padding: 0 2.75rem;
	@media (max-width: $large-mobile-width) {
		flex: 1;
		padding: 0 1rem;
	}
}

.error-message {
	color: #f14742;
	margin-top: 0.5rem;
	position: absolute;
	top: calc(100% + 0.5rem);
	transition: opacity 0.3s ease-in-out, transform 0.2s ease-in-out;
	&.appeare-enter-from,
	&.appeare-leave-to {
		transform: translateY(-5px);
		opacity: 0;
	}
	&.appeare-enter-to,
	&.appeare-leave-from {
		transform: translateY(0);
		opacity: 1;
	}
}
</style>
