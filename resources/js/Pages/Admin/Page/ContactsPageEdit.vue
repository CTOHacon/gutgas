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
					label="Контент"
					class="mb-1"
					:data-getter="() => pageForm.content"
					:data-setter="value => (pageForm.content = value)"
				>
					<TextContentEditor v-model="pageForm.content" />
				</FormFieldWrapper>
			</div>
			<div class="col-3">
				<div class="sticky-top">
					<div class="p-2 bg-light">
						<PageManageNavigation :page="page" />
					</div>
					<button
						class="btn btn-success w-100 mt-3"
						@click="onFormSubmit"
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

const { _t } = useTranslations();

const { addMessage } = useMessages();

const props = defineProps<{
	page: TPage;
}>();

const pageForm = useForm<TPageForm>({
	title: _t(props.page.title),
	content: _t(props.page.content),
	meta: {},
	translatableMetaKeys: []
});

const onFormSubmit = () => {
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

useSaveShortcut(onFormSubmit);
</script>

<style scoped></style>
