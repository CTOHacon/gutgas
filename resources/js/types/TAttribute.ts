import { ITranslate } from './ITranslate';
import { TAttributeGroup } from './TAttributeGroup';

export type TAttribute = {
	id: number;
	sequence: number;
	name: ITranslate<string>;
	attribute_group_id: number;
	attribute_group?: TAttributeGroup;
};

export type TAttributeForm = {
	name: string;
	sequence: number;
	attribute_group_id?: number;
};
