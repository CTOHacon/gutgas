<template>
	<AdminLayout>
		<ProductsGroupForm :form="form" />

		<button type="submit" class="btn btn-success" @click="submitEditForm">
			Створити
		</button>
	</AdminLayout>
</template>

<script setup lang="ts">
import ProductsGroupForm from '@/AdminComponents/ProductsGroup/ProductsGroupForm.vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { useSaveShortcut } from '@/modules/useSaveShortcut';
import { TProductsGroupForm } from '@/types/TProductsGroup';
import { useForm } from '@inertiajs/vue3';

const form = useForm<TProductsGroupForm>({
	name: '',
	is_active: true,
	icon: '',
	icon_hover: '',
	icon_active: '',
	meta: {
		title: '',
		contentTitle: '',
		faqItems: [],
		content: ''
	},
	translatableMetaKeys: ['faqItems', 'title', 'contentTitle', 'content']
});

const submitEditForm = () => {
	form.clearErrors();
	form.post(route('admin.productsGroup.store'), {
		preserveScroll: true,
		onSuccess: () => {
			form.reset();
		}
	});
};

useSaveShortcut(submitEditForm);
</script>

<style scoped></style>
