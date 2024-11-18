import { IMetaField } from '@/types/IMetaField';
import { usePage } from '@inertiajs/vue3';

export type MetaValue<Value> = IMetaField<Value> | null;

export const getMeta = <ExpectedValueType>(
	metaValues: IMetaField<any>[],
	key: string,
	strict = false
): ExpectedValueType | undefined => {
	const { locale } = usePage().props;

	if (!metaValues) return undefined;

	let metaField = metaValues.find(meta => meta.key === `${key}_${locale}`);
	if (!strict && !metaField)
		metaField = metaValues.find(
			meta => meta.key.startsWith(`${key}_`) && meta.value !== null
		);

	if (!metaField) {
		metaField = metaValues.find(meta => meta.key === key);
	}
	if (!metaField) return undefined;

	const value = metaField.value as ExpectedValueType;

	// if value is json, we need to parse it
	if (metaField.type === 'json' || metaField.type === 'collection') {
		return JSON.parse(value as string) || undefined;
	}

	return value;
};
