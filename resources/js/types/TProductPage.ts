import { IMediaFile } from './IMediaFile';
import { IMetaField } from './IMetaField';
import { ITranslate } from './ITranslate';
import { TAdvantageCardProps } from './TAdvantageCardProps';
import { TFaqItemProps } from './TFaqItemProps';
import { TSeoEntity } from './TSeoEntity';

export type TProductPage = {
	id: number;
	product_id: number;
	title: ITranslate<string>;
	description: ITranslate<string>;
	media_gallery: IMediaFile[];
	characteristic_icons: IMediaFile[];
	documentations: IMediaFile[];
	meta: [IMetaField<TFaqItemProps>, IMetaField<TAdvantageCardProps>];
	seoEntity: TSeoEntity;
};

export type TProductPageForm = {
	title: string;
	description: string;
	media_gallery: IMediaFile[];
	characteristic_icons: IMediaFile[];
	documentations: IMediaFile[];
	faqItems: TFaqItemProps[];
	advantageItems: TAdvantageCardProps[];
};
