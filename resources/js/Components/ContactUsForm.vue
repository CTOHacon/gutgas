<template>
	<form class="contact-us-form" @submit.prevent="onSubmit">
		<div class="">
			<BaseFormInput
				v-model="form.name"
				:placeholder="__('formName')"
				:error-message="form.errors.name"
				:is-valid="form.name.length > 3"
				class="mb-12"
			/>
			<BaseFormInput
				v-model="form.phone"
				:placeholder="__('formPhone')"
				mask="+38 (###) ###-##-##"
				type="tel"
				:error-message="form.errors.phone"
				:is-valid="form.phone.length === 19"
				class="mb-12"
			/>
			<BaseFormInput
				v-model="form.email"
				:placeholder="__('formEmail')"
				type="email"
				:error-message="form.errors.email"
				:is-valid="isEmailValid(form.email)"
				class="mb-12"
			/>
			<BaseFormTextarea
				v-model="form.question"
				:error-message="form.errors.question"
				:rows="5"
				:placeholder="__('formQuestion')"
			/>
		</div>
		<BaseButton
			:disabled="form.processing"
			class="submit fs-medium mt-16"
			>{{ __('formSend') }}</BaseButton
		>
		<small class="policy-message fs-semi-small mt-12">
			{{ __('formAgree') }} <a href="#">{{ __('formAgreeLink') }}</a>
		</small>
	</form>
</template>

<script setup lang="ts">
import BaseFormInput from './BaseFormInput.vue';
import BaseFormTextarea from './BaseFormTextarea.vue';
import BaseButton from './BaseButton.vue';
import { useForm, usePage } from '@inertiajs/vue3';
import { isEmailValid } from '@/utils/IsEmailValid';

const { __ } = useTranslations();

const form = useForm({
	name: '',
	phone: '',
	email: '',
	question: ''
});
const onSubmit = () => {
	form.post(route('consultationMail'), {
		onSuccess: () => {},
		preserveScroll: true
	});
};
</script>

<style scoped lang="scss">
.submit {
	padding: 0.75rem 2.75rem;
	font-weight: 700;
}
.policy-message {
	color: rgba(183, 62, 56, 1);
	max-width: 255px;
	display: block;
	a {
		color: rgba(242, 73, 66, 1);
		text-decoration: underline;
	}
}
</style>
