<template>
	<form @submit.prevent="onSubmit" class="contact-us-form-small">
		<h3
			class="fs-semi-large fw-800"
			:class="{ 'mb-20': subtitle }"
			v-html="title"
			v-if="title"
		></h3>
		<p class="fs-small fw-300" v-html="subtitle" v-if="subtitle"></p>
		<BaseFormInput
			v-model="form.name"
			:placeholder="__('formName')"
			:error-message="form.errors.name"
			:is-valid="form.name.length > 3"
			class="mt-40 mb-8"
		/>
		<BaseFormInput
			v-model="form.phone"
			:placeholder="__('formPhone')"
			:error-message="form.errors.phone"
			:is-valid="form.phone.length === 19"
			type="tel"
			mask="+38 (###) ###-##-##"
			class="mt-20"
		/>
		<BaseButton class="submit mt-24" :class="submitButtonClasses">
			{{ __('formSend') }}
		</BaseButton>
		<p class="privacy-message fs-semi-small mt-16">
			{{ __('formAgree') }}
			<a>{{ __('formAgreeLink') }}</a>
		</p>
	</form>
</template>

<script setup lang="ts">
import BaseFormInput from './BaseFormInput.vue';
import BaseButton from './BaseButton.vue';
import { useForm, usePage } from '@inertiajs/vue3';

const { __, _t } = useTranslations();

const props = defineProps<{
	title?: string;
	subtitle?: string;
	submitButtonClasses?: string;
}>();

const form = useForm({
	name: '',
	phone: ''
});
const onSubmit = () => {
	form.post(route('consultationMail'), {
		onSuccess: () => {},
		preserveScroll: true
	});
};
</script>

<style scoped lang="scss">
.contact-us-form-small {
	border-radius: 4px;
	background: linear-gradient(
		146.9deg,
		rgba(255, 75, 68, 0.22) 0%,
		rgba(255, 75, 68, 0.23) 100%
	);

	display: flex;
	flex-direction: column;
	position: relative;
	& > * {
		position: relative;
		z-index: 1;
	}
	&::before {
		content: '';
		position: absolute;
		z-index: 1;
		top: 1px;
		left: 1px;
		right: 1px;
		bottom: 1px;
		border-radius: 4px;

		background: linear-gradient(
				135deg,
				rgba(236, 35, 71, 0.12) 0%,
				rgba(224, 46, 38, 0) 100%
			),
			#0f0f10;
	}
}

.submit {
	width: fit-content;
}

.privacy-message {
	color: #b73e38;
	max-width: 250px;
	text-align: center;
	margin-left: auto;
	margin-right: auto;
	a {
		text-decoration: underline;
		color: #f24942;
	}
}
</style>
