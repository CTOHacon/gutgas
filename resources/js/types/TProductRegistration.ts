export type TProductRegistrationForm = {
	first_name: string;
	last_name: string;
	phone: string;
	email: string;
	city: string;
	address: string;
	model: string;
	serial_number: string;
	purchase_date: string;
	price: string;
	purchase_place: string;
	subscription_consent: boolean;
	locale: string;
	website: string;
};

export type TProductRegistration = {
	id: number;
	first_name: string;
	last_name: string;
	phone: string;
	email: string;
	city: string;
	address: string | null;
	model: string | null;
	serial_number: string;
	purchase_date: string;
	price: number;
	purchase_place: string;
	subscription_consent: boolean;
	locale: string | null;
	ip: string | null;
	confirmed_at: string | null;
	created_at: string;
	updated_at: string;
};
