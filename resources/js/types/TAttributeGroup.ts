import { ITranslate } from './ITranslate';
import { TAttribute } from './TAttribute';

export type TAttributeGroup = {
	id: number;
	name: ITranslate<string>;
	use_in_filters: 0 | 1;
	attributes_count?: number;
	attributes?: TAttribute[];
};

export type TAttributeGroupForm = {
	name: string;
};
