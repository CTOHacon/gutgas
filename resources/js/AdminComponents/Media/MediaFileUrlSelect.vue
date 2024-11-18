<template>
	<div class="medi-file-card">
		<MediaFileCard :imageUrl="modelValue">
			<template #controls>
				<button
					class="btn btn-primary"
					@click="setupMediaGalleryModal()"
				>
					Вибрати Медіа файл
				</button>
			</template>
		</MediaFileCard>
	</div>
</template>

<script setup lang="ts">
import { IMediaFile } from '@/types/IMediaFile';
import { ref, computed } from 'vue';
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
	modelValue: string | null | undefined;
}>();

const mediaSelectionValue = computed(() => {
	return props.modelValue ? [props.modelValue] : [];
});

const setupMediaGalleryModal = () => {
	setMediaGallerySelectionLimit(1);
	setMediaSelectionSubmitCallback((media: IMediaFile[]) => {
		closeMediaGalleryModal();
		emit('update:modelValue', media[0].url);
	});
	setMediaSelectionValue(mediaSelectionValue.value);

	openMediaGalleryModal();
};

const emit = defineEmits({
	'update:modelValue': (value: string | null) => true
});
</script>

<style scoped></style>
