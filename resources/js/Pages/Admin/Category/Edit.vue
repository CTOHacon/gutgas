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
				<FormError :error="form.errors.parent_id" />
			</div>
			<button type="submit" class="btn btn-success">
				Зберегти Зміни
			</button>
		</form>
	</AdminLayout>
</template>

<script setup lang="ts">
import AdminLayout from '@/Layouts/AdminLayout.vue';
import FormError from '@/AdminComponents/FormError.vue';
import { useForm, Link } from '@inertiajs/vue3';

import { ICategoryTree } from '@/types/ICategoryTree';
import { ICategory } from '@/types/ICategory';

import CategorySelect from '@/AdminComponents/Category/CategorySelect.vue';
import { toRefs } from 'vue';
import { useSaveShortcut } from '@/modules/useSaveShortcut';

const props = defineProps<{
	categories: ICategoryTree[];
	category: ICategory;
}>();

const { categories, category } = toRefs(props);

const form = useForm({
	name: category.value.name,
	parent_id: category.value.parent_id
});

const submitEditForm = () => {
	form.clearErrors();
	form.put(route('admin.category.update', category.value.id), {
		preserveScroll: true,
		onSuccess: () => {}
	});
};

useSaveShortcut(submitEditForm);
</script>

<style scoped></style>
