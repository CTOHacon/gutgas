<template>
	<AdminLayout>
		<form @submit.prevent="submitEditForm">
			<div class="mb-3">
				<label class="form-label">Назва</label>
				<input class="form-control fs-2" v-model="form.name" />
				<FormError :error="form.errors.name" />
			</div>
			<div class="mb-3">
				<p class="form-label">Батьківська Категорія</p>
				<CategorySelect
					v-model="form.parent_id"
					:categories="categories"
				/>
			</div>
			<button type="submit" class="btn btn-success">Створити</button>
		</form>
	</AdminLayout>
</template>

<script setup lang="ts">
import AdminLayout from '@/Layouts/AdminLayout.vue';
import FormError from '@/AdminComponents/FormError.vue';
import { useForm, Link } from '@inertiajs/vue3';

import { ICategoryTree } from '@/types/ICategoryTree';

import CategorySelect from '@/AdminComponents/Category/CategorySelect.vue';
import { useSaveShortcut } from '@/modules/useSaveShortcut';

const props = defineProps<{ categories: ICategoryTree[] }>();

const form = useForm({
	name: '',
	parent_id: null
});

const submitEditForm = () => {
	form.clearErrors();
	form.post(route('admin.category.store'), {
		preserveScroll: true,
		onSuccess: () => {
			form.reset();
		}
	});
};

useSaveShortcut(submitEditForm);
</script>

<style scoped></style>
