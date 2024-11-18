import { IMediaFile } from '@/types/IMediaFile';
import axios from 'axios';

// laravel pagination response
// interface IGeIMediaFilesResponse {
// 	data: IMediaFile[];
// 	current_page: number;
// 	from: number;
// 	last_page: number;
// 	per_page: number;
// 	to: number;
// 	total: number;
// }
export const geIMediaFiles = async (
	params: {
		lastItemId: number | null;
		search?: string;
	} = {
		lastItemId: null
	}
) => {
	const response = await axios.get<IMediaFile[]>(
		route('ajax.mediaFile.index'),
		{
			params
		}
	);

	return response;
};

export const storeMediaFile = async (file: File) => {
	const formData = new FormData();
	formData.append('file', file);

	const response = await axios.post<IMediaFile>(
		route('ajax.mediaFile.store'),
		formData,
		{
			headers: {
				'Content-Type': 'multipart/form-data'
			}
		}
	);

	return response;
};

export const deleteMediaFile = async (id: number) => {
	const response = await axios.delete(route('ajax.mediaFile.destroy', id));

	return response;
};
