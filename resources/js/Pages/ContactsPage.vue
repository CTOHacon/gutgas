<template>
	<SeoEntityMetaFields :seo-entity="page.seo_entity" />
	<WebsitePage>
		<PageHeadSpacer class="mb-60" />
		<BaseBreadcrumbs class="mb-40" />
		<div class="container">
			<div class="g-layout main-layout">
				<div class="g-col-6 g-small-desktop-col-12">
					<h1 class="fs-h3 mb-48">{{ _t(page.title) }}</h1>
					<ContactUsForm />
				</div>
				<div class="g-col-6 g-small-desktop-col-12">
					<h1 class="fs-h3 mb-48">{{ __('contactInfo') }}</h1>
					<ContactItems />
				</div>
			</div>
		</div>
		<!-- <PageContentSection class="mb-48">
			<div v-html="_t(page.content)"></div>
		</PageContentSection> -->
	</WebsitePage>
</template>

<script setup lang="ts">
import WebsitePage from '@/Layouts/WebsitePage.vue';
import { TPage } from '@/types/TPage';
import { getMeta } from '@/utils/getMeta';
import PageHeadSpacer from '@/Components/PageHeadSpacer.vue';
import { Head, usePage } from '@inertiajs/vue3';
import SeoEntityMetaFields from '@/Components/SeoEntityMetaFields.vue';
import BaseBreadcrumbs from '@/Components/BaseBreadcrumbs.vue';
import ContactUsForm from '@/Components/ContactUsForm.vue';
import { TContactItemProps } from '@/types/TContactItemProps';
import { getGlobalSetting } from '@/utils/getGlobalSetting';
import ContactItems from '@/Components/ContactItems.vue';

const props = defineProps<{
	page: TPage;
}>();

const { __ } = useTranslations();

const { _t } = useTranslations();
</script>

<style scoped lang="scss">
@import '@/styles/variables';
.main-layout {
	gap: 54px;
	@media (max-width: $small-desktop-width) {
		column-gap: 0;
		row-gap: 24px;
	}
}
</style>
