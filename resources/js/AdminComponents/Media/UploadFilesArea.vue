<template>
	<div class="p-2 border rounded bg-white">
		<div
			class="drag-area d-flex justify-content-center align-items-center border rounded p-4"
			@dragover.prevent
			@drop.prevent="onDrop"
			@click="openFileSelect"
		>
			<i class="bi bi-cloud-arrow-up fs-2"></i>
			<h4 class="mb-0 ms-2">Вибрати Файли</h4>
		</div>
		<input
			type="file"
			multiple
			ref="fileInput"
			@change="onFileChange"
			style="display: none"
		/>
		<div
			v-if="files.length"
			class="row mt-2"
			style="--bs-gutter-x: 0.5rem; --bs-gutter-y: 0.5rem"
		>
			<div class="col-2" v-for="file in files" :key="file.name">
				<UploadedMediaCard
					:file="file"
					:isUploading="uploading"
					@remove="removeFile"
				/>
			</div>
		</div>
		<div class="d-flex mt-2 gap-1">
			<button
				class="btn btn-success"
				@click="submitFiles"
				:disabled="!files.length || uploading"
			>
				Завантажити
			</button>
		</div>
	</div>
</template>

<script setup lang="ts">
import { IMediaFile } from '@/types/IMediaFile';
import { storeMediaFile } from '@/api/mediaFileService';
import { ref, reactive } from 'vue';
import UploadedMediaCard from './UploadedMediaCard.vue';
import useMessages from '@/modules/useMessages';

const fileInput = ref<HTMLElement | null>(null);
const files = ref<File[]>([]);

const openFileSelect = () => {
	fileInput.value?.click();
};
const onFileChange = (e: any) => {
	files.value.push(...e.target.files);
};
const onDrop = (e: any) => {
	files.value.push(...e.dataTransfer.files);
};

const uploading = ref(false);

const submitFiles = async () => {
	emit('uploading');
	uploading.value = true;

	const filePromises = Array.from(files.value).map(async (file, i) => {
		try {
			const responce = await storeMediaFile(file);
			if (responce.status === 201) {
				addMessage({
					type: 'success',
					text: `${file.name} uploaded successfully`
				});
				removeFile(file);
				return responce.data;
			}
		} catch (e) {
			addMessage({ type: 'danger', text: `${file.name} upload error` });
			console.log(e);
		}
	});

	const results = await Promise.all(filePromises);

	uploading.value = false;

	emit('upload', results.filter(Boolean) as IMediaFile[]);
};

const removeFile = (file: File) => {
	files.value = Object.values(files.value).filter(f => f !== file);
};

const emit = defineEmits({
	uploading: () => true,
	upload: async (uploadedFiles: IMediaFile[]) => true
});
const { addMessage } = useMessages();
</script>
<style scoped>
.drag-area {
	border-style: dashed !important;
	cursor: pointer;
	transition: all 0.2s ease-in-out;
}
.drag-area:hover {
	border-color: var(--bs-link-hover-color) !important;
	color: var(--bs-link-hover-color) !important;
}
</style>
