export type TNovaPoshtaSettlement = {
	value: string;
	label: string;
	cityRef: string;
	area: string;
};

export type TNovaPoshtaWarehouse = {
	value: string;
	label: string;
	ref: string;
};

export type TNovaPoshtaWarehouseType = 'department' | 'postal_machine';
