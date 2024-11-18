import { ITranslate } from './ITranslate';
import { TProductFilter } from './TProductFilter';

export type TProductFilterValue = {
	id: number;
	value: ITranslate<string>;
	sequence: number;
	product_filter_id: number;
	productFilter?: TProductFilter;
};

export type TProductFilterValueForm = {
	value: string;
	sequence: number;
	product_filter_id?: number;
};
