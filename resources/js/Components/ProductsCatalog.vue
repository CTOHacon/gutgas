<template>
	<div ref="productsCatalogLayoutRef">
		<ProductsCatalogLayout>
			<template #filters>
				<ProductsCatalogFilters
					:product-filters="productsCatalogData.productFilters"
					:selected-product-filter-values="
						filterForm.selectedProductFilterValues
					"
					@attributes-select="
						newSelectedProductFiters => {
							filterForm.selectedProductFilterValues =
								newSelectedProductFiters;
						}
					"
					:price-min="productsCatalogData.priceMin"
					:price-max="productsCatalogData.priceMax"
					:price-range="filterForm.priceRange"
					@price-range-change="
						newRange => {
							filterForm.priceRange = newRange;
						}
					"
				/>
			</template>
			<template #productsGroups>
				<ProductsGroupsMenu
					class="mb-40"
					:reactiveFilterMode="props.reactiveFilterMode ?? false"
					@products-group-select="
						newProductsGroup => {
							filterForm.productsGroup = newProductsGroup;
						}
					"
					:products-groups="productsCatalogData.productsGroups"
				/>
			</template>
			<template #products>
				<template v-if="displayMode === 'catalogFull'">
					<ProductCatalogCard
						class="g-col-4 g-large-mobile-col-6 g-mobile-col-12"
						v-for="product in productsCatalogData.products.data"
						:key="product.id"
						:product="product"
					/>
				</template>
				<template v-else>
					<ProductsCatalogPreviewSection
						v-if="previewSections && previewProducts"
						v-for="(section, index) in previewSections"
						:key="index"
						class="g-col-12"
						:image="section.image"
						:title="section.title"
						:text="section.text"
						:featured-product="
							previewProducts.find(product => {
								return product.id == section.featuredProduct;
							})
						"
						:products="
							previewProducts.filter(product =>
								section.products.includes(product.id)
							)
						"
					>
					</ProductsCatalogPreviewSection>
					<ViewAllProductsButton
						v-if="previewSections && previewProducts"
						class="g-col-12"
						:slug="
							route('seo-entity', [
								usePage().props.locale,
								getGlobalSetting('productsCatalogSlug') ||
									'catalog'
							])
						"
						:total-count="productsCatalogData.products.total"
					/>
				</template>
			</template>
			<template #pagination>
				<BasePagination
					v-if="
						displayMode === 'catalogFull' &&
						productsCatalogData.products.data.length
					"
					:data="productsCatalogData.products"
					class="g-col-start-4 g-col-end-13 g-tablet-col-start-1 g-tablet-col-end-13 mt-32"
				/>
			</template>
		</ProductsCatalogLayout>
	</div>
</template>

<script setup lang="ts">
import { watch, ref, toRef } from 'vue';
import ProductCatalogCard from './ProductCatalogCard.vue';
import ProductsCatalogFilters from './ProductsCatalogFilters.vue';
import BasePagination from './BasePagination.vue';
import ProductsCatalogPreviewSection from './ProductsCatalogPreviewSection.vue';
import { useForm, usePage } from '@inertiajs/vue3';
import { TProductsCatalogFilterForm } from '@/types/TProductsCatalogFilterForm';
import { TproductsCatalogData } from '@/types/TproductsCatalogData';
import { TProductsCatalogPreviewSectionStoreData } from '@/types/TProductsCatalogPreviewSectionProps';
import ProductsCatalogLayout from '@/Layouts/ProductsCatalogLayout.vue';
import { TProduct } from '@/types/TProduct';
import ProductsGroupsMenu from './ProductsGroupsMenu.vue';
import ViewAllProductsButton from './ViewAllProductsButton.vue';
import { getGlobalSetting } from '@/utils/getGlobalSetting';

const props = defineProps<{
	productsCatalogData: TproductsCatalogData;
	catalogSlug: string;
	previewSections?: TProductsCatalogPreviewSectionStoreData[];
	previewProducts?: TProduct[];
	reactiveFilterMode?: boolean;
	mode: 'catalogPreview' | 'catalogFull';
}>();

const displayMode = ref(props.mode);

const filterForm = useForm<TProductsCatalogFilterForm>({
	priceRange: props.productsCatalogData.priceRange,
	productsGroup: props.productsCatalogData.productsGroup,
	selectedProductFilterValues:
		props.productsCatalogData.selectedProductFilterValues?.map(
			val => +val
		) ?? []
});

const productsCatalogLayoutRef = ref<HTMLElement>();
watch(
	[
		() => filterForm.selectedProductFilterValues,
		() => filterForm.priceRange,
		() => filterForm.productsGroup
	],
	() => {
		if (
			productsCatalogLayoutRef.value &&
			window.matchMedia('(min-width: 1024px)').matches
		) {
			window.scrollTo({
				top: productsCatalogLayoutRef.value.offsetTop - 130,
				behavior: 'smooth'
			});
		}
		filterForm.get(
			route('seo-entity', [usePage().props.locale, props.catalogSlug]),
			{
				data: {
					...filterForm.data,
					page: 1
				},
				onSuccess: () => {
					displayMode.value = 'catalogFull';
				},
				preserveScroll: true
			}
		);
	}
);
</script>

<style scoped lang="scss">
.products-layout {
	gap: 0;
}
.filters {
	position: sticky;
	max-height: calc(100vh - 160px);
}
.banner {
	border: 1px solid #000;
}
</style>
