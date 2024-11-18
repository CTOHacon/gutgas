<template>
	<div>
		<div class="mb-1">
			<label class="form-label">Назва</label>
			<input class="form-control" v-model="form.name" />
			<FormError :error="form.errors.name" />
		</div>
		<div>
			<label class="form-label">Індекс Послідовності</label>
			<input class="form-control" v-model="form.sequence" />
			<FormError :error="form.errors.sequence" />
		</div>
	</div>
</template>

<script setup lang="ts">
import { InertiaForm } from '@inertiajs/vue3';
import FormError from '../FormError.vue';
import { TProductFilterForm } from '@/types/TProductFilter';

const props = defineProps<{
	form: InertiaForm<TProductFilterForm>;
}>();
</script>

<style scoped></style>
