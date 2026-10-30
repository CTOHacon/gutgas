<template>
	<Head>
		<title>{{ __('title') }}</title>
	</Head>
	<WebsitePage>
		<PageHeadSpacer class="mb-60" />
		<BaseBreadcrumbs class="mt-32 mb-40" />
		<div class="container mb-116">
			<h1 class="fs-h4 mb-16">{{ __('title') }}</h1>
			<p class="intro mb-48">{{ __('intro') }}</p>

			<div v-if="submitted" ref="successRef" class="success">
				<svg
					class="success__icon mb-24"
					viewBox="0 0 48 48"
					aria-hidden="true"
				>
					<circle cx="24" cy="24" r="22" />
					<path d="M14 24.5l7 7 13-14" />
				</svg>
				<h2 class="fs-h5 mb-12">{{ __('success_title') }}</h2>
				<p class="success__text mb-32">{{ __('success_text') }}</p>
				<div class="success__actions">
					<BaseButton class="p-16 fs-medium" @click="submitted = false">
						{{ __('another') }}
					</BaseButton>
					<Link :href="`/${locale}`" class="underline color-danger fs-medium">
						{{ __('home') }}
					</Link>
				</div>
			</div>

			<div v-else class="g-layout layout">
				<form
					class="g-col-7 g-tablet-col-12 registration-form"
					@submit.prevent="onSubmit"
				>
					<fieldset class="group">
						<legend class="group__title fs-medium">
							{{ __('section_contact') }}
						</legend>
						<div class="fields">
							<BaseFormInput
								v-model="form.first_name"
								:placeholder="`${__('label_first_name')} *`"
								:error-message="form.errors.first_name"
							/>
							<BaseFormInput
								v-model="form.last_name"
								:placeholder="`${__('label_last_name')} *`"
								:error-message="form.errors.last_name"
							/>
							<BaseFormInput
								:model-value="form.phone"
								:placeholder="`${__('label_phone')} *`"
								type="tel"
								:error-message="form.errors.phone"
								:is-valid="form.phone.length === 19"
								@update:model-value="onPhoneInput"
							/>
							<BaseFormInput
								v-model="form.email"
								:placeholder="`${__('label_email')} *`"
								type="email"
								:error-message="form.errors.email"
								:is-valid="isEmailValid(form.email)"
							/>
							<BaseFormInput
								v-model="form.city"
								:placeholder="`${__('label_city')} *`"
								:error-message="form.errors.city"
							/>
							<BaseFormInput
								v-model="form.address"
								:placeholder="__('label_address')"
								:error-message="form.errors.address"
							/>
						</div>
					</fieldset>

					<fieldset class="group">
						<legend class="group__title fs-medium">
							{{ __('section_product') }}
						</legend>
						<div class="fields">
							<div>
								<BaseFormInput
									v-model="form.model"
									:placeholder="__('label_model')"
									:error-message="form.errors.model"
								/>
								<p class="hint mt-8">{{ __('model_hint') }}</p>
							</div>
							<div>
								<BaseFormInput
									v-model="form.serial_number"
									:placeholder="`${__('label_serial_number')} *`"
									:error-message="form.errors.serial_number"
								/>
								<p class="hint mt-8">{{ __('serial_hint') }}</p>
							</div>
							<label class="date-field">
								<span class="hint">{{ __('label_purchase_date') }} *</span>
								<BaseFormInput
									v-model="form.purchase_date"
									placeholder=""
									type="date"
									:error-message="form.errors.purchase_date"
								/>
							</label>
							<BaseFormInput
								v-model="form.price"
								class="price-field"
								:placeholder="`${__('label_price')} *`"
								type="number"
								:error-message="form.errors.price"
							/>
						</div>
					</fieldset>

					<fieldset class="group">
						<legend class="group__title fs-medium">
							{{ __('section_purchase') }}
						</legend>
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
						/>
					</fieldset>

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

					<div class="submit-row">
						<BaseButton
							type="submit"
							class="submit p-16 fs-medium"
							:disabled="form.processing"
						>
							{{ __('submit') }}
						</BaseButton>
						<p class="hint">{{ __('required') }}</p>
					</div>
				</form>

				<aside class="g-col-5 g-tablet-col-12 aside">
					<div class="aside__card">
						<img
							src="@/assets/home-about-balon-1.png"
							alt=""
							class="aside__image"
							loading="lazy"
						/>
						<h2 class="fs-medium mb-12">{{ __('aside_title') }}</h2>
						<p class="aside__text mb-16">{{ __('aside_text') }}</p>
						<p class="aside__note">{{ __('aside_note') }}</p>
					</div>
				</aside>
			</div>
		</div>
	</WebsitePage>
