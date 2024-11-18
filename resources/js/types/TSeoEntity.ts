import { ITranslate } from './ITranslate';

export type TSeoEntity = {
	id: number;
	slug: string;
	title: string;
	breadcrumbs_title: string;
	description: string;
	og_image: string;
	controller: string;
	action: string;
	is_active: 0 | 1;
};

export type TSeoEntityForm = {
	slug: string;
	title: string;
	breadcrumbs_title: string;
	description: string;
	og_image: string;
	controller?: string;
	action?: string;
	is_active: boolean;
};
