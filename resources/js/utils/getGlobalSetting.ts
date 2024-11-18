import { usePage } from '@inertiajs/vue3';
import { isJsonString } from './isJsonString';

export const getGlobalSetting = <ExpectedValueType = string>(
	key: string
): ExpectedValueType | undefined => {
	const { locale } = usePage().props;
	const { globalSettings } = usePage().props;

	if (!globalSettings) return undefined;
	let globalSetting = globalSettings.find(
		globalSetting => globalSetting.key === `${key}_${locale}`
	);
	if (!globalSetting) {
		globalSetting = globalSettings.find(
			globalSetting => globalSetting.key === key
		);
	}
	if (!globalSetting) return undefined;

	const value = globalSetting.value as ExpectedValueType;

	if (typeof value === 'string' && isJsonString(value)) {
		return JSON.parse(value);
	}
	return value;
};
