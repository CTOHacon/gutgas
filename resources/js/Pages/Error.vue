<template>
	<Head>
		<title>404</title>
		<meta name="robots" content="noindex, nofollow" />
	</Head>
	<WebsitePage>
		<PageHeadSpacer class="mb-60" />
		<div class="container">
			<div class="pv-88 text-center mb-60">
				<h1 class="fs-h1 mb-24">{{ status }} X(</h1>
				<a
					class="color-danger underline fs-h3"
					:href="'/' + usePage().props.locale"
				>
					{{ __('toHome') }}
				</a>
			</div>
		</div>
	</WebsitePage>
</template>

<script setup lang="ts">
import PageHeadSpacer from '@/Components/PageHeadSpacer.vue';
import WebsitePage from '@/Layouts/WebsitePage.vue';
import { Head, usePage } from '@inertiajs/vue3';

const { __ } = useTranslations();

const props = defineProps<{
	status: number;
}>();
</script>

<style scoped lang="scss"></style>
