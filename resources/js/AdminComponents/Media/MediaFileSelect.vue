<template>
	<div class="medi-file-card">
		<MediaFileCard
			:imageUrl="
				modelValue ? getMediaFileUrl(modelValue, 'thumbnail') : null
			"
			:name="modelValue ? modelValue.name : null"
			:type="modelValue ? modelValue.mime_type : null"
		>
			<template #controls>
				<button
					class="btn btn-primary"
					@click="setupMediaGalleryModal()"
				>
					Вибрати Медіа Файлз
				</button>
			</template>
		</MediaFileCard>
	</div>
</template>

<script setup lang="ts">
import getMediaFileUrl from '@/modules/helpers/getMediaFileUrl';
import { IMediaFile } from '@/types/IMediaFile';
import { computed } from 'vue';
import MediaFileCard from './MediaFileCard.vue';
import useMediaSelectModal from '@/modules/useMediaSelectModal';

const {
	setMediaGallerySelectionLimit,
	openMediaGalleryModal,
	closeMediaGalleryModal,
	setMediaSelectionSubmitCallback,
	setMediaSelectionValue
} = useMediaSelectModal();

const props = defineProps<{
	modelValue: IMediaFile | null;
}>();

const mediaSelectionValue = computed(() => {
	return props.modelValue ? [props.modelValue] : [];
});

const setupMediaGalleryModal = () => {
	setMediaGallerySelectionLimit(1);
	setMediaSelectionSubmitCallback((media: IMediaFile[]) => {
		closeMediaGalleryModal();
		emit('update:modelValue', media[0]);
	});
	setMediaSelectionValue(mediaSelectionValue.value);

	openMediaGalleryModal();
};

const emit = defineEmits({
	'update:modelValue': (value: IMediaFile | null) => true
});
</script>

<style scoped></style>
