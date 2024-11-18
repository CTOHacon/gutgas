<template>
	<div>
		<FormFieldWrapper label="Назва" class="mb-3">
			<label class="form-label">Назва</label>
			<input class="form-control" v-model="form.name" />
		</FormFieldWrapper>
		<FormFieldWrapper label="Products" class="mb-3">
			<label class="form-label">ID товарів розділені через "; "</label>
			<input class="form-control" v-model="products" />
		</FormFieldWrapper>
	</div>
</template>

<script setup lang="ts">
import { InertiaForm } from '@inertiajs/vue3';
import FormError from '../FormError.vue';
import { TRequiredProductsGroupForm } from '@/types/TRequiredProductsGroup';
import { ref, watch } from 'vue';
import FormFieldWrapper from '../FormFieldWrapper.vue';

const props = defineProps<{
	form: InertiaForm<TRequiredProductsGroupForm>;
}>();

const products = ref<string>(props.form.products.join('; '));

watch(
	() => products.value,
	value => {
		props.form.products = products.value
			.split(';')
			.filter((item: string) => !isNaN(parseInt(item)))
			.map((item: string) => parseInt(item));
	}
);
</script>

<style scoped></style>