</template>

<script setup lang="ts">
import { nextTick, ref } from 'vue';
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
const successRef = ref<HTMLElement | null>(null);

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
			// Bring the confirmation into view below the fixed header
			nextTick(() => {
				const top = (successRef.value?.getBoundingClientRect().top ?? 0) + window.scrollY - 160;
				window.scrollTo({ top: Math.max(top, 0), behavior: 'smooth' });
			});
		}
	});
};
</script>

<style scoped lang="scss">
@import '@/styles/variables';

.intro {
	max-width: 640px;
	color: #bdbdbd;
}

.layout {
	align-items: start;
	row-gap: 3rem;
}

.group {
	border: 0;
	margin: 0 0 2.5rem;
	padding: 0;
	min-width: 0;
}
.group__title {
	padding: 0 0 0.75rem;
	margin: 0 0 0.5rem; // global styles shift legend left by 7px
	width: 100%;
	color: #fff;
	border-bottom: 1px solid rgba(131, 141, 151, 0.24);
}

.fields {
	display: grid;
	grid-template-columns: 1fr 1fr;
	column-gap: 1.5rem;
	row-gap: 0.25rem;
	align-items: end;
	@media (max-width: $large-mobile-width) {
		grid-template-columns: 1fr;
	}
}

.hint {
	font-size: 0.875rem;
	color: #828282;
	display: block;
}

.date-field {
	display: block;
	padding-top: 0.75rem;
	:deep(input) {
		color-scheme: dark;
	}
}

.price-field :deep(input) {
	-moz-appearance: textfield;
	&::-webkit-outer-spin-button,
	&::-webkit-inner-spin-button {
		-webkit-appearance: none;
		margin: 0;
	}
}

.submit-row {
	display: flex;
	align-items: center;
	flex-wrap: wrap;
	gap: 1rem 1.5rem;
}
.submit {
	min-width: 260px;
}

.aside {
	position: sticky;
	top: 140px;
	@media (max-width: $tablet-width) {
		position: static;
	}
}
.aside__card {
	border-radius: 8px;
	background: rgba(21, 21, 21, 0.5);
	border: 1px solid rgba(131, 141, 151, 0.16);
	padding: 2rem;
}
.aside__image {
	display: block;
	width: 120px;
	height: 120px;
	margin-bottom: 1.5rem;
	border-radius: 50%;
	box-shadow: 0 0 0 6px rgba(242, 73, 66, 0.12);
}
.aside__text {
	color: #bdbdbd;
	line-height: 1.5;
}
.aside__note {
	font-size: 0.875rem;
	color: #828282;
	padding-top: 1rem;
	border-top: 1px dashed rgba(131, 141, 151, 0.4);
}

.success {
	max-width: 640px;
	margin: 0 auto;
	padding: 3rem 2rem;
	text-align: center;
	border-radius: 8px;
	background: rgba(21, 21, 21, 0.5);
	border: 1px solid rgba(131, 141, 151, 0.16);
}
.success__icon {
	width: 56px;
	height: 56px;
	fill: none;
	stroke: #f24942;
	stroke-width: 2.5;
	stroke-linecap: round;
	stroke-linejoin: round;
}
.success__text {
	color: #bdbdbd;
	max-width: 460px;
	margin-left: auto;
	margin-right: auto;
}
.success__actions {
	display: flex;
	flex-direction: column;
	align-items: center;
	gap: 1.25rem;
}

.hp {
	position: absolute;
	left: -9999px;
	width: 1px;
	height: 1px;
	overflow: hidden;
}
</style>
