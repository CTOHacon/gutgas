<template>
	<title>{{ __('thankYouTitle') }}</title>
	<meta name="robots" content="noindex" />
	<WebsitePage>
		<PageHeadSpacer class="mb-60" />
		<div class="container pv-88 text-center">
			<h1 class="fs-h1 mb-16 lh-100">{{ __('thankYouTitle') }}</h1>
			<p class="fs-large mb-32">{{ __('thankYouText') }}</p>
			<p v-if="order" class="mb-16">
				{{ __('orderNumber') }}:
				<strong class="fw-800">{{ order.id }}</strong>
			</p>
			<Link
				:href="`/${usePage().props.locale}`"
				class="lh-100 underline color-danger fs-medium mt-12"
			>
				{{ __('backToHome') }}
			</Link>
		</div>
	</WebsitePage>
</template>

<script setup lang="ts">
import WebsitePage from '@/Layouts/WebsitePage.vue';
import { TPage } from '@/types/TPage';
import PageHeadSpacer from '@/Components/PageHeadSpacer.vue';
import { Link, usePage } from '@inertiajs/vue3';
import { TOrder } from '@/types/TOreder';

const props = defineProps<{
	order?: TOrder;
	thankYouTranslations: Record<string, string>;
}>();

const { _t, __ } = useTranslations([props.thankYouTranslations]);
</script>

<style scoped></style>
