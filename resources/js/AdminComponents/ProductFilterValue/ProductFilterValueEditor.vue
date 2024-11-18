<template>
	<div class="d-flex align-items-end gap-1">
		<ProductFilterValueForm :form="form" />
		<div class="btn-group mt-1">
			<button class="btn btn-primary" @click="onSubmit">Зберегти</button>
			<button class="btn btn-danger" @click="onDelete">Видалити</button>
		</div>
	</div>
</template>

<script setup lang="ts">
import ProductFilterValueForm from './ProductFilterValueForm.vue';
import { router, useForm } from '@inertiajs/vue3';
import {
	TProductFilterValue,
	TProductFilterValueForm
} from '@/types/TProductFilterValue';

const { _t } = useTranslations();

const props = defineProps<{
	productFilterValue: TProductFilterValue;
}>();

const form = useForm<TProductFilterValueForm>({
	value: _t(props.productFilterValue.value),
	sequence: props.productFilterValue.sequence
});

const onSubmit = () =>
	form.put(
		route('admin.productFilterValue.update', props.productFilterValue.id),
		{
			preserveScroll: true,
			preserveState: false
		}
	);

const onDelete = () => {
	if (confirm('Підтвердити Видалення?'))
		router.delete(
			route(
				'admin.productFilterValue.destroy',
				props.productFilterValue.id
			)
		);
};
</script>

<style scoped></style>
