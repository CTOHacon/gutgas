import { IMetaField } from './IMetaField';
import { ITranslate } from './ITranslate';
import { TSeoEntity } from './TSeoEntity';

export type TProductsGroup = {
	id: number;
	name: ITranslate<string>;
	icon: string;
	icon_hover: string;
	icon_active: string;
	is_active: 0 | 1;
	seo_entity: TSeoEntity;
	meta: IMetaField<any>[];
};

export type TProductsGroupForm = {
	name: string;
	icon: string;
	icon_hover: string;
	icon_active: string;
	is_active: boolean;
	meta: { [key: string]: any };
	translatableMetaKeys: string[];
};
