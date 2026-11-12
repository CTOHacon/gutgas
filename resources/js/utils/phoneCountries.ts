export type TPhoneCountry = {
	code: string;
	flag: string;
	dial: string;
	/** National number layout, "#" = digit. Its digit count is the maximum length. */
	mask: string;
	/** Minimum national digits for the number to look complete. */
	min: number;
	/** National trunk prefix typed by locals ("0" in 067..., "8" in KZ); dropped from input. */
	trunk?: string;
};

export const PHONE_COUNTRIES: TPhoneCountry[] = [
	{ code: 'UA', flag: '🇺🇦', dial: '380', mask: '## ### ## ##', min: 9, trunk: '0' },
	{ code: 'GE', flag: '🇬🇪', dial: '995', mask: '### ## ## ##', min: 9, trunk: '0' },
	{ code: 'MD', flag: '🇲🇩', dial: '373', mask: '## ### ###', min: 8, trunk: '0' },
	{ code: 'KZ', flag: '🇰🇿', dial: '7', mask: '### ### ## ##', min: 10, trunk: '8' },
	{ code: 'US', flag: '🇺🇸', dial: '1', mask: '### ### ####', min: 10 },
	{ code: 'GB', flag: '🇬🇧', dial: '44', mask: '#### ######', min: 10, trunk: '0' },
	{ code: 'PL', flag: '🇵🇱', dial: '48', mask: '### ### ###', min: 9 },
	{ code: 'RO', flag: '🇷🇴', dial: '40', mask: '### ### ###', min: 9, trunk: '0' },
	{ code: 'DE', flag: '🇩🇪', dial: '49', mask: '#### ########', min: 10, trunk: '0' },
	{ code: 'CZ', flag: '🇨🇿', dial: '420', mask: '### ### ###', min: 9 },
	{ code: 'SK', flag: '🇸🇰', dial: '421', mask: '### ### ###', min: 9, trunk: '0' },
	{ code: 'HU', flag: '🇭🇺', dial: '36', mask: '## ### ####', min: 8 },
	{ code: 'LT', flag: '🇱🇹', dial: '370', mask: '### #####', min: 8 },
	{ code: 'LV', flag: '🇱🇻', dial: '371', mask: '## ### ###', min: 8 },
	{ code: 'EE', flag: '🇪🇪', dial: '372', mask: '#### ####', min: 7 },
	{ code: 'AZ', flag: '🇦🇿', dial: '994', mask: '## ### ## ##', min: 9, trunk: '0' },
	{ code: 'AM', flag: '🇦🇲', dial: '374', mask: '## ### ###', min: 8, trunk: '0' },
	{ code: 'TR', flag: '🇹🇷', dial: '90', mask: '### ### ## ##', min: 10, trunk: '0' },
	{ code: 'IL', flag: '🇮🇱', dial: '972', mask: '## ### ####', min: 9, trunk: '0' },
	{ code: 'IT', flag: '🇮🇹', dial: '39', mask: '### ### ####', min: 9 },
	{ code: 'ES', flag: '🇪🇸', dial: '34', mask: '### ### ###', min: 9 },
	{ code: 'FR', flag: '🇫🇷', dial: '33', mask: '# ## ## ## ##', min: 9, trunk: '0' },
	{ code: 'NL', flag: '🇳🇱', dial: '31', mask: '# ########', min: 9, trunk: '0' },
	{ code: 'AT', flag: '🇦🇹', dial: '43', mask: '### ########', min: 10, trunk: '0' }
];

/** Site locale → default country: ua/ru are the Ukrainian site, others follow their language. */
const LOCALE_COUNTRY: Record<string, string> = {
	ua: 'UA',
	ru: 'UA',
	ka: 'GE',
	ro: 'MD',
	kk: 'KZ',
	en: 'US'
};

export const findPhoneCountry = (code: string): TPhoneCountry =>
	PHONE_COUNTRIES.find(country => country.code === code) ??
	PHONE_COUNTRIES[0];

export const defaultPhoneCountry = (locale: string): TPhoneCountry =>
	findPhoneCountry(LOCALE_COUNTRY[locale] ?? 'UA');

export const maxDigits = (country: TPhoneCountry): number =>
	country.mask.replace(/[^#]/g, '').length;

/** Lays digits over the mask, stopping at the last typed digit. */
export const applyPhoneMask = (digits: string, mask: string): string => {
	let result = '';
	let index = 0;
	for (const char of mask) {
		if (index >= digits.length) break;
		if (char === '#') result += digits[index++];
		else result += char;
	}
	return result;
};

/**
 * Finds the country whose dial code prefixes an international number
 * (longest code wins, so "+380" beats "+38x" lookalikes).
 */
export const matchDialCode = (
	digits: string
): TPhoneCountry | undefined =>
	[...PHONE_COUNTRIES]
		.sort((a, b) => b.dial.length - a.dial.length)
		.find(country => digits.startsWith(country.dial));

/** Splits a stored "+380 67 123 45 67" back into country + national digits. */
export const parsePhone = (
	value: string,
	fallback: TPhoneCountry
): { country: TPhoneCountry; digits: string } => {
	const digits = value.replace(/\D/g, '');
	if (value.trim().startsWith('+')) {
		// keep the current country when it matches (US and KZ/RU share codes)
		const country = digits.startsWith(fallback.dial)
			? fallback
			: matchDialCode(digits);
		if (country)
			return { country, digits: digits.slice(country.dial.length) };
	}
	return { country: fallback, digits };
};
