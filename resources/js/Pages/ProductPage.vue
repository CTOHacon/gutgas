<template>
	<SeoEntityMetaFields :seo-entity="seoEntity"/>
	<Head>
		<component :is="'script'" type="application/ld+json">
			{{ JSON.stringify(productStructuredData) }}
    	</component>
	</Head>
	<WebsitePage>
		<PageHeadSpacer class="mb-60" />
		<BaseBreadcrumbs class="mt-32 mb-40" />
		<div class="container">
			<div class="f- justify-between mb-32">
				<h1 class="fs-h4">{{ _t(productPage.title) ?? _t(product.name) }}</h1>
				<p
					class="f- align-center fs-medium color-typography-secondary"
					@click="copyToClipboard(`${product.sku}`)"
				>
					<span
						>{{__('sku')}}.: <strong>{{ product.sku }}</strong></span
					>
					<button class="large-mobile-hidden">
						<img
							class="ml-8"
							src="@/assets/small-copy-icon.svg"
							alt="copy"
						/>
					</button>
				</p>
			</div>

			<!-- LAYOUT -->
			<div class="g-layout">
				<!-- MAIN -->
				<main class="g-col-8 g-large-mobile-col-12">
					<ProductGallerySlider
						class="mt-72 mb-88 gallery"
						:images="
							productPage.media_gallery.map(mediaFile => mediaFile.url)
						"
						:image-base-alt="_t(product.name)"
					/>
					<BaseTabs controlsBackground="#141414">
						<BaseTab
							name="description"
							label="Опис"
							:active="true"
							class="pt-40 fs-typography-content typography-content lh-160 color-typography-secondary"
						>
						<div v-html="_t(productPage.description)"></div>
						</BaseTab>
						<BaseTab
							name="stats"
							label="Характеристика"
							:active="true"
						>
							<div class="pt-40">
								<div class="characteristic-icons pb-24 mb-24 ">
										<img v-for="charIcon in productPage.characteristic_icons" :src="charIcon.url" :alt="charIcon.name">
								</div>
								<p v-for="attributeGroup in attributeGroups" class="f- fw-700 mb-12">
									<strong class="color-secondary mr-16">{{ _t(attributeGroup.name) }}:</strong>
									<ul>
										<li
											v-for="attribute in attributeGroup.attributes"
										>
											{{ _t(attribute.name) }}
										</li>
									</ul>
								</p>
							</div>
						</BaseTab>
						<template v-if="productPage.documentations.length">
							<BaseTab
								name="docs"
								label="Документація"
								class="pt-40"
								:active="true"
							>
								<div class="documentation">
									<AttachedFileCard
										v-for="documentation in productPage.documentations"
										:key="documentation.id"
										:file="documentation"
									/>
								</div>
							</BaseTab>
						</template>
					</BaseTabs>
				</main>
				<!-- END MAIN -->

				<!-- ASIDE -->
				<aside class="g-col-4 g-large-mobile-col-12">
					<div
						class="aside-content"
						:style="{
							top: headerHeight + 'px'
						}"
					>
						<ProductPicker :product="product" class="mb-16" />
						<ContactUsSmallForm
							submitButtonClasses="ph-88 pv-12"
							:title="__('needHelp')"
							class="ph-40 pv-36"
						/>
					</div>
				</aside>
				<!-- END ASIDE -->
			</div>
			<!-- END LAYOUT -->
		</div>
		<PageSeparator class="mt-72 mb-116" color="#26282B" />
		<FaqSection v-if="faqItems?.length" :faq-items="faqItems" class="mb-116" />
		<AdvantagesSection :items="getGlobalSetting('ourAdvantages')" class="mb-116" />
	</WebsitePage>
</template>

<script setup lang="ts">
import WebsitePage from '@/Layouts/WebsitePage.vue';
import PageHeadSpacer from '@/Components/PageHeadSpacer.vue';
import BaseBreadcrumbs from '@/Components/BaseBreadcrumbs.vue';
import ProductGallerySlider from '@/Components/ProductGallerySlider.vue';
import BaseTabs from '@/Components/BaseTabs.vue';
import BaseTab from '@/Components/BaseTab.vue';
import PageSeparator from '@/Components/PageSeparator.vue';
import ContactUsSmallForm from '@/Components/ContactUsSmallForm.vue';
import useHeaderElement from '@/composables/headerElement';
import ProductPicker from '@/Components/ProductPicker.vue';
import FaqSection from '@/Components/FaqSection.vue';
import AdvantagesSection from '@/Components/AdvantagesSection.vue';
import { TProduct } from '@/types/TProduct';
import { TAttributeGroup } from '@/types/TAttributeGroup';
import { TProductPage } from '@/types/TProductPage';
import AttachedFileCard from '@/Components/AttachedFileCard.vue';
import { getMeta } from '@/utils/getMeta';
import { ref } from 'vue';
import { TFaqItemProps } from '@/types/TFaqItemProps';
import { TAdvantageCardProps } from '@/types/TAdvantageCardProps';
import { Head } from '@inertiajs/vue3';
import { TSeoEntity } from '@/types/TSeoEntity';
import SeoEntityMetaFields from '@/Components/SeoEntityMetaFields.vue';
import { getGlobalSetting } from '@/utils/getGlobalSetting';

const { __, _t } = useTranslations();

const copyToClipboard = (text: string) => {
	navigator.clipboard.writeText(text);
};

const props = defineProps<{
	product: TProduct;
	productStructuredData: any;
	productPage: TProductPage;
	attributeGroups: TAttributeGroup[];
	seoEntity: TSeoEntity;
}>();

const faqItems = ref<TFaqItemProps[] | undefined>(getMeta(props.productPage.meta, 'faqItems'));

const { headerHeight } = useHeaderElement();
</script>

<style scoped lang="scss">
.aside-content {
	position: sticky;
}
.characteristic-icons{
	position: relative;
	display: grid;
	grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
	justify-items: center;
	gap: 24px;
	&::before{
		content: '';
		width: 100%;
		height: 3px;
		background:  #838D97;
		border-radius: 3px;
		position: absolute;
		bottom: 0;

	}
}
.documentation{
	display: grid;
	grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
	gap: 8px;
}

:deep(.gallery .thumb img) {
	object-fit: contain;
	width: auto;
}

</style>