<template>
	<AdminLayout>
		<h3 class="mb-3">
			Edit Page - {{ _t(page.title) || 'Без Заголовка' }}
			<em>({{ page.type }})</em>
		</h3>
		<div class="row">
			<div class="col-9">
				<FormFieldWrapper class="mb-3" label="Заголовок">
					<input
						type="text"
						placeholder="Заголовок"
						class="form-control"
						v-model="pageForm.title"
					/>
					<FormError :error="pageForm.errors.title" />
				</FormFieldWrapper>
				<FormFieldWrapper label="Hero" class="mb-3">
					<input
						type="text"
						placeholder="Заголовок Головного Екрану"
						class="form-control mb-1"
						v-model="pageForm.meta.heroTitle"
					/>
					<QuillEditor
						:toolbar="{
							container: [['bold']]
						}"
						content-type="html"
						@update:content="
							value => (pageForm.meta.heroSubTitle = value)
						"
						:content="pageForm.meta.heroSubTitle"
					/>
				</FormFieldWrapper>
				<FormFieldWrapper label="Секція Про Нас" class="mb-3">
					<input
						type="text"
						placeholder="Заголовок"
						class="form-control mb-1"
						v-model="pageForm.meta.aboutTitle"
					/>
					<QuillEditor
						:toolbar="{
							container: [['bold']]
						}"
						content-type="html"
						@update:content="
							value => (pageForm.meta.aboutContent = value)
						"
						:content="pageForm.meta.aboutContent"
					/>
				</FormFieldWrapper>
				<FormFieldWrapper
					label="Каталог"
					class="mb-3"
					:data-getter="
						() => {
							return {
								title: pageForm.meta.catalogPreviewTitle,
								slug: pageForm.meta.catalogPreviewSlug,
								items: pageForm.meta.catalogPreviewItems
							};
						}
					"
					:data-setter="
						({ title, slug, items }) => {
							pageForm.meta.catalogPreviewTitle = title;
							pageForm.meta.catalogPreviewItems = items;
						}
					"
				>
					<input
						type="text"
						placeholder="Заголовок"
						class="form-control mb-1"
						v-model="pageForm.meta.catalogPreviewTitle"
					/>
					<ProductsCatalogPreviewEditBlock
						v-model="pageForm.meta.catalogPreviewItems"
					/>
				</FormFieldWrapper>
				<FormFieldWrapper
					label="Переваги Та Недоліки"
					class="mb-3"
					:data-getter="
						() => {
							return {
								title: pageForm.meta.compairTitle,
								subTitle: pageForm.meta.compairSubTitle,
								items: pageForm.meta.compairItems
							};
						}
					"
					:data-setter="
						({ title, subTitle, items }) => {
							pageForm.meta.compairTitle = title;
							pageForm.meta.compairSubTitle = subTitle;
							pageForm.meta.compairItems = items;
						}
					"
				>
					<input
						type="text"
						placeholder="Заголовок"
						class="form-control mb-1"
						v-model="pageForm.meta.compairTitle"
					/>
					<label>Підзаголовок</label>
					<QuillEditor
						:toolbar="{
							container: [['bold']]
						}"
						content-type="html"
						@update:content="
							value => (pageForm.meta.compairSubTitle = value)
						"
						:content="pageForm.meta.compairSubTitle"
						class="mb-1"
					/>
					<ProsConsEditBlock v-model="pageForm.meta.compairItems" />
				</FormFieldWrapper>
				<FormFieldWrapper
					label="Способи Використання"
					class="mb-3"
					:data-getter="
						() => {
							return {
								title: pageForm.meta.useCasesTitle,
								items: pageForm.meta.useCasesItems
							};
						}
					"
					:data-setter="
						({ title, items }) => {
							pageForm.meta.useCasesTitle = title;
							pageForm.meta.useCasesItems = items;
						}
					"
				>
					<input
						type="text"
						placeholder="Заголовок"
						class="form-control mb-1"
						v-model="pageForm.meta.useCasesTitle"
					/>
					<UseCaseEditBlock v-model="pageForm.meta.useCasesItems" />
				</FormFieldWrapper>
				<FormFieldWrapper label="Контатктна Форма" class="mb-3">
					<input
						type="text"
						placeholder="Заголовок"
						class="form-control mb-1"
						v-model="pageForm.meta.contactFormTitle"
					/>
					<input
						type="text"
						placeholder="Підзаголовок"
						class="form-control mb-1"
						v-model="pageForm.meta.contactFormSubTitle"
					/>
					<label>Contact Form Text</label>
					<QuillEditor
						:toolbar="{
							container: [['bold']]
						}"
						content-type="html"
						@update:content="
							value => (pageForm.meta.contactFormText = value)
						"
						:content="pageForm.meta.contactFormText"
					/>
				</FormFieldWrapper>
			</div>
			<div class="col-3">
				<div class="sticky-top">
					<div class="p-2 bg-light">
						<PageManageNavigation :page="props.page" />
					</div>
					<button
						class="btn btn-success w-100 mt-3"
						@click="onSubmitSeoEntityForm"
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
import PageManageNavigation from '@/AdminComponents/Page/PageManageNavigation.vue';
import { TPage, TPageForm } from '@/types/TPage';
import { useForm } from '@inertiajs/vue3';
import FormFieldWrapper from '@/AdminComponents/FormFieldWrapper.vue';
import FormError from '@/AdminComponents/FormError.vue';
import { TProductProsConsCardProps } from '@/types/TProductProsConsCardProps';
import { TUseCaseCardProps } from '@/types/TUseCaseCardProps';
import { TProductsCatalogPreviewSectionStoreData } from '@/types/TProductsCatalogPreviewSectionProps';
import ProsConsEditBlock from '@/AdminComponents/ContentBlocks/ProsConsEditBlock.vue';
import { QuillEditor } from '@vueup/vue-quill';
import UseCaseEditBlock from '@/AdminComponents/ContentBlocks/UseCaseEditBlock.vue';
import ProductsCatalogPreviewEditBlock from '@/AdminComponents/ContentBlocks/ProductsCatalogPreviewEditBlock.vue';
import { useSaveShortcut } from '@/modules/useSaveShortcut';

