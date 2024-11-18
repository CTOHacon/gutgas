<template>
	<div class="row g-1">
		<div class="col-6">
			<label class="form-label">Значення</label>
			<input class="form-control" v-model="form.value" />
			<FormError :error="form.errors.value" />
		</div>
		<div class="col-6">
			<label class="form-label">Індекс Послідовності</label>
			<input class="form-control" v-model="form.sequence" />
			<FormError :error="form.errors.sequence" />
		</div>
	</div>
</template>

<script setup lang="ts">
import { InertiaForm } from '@inertiajs/vue3';
import FormError from '../FormError.vue';
import { TProductFilterValueForm } from '@/types/TProductFilterValue';

const props = defineProps<{
	form: InertiaForm<TProductFilterValueForm>;
}>();
</script>

<style scoped></style>
