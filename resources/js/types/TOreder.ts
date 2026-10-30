import ICartProduct from './ICartProduct';

export type TShippingMethod =
	| 'np_department'
	| 'np_postal_machine'
	| 'np_courier'
	| 'other';

export type TOrder = {
	id: number;
	client_name: string;
	client_surname: string | null;
	client_phone: string;
	client_email: string;
	client_message: string;
	// Server-side snapshot of the products at the time of the order
	cart_content: ICartProduct[];
	shipping_message: string | null;
	shipping_method: TShippingMethod | null;
	shipping_city: string | null;
	shipping_city_ref: string | null;
	shipping_area: string | null;
	shipping_warehouse: string | null;
	shipping_warehouse_ref: string | null;
	shipping_address: string | null;
	locale: string | null;
	crm_order_id: number | null;
	crm_sent_at: string | null;
	crm_error: string | null;
	status_name: string;
	status_color: string;
	compleated: 1 | 0;
	created_at: string;
	updated_at: string;
};

// Public checkout form: the cart carries only ids and quantities
export type TOrderForm = {
	client_name: string;
	client_surname: string;
	client_phone: string;
	client_email: string;
	client_message: string;
	locale: string;
	cart_content: { id: number; quantity: number }[];
	shipping_method: TShippingMethod;
	shipping_city: string;
	shipping_city_ref: string;
	shipping_area: string;
	shipping_warehouse: string;
	shipping_warehouse_ref: string;
	shipping_address: string;
	shipping_message: string;
};

// Admin edit form
export type TOrderAdminForm = {
	client_name: string;
	client_surname: string | null;
	client_phone: string;
	client_email: string;
	client_message: string;
	shipping_message: string | null;
	status_name: string;
	status_color: string;
	compleated: boolean;
};
