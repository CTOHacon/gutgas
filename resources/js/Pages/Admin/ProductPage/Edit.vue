<template>
	<AdminLayout>
		<div class="row">
			<div class="col-9">
				<ProductPageForm
					:form="productPageForm"
					@submit="onSubmitPageForm"
				/>
			</div>
			<div class="col-3">
				<div class="sticky-top">
					<div class="p-2 bg-light">
						<ProductManageNavigation :product="product" />
					</div>
					<button
						class="btn btn-success w-100 mt-3"
						@click="onSubmitPageForm"
					>
						Зберегти Сторінку Продукту
					</button>
				</div>
			</div>
		</div>
	</AdminLayout>
</template>

<script setup lang="ts">
import ProductManageNavigation from '@/AdminComponents/Product/ProductManageNavigation.vue';
import ProductPageForm from '@/AdminComponents/ProductPage/ProductPageForm.vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import useMessages from '@/modules/useMessages';
import { useSaveShortcut } from '@/modules/useSaveShortcut';
import { TAdvantageCardProps } from '@/types/TAdvantageCardProps';
import { TFaqItemProps } from '@/types/TFaqItemProps';
import { TProduct } from '@/types/TProduct';
import { TProductPage, TProductPageForm } from '@/types/TProductPage';
import { useForm } from '@inertiajs/vue3';

const { _t } = useTranslations();

const props = defineProps<{
	product: TProduct;
	productPage: TProductPage;
}>();

const { addMessage } = useMessages();

const productPageForm = useForm<TProductPageForm>({
	title: _t(props.productPage.title),
	description: _t(props.productPage.description),
	media_gallery: props.productPage.media_gallery,
	characteristic_icons: props.productPage.characteristic_icons,
	documentations: props.productPage.documentations,
	faqItems:
		getMeta<TFaqItemProps[]>(props.productPage.meta, 'faqItems') ?? [],
	advantageItems:
		getMeta<TAdvantageCardProps[]>(
			props.productPage.meta,
			'advantageItems'
		) ?? []
});

const onSubmitPageForm = () => {
	productPageForm.clearErrors();
	productPageForm.put(
		route('admin.productPage.update', props.productPage.id),
		{
			preserveScroll: true,
			onSuccess: () => {},
			onError: () => {
				Object.entries(productPageForm.errors).forEach(
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

useSaveShortcut(onSubmitPageForm);
</script>

<style scoped></style>
