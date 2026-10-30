/**
 * Formats user input into the "+38 (###) ###-##-##" mask used across the site.
 */
export const formatUaPhone = (value: string): string => {
	let digits = value.replace(/\D/g, '');
	if (digits.startsWith('38')) digits = digits.slice(2);
	digits = digits.slice(0, 10);
	if (!digits.length) return '';

	let result = '+38 (' + digits.slice(0, 3);
	if (digits.length >= 3) result += ')';
	if (digits.length > 3) result += ' ' + digits.slice(3, 6);
	if (digits.length > 6) result += '-' + digits.slice(6, 8);
	if (digits.length > 8) result += '-' + digits.slice(8, 10);
	return result;
};
