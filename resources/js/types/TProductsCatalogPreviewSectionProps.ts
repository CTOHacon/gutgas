import { TProduct } from './TProduct';

export type TProductsCatalogPreviewSectionStoreData = {
	title: string;
	image: string;
	text: string;
	featuredProduct: number | null;
	products: number[];
};

export type TProductsCatalogPreviewSectionProps = {
	title: string;
	image: string;
	text: string;
	featuredProduct?: TProduct;
	products: TProduct[];
};

export type TCatalogPreview = {};
