import { IScaledMediaFile } from './IScaledMediaFile';
import { TMediaFileScales } from './TMediaFileScales';

type ScaledMediaFilesMap = {
	[scale in TMediaFileScales]?: IScaledMediaFile;
};

export interface IMediaFile extends ScaledMediaFilesMap {
	id: number;
	name: string;
	size: string;
	extension: string;
	mime_type: string;
	path: string;
	url: string;

	scaled_items?: IScaledMediaFile[] | undefined;

	// Custom properties for the frontend
	shouldBeDeleted?: boolean | undefined;
}
