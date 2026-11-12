<template>
	<Head v-if="items.length">
		<component :is="'script'" type="application/ld+json">
			{{ JSON.stringify(itemList) }}
		</component>
	</Head>
</template>

<script setup lang="ts">
import { Head, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import { IPaginated } from '@/types/IPaginated';
import { TProduct } from '@/types/TProduct';

/** schema.org ItemList of the products shown on a catalog / category page. */
const props = defineProps<{
	products: IPaginated<TProduct>;
	name?: string;
}>();

const page = usePage();
const locale = page.props.locale as string;
const baseUrl = (page.props.baseUrl as string).replace(/\/+$/, '');

const items = computed(() =>
	(props.products.data ?? []).filter(product => product.seo_entity?.slug)
);

const itemList = computed(() => {
	const offset = ((props.products.current_page ?? 1) - 1) * (props.products.per_page ?? 0);
	return {
		'@context': 'https://schema.org',
		'@type': 'ItemList',
		...(props.name && { name: props.name }),
		numberOfItems: items.value.length,
		itemListElement: items.value.map((product, index) => ({
			'@type': 'ListItem',
			position: offset + index + 1,
			url: `${baseUrl}/${locale}/${product.seo_entity!.slug}`
		}))
	};
});
</script>
