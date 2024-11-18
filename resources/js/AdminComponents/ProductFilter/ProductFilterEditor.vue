<template>
	<ProductFilterForm :form="form" class="mb-1" />
	<div class="btn-group">
		<button class="btn btn-primary" @click="onSubmit">Зберегти</button>
		<button class="btn btn-danger" @click="onDelete">Видалити</button>
	</div>
</template>

<script setup lang="ts">
import { TProductFilter, TProductFilterForm } from '@/types/TProductFilter';
import { router, useForm } from '@inertiajs/vue3';
import ProductFilterForm from './ProductFilterForm.vue';

const { _t, __ } = useTranslations();

const props = defineProps<{
	productFilter: TProductFilter;
}>();

const form = useForm<TProductFilterForm>({
	name: _t(props.productFilter.name),
	sequence: props.productFilter.sequence
});

const onSubmit = () =>
	form.put(route('admin.productFilter.update', props.productFilter.id), {
		preserveScroll: true,
		preserveState: false
	});

const onDelete = () => {
	if (confirm('Підтвердити Видалення фільтру?'))
		router.delete(
			route('admin.productFilter.destroy', props.productFilter.id)
		);
};
</script>

<style scoped></style>
