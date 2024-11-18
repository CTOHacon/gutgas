import { TProduct } from '@/types/TProduct';
import { axios } from './axios';
import ICartProduct from '@/types/ICartProduct';

// getRequiredProducts
export const geRequiredProducts = async (
	products: ICartProduct[] | TProduct[]
) => {
	const response = await axios.get<{ requiredProducts: TProduct[] }>(
		route('ajax.getRequiredProducts'),
		{
			params: {
				products: products.map(product => product.id)
			}
		}
	);

	return response.data.requiredProducts;
};
