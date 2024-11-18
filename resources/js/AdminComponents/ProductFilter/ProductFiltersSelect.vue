<template>
	<table class="table table table-bordered mb-0">
		<tbody>
			<tr
				class="border rounded p-1 mb-1"
				v-for="productFilter in productFilters"
			>
				<td>
					{{ _t(productFilter.name) }}
				</td>
				<td>
					<select
						class="form-control"
						@change="
							event => onSelectChange(event, productFilter.id)
						"
					>
						<option value="null">Відсутній</option>
						<option
							:selected="
								modelValue.includes(productFilterValue.id)
							"
							v-for="productFilterValue in productFilter.product_filter_values"
							:value="productFilterValue.id"
						>
							{{ _t(productFilterValue.value) }}
						</option>
					</select>
				</td>
			</tr>
		</tbody>
	</table>
</template>

<script setup lang="ts">
import { TProductFilter } from '@/types/TProductFilter';
import { watchEffect } from 'vue';
import { ref } from 'vue';

const { _t } = useTranslations();

const props = defineProps<{
	productFilters: TProductFilter[];
	modelValue: number[];
}>();

const valuesMap = ref<{ [key: number]: number | null }>({});
watchEffect(() => {
	props.productFilters.forEach(productFilter => {
		valuesMap.value[productFilter.id] = null;
		props.modelValue.forEach(productFilterValue => {
			if (
				productFilter.product_filter_values.find(
					value => value.id === productFilterValue
				)
			)
				valuesMap.value[productFilter.id] = productFilterValue;
		});
	});
});

const onSelectChange = (event: Event, productFilterId: number) => {
	const eventTarget = event.target as HTMLSelectElement;
	const value = +eventTarget.value;
	valuesMap.value[productFilterId] = value;

	let returnValue: number[] = [];
	props.productFilters.forEach(productFilter => {
		const value = valuesMap.value[productFilter.id];
		if (typeof value === 'number') {
			returnValue.push(value);
		}
	});

	emit('update:modelValue', returnValue);
};

const emit = defineEmits({
	'update:modelValue': (value: number[]) => true
});
</script>

<style scoped></style>
