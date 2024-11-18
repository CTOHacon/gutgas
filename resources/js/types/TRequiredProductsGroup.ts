import { ITranslate } from './ITranslate';
import { TProduct } from './TProduct';

export type TRequiredProductsGroup = {
	id: number;
	name: ITranslate<string>;
	product_ids: number[];
	products: TProduct[];
};

export type TRequiredProductsGroupForm = {
	name: string;
	products: number[];
};
