import { IPaginated } from './IPaginated';
import { TAttributeGroup } from './TAttributeGroup';
import { TProduct } from './TProduct';
import { TProductFilter } from './TProductFilter';
import { TProductsGroup } from './TProductsGroup';

export type TproductsCatalogData = {
	priceMin: number;
	priceMax: number;
	priceRange: {
		from: number;
		to: number;
	};

	productFilters: TProductFilter[];
	selectedProductFilterValues: number[];

	products: IPaginated<TProduct>;
	productsGroups: TProductsGroup[];
	productsGroup: number;
	totalProductsCount: number;
};
