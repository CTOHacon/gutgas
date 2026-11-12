import { getGlobalSetting } from '@/utils/getGlobalSetting';

/** "0 (800) 35-35-05" -> "+380800353505" (E.164 for the Ukrainian number). */
const toE164 = (phone: string): string | undefined => {
	const digits = phone.replace(/\D/g, '');
	if (!digits) return undefined;
	if (phone.trim().startsWith('+')) return `+${digits}`;
	if (digits.startsWith('380')) return `+${digits}`;
	if (digits.startsWith('0')) return `+38${digits}`;
	return `+${digits}`;
};

export const organizationId = (baseUrl: string) => `${baseUrl}/#organization`;

export const buildOrganization = (baseUrl: string) => {
	const phone = toE164(getGlobalSetting('phone') ?? '');
	const email = getGlobalSetting('email') || undefined;
	const sameAs = ['facebook', 'instagram', 'telegram']
		.map(key => getGlobalSetting(key))
		.filter((url): url is string => !!url && /^https?:\/\//.test(url));

	return {
		'@context': 'https://schema.org',
		'@type': 'Organization',
		'@id': organizationId(baseUrl),
		name: 'GUTGAS',
		url: baseUrl,
		logo: `${baseUrl}/apple-touch-icon.png`,
		...(email && { email }),
		...(phone && {
			telephone: phone,
			contactPoint: {
				'@type': 'ContactPoint',
				telephone: phone,
				contactType: 'customer service',
				areaServed: 'UA',
				...(email && { email })
			}
		}),
		address: {
			'@type': 'PostalAddress',
			streetAddress: 'вул. Володимира Великого, 2',
			addressLocality: 'Львів',
			postalCode: '79004',
			addressCountry: 'UA'
		},
		...(sameAs.length && { sameAs })
	};
};

export const buildWebSite = (baseUrl: string, locale: string, inLanguage: string) => ({
	'@context': 'https://schema.org',
	'@type': 'WebSite',
	'@id': `${baseUrl}/#website`,
	name: 'GUTGAS',
	url: `${baseUrl}/${locale}`,
	inLanguage,
	publisher: { '@id': organizationId(baseUrl) }
});

export const toAbsoluteUrl = (baseUrl: string, url?: string | null) => {
	if (!url) return undefined;
	if (/^https?:\/\//.test(url)) return url;
	return `${baseUrl}/${url.replace(/^\/+/, '')}`;
};
