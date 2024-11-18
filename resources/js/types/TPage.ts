import { IMetaField } from './IMetaField';
import { ITranslate } from './ITranslate';
import { TSeoEntity } from './TSeoEntity';

export type TPage = {
	id: number;
	title: ITranslate<string>;
	content: ITranslate<string>;
	type: string;
	meta: IMetaField<any>[];
	seo_entity: TSeoEntity;
};

export type TPageForm = {
	title: string;
	content: string;
	meta: { [key: string]: any };
	translatableMetaKeys: string[];
};
