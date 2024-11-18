<template>
	<AdminLayout>
		<h3 class="mb-3">Редагувати SEO Дані</h3>
		<div class="row">
			<div class="col-9">
				<SeoEntityForm :form="productSeoEntityForm" />
			</div>
			<div class="col-3">
				<div class="sticky-top">
					<div class="p-2 bg-light">
						<ProductsGroupManageNavigation
							:products-group="props.productsGroup"
						/>
					</div>
					<button
						class="btn btn-success w-100 mt-3"
						@click="onSubmitSeoEntityForm"
					>
						Зберегти SEO Дані
					</button>
				</div>
			</div>
		</div>
	</AdminLayout>
</template>

<script setup lang="ts">
import SeoEntityForm from '@/AdminComponents/SeoEntity/SeoEntityForm.vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import useMessages from '@/modules/useMessages';
import { TSeoEntity, TSeoEntityForm } from '@/types/TSeoEntity';
import { useForm } from '@inertiajs/vue3';
import { TProductsGroup } from '@/types/TProductsGroup';
import ProductsGroupManageNavigation from '@/AdminComponents/ProductsGroup/ProductsGroupManageNavigation.vue';
import { useSaveShortcut } from '@/modules/useSaveShortcut';

const { _t } = useTranslations();

const { addMessage } = useMessages();

const props = defineProps<{
	productsGroup: TProductsGroup;
	seoEntity: TSeoEntity;
}>();

const productSeoEntityForm = useForm<TSeoEntityForm>({
	title: _t(props.seoEntity.title),
	breadcrumbs_title: _t(props.seoEntity.breadcrumbs_title),
	description: _t(props.seoEntity.description),
	og_image: _t(props.seoEntity.og_image),
	slug: props.seoEntity.slug,
	is_active: +_t(props.seoEntity.is_active, true) === 1
});

const onSubmitSeoEntityForm = () => {
	productSeoEntityForm.clearErrors();
	productSeoEntityForm.put(
		route('admin.seoEntity.update', props.seoEntity.id),
		{
			preserveScroll: true,
			onSuccess: () => {},
			onError: () => {
				Object.entries(productSeoEntityForm.errors).forEach(
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

useSaveShortcut(onSubmitSeoEntityForm);
</script>

<style scoped></style>
