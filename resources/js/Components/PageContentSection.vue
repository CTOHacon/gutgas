<template>
	<div class="container">
		<h2 class="fs-h4 mb-60" v-if="title">{{ title }}</h2>

		<div class="g-layout">
			<div
				class="g-col-8 g-large-mobile-col-12 typography-content fs-typography-content lh-210 color-typography-secondary"
				ref="contentRef"
			>
				<slot />
			</div>

			<aside class="f- justify-center g-col-4 g-large-mobile-col-12">
				<div
					class="aside-content sticky"
					:style="{
						top: headerHeight + 'px'
					}"
				>
					<BaseTableOfContnets
						:items="tableOfContentsItems"
						:content-ref="contentRef"
						class="mb-16"
					/>
					<ContactUsSmallForm
						submit-button-classes="pv-12 ph-44"
						:title="__('adviseBalon?')"
						:subtitle="__('fillFormAndWeWillHelpChouseBalon')"
						class="mb-40 pt-40 ph-8 pb-16 align-center text-center"
					/>
				</div>
			</aside>
		</div>
	</div>
</template>

<script setup lang="ts">
import { ref, watchEffect } from 'vue';
import ContactUsSmallForm from './ContactUsSmallForm.vue';
import useHeaderElement from '@/composables/headerElement';
import BaseTableOfContnets from './BaseTableOfContnets.vue';
const props = defineProps<{
	title?: string;
}>();

const { __ } = useTranslations();

const contentRef = ref<HTMLElement | null>(null);
const tableOfContentsItems = ref<
	{
		title: string;
		slug: string;
	}[]
>([]);

watchEffect(() => {
	if (!contentRef.value) return;
	const anchors = Array.from(contentRef.value.querySelectorAll('h2, h3, h4'));
	const key = Math.random().toString(36).substring(2, 10);
	tableOfContentsItems.value = anchors.map((anchor, index) => {
		const id = `${key}-${index}`;
		anchor.id = id;
		return {
			title: anchor.textContent || '',
			slug: id
		};
	});
});

const { headerHeight } = useHeaderElement();
</script>

<style scoped lang="scss">
$headerHeight: v-bind(headerHeight);
.aside-content {
	max-width: 285px;
	width: 100%;
	margin: 0 auto;
	height: fit-content;
}
.sticky {
	position: sticky;
}
</style>
