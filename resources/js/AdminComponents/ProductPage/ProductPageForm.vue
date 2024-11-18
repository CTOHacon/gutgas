<template>
	<div>
		<FormFieldWrapper label="Заголовок" class="mb-3">
			<input
				class="form-control fs-4"
				v-model="form.title"
				placeholder="Заголовок"
			/>
			<FormError :error="form.errors.title" />
		</FormFieldWrapper>
		<FormFieldWrapper label="Product Gallery" class="mb-3">
			<MediaFileMultiSelect v-model="form.media_gallery" />
			<FormError :error="form.errors.media_gallery" />
		</FormFieldWrapper>
		<FormFieldWrapper
			label="Опис"
			class="mb-3"
			:data-getter="() => form.description"
			:data-setter="value => (form.description = value)"
		>
			<QuillEditor
				class="plain-content-ql-editor"
				content-type="html"
				@update:content="value => (form.description = value)"
				:content="form.description"
			/>
		</FormFieldWrapper>
		<FormFieldWrapper
			label="Зображення До Характеристики"
			class="mb-3"
			:dataGetter="() => form.characteristic_icons"
			:dataSetter="value => (form.characteristic_icons = value)"
		>
			<MediaFileMultiSelect v-model="form.characteristic_icons" />
			<FormError :error="form.errors.characteristic_icons" />
		</FormFieldWrapper>
		<FormFieldWrapper
			label="Документація"
			class="mb-3"
			:dataGetter="() => form.documentations"
			:dataSetter="value => (form.documentations = value)"
		>
			<MediaFileMultiSelect v-model="form.documentations" />
			<FormError :error="form.errors.documentations" />
		</FormFieldWrapper>
		<FormFieldWrapper
			label="Питання та Відповіді"
			:dataGetter="() => form.faqItems"
			:dataSetter="value => (form.faqItems = value)"
			class="mb-3"
		>
			<FaqEditBlock v-model="form.faqItems" />
		</FormFieldWrapper>
	</div>
</template>

<script setup lang="ts">
import { TProductPageForm } from '@/types/TProductPage';
import { InertiaForm } from '@inertiajs/vue3';
import FormError from '../FormError.vue';
import MediaFileMultiSelect from '../Media/MediaFileMultiSelect.vue';
import { QuillEditor } from '@vueup/vue-quill';
import FormFieldWrapper from '../FormFieldWrapper.vue';
import FaqEditBlock from '../ContentBlocks/FaqEditBlock.vue';

const props = defineProps<{
	form: InertiaForm<TProductPageForm>;
}>();
</script>

<style scoped lang="scss"></style>
