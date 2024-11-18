<template>
	<div class="g-layout contacts-layout">
		<template v-for="contactItem in items">
			<div class="g-col-6 pl-24">
				<p class="fs-medium fw-700">
					{{ contactItem.name }}
				</p>
			</div>
			<div class="g-col-6">
				<template
					v-for="(contactItemValue, index) in contactItem.values"
				>
					<p
						v-if="!contactItemValue.link"
						:class="{
							'mb-12': index + 1 !== contactItem.values.length
						}"
					>
						<strong class="fs-medium fw-700 block">
							{{ contactItemValue.value }}
						</strong>
						<small
							class="fs-semi-small ls--1 color-secondary block"
						>
							{{ contactItemValue.comment }}
						</small>
					</p>
					<a
						v-else
						:class="{
							'mb-12': index + 1 !== contactItem.values.length
						}"
						class="hover-underline block"
						:href="contactItemValue.link"
						><strong class="fs-medium fw-700 block">
							{{ contactItemValue.value }}
						</strong>
						<small
							class="fs-semi-small ls--1 color-secondary block"
						>
							{{ contactItemValue.comment }}
						</small>
					</a>
				</template>
			</div>
		</template>
	</div>
</template>

<script setup lang="ts">
import { TContactItemProps } from '@/types/TContactItemProps';
import { getGlobalSetting } from '@/utils/getGlobalSetting';
import { ref } from 'vue';

const props = defineProps<{
	items?: TContactItemProps[];
}>();

const items = ref(
	props.items
		? props.items
		: getGlobalSetting<TContactItemProps[]>('contactItems') ?? []
);
</script>

<style scoped lang="scss">
@import '@/styles/variables';
.contacts-layout {
	column-gap: 20px;
	row-gap: 16px;
	@media (max-width: $small-desktop-width) {
		.g-col-6 {
			padding-left: 0;
		}
	}
}
</style>
