<template>
	<AdminLayout>
		<RequiredProductsGroupForm :form="requiredProductsGroupForm" />

		<button type="button" class="btn btn-primary" @click="onSubmitForm">
			Зберегти
		</button>
	</AdminLayout>
</template>

<script setup lang="ts">
import AdminLayout from '@/Layouts/AdminLayout.vue';
import useMessages from '@/modules/useMessages';
import { useForm } from '@inertiajs/vue3';

import { useSaveShortcut } from '@/modules/useSaveShortcut';
import {
	TRequiredProductsGroup,
	TRequiredProductsGroupForm
} from '@/types/TRequiredProductsGroup';
import RequiredProductsGroupForm from '@/AdminComponents/RequiredProductsGroup/RequiredProductsGroupForm.vue';

const { _t } = useTranslations();

const { addMessage } = useMessages();

const props = defineProps<{
	requiredProductsGroup: TRequiredProductsGroup;
}>();

const requiredProductsGroupForm = useForm<TRequiredProductsGroupForm>({
	name: _t(props.requiredProductsGroup.name),
	products: props.requiredProductsGroup.product_ids.sort((a, b) => a - b)
});

const onSubmitForm = () => {
	requiredProductsGroupForm.clearErrors();
	requiredProductsGroupForm.put(
		route(
			'admin.requiredProductsGroup.update',
			props.requiredProductsGroup.id
		),
		{
			preserveScroll: true,
			preserveState: false,
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
		}
	);
};

useSaveShortcut(onSubmitForm);
</script>

<style scoped></style>
