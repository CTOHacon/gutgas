import { IUser } from './IUser';

export interface IAuth {
	user: IUser | undefined;
	permissions: string[];
}
