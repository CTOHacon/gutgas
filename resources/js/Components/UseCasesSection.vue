<template>
	<section class="use-cases-section" v-if="items">
		<div class="container">
			<h2 class="fs-h3 title mb-72" v-if="title" v-html="title" />
			<div class="g-layout">
				<UseCaseCard
					class="g-col-6 g-tablet-col-12"
					v-for="(item, index) in items"
					:key="index"
					:image="item.image"
					:title="item.title"
					:description="item.description"
				/>
			</div>
		</div>
	</section>
</template>

<script setup lang="ts">
import { TUseCaseCardProps } from '@/types/TUseCaseCardProps';
import UseCaseCard from './UseCaseCard.vue';

const props = defineProps<{
	title?: string;
	items?: TUseCaseCardProps[];
}>();
</script>

<style scoped>
.g-layout {
	gap: 20px;
}
</style>
