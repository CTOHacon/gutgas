export type TProductsCatalogFilterForm = {
	selectedProductFilterValues: number[];
	productsGroup: number | null;
	priceRange: {
		from: number;
		to: number;
	};
};
