import { IRole } from './IRole';

export type IUser = {
	id: number;
	name: string;
	username: string;
	email: string;
	email_verified_at: string;
	created_at: string;
	updated_at: string;

	permissions: string[];
	roles: IRole[];
};
