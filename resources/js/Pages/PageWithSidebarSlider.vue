<template>
	<SeoEntityMetaFields :seo-entity="page.seo_entity" />
	<WebsitePage>
		<PageHeadSpacer class="mb-60" />
		<BaseBreadcrumbs class="mb-48" />
		<div class="container mb-72">
			<div class="g-layout-12 content-layout">
				<div class="g-col-6 g-tablet-col-12 gap-0">
					<div
						class="typography-content fs-typography-content lh-210 color-typography-secondary"
						ref="contentRef"
					>
						<div v-html="_t(page.content)" />
					</div>
				</div>
				<div class="g-col-6 g-tablet-col-12">
					<ProductGallerySlider
						:images="images"
						:image-base-alt="_t(page.title)"
						class="mb-48 images-slider"
					/>
				</div>
			</div>
		</div>
		<ContactUsSection
			class="mb-116"
			:title="getMeta(page.meta, 'contactFormTitle')"
			:subtitle="getMeta(page.meta, 'contactFormSubTitle')"
			:text="getMeta(page.meta, 'contactFormText')"
		/>
		<AdvantagesSection
			:items="getGlobalSetting('ourAdvantages')"
			class="mb-116"
		/>
	</WebsitePage>
</template>

<script setup lang="ts">
import WebsitePage from '@/Layouts/WebsitePage.vue';
import { TPage } from '@/types/TPage';
import PageHeadSpacer from '@/Components/PageHeadSpacer.vue';
import ProductGallerySlider from '@/Components/ProductGallerySlider.vue';
import { getMeta } from '@/utils/getMeta';
import { ref } from 'vue';
import ContactUsSection from '@/Components/ContactUsSection.vue';
import AdvantagesSection from '@/Components/AdvantagesSection.vue';
import { IMediaFile } from '@/types/IMediaFile';
import SeoEntityMetaFields from '@/Components/SeoEntityMetaFields.vue';
import BaseBreadcrumbs from '@/Components/BaseBreadcrumbs.vue';
import { getGlobalSetting } from '@/utils/getGlobalSetting';

const props = defineProps<{
	page: TPage;
}>();

const images = ref<string[]>(
	getMeta<IMediaFile[]>(props.page.meta, 'sidebarSliderImages')?.map(
		mediaFile => mediaFile.url
	) || []
);

const { _t } = useTranslations();
</script>

<style scoped lang="scss">
.content-layout {
	gap: 4rem;
	@media (max-width: 1200px) {
		gap: 0;
	}
}
:deep(.main-slider-slide__image-container) {
	margin-bottom: 32px;
	max-width: 387px;
}
:deep(.main-slider-slide__image-container img) {
	object-fit: cover !important;
}
</style>
