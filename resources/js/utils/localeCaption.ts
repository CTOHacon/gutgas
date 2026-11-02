export default function localeCaption(key: string) {
	switch (key) {
		case 'en':
			return 'Eng';
		case 'ru':
			return 'Рус';
		case 'ua':
			return 'Укр';
		case 'ka':
			return 'ქარ';
		case 'ro':
			return 'Rom';
		case 'kk':
			return 'Қаз';
		default:
			return key;
	}
}
