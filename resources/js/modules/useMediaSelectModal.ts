import { IMediaFile } from '@/types/IMediaFile';
import { TMediaSelection } from '@/types/TMediaSelection';
import { ref } from 'vue';

const mediaGalleryModalOpened = ref(false);
const openMediaGalleryModal = () => {
	mediaGalleryModalOpened.value = true;
};
const closeMediaGalleryModal = () => {
	mediaGalleryModalOpened.value = false;
};
const toggleMediaGalleryModal = () => {
	mediaGalleryModalOpened.value = !mediaGalleryModalOpened.value;
};

const mediaSelectionValue = ref<TMediaSelection>([]);
const setMediaSelectionValue = (value: TMediaSelection) => {
	mediaSelectionValue.value = value;
};

const mediaGallerySelectionLimit = ref<number | undefined>();
const setMediaGallerySelectionLimit = (value: number | undefined) => {
	mediaGallerySelectionLimit.value = value;
};

const mediaSelectionSubmitCallback = ref((media: IMediaFile[]) => {});
const setMediaSelectionSubmitCallback = (
	callback: (media: IMediaFile[]) => void
) => {
	mediaSelectionSubmitCallback.value = callback;
};

export const useMediaSelectModal = () => {
	return {
		mediaGalleryModalOpened,
		openMediaGalleryModal,
		closeMediaGalleryModal,
		toggleMediaGalleryModal,
		mediaSelectionValue,
		setMediaSelectionValue,
		mediaGallerySelectionLimit,
		setMediaGallerySelectionLimit,
		mediaSelectionSubmitCallback,
		setMediaSelectionSubmitCallback
	};
};

export default useMediaSelectModal;
