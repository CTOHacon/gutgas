<template>
	<SeoEntityMetaFields :seo-entity="page.seo_entity" />
	<WebsitePage>
		<PageHeadSpacer class="mb-60" />
		<BaseBreadcrumbs class="mb-40" />
		<div class="container">
			<h1 class="fs-h2 mb-48">{{ _t(page.title) }}</h1>
			<div
				class="g-col-8 g-large-mobile-col-12 lh-133 typography-content fs-typography-content color-typography-secondary mb-60"
				v-html="_t(page.content)"
			></div>
			<div class="g-layout main-layout mb-116">
				<div class="g-col-6 g-small-desktop-col-12">
					<h2 class="fs-h3 mb-48">{{ __('askAQuestion') }}</h2>
					<ContactUsForm />
				</div>
				<div class="g-col-6 g-small-desktop-col-12">
					<h2 class="fs-h3 mb-48">{{ __('contactInfo') }}</h2>
					<ContactItems />
				</div>
			</div>
		</div>
	</WebsitePage>
</template>

<script setup lang="ts">
import WebsitePage from '@/Layouts/WebsitePage.vue';
import { TPage } from '@/types/TPage';
import PageHeadSpacer from '@/Components/PageHeadSpacer.vue';
import { Head, usePage } from '@inertiajs/vue3';
import SeoEntityMetaFields from '@/Components/SeoEntityMetaFields.vue';
import ContactUsForm from '@/Components/ContactUsForm.vue';
import ContactItems from '@/Components/ContactItems.vue';
import BaseBreadcrumbs from '@/Components/BaseBreadcrumbs.vue';

const { __ } = useTranslations();

const props = defineProps<{
	page: TPage;
}>();

const { _t } = useTranslations();
</script>

<style scoped></style>
