<template>
	<SeoEntityMetaFields :seo-entity="page.seo_entity" />
	<Head>
		<meta
			name="robots"
			content="noindex, nofollow"
			v-if="usePage().props.pageNumber > 1"
		/>
	</Head>
	<WebsitePage>
		<HomeHero
			:title="getMeta(page.meta, 'heroTitle')"
			:subtitle="getMeta(page.meta, 'heroSubTitle')"
			class="mb-60 mb-116"
		/>

		<AboutSection
			class="mb-163"
			:title="getMeta(page.meta, 'aboutTitle')"
			:content="getMeta(page.meta, 'aboutContent')"
		/>
		<div class="container mb-32">
			<h2 class="fs-h2 mb-68">
				{{ getMeta(page.meta, 'catalogPreviewTitle') }}
			</h2>
			<ProductsCatalog
				:catalog-slug="page.seo_entity.slug"
				:products-catalog-data="productsCatalogData"
				:preview-sections="
					getMeta(page.meta, 'catalogPreviewItems') || []
				"
				:preview-products="previewProducts"
				:mode="
					!productsCatalogData.selectedProductFilterValues.length &&
					+productsCatalogData.priceRange.from ===
						+productsCatalogData.priceMin &&
					+productsCatalogData.priceRange.to ==
						+productsCatalogData.priceMax &&
					!productsCatalogData.productsGroup
						? 'catalogPreview'
						: 'catalogFull'
				"
				:reactiveFilterMode="true"
			/>
		</div>
		<PageSeparator />
		<ProductsCompairSection
			class="mb-116"
			:items="getMeta(page.meta, 'compairItems')"
			:title="getMeta(page.meta, 'compairTitle')"
			:subtitle="getMeta(page.meta, 'compairSubTitle')"
		/>
		<UseCasesSection
			class="mb-116"
			:items="getMeta(page.meta, 'useCasesItems')"
			:title="getMeta(page.meta, 'useCasesTitle')"
		/>
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
import AboutSection from '@/Components/AboutSection.vue';
import HomeHero from '@/Components/HomeHero.vue';
import WebsitePage from '@/Layouts/WebsitePage.vue';
import PageSeparator from '@/Components/PageSeparator.vue';
import ProductsCompairSection from '@/Components/ProductsCompairSection.vue';
import UseCasesSection from '@/Components/UseCasesSection.vue';
import ContactUsSection from '@/Components/ContactUsSection.vue';
import { getMeta } from '@/utils/getMeta';
import { TPage } from '@/types/TPage';
import { TProduct } from '@/types/TProduct';
import AdvantagesSection from '@/Components/AdvantagesSection.vue';
import { TproductsCatalogData } from '@/types/TproductsCatalogData';
import ProductsCatalog from '@/Components/ProductsCatalog.vue';
import SeoEntityMetaFields from '@/Components/SeoEntityMetaFields.vue';
import { Head, usePage } from '@inertiajs/vue3';
import { getGlobalSetting } from '@/utils/getGlobalSetting';

const props = defineProps<{
	page: TPage;
	previewProducts: TProduct[];
	productsCatalogData: TproductsCatalogData;
}>();

const { _t } = useTranslations();
</script>

<style scoped></style>
