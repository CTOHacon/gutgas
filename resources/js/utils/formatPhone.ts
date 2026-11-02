import { formatUaPhone } from './formatUaPhone';

/** Locales that keep the Ukrainian "+38 (###) ###-##-##" mask. Keep in sync with config('app.ua_phone_locales'). */
const UA_PHONE_LOCALES = ['ua', 'ru'];

export const usesUaPhoneMask = (locale: string): boolean => UA_PHONE_LOCALES.includes(locale);

/**
 * Light normalizer for international numbers: keeps digits, spaces, dashes,
 * parentheses and a single leading "+".
 */
export const formatIntlPhone = (value: string): string => {
	const cleaned = value.replace(/[^\d+\s\-()]/g, '');
	const plus = cleaned.trimStart().startsWith('+') ? '+' : '';
	return (plus + cleaned.replace(/\+/g, '')).slice(0, 25);
};

export const formatPhone = (value: string, locale: string): string =>
	usesUaPhoneMask(locale) ? formatUaPhone(value) : formatIntlPhone(value);

/** Mirrors the server E.164 rule for international locales. */
export const isIntlPhoneValid = (value: string): boolean =>
	/^\+[1-9]\d{7,14}$/.test(value.replace(/[\s\-().]/g, ''));
