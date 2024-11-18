<template>
	<div>
		<div class="row mb-3">
			<div class="col-3">
				<div class="mb-3">
					<label class="form-label">Назва</label>
					<input class="form-control" v-model="form.name" />
					<FormError :error="form.errors.name" />
				</div>
				<div class="mb-3">
					<div class="form-check">
						<input
							class="form-check-input"
							type="checkbox"
							v-model="form.is_active"
							id="use_in_filters"
						/>
						<label class="form-check-label" for="use_in_filters">
							Відображати посилання у Каталозі
						</label>
					</div>
					<FormError :error="form.errors.is_active" />
				</div>
			</div>
			<div class="col-9">
				<div class="row mb-3">
					<div class="col-4">
						<label class="form-label">Іконка</label>
						<MediaFileUrlSelect v-model="form.icon" />
						<FormError :error="form.errors.icon" />
					</div>
					<div class="col-4">
						<label class="form-label">Іконка При Наведенні</label>
						<MediaFileUrlSelect v-model="form.icon_hover" />
					</div>
					<div class="col-4">
						<label class="form-label">Іконка Активності</label>
						<MediaFileUrlSelect v-model="form.icon_active" />
					</div>
				</div>
			</div>
		</div>
		<h3 class="mb-3">Налаштування Сторінки</h3>
		<FormFieldWrapper class="mb-3" label="H1 заголовок">
			<input
				type="text"
				placeholder="Заголовок"
				class="form-control"
				v-model="form.meta.title"
			/>
		</FormFieldWrapper>
		<FormFieldWrapper class="mb-3" label="Заголовок текстового Контенту">
			<input
				type="text"
				placeholder="Заголовок"
				class="form-control"
				v-model="form.meta.contentTitle"
			/>
		</FormFieldWrapper>
		<FormFieldWrapper class="mb-3" label="Текст сторінки">
			<TextContentEditor v-model="form.meta.content" />
		</FormFieldWrapper>
		<FormFieldWrapper
			class="mb-3"
			label="Питання Та Відповіді"
			:dataGetter="() => form.meta.faqItems"
			:dataSetter="value => (form.meta.faqItems = value)"
		>
			<FaqEditBlock v-model="form.meta.faqItems" />
		</FormFieldWrapper>
	</div>
</template>

<script setup lang="ts">
import { InertiaForm } from '@inertiajs/vue3';
import FormError from '../FormError.vue';
import { TProductsGroupForm } from '@/types/TProductsGroup';
import MediaFileUrlSelect from '../Media/MediaFileUrlSelect.vue';
import FormFieldWrapper from '../FormFieldWrapper.vue';
import TextContentEditor from '../TextContentEditor.vue';
import FaqEditBlock from '../ContentBlocks/FaqEditBlock.vue';

const props = defineProps<{
	form: InertiaForm<TProductsGroupForm>;
}>();
</script>

<style scoped></style>
