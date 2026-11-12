import { computed, onMounted, ref, watch } from 'vue';
import {
	TPhoneCountry,
	applyPhoneMask,
	defaultPhoneCountry,
	matchDialCode,
	maxDigits,
	parsePhone
} from '@/utils/phoneCountries';

/**
 * State of a phone field with a country picker. The model value is
 * "+<dial> <national>", e.g. "+380 67 123 45 67" ('' while no digits typed).
 */
export default function usePhoneNumber(options: {
	modelValue: () => string;
	locale: () => string;
	emitValue: (value: string) => void;
	emitCountry?: (code: string) => void;
}) {
	const initial = parsePhone(
		options.modelValue() ?? '',
		defaultPhoneCountry(options.locale())
	);
	const country = ref<TPhoneCountry>(initial.country);
	const digits = ref(initial.digits);

	const national = computed(() =>
		applyPhoneMask(digits.value, country.value.mask)
	);
	const isComplete = computed(
		() => digits.value.length >= country.value.min
	);
	/** Mask with zeros, e.g. "00 000 00 00", for placeholders. */
	const example = computed(() => country.value.mask.replace(/#/g, '0'));

	const composed = () =>
		digits.value ? `+${country.value.dial} ${national.value}` : '';

	// external changes (form reset, prefill)
	watch(options.modelValue, value => {
		if (value === composed()) return;
		const parsed = parsePhone(value ?? '', country.value);
		country.value = parsed.country;
		digits.value = parsed.digits.slice(0, maxDigits(parsed.country));
	});

	if (options.emitCountry) {
		watch(country, value => options.emitCountry!(value.code));
		onMounted(() => options.emitCountry!(country.value.code));
	}

	const normalize = (raw: string) => {
		let next = raw.replace(/\D/g, '');

		// pasted "+48 ..." switches the country
		if (raw.trim().startsWith('+')) {
			const match = next.startsWith(country.value.dial)
				? country.value
				: matchDialCode(next);
			if (match) {
				country.value = match;
				next = next.slice(match.dial.length);
			}
		} else if (
			next.startsWith(country.value.dial) &&
			next.length > maxDigits(country.value)
		) {
			// "380671234567" typed without the plus
			next = next.slice(country.value.dial.length);
		}

		const trunk = country.value.trunk;
		if (trunk && next.startsWith(trunk)) next = next.slice(trunk.length);

		return next.slice(0, maxDigits(country.value));
	};

	/** Input handler: normalizes, re-renders the formatted value, emits. */
	const onInput = (event: Event) => {
		const target = event.target as HTMLInputElement;
		digits.value = normalize(target.value);
		// keep the DOM in sync when the formatted value is unchanged (e.g. a letter typed)
		target.value = national.value;
		options.emitValue(composed());
	};

	const setCountry = (next: TPhoneCountry) => {
		country.value = next;
		digits.value = digits.value.slice(0, maxDigits(next));
		options.emitValue(composed());
	};

	return { country, digits, national, isComplete, example, onInput, setCountry };
}
