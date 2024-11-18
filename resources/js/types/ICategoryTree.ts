import { ICategory } from './ICategory';

export interface ICategoryTree extends ICategory {
	depth: number;
	path: string;
	products_count?: number;
	descedant_products_count?: number;
	children: ICategoryTree[];
}
