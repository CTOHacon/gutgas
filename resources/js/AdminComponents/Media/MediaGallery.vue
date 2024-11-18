<template>
	<div class="">
		<UploadFilesArea
			v-if="hasPermissions('admin.media.manage')"
			@upload="onUpload"
		/>
		<div class="d-flex gap-1 flex-wrap p-2 bg-white my-1 sticky-top">
			<button
				class="btn btn-success"
				v-if="selectMode"
				@click="onSelectedMediaFilesSubmit"
			>
				Підтвердити
			</button>
			<button
				v-if="selectedMediaFiles.length"
				class="btn btn-dark"
				@click="clearSelection"
			>
				Очистити виділення ({{ selectedMediaFiles.length }})
			</button>

			<button
				class="btn btn-danger"
				v-if="
					selectedMediaFiles.length &&
					hasPermissions('admin.media.manage')
				"
				:disabled="selectedMediaFiles.length === 0"
				@click="onSelectedMediaFilesDelete"
			>
				Видалити
			</button>
			<div class="d-flex gap-1 ms-auto">
				<input
					class="form-control"
					placeholder="Пошук..."
					v-model="search"
				/>
			</div>
		</div>
		<div class="gallery-list">
			<MediaGalleryItemCard
				v-for="file in mediaFiles"
				:key="file.id"
				:mediaFile="file"
				:selected="
					selectedMediaFiles.find(
						selectedFile => selectedFile?.id === file.id
					) !== undefined
				"
				:shouldBeDelete="file.shouldBeDeleted"
				@delete="onDelete"
				@select="onSelect"
			/>
		</div>
		<button
			class="btn btn-primary mt-2"
			@click="fetchMediaFiles"
			v-if="!isLastPage"
		>
			Load more
		</button>
	</div>
</template>

<script setup lang="ts">
import { geIMediaFiles } from '@/api/mediaFileService';
import { IMediaFile } from '@/types/IMediaFile';
import { ref, onMounted, computed, watch } from 'vue';
import UploadFilesArea from './UploadFilesArea.vue';
import MediaGalleryItemCard from './MediaGalleryItemCard.vue';
import useAuth from '@/modules/useAuth';
import debounce from 'lodash.debounce';
const { hasPermissions } = useAuth();

const props = defineProps<{
	selectMode?: boolean;
	selection?: IMediaFile[] | number[] | string[];
	selectionLimit?: number;
}>();

const mediaFiles = ref<IMediaFile[]>([]);
const lastItemId = computed(() =>
	mediaFiles.value.length
		? mediaFiles.value[mediaFiles.value.length - 1].id
		: null
);
const isLastPage = ref(false);

const search = ref('');

const searchMediaFiles = debounce(async () => {
	mediaFiles.value = [];
	await fetchMediaFiles();
}, 500);
watch(search, searchMediaFiles);

const selectedMediaFiles = ref<IMediaFile[]>([]);
watch(
	() => props.selection,
	() => {
		let newSelectedMediaFiles: IMediaFile[] = [];
		props.selection?.forEach(item => {
			let file = null;
			if (typeof item === 'number')
				file = mediaFiles.value.find(f => f.id === item);
			else if (typeof item === 'string') {
				file = mediaFiles.value.find(f => f.url === item);
			} else file = item;

			if (file) {
				newSelectedMediaFiles?.push(file);
			}
		});
		selectedMediaFiles.value = newSelectedMediaFiles ?? [];
	},
	{ immediate: true }
);

const fetchMediaFiles = async () => {
	const files = await geIMediaFiles({
		lastItemId: lastItemId.value,
		search: search.value
	});

	mediaFiles.value.push(...files.data);
};

onMounted(() => {
	if (mediaFiles.value.length) return;
	fetchMediaFiles();
});

const onUpload = (files: IMediaFile[]) => {
	mediaFiles.value.unshift(...files);
	if (props.selectMode) {
		selectedMediaFiles.value.push(...files);
	}
};

const onDelete = (file: IMediaFile) => {
	mediaFiles.value = mediaFiles.value.filter(f => f.id !== file.id);

	selectedMediaFiles.value = selectedMediaFiles.value.filter(
		f => f.id !== file.id
	);
};

const clearSelection = () => {
	selectedMediaFiles.value = [];
};
const onSelect = (file: IMediaFile) => {
	const index = selectedMediaFiles.value.findIndex(f => f.id === file.id);
	if (index !== -1) {
		selectedMediaFiles.value.splice(index, 1);
		return;
	}

	if (
		props.selectionLimit &&
		selectedMediaFiles.value.length >= props.selectionLimit
	) {
		selectedMediaFiles.value.splice(selectedMediaFiles.value.length - 1, 1);
	}

	selectedMediaFiles.value.push(file);
};

const onSelectedMediaFilesSubmit = () => {
	emit('submitSelectedFiles', selectedMediaFiles.value);

	selectedMediaFiles.value = [];
};

const onSelectedMediaFilesDelete = () => {
	if (
		!confirm(
			`Are you sure you want to delete ${selectedMediaFiles.value.length} files?`
		)
	)
		return;

	selectedMediaFiles.value.forEach(file => {
		mediaFiles.value = mediaFiles.value.map(f => {
			if (f.id === file.id) {
				f.shouldBeDeleted = true;
			}
			return f;
		});
	});
};

const emit = defineEmits({
	submitSelectedFiles: (mediaFiles: IMediaFile[]) => true
});
</script>

<style scoped lang="scss">
.gallery-list {
	// auto grid 1fr 200px
	display: grid;
	gap: 0.5rem;
	grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
	padding: 2px;
}
</style>
