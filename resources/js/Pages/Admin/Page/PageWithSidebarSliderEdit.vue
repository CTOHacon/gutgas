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
				<FormFieldWrapper
					label="Content"
					class="mb-1"
					:data-getter="() => pageForm.content"
					:data-setter="value => (pageForm.content = value)"
				>
					<TextContentEditor v-model="pageForm.content" />
				</FormFieldWrapper>
				<FormFieldWrapper
					label="Slider Images"
					class="mb-3"
					:dataGetter="() => pageForm.meta.sidebarSliderImages"
					:dataSetter="
						value => (pageForm.meta.sidebarSliderImages = value)
					"
				>
					<MediaFileMultiSelect
						v-model="pageForm.meta.sidebarSliderImages"
					/>
				</FormFieldWrapper>
				<FormFieldWrapper label="Contact Form" class="mb-3">
					<input
						type="text"
						placeholder="Заголовок"
						class="form-control mb-1"
						v-model="pageForm.meta.contactFormTitle"
					/>
					<input
						type="text"
						placeholder="Sub Title"
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
						<PageManageNavigation :page="page" />
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
import PageManageNavigation from '@/AdminComponents/Page/PageManageNavigation.vue';
import { TPage, TPageForm } from '@/types/TPage';
import { useForm } from '@inertiajs/vue3';
import FormFieldWrapper from '@/AdminComponents/FormFieldWrapper.vue';
import FormError from '@/AdminComponents/FormError.vue';

import TextContentEditor from '@/AdminComponents/TextContentEditor.vue';
import { useSaveShortcut } from '@/modules/useSaveShortcut';
import { QuillEditor } from '@vueup/vue-quill';
import MediaFileMultiSelect from '@/AdminComponents/Media/MediaFileMultiSelect.vue';

const { _t } = useTranslations();

const { addMessage } = useMessages();

const props = defineProps<{
	page: TPage;
}>();

const pageForm = useForm<TPageForm>({
	title: _t(props.page.title),
	content: _t(props.page.content),
	meta: {
		advantagesItems:
			getMeta<string>(props.page.meta, 'advantagesItems') || [],
		contactFormTitle:
			getMeta<string>(props.page.meta, 'contactFormTitle') || '',
		contactFormSubTitle:
			getMeta<string>(props.page.meta, 'contactFormSubTitle') || '',
		contactFormText:
			getMeta<string>(props.page.meta, 'contactFormText') || '',
		sidebarSliderImages:
			getMeta<string[]>(props.page.meta, 'sidebarSliderImages') || []
	},
	translatableMetaKeys: [
		'advantagesItems',
		'contactFormTitle',
		'contactFormSubTitle',
		'contactFormText'
	]
});

const onSubmitForm = () => {
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

useSaveShortcut(onSubmitForm);
</script>

<style scoped></style>
