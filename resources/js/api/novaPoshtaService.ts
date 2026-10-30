import { axios } from './axios';
import {
	TNovaPoshtaSettlement,
	TNovaPoshtaWarehouse,
	TNovaPoshtaWarehouseType
} from '@/types/TNovaPoshta';

export const searchNovaPoshtaSettlements = async (query: string) => {
	const response = await axios.get<{ options: TNovaPoshtaSettlement[] }>(
		route('novaPoshta.settlements'),
		{ params: { q: query } }
	);

	return response.data.options;
};

export const getNovaPoshtaWarehouses = async (
	cityRef: string,
	type: TNovaPoshtaWarehouseType,
	query: string = ''
) => {
	const response = await axios.get<{ options: TNovaPoshtaWarehouse[] }>(
		route('novaPoshta.warehouses'),
		{ params: { cityRef, type, q: query } }
	);

	return response.data.options;
};
