import { IMediaFile } from '@/types/IMediaFile';
import { TMediaFileScales } from '@/types/TMediaFileScales';

const getMediaFileUrl = (mediaFile: IMediaFile, scale: TMediaFileScales) => {
	if (mediaFile[scale]) return mediaFile[scale]?.url;

	if (!mediaFile.scaled_items) return mediaFile.url;

	const scaledItem = mediaFile.scaled_items.find(
		scaledItem => scaledItem.scale === scale
	);
	return scaledItem ? scaledItem.url : mediaFile.url;
};

export default getMediaFileUrl;
