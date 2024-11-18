import { usePage } from '@inertiajs/vue3';

export const useTranslations = (
	extraSources: Record<string, string>[] | null = null
) => {
	let {
		props: { translations, locale }
	} = usePage();

	if (extraSources) {
		translations = {
			...translations,
			...Object.assign({}, ...extraSources)
		};
	}

	const __ = (key: string, replace: any = {}) => {
		var translation = translations[key] ? translations[key] : key;
		Object.keys(replace).forEach(function (key) {
			translation = translation.replace(':' + key, replace[key]);
		});
		return translation;
	};

	const _t = (value: any, strict: boolean = false) => {
		if (!value) return '';
		if (value[locale]) {
			return value[locale];
		}
		if (!strict) return value[Object.keys(value)[0]] || '';
		return '';
	};

	return {
		__,
		_t
	};
};
