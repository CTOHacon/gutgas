<template>
	<div class="advantages-section" v-if="items?.length">
		<div class="container">
			<div class="f-row justify-center">
				<AdvantageCard
					v-for="advantage in items"
					:key="advantage.title"
					:image="advantage.image"
					:title="advantage.title"
					:description="advantage.description"
					class="f-col-4 f-large-mobile-col-6 f-mobile-col-12"
				/>
			</div>
		</div>
	</div>
</template>

<script setup lang="ts">
import AdvantageCard from './AdvantageCard.vue';
import { TAdvantageCardProps } from '@/types/TAdvantageCardProps';

const props = defineProps<{
	items?: TAdvantageCardProps[];
}>();
</script>

<style scoped></style>
