<template>
	<div
		class="product-catalog-filters"
		:class="{
			isMobileOpened: isMobileOpened
		}"
	>
		<div class="hidden tablet-visible">
			<button
				class="toggle-button"
				@click="isMobileOpened = !isMobileOpened"
				:class="{
					active: isMobileOpened
				}"
			>
				<svg
					width="24"
					height="24"
					viewBox="0 0 24 24"
					fill="none"
					class="mr-16"
				>
					<path
						d="M7 5.5L20 5.5"
						stroke="white"
						stroke-linecap="round"
					/>
					<path
						d="M17 17.5L4 17.5"
						stroke="white"
						stroke-linecap="round"
					/>
					<path
						d="M4 11.5L10.5 11.5M13.5 11.5L20 11.5"
						stroke="white"
						stroke-linecap="round"
					/>
					<circle cx="5.5" cy="5.5" r="1.5" stroke="white" />
					<circle
						cx="1.5"
						cy="1.5"
						r="1.5"
						transform="matrix(-1 0 0 1 20 16)"
						stroke="white"
					/>
					<circle cx="12" cy="11.5" r="1.5" stroke="white" />
				</svg>

				{{ __('filters') }}
			</button>
		</div>
		<div class="body" ref="scrollContainer">
			<div class="section" v-if="priceRange && priceMin && priceMax">
				<div class="f- mb-20 justify-between align-center">
					<p class="fs-semi-large">{{ __('price') }}</p>
					<p class="fs-medium lh-100">
						<span class="mr-8 color-secondary"
							>₴ {{ priceRange.from }}</span
						>
						<span class="mr-8 color-secondary">–</span>
						<span>{{ priceRange.to }}</span>
					</p>
				</div>
				<BaseRangeSliderField
					:min="priceMin"
					:max="priceMax"
					:step="1"
					:modelValue="priceRange"
					@update:modelValue="onPriceRangeChange"
				/>
			</div>
			<div class="section" v-for="productFitler in productFilters">
				<p class="fs-semi-large mb-16">{{ _t(productFitler.name) }}</p>
				<BaseCheckboxField
					v-for="value in productFitler.product_filter_values"
					:key="value.id"
					:label="_t(value.value)"
					:name="`${value.id}`"
					:checked="
						selectedProductFilterValues.findIndex(
							item => item === value.id
						) !== -1
					"
					@check="onOptionCheck(value)"
					class="checkbox-field"
				/>
			</div>
		</div>
		<div class="f-centered footer pv-24">
			<BaseCancelButton
				class="clean-up-filters fs-medium"
				@click="resetFilters"
			>
				{{ __('clearFilters') }}
			</BaseCancelButton>
		</div>
	</div>
</template>

<script setup lang="ts">
import BaseRangeSliderField from './BaseRangeSliderField.vue';
import BaseCheckboxField from './BaseCheckboxField.vue';
import BaseCancelButton from './BaseCancelButton.vue';
import { ref } from 'vue';
import { watch } from 'vue';
import { onMounted } from 'vue';
import { TProductFilter } from '@/types/TProductFilter';
import { TProductFilterValue } from '@/types/TProductFilterValue';

const { __, _t } = useTranslations();

const props = defineProps<{
	productFilters: TProductFilter[];
	selectedProductFilterValues: number[];
	priceMin: number;
	priceMax: number;
	priceRange?: {
		from: number;
		to: number;
	};
}>();

const scrollContainer = ref<null | HTMLElement>(null);
const saveScroll = () => {
	if (scrollContainer.value)
		localStorage.setItem(
			'scrollPosition',
			`${scrollContainer.value.scrollTop}`
		);
};

const restoreScroll = () => {
	const restoredValue = hasWindow()
		? localStorage.getItem('scrollPosition')
		: 0;
	if (scrollContainer.value && restoredValue !== null)
		scrollContainer.value.scrollTop = hasWindow() ? +restoredValue : 0;
};

onMounted(() => {
	restoreScroll();
});

const resetFilters = () => {
	emit('attributes-select', []);
	emit('price-range-change', { from: props.priceMin, to: props.priceMax });
};

const onOptionCheck = (option: TProductFilterValue) => {
	const modelValue = props.selectedProductFilterValues;
	const index = modelValue.findIndex(item => item === option.id);

	// save scroll on component update
	saveScroll();

	if (index === -1) {
		emit('attributes-select', [...modelValue, option.id]);
	} else {
		const newModelValue = [...modelValue];
		newModelValue.splice(index, 1);
		emit('attributes-select', newModelValue);
	}
};

const onPriceRangeChange = (newRange: { from: number; to: number }) => {
	emit('price-range-change', newRange);
	saveScroll();
};

const isMobileOpened = ref(
	hasWindow() ? localStorage.getItem('isFilteredOpened') === 'true' : false
);

watch(
	() => isMobileOpened.value,
	() => {
		localStorage.setItem(
			'isFilteredOpened',
			isMobileOpened.value.toString()
		);
	}
);

const emit = defineEmits({
	'attributes-select': (value: number[]) => true,
	'price-range-change': (value: { from: number; to: number }) => true
});
</script>

<style scoped lang="scss">
@import '@/styles/variables.scss';
/*! purgecss start ignore */
.product-catalog-filters {
	border-radius: 2px;
	background: #202020;
	box-shadow: 45px 0px 193px -33px #000;
	display: flex;
	flex-direction: column;
	max-height: 100%;

	@media (max-width: $tablet-width) {
		border-radius: 8px 8px 0px 0px;
		border: 1px solid rgba(19, 19, 19, 0.86);
		border-bottom: 1px solid #242424;
		background: rgba(19, 19, 19, 0.86);
		box-shadow: 0px -51px 44px -26px #000;
		transition: all 0.2s ease-in-out;
		&.isMobileOpened {
			border-radius: 24px;
			border: 1px solid #242424;
			background: rgba(19, 19, 19, 0.96);
			box-shadow: 0px -51px 44px -26px #000;
		}
	}
}
.body {
	flex: 1;
	overflow: auto;
	@media (max-width: $tablet-width) {
		display: none;
		overflow: hidden;
		padding: 0 31px;
	}
}
.isMobileOpened .body {
	display: block;
}
.section {
	padding: 24px 32px;
	&:not(:last-child) {
		border-bottom: 1px solid #000;
	}
	@media (max-width: $large-mobile-width) {
		padding: 16px 0;
		&:not(:last-child) {
			border-bottom: 1px solid #4e4e4e;
		}
	}
}
.checkbox-field {
	padding: 6px 2px;
}

.footer {
	position: relative;
	&::before {
		content: '';
		background: radial-gradient(
			131.2% 100% at 50% 100%,
			rgba(0, 0, 0, 0.32) 0%,
			rgba(0, 0, 0, 0) 100%
		);
		position: absolute;
		left: 0;
		right: 0;
		bottom: 100%;
		height: 40px;
		pointer-events: none;
	}
	@media (max-width: $tablet-width) {
		display: none !important;
	}
}
.isMobileOpened .footer {
	display: flex !important;
}

.toggle-button {
	display: flex;
	align-items: center;
	padding: 15px 12px;
	width: 100%;
}
/*! purgecss end ignore */
</style>
