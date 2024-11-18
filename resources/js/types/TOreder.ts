import ICartProduct from './ICartProduct';

export type TOrder = {
	id: number;
	client_name: string;
	client_phone: string;
	client_email: string;
	client_message: string;
	cart_content: ICartProduct[];
	shipping_message: string;
	status_name: string;
	status_color: string;
	compleated: 1 | 0;
	created_at: string;
	updated_at: string;
};

export type TOrderForm = {
	client_name: string;
	client_phone: string;
	client_email: string;
	client_message: string;
	cart_content: ICartProduct[];
	shipping_message: string;
	status_name: string;
	status_color: string;
	compleated: boolean;
};
