<template>
	<SeoEntityMetaFields :seo-entity="productsGroup.seo_entity" />
	<Head>
		<link
			rel="prev"
			:href="productsCatalogData.products.prev_page_url"
			v-if="productsCatalogData.products.prev_page_url"
		/>
		<link
			rel="next"
			:href="productsCatalogData.products.next_page_url"
			v-if="productsCatalogData.products.next_page_url"
		/>
	</Head>
	<WebsitePage>
		<PageHeadSpacer class="mb-60" />
		<BaseBreadcrumbs class="mb-40" />
		<section class="container mb-88">
			<h1 class="fs-h2 mb-68">
				{{
					getMeta(productsGroup.meta, 'title') ||
					_t(productsGroup.name)
				}}
			</h1>
			<div ref="productsCatalogLayoutRef">
				<ProductsCatalog
					:catalog-slug="productsGroup.seo_entity.slug"
					:products-catalog-data="productsCatalogData"
					mode="catalogFull"
				/>
			</div>
		</section>

		<PageSeparator class="mb-116" />
		<PageContentSection
			class="mb-48"
			v-if="getMeta(productsGroup.meta, 'contentTitle')"
			:title="getMeta(productsGroup.meta, 'contentTitle')"
		>
			<div v-html="getMeta(productsGroup.meta, 'content')"></div>
		</PageContentSection>
		<PageSeparator v-if="faqItems.length" class="mb-116" />
		<FaqSection
			v-if="faqItems.length"
			:faq-items="faqItems"
			class="mb-116"
		/>
		<PageSeparator class="mb-116" />
	</WebsitePage>
</template>

<script setup lang="ts">
import WebsitePage from '@/Layouts/WebsitePage.vue';
import PageSeparator from '@/Components/PageSeparator.vue';
import PageHeadSpacer from '@/Components/PageHeadSpacer.vue';
import PageContentSection from '@/Components/PageContentSection.vue';
import FaqSection from '@/Components/FaqSection.vue';
import { Head, usePage } from '@inertiajs/vue3';
import { getMeta } from '@/utils/getMeta';
import { TFaqItemProps } from '@/types/TFaqItemProps';
import { TproductsCatalogData } from '@/types/TproductsCatalogData';
import ProductsCatalog from '@/Components/ProductsCatalog.vue';
import { TProductsGroup } from '@/types/TProductsGroup';
import SeoEntityMetaFields from '@/Components/SeoEntityMetaFields.vue';
import BaseBreadcrumbs from '@/Components/BaseBreadcrumbs.vue';

const { _t, __ } = useTranslations();

const { baseUrl } = usePage().props;

const props = defineProps<{
	productsCatalogData: TproductsCatalogData;
	productsGroup: TProductsGroup;
}>();

const faqItems =
	getMeta<TFaqItemProps[]>(props.productsGroup.meta, 'faqItems') || [];
</script>

<style scoped></style>
