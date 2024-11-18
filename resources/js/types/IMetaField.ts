export interface IMetaField<Value> {
	created_at: string;
	id: number;
	key: string;
	owner_id: number;
	owner_type: string;
	status: boolean;
	type:
		| 'string'
		| 'integer'
		| 'null'
		| 'collection'
		| 'json'
		| 'array'
		| 'boolean';
	updated_at: string;
	value: Value;
}
