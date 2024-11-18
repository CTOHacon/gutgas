<template>
	<Head>
		<title>{{ _t(seoEntity.title) }}</title>
		<title>
			{{ _t(seoEntity.title)
			}}{{
				usePage().props.pageNumber !== 1
					? ` - ${__('page')} ${usePage().props.pageNumber}`
					: ''
			}}
			| GUTGAS
		</title>
		<meta name="description" :content="_t(seoEntity.description)" />
		<meta property="og:title" :content="_t(seoEntity.title)" />
		<meta property="og:description" :content="_t(seoEntity.description)" />
		<meta property="og:type" content="website" />
		<meta
			property="og:url"
			:content="
				route('seo-entity', [usePage().props.locale, seoEntity.slug])
			"
		/>
		<meta
			v-if="seoEntity.og_image"
			property="og:image"
			:content="_t(seoEntity.og_image)"
		/>
		<meta property="og:locale" :content="usePage().props.locale" />
		<link rel="canonical" :href="currentLocation" />

		<component :is="'script'" type="application/ld+json">
			{{ JSON.stringify(getGlobalSetting('extraMicrodata')) }}
		</component>

		<meta name="robots" v-if="hasQueryParams" content="noindex, nofollow" />
	</Head>
</template>

<script setup lang="ts">
import { TSeoEntity } from '@/types/TSeoEntity';
import { getGlobalSetting } from '@/utils/getGlobalSetting';
import { Head, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';

const { _t, __ } = useTranslations();

const props = defineProps<{
	seoEntity: TSeoEntity;
}>();

const fullUrl = usePage().props.currentUrl;
const currentLocation = ref<string>(fullUrl.split('?')[0]);
const hasQueryParams = ref<boolean>(fullUrl.includes('?'));
</script>

<style scoped></style>
