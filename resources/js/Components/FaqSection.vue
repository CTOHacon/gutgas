<template>
	<section
		class="faq-section"
		itemscope
		itemtype="https://schema.org/FAQPage"
	>
		<div class="container">
			<h2 class="fs-h3 mb-72">{{ __('questionsAndAnswers') }}</h2>
			<div class="g-layout">
				<FaqCard
					v-for="(faqItem, key) in faqItems"
					:key="key"
					class="g-col-4 g-tablet-col-6 g-large-mobile-col-12"
					:question="faqItem.question"
					:is-active="key === currentItem"
					@click="onItemClick($event, key)"
				>
					<div v-html="faqItem.answer" />
				</FaqCard>
			</div>
		</div>
	</section>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import FaqCard from './FaqCard.vue';
import { TFaqItemProps } from '@/types/TFaqItemProps';
const currentItem = ref<number | null>(null);
const onItemClick = (state: boolean, key: number) => {
	state ? (currentItem.value = key) : (currentItem.value = null);
};
const props = defineProps<{
	faqItems: TFaqItemProps[];
}>();
const { __ } = useTranslations();
// const faqItems = [
// 	{
// 		question: 'Які комплектуючі для газового балона?',
// 		answer: `<p><strong>Вибір клієнтів:</strong></p>
// 				<ul>
// 					<li>Перехідник Type-A (США – цанговий балон) O-GRILL</li>
// 					<li>
// 						Редуктор та шланг для газового гриля (євро з датчиком
// 						залишку газу) 777735
// 					</li>
// 				</ul>`
// 	},
// 	{
// 		question: 'Які бувають газові балони?',
// 		answer: `<p><strong>Вибір клієнтів:</strong></p>
// 				<ul>
// 					<li>Перехідник Type-A (США – цанговий балон) O-GRILL</li>
// 					<li>
// 						Редуктор та шланг для газового гриля (євро з датчиком
// 						залишку газу) 777735
// 					</li>
// 				</ul>`
// 	},
// 	{
// 		question: 'Які умови доставки?',
// 		answer: `<p><strong>Вибір клієнтів:</strong></p>
// 				<ul>
// 					<li>Перехідник Type-A (США – цанговий балон) O-GRILL</li>
// 					<li>
// 						Редуктор та шланг для газового гриля (євро з датчиком
// 						залишку газу) 777735
// 					</li>
// 				</ul>`
// 	},
// 	{
// 		question: 'Як проходить оплата? ',
// 		answer: `<p><strong>Вибір клієнтів:</strong></p>
// 				<ul>
// 					<li>Перехідник Type-A (США – цанговий балон) O-GRILL</li>
// 					<li>
// 						Редуктор та шланг для газового гриля (євро з датчиком
// 						залишку газу) 777735
// 					</li>
// 				</ul>`
// 	},
// 	{
// 		question: 'Який матеріал пі дякі задачі підходить краще? ',
// 		answer: `<p><strong>Вибір клієнтів:</strong></p>
// 				<ul>
// 					<li>Перехідник Type-A (США – цанговий балон) O-GRILL</li>
// 					<li>
// 						Редуктор та шланг для газового гриля (євро з датчиком
// 						залишку газу) 777735
// 					</li>
// 				</ul>`
// 	},
// 	{
// 		question: 'Який термін придатності у балонів? ',
// 		answer: `<p><strong>Вибір клієнтів:</strong></p>
// 				<ul>
// 					<li>Перехідник Type-A (США – цанговий балон) O-GRILL</li>
// 					<li>
// 						Редуктор та шланг для газового гриля (євро з датчиком
// 						залишку газу) 777735
// 					</li>
// 				</ul>`
// 	}
// ];
</script>

<style scoped lang="scss">
.g-layout {
	gap: 4px;
}
</style>
