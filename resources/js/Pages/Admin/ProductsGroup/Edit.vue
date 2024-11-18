<template>
	<AdminLayout>
		<div class="row">
			<div class="col-9">
				<ProductsGroupForm :form="productsGroupForm" />
			</div>
			<div class="col-3">
				<div class="sticky-top">
					<div class="p-2 bg-light">
						<ProductsGroupManageNavigation
							:productsGroup="productsGroup"
						/>
					</div>
					<button
						class="btn btn-success w-100 mt-3"
						@click="onSubmitForm"
					>
						Зберегти
					</button>
				</div>
			</div>
		</div>
	</AdminLayout>
</template>

<script setup lang="ts">
import AdminLayout from '@/Layouts/AdminLayout.vue';
import useMessages from '@/modules/useMessages';
import { useForm } from '@inertiajs/vue3';

import { TProductsGroup, TProductsGroupForm } from '@/types/TProductsGroup';
import ProductsGroupForm from '@/AdminComponents/ProductsGroup/ProductsGroupForm.vue';
import ProductsGroupManageNavigation from '@/AdminComponents/ProductsGroup/ProductsGroupManageNavigation.vue';
import { useSaveShortcut } from '@/modules/useSaveShortcut';
import { TFaqItemProps } from '@/types/TFaqItemProps';

const { _t } = useTranslations();

const { addMessage } = useMessages();

const props = defineProps<{
	productsGroup: TProductsGroup;
}>();

const productsGroupForm = useForm<TProductsGroupForm>({
	name: _t(props.productsGroup.name),
	icon: props.productsGroup.icon,
	icon_active: props.productsGroup.icon_active,
	icon_hover: props.productsGroup.icon_hover,
	is_active: props.productsGroup.is_active === 1,
	meta: {
		title: getMeta<string>(props.productsGroup.meta, 'title') ?? '',
		contentTitle:
			getMeta<string>(props.productsGroup.meta, 'contentTitle') ?? '',
		faqItems:
			getMeta<TFaqItemProps[]>(props.productsGroup.meta, 'faqItems') ??
			[],
		content: getMeta<string>(props.productsGroup.meta, 'content') ?? ''
	},
	translatableMetaKeys: ['faqItems', 'title', 'contentTitle', 'content']
});

const onSubmitForm = () => {
	productsGroupForm.clearErrors();
	productsGroupForm.put(
		route('admin.productsGroup.update', props.productsGroup.id),
		{
			preserveScroll: true,
			onSuccess: () => {},
			onError: () => {
				Object.entries(productsGroupForm.errors).forEach(
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
