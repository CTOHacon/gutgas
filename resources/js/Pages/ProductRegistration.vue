<template>
	<Head>
		<title>{{ __('title') }}</title>
	</Head>
	<WebsitePage>
		<PageHeadSpacer class="mb-60" />
		<BaseBreadcrumbs class="mt-32 mb-40" />
		<div class="container">
			<h1 class="fs-h4 mb-24">{{ __('title') }}</h1>
			<p class="intro mb-48">{{ __('intro') }}</p>

			<div v-if="submitted" class="success pv-48 ph-24 text-center">
				<h2 class="fs-large mb-16">{{ __('success_title') }}</h2>
				<p class="mb-32">{{ __('success_text') }}</p>
				<BaseButton class="p-16 fs-medium" @click="submitted = false">
					{{ __('another') }}
				</BaseButton>
				<div class="mt-24">
					<Link
						:href="`/${locale}`"
						class="lh-100 underline color-danger fs-medium"
					>
						{{ __('home') }}
					</Link>
				</div>
			</div>

			<form v-else class="registration-form mb-116" @submit.prevent="onSubmit">
				<div class="g-layout mb-12">
					<div class="g-col-6 g-large-mobile-col-12">
						<BaseFormInput
							v-model="form.first_name"
							:placeholder="`${__('label_first_name')} *`"
							:error-message="form.errors.first_name"
							class="mb-12"
						/>
						<BaseFormInput
							:model-value="form.phone"
							:placeholder="`${__('label_phone')} *`"
							type="tel"
							:error-message="form.errors.phone"
							:is-valid="form.phone.length === 19"
							class="mb-12"
							@update:model-value="onPhoneInput"
						/>
					</div>
					<div class="g-col-6 g-large-mobile-col-12">
						<BaseFormInput
							v-model="form.last_name"
							:placeholder="`${__('label_last_name')} *`"
							:error-message="form.errors.last_name"
							class="mb-12"
						/>
						<BaseFormInput
							v-model="form.email"
							:placeholder="`${__('label_email')} *`"
							type="email"
							:error-message="form.errors.email"
							:is-valid="isEmailValid(form.email)"
							class="mb-12"
						/>
					</div>
				</div>

				<div class="g-layout mb-12">
					<div class="g-col-6 g-large-mobile-col-12">
						<BaseFormInput
							v-model="form.city"
							:placeholder="`${__('label_city')} *`"
							:error-message="form.errors.city"
						/>
					</div>
					<div class="g-col-6 g-large-mobile-col-12">
						<BaseFormTextarea
							v-model="form.address"
							:placeholder="__('label_address')"
							:error-message="form.errors.address"
							:rows="3"
						/>
					</div>
				</div>

				<div class="g-layout mb-12">
					<div class="g-col-6 g-large-mobile-col-12">
						<BaseFormInput
							v-model="form.model"
							:placeholder="__('label_model')"
							:error-message="form.errors.model"
						/>
						<details class="hint mt-8">
							<summary>{{ __('where') }}</summary>
							<p class="mt-8">{{ __('where_text') }}</p>
						</details>
					</div>
					<div class="g-col-6 g-large-mobile-col-12">
						<BaseFormInput
							v-model="form.serial_number"
							:placeholder="`${__('label_serial_number')} *`"
							:error-message="form.errors.serial_number"
						/>
						<p class="hint mt-8">{{ __('serial_hint') }}</p>
					</div>
				</div>

				<div class="g-layout mb-12">
					<div class="g-col-6 g-large-mobile-col-12">
						<span class="hint">
							{{ __('label_purchase_date') }} *
						</span>
						<BaseFormInput
							v-model="form.purchase_date"
							placeholder=""
							type="date"
							class="date-field"
							:error-message="form.errors.purchase_date"
						/>
					</div>
					<div class="g-col-6 g-large-mobile-col-12">
						<BaseFormInput
							v-model="form.price"
							:placeholder="`${__('label_price')} *`"
							type="number"
							:error-message="form.errors.price"
						/>
					</div>
				</div>

				<BaseFormInput
					v-model="form.purchase_place"
					:placeholder="`${__('label_purchase_place')} * (${__('place_hint')})`"
					:error-message="form.errors.purchase_place"
					class="mb-24"
				/>

				<BaseCheckboxField
					v-model="form.subscription_consent"
					name="subscription_consent"
					:label="__('consent')"
					class="mb-24"
				/>

				<!-- honeypot: hidden from real users, bots tend to fill it -->
				<div class="hp" aria-hidden="true">
					<input
						v-model="form.website"
						type="text"
						name="website"
						tabindex="-1"
						autocomplete="off"
					/>
				</div>

				<p class="hint mb-16">{{ __('required') }}</p>
				<BaseButton
					type="submit"
					class="submit p-16 fs-medium"
					:disabled="form.processing"
				>
					{{ __('submit') }}
				</BaseButton>
			</form>
		</div>
	</WebsitePage>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import WebsitePage from '@/Layouts/WebsitePage.vue';
import PageHeadSpacer from '@/Components/PageHeadSpacer.vue';
import BaseBreadcrumbs from '@/Components/BaseBreadcrumbs.vue';
import BaseFormInput from '@/Components/BaseFormInput.vue';
import BaseFormTextarea from '@/Components/BaseFormTextarea.vue';
import BaseCheckboxField from '@/Components/BaseCheckboxField.vue';
import BaseButton from '@/Components/BaseButton.vue';
import { isEmailValid } from '@/utils/IsEmailValid';
import { formatUaPhone } from '@/utils/formatUaPhone';
import { TProductRegistrationForm } from '@/types/TProductRegistration';

const props = defineProps<{
	registrationTranslations: Record<string, string>;
}>();

const { __ } = useTranslations([props.registrationTranslations]);
const locale = usePage().props.locale as string;

const submitted = ref(false);

const form = useForm<TProductRegistrationForm>({
	first_name: '',
	last_name: '',
	phone: '',
	email: '',
	city: '',
	address: '',
	model: '',
	serial_number: '',
	purchase_date: '',
	price: '',
	purchase_place: '',
	subscription_consent: false,
	locale,
	website: ''
});

const onPhoneInput = (value?: string) => {
	form.phone = formatUaPhone(value ?? '');
};

const onSubmit = () => {
	form.post(route('productRegistration.store'), {
		preserveScroll: true,
		onSuccess: () => {
			form.reset();
			submitted.value = true;
		}
	});
};
</script>

<style scoped lang="scss">
@import '@/styles/variables';
.intro {
	max-width: 760px;
}
.registration-form {
	max-width: 900px;
}
.hint {
	font-size: 0.875rem;
	color: #828282;
	display: block;
	summary {
		cursor: pointer;
		text-decoration: underline;
	}
}
.date-field :deep(input) {
	color-scheme: dark;
}
.hp {
	position: absolute;
	left: -9999px;
	width: 1px;
	height: 1px;
	overflow: hidden;
}
.submit {
	min-width: 260px;
}
</style>
