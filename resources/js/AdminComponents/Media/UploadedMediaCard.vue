<template>
	<MediaFileCard
		:imageUrl="filePreviewURL"
		:name="file.name"
		:type="file.type"
		:disabled="isUploading"
	>
		<template #controls>
			<button class="btn btn-danger" @click="onDelete">
				<i class="bi bi-trash"></i>
			</button>
		</template>
	</MediaFileCard>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import MediaFileCard from './MediaFileCard.vue';
const props = defineProps<{
	file: File;
	isUploading?: boolean;
}>();

const filePreviewURL = ref<string>('');
onMounted(() => {
	const reader = new FileReader();
	reader.readAsDataURL(props.file);
	reader.onload = () => {
		filePreviewURL.value = reader.result as string;
	};
});

const onDelete = () => {
	emit('remove', props.file);
};

const emit = defineEmits({
	remove: (file: File) => true
});
</script>

<style scoped lang="scss">
.uploaded-media-card {
	overflow: hidden;
	&__image-container {
		width: 100%;
		height: 150px;
		img {
			width: 100%;
			height: 100%;
			object-fit: cover;
		}
	}
	&__name {
		white-space: nowrap;
		overflow: hidden;
		text-overflow: ellipsis;
	}
}
</style>
