<template>
	<Head>
		<title>
			{{ _t(seoEntity.title)
			}}{{
				usePage().props.pageNumber !== 1
					? ` - ${__('page')} ${usePage().props.pageNumber}`
					: ''
			}}
			| GUTGAS
		</title>
		<meta
			v-if="description"
			name="description"
			:content="description"
		/>
		<meta property="og:site_name" content="GUTGAS" />
		<meta property="og:title" :content="_t(seoEntity.title)" />
		<meta
			v-if="description"
			property="og:description"
			:content="description"
		/>
		<meta property="og:type" :content="ogType" />
		<meta property="og:url" :content="ogUrl" />
		<meta v-if="image" property="og:image" :content="image" />
		<meta property="og:locale" :content="ogLocale" />
		<meta
			name="twitter:card"
			:content="image ? 'summary_large_image' : 'summary'"
		/>
		<meta name="twitter:title" :content="_t(seoEntity.title)" />
		<meta
			v-if="description"
			name="twitter:description"
			:content="description"
		/>
		<meta v-if="image" name="twitter:image" :content="image" />
		<link rel="canonical" :href="currentLocation" />

		<component
			v-for="(schema, index) in jsonLd"
			:key="index"
			:is="'script'"
			type="application/ld+json"
		>
			{{ JSON.stringify(schema) }}
		</component>

		<meta name="robots" v-if="hasQueryParams" content="noindex, nofollow" />
	</Head>
</template>

<script setup lang="ts">
import { TSeoEntity } from '@/types/TSeoEntity';
import { getGlobalSetting } from '@/utils/getGlobalSetting';
import {
	buildOrganization,
	buildWebSite,
	toAbsoluteUrl
} from '@/utils/structuredData';
import { Head, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const { _t, __ } = useTranslations();

const props = withDefaults(
	defineProps<{
		seoEntity: TSeoEntity;
		ogType?: string;
		/** Used when the SEO entity has no og_image of its own. */
		fallbackImage?: string | null;
	}>(),
	{ ogType: 'website', fallbackImage: null }
);

const OG_LOCALES: Record<string, string> = {
	ua: 'uk_UA',
	ru: 'ru_RU',
	ka: 'ka_GE',
	en: 'en_US',
	ro: 'ro_MD',
	kk: 'kk_KZ'
};
const page = usePage();
const locale = page.props.locale as string;
const baseUrl = (page.props.baseUrl as string).replace(/\/+$/, '');
const ogLocale = OG_LOCALES[locale] ?? locale;

const fullUrl = page.props.currentUrl as string;
const currentLocation = fullUrl.split('?')[0];
const hasQueryParams = fullUrl.includes('?');

const description = computed(() => _t(props.seoEntity.description) || '');
const ogUrl = computed(() =>
	props.seoEntity.slug === '/'
		? `${baseUrl}/${locale}`
		: `${baseUrl}/${locale}/${props.seoEntity.slug.replace(/^\/+/, '')}`
);
const image = computed(() =>
	toAbsoluteUrl(
		baseUrl,
		_t(props.seoEntity.og_image) || props.fallbackImage || undefined
	)
);

const isHomePage = computed(() => props.seoEntity.slug === '/');

// Admin "extra microdata" (Global settings -> Microdata). The Organization is
// now generated in code, so a legacy Organization block stored there is skipped
// to avoid two conflicting Organization entities.
const extraMicrodata = computed(() => {
	const value = getGlobalSetting<unknown>('extraMicrodata');
	const items = (Array.isArray(value) ? value : [value]).filter(
		item => item && typeof item === 'object'
	) as Record<string, unknown>[];
	return items.filter(item => item['@type'] !== 'Organization');
});

const jsonLd = computed(() => [
	buildOrganization(baseUrl),
	...(isHomePage.value
		? [buildWebSite(baseUrl, locale, ogLocale.replace('_', '-'))]
		: []),
	...extraMicrodata.value
]);
</script>

<style scoped></style>
