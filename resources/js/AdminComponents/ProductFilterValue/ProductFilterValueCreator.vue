<template>
	<div>
		<ProductFilterValueForm :form="form" />
		<button class="btn btn-primary mt-1" @click="onSubmit">
			Додати Значення
		</button>
	</div>
</template>

<script setup lang="ts">
import { TProductFilter } from '@/types/TProductFilter';
import ProductFilterValueForm from './ProductFilterValueForm.vue';
import { useForm } from '@inertiajs/vue3';
import { TProductFilterValueForm } from '@/types/TProductFilterValue';

const props = defineProps<{
	productFilter: TProductFilter;
}>();

const form = useForm<TProductFilterValueForm>({
	value: '',
	sequence: 0,
	product_filter_id: props.productFilter.id
});

const onSubmit = () => {
	form.post(route('admin.productFilterValue.store'), {
		preserveScroll: true,
		onSuccess: () => {
			form.value = '';
			form.sequence = 0;
		}
	});
};
</script>

<style scoped></style>
