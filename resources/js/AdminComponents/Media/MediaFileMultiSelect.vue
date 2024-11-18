<template>
	<div class="p-2 bg-light rounded">
		<div class="images-list mb-2" v-if="modelValue.length">
			<MediaFileCard
				v-for="file in modelValue"
				:key="file.id"
				:image-url="getMediaFileUrl(file, 'thumbnail') || null"
				:name="file.name"
				:type="file.mime_type"
			>
				<template #controls>
					<button
						class="btn btn-danger"
						@click="removeMediaFile(file)"
					>
						Прибрати
					</button>
				</template>
			</MediaFileCard>
		</div>
		<button class="btn btn-primary" @click="setupMediaGalleryModal()">
			Вибрати Медіа Файли
		</button>
	</div>
</template>

<script setup lang="ts">
import getMediaFileUrl from '@/modules/helpers/getMediaFileUrl';
import { IMediaFile } from '@/types/IMediaFile';
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
	modelValue: IMediaFile[];
}>();

const setupMediaGalleryModal = () => {
	setMediaGallerySelectionLimit(undefined);
	setMediaSelectionSubmitCallback((media: IMediaFile[]) => {
		closeMediaGalleryModal();
		emit('update:modelValue', media);
	});
	setMediaSelectionValue(props.modelValue);

	openMediaGalleryModal();
};

const removeMediaFile = (file: IMediaFile) => {
	const newMediaFiles = props.modelValue.filter(f => f.id !== file.id);
	emit('update:modelValue', newMediaFiles);
};

const emit = defineEmits({
	'update:modelValue': (value: IMediaFile[]) => true
});
</script>

<style scoped lang="scss">
.images-list {
	display: grid;
	grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
	gap: 0.5rem;
}
</style>
