import { TProduct } from '@/types/TProduct';

export default interface ICartProduct extends TProduct {
	quantity: number;
}
