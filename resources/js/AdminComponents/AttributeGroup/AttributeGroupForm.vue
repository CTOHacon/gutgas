<template>
	<div>
		<div class="mb-3">
			<label class="form-label">Назва</label>
			<input class="form-control" v-model="form.name" />
			<FormError :error="form.errors.name" />
		</div>
	</div>
</template>

<script setup lang="ts">
import { TAttributeGroupForm } from '@/types/TAttributeGroup';
import { InertiaForm } from '@inertiajs/vue3';
import FormError from '../FormError.vue';

const props = defineProps<{
	form: InertiaForm<TAttributeGroupForm>;
}>();
</script>

<style scoped></style>
