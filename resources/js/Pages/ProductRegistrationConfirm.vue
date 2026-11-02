<template>
	<Head>
		<title>{{ __('confirm_page_title') }}</title>
		<meta name="robots" content="noindex" />
	</Head>
	<WebsitePage>
		<PageHeadSpacer class="mb-60" />
		<div class="container pv-88">
			<div class="result" :class="{ 'result--error': !confirmed }">
				<svg class="result__icon mb-24" viewBox="0 0 48 48" aria-hidden="true">
					<circle cx="24" cy="24" r="22" />
					<path v-if="confirmed" d="M14 24.5l7 7 13-14" />
					<path v-else d="M17 17l14 14M31 17L17 31" />
				</svg>
				<h1 class="fs-h5 mb-12">
					{{ __(confirmed ? 'confirm_success_title' : 'confirm_error_title') }}
				</h1>
				<p class="result__text mb-32">
					{{ __(confirmed ? 'confirm_success_text' : 'confirm_error_text') }}
				</p>
				<div class="result__actions">
					<BaseButton class="p-16 fs-medium" @click="goToRegistration">
						{{ __('another') }}
					</BaseButton>
					<Link :href="`/${locale}`" class="underline color-danger fs-medium">
						{{ __('home') }}
					</Link>
				</div>
			</div>
		</div>
	</WebsitePage>
</template>

<script setup lang="ts">
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import WebsitePage from '@/Layouts/WebsitePage.vue';
import PageHeadSpacer from '@/Components/PageHeadSpacer.vue';
import BaseButton from '@/Components/BaseButton.vue';

const props = defineProps<{
	confirmed: boolean;
	registrationTranslations: Record<string, string>;
}>();

const { __ } = useTranslations([props.registrationTranslations]);
const locale = usePage().props.locale as string;

const goToRegistration = () => router.visit(`/${locale}/registration`);
</script>

<style scoped lang="scss">
.result {
	max-width: 640px;
	margin: 0 auto;
	padding: 3rem 2rem;
	text-align: center;
	border-radius: 8px;
	background: rgba(21, 21, 21, 0.5);
	border: 1px solid rgba(131, 141, 151, 0.16);
}
.result__icon {
	width: 56px;
	height: 56px;
	fill: none;
	stroke: #f24942;
	stroke-width: 2.5;
	stroke-linecap: round;
	stroke-linejoin: round;
}
.result--error .result__icon {
	stroke: #828282;
}
.result__text {
	color: #bdbdbd;
	max-width: 460px;
	margin-left: auto;
	margin-right: auto;
}
.result__actions {
	display: flex;
	flex-direction: column;
	align-items: center;
	gap: 1.25rem;
}
</style>
