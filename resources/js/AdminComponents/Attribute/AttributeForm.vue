<template>
	<div class="mb-3">
		<label class="form-label">Значення</label>
		<input
			class="form-control mb-1"
			v-model="form.name"
			placeholder="Значення"
		/>
		<FormError :error="form.errors.name" />
		<label class="form-label">Значення для сортування</label>
		<input
			class="form-control"
			v-model="form.sequence"
			placeholder="Індекс Сортування"
		/>
		<FormError :error="form.errors.name" />
	</div>
</template>

<script setup lang="ts">
import { InertiaForm, useForm } from '@inertiajs/vue3';
import FormError from '../FormError.vue';
import { TAttributeForm } from '@/types/TAttribute';

const props = defineProps<{
	form: InertiaForm<TAttributeForm>;
}>();
</script>

<style scoped></style>
