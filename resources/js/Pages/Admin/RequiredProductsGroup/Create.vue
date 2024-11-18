<template>
	<AdminLayout>
		<RequiredProductsGroupForm :form="requiredProductsGroupForm" />

		<button
			class="btn btn-primary"
			@click="onSubmitForm"
			:disabled="requiredProductsGroupForm.processing"
		>
			Створити
		</button>
	</AdminLayout>
</template>

<script setup lang="ts">
import AdminLayout from '@/Layouts/AdminLayout.vue';
import useMessages from '@/modules/useMessages';
import { useForm } from '@inertiajs/vue3';

import { useSaveShortcut } from '@/modules/useSaveShortcut';
import { TRequiredProductsGroupForm } from '@/types/TRequiredProductsGroup';
import RequiredProductsGroupForm from '@/AdminComponents/RequiredProductsGroup/RequiredProductsGroupForm.vue';

const { addMessage } = useMessages();

const requiredProductsGroupForm = useForm<TRequiredProductsGroupForm>({
	name: '',
	products: []
});

const onSubmitForm = () => {
	requiredProductsGroupForm.clearErrors();
	requiredProductsGroupForm.post(route('admin.requiredProductsGroup.store'), {
		preserveScroll: true,
		onSuccess: () => {},
		onError: () => {
			Object.entries(requiredProductsGroupForm.errors).forEach(
				([key, value]) => {
					addMessage({
						type: 'error',
						text: value
					});
				}
			);
		}
	});
};

useSaveShortcut(onSubmitForm);
</script>

<style scoped></style>
