import { TMediaFileScales } from './TMediaFileScales';

export interface IScaledMediaFile {
	id: number;
	media_file_id: number;
	size: string;
	path: string;
	url: string;
	scale: TMediaFileScales;
}
