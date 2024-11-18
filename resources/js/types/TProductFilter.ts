import { ITranslate } from './ITranslate';
import { TProductFilterValue } from './TProductFilterValue';

export type TProductFilter = {
	id: number;
	name: ITranslate<string>;
	sequence: number;
	product_filter_values: TProductFilterValue[];
};

export type TProductFilterForm = {
	name: string;
	sequence: number;
};