const { _t } = useTranslations();

const { addMessage } = useMessages();

const props = defineProps<{
	page: TPage;
}>();

const pageForm = useForm<TPageForm>({
	title: _t(props.page.title),
	content: _t(props.page.content),
	meta: {
		heroTitle: getMeta<string>(props.page.meta, 'heroTitle') ?? '',
		heroSubTitle: getMeta<string>(props.page.meta, 'heroSubTitle') ?? '',

		aboutTitle: getMeta<string>(props.page.meta, 'aboutTitle') ?? '',
		aboutContent: getMeta<string>(props.page.meta, 'aboutContent') ?? '',

		catalogPreviewTitle:
			getMeta<string>(props.page.meta, 'catalogPreviewTitle') ?? '',
		catalogPreviewItems:
			getMeta<TProductsCatalogPreviewSectionStoreData[]>(
				props.page.meta,
				'catalogPreviewItems'
			) ?? [],

		compairTitle: getMeta<string>(props.page.meta, 'compairTitle') ?? '',
		compairSubTitle:
			getMeta<string>(props.page.meta, 'compairSubTitle') ?? '',
		compairItems:
			getMeta<TProductProsConsCardProps[]>(
				props.page.meta,
				'compairItems'
			) ?? [],

		useCasesTitle: getMeta<string>(props.page.meta, 'useCasesTitle') ?? '',
		useCasesItems:
			getMeta<TUseCaseCardProps[]>(props.page.meta, 'useCasesItems') ??
			[],

		contactFormTitle:
			getMeta<string>(props.page.meta, 'contactFormTitle') ?? '',
		contactFormSubTitle:
			getMeta<string>(props.page.meta, 'contactFormSubTitle') ?? '',
		contactFormText:
			getMeta<string>(props.page.meta, 'contactFormText') ?? ''
	},
	translatableMetaKeys: [
		'heroTitle',
		'heroSubTitle',
		'aboutTitle',
		'aboutContent',
		'catalogPreviewTitle',
		'catalogPreviewSlug',
		'catalogPreviewItems',
		'compairTitle',
		'compairSubTitle',
		'compairItems',
		'useCasesTitle',
		'useCasesItems',
		'contactFormTitle',
		'contactFormSubTitle',
		'contactFormText'
	]
});

const onSubmitSeoEntityForm = () => {
	pageForm.clearErrors();
	pageForm.put(route('admin.page.update', props.page.id), {
		preserveScroll: true,
		onSuccess: () => {},
		onError: () => {
			Object.entries(pageForm.errors).forEach(([key, value]) => {
				addMessage({
					type: 'error',
					text: value
				});
			});
		}
	});
};

useSaveShortcut(onSubmitSeoEntityForm);
</script>

<style scoped></style>
