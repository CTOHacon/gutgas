<template>
	<div>
		<div class="row g-1 mb-1">
			<div class="col-4">
				<FormFieldWrapper label="OG Зображення" class="h-100 mb-1">
					<MediaFileUrlSelect class="mb-1" v-model="form.og_image" />
				</FormFieldWrapper>
			</div>
			<div class="col-8">
				<FormFieldWrapper label="Заголовок" class="mb-1">
					<input
						class="form-control"
						v-model="form.title"
						placeholder="Заголовок"
					/>
					<FormError
						v-if="form.errors.title"
						class="mt-1"
						:error="form.errors.title"
					/>
				</FormFieldWrapper>
				<FormFieldWrapper label="Опис">
					<textarea
						class="form-control"
						v-model="form.description"
						placeholder="Опис"
					/>
					<FormError
						v-if="form.errors.description"
						class="mt-1"
						:error="form.errors.description"
					/>
				</FormFieldWrapper>
			</div>
		</div>
		<FormFieldWrapper label="Заголовок Хлібних Крихт" class="mb-1">
			<input
				class="form-control"
				v-model="form.breadcrumbs_title"
				placeholder="Заголовок Breadcrumbs"
			/>
			<FormError
				v-if="form.errors.breadcrumbs_title"
				class="mt-1"
				:error="form.errors.breadcrumbs_title"
			/>
		</FormFieldWrapper>
		<FormFieldWrapper label="Посилання (Slug)" class="mb-3">
			<input
				class="form-control"
				v-model="form.slug"
				placeholder="Slug"
			/>
			<FormError
				v-if="form.errors.slug"
				class="mt-1"
				:error="form.errors.slug"
			/>
		</FormFieldWrapper>
		<div class="mb-3">
			<div class="form-check">
				<input
					class="form-check-input"
					type="checkbox"
					v-model="form.is_active"
					id="is_active"
				/>
				<label class="form-check-label" for="is_active">
					Дозволити Видачу Для Поточної Мови ({{
						usePage().props.locale
					}})
				</label>
			</div>
			<FormError :error="form.errors.is_active" />
		</div>
	</div>
</template>

<script setup lang="ts">
import { TSeoEntityForm } from '@/types/TSeoEntity';
import FormFieldWrapper from '../FormFieldWrapper.vue';
import MediaFileUrlSelect from '../Media/MediaFileUrlSelect.vue';
import FormError from '../FormError.vue';
import { InertiaForm, usePage } from '@inertiajs/vue3';

const props = defineProps<{
	form: InertiaForm<TSeoEntityForm>;
}>();
</script>

<style scoped></style>
