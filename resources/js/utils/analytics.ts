import { hasWindow } from '@/utils/hasWindow';

/**
 * Push a GTM dataLayer event. Safe under SSR (no-op without window).
 */
export const pushDataLayer = (payload: Record<string, unknown>) => {
	if (!hasWindow()) return;
	window.dataLayer = window.dataLayer || [];
	window.dataLayer.push(payload);
};

/**
 * Lead form (consultation / callback) accepted by the server.
 * Call only from Inertia `onSuccess` — fires after 2xx + redirect to Thank You page,
 * never on validation errors (422) or throttling (429).
 */
export const trackLeadFormSubmitted = () => {
	pushDataLayer({ event: 'lead_form_submitted' });
};

/**
 * Checkout order accepted by the server (order stored, redirect to Thank You page).
 * Call only from Inertia `onSuccess` of the checkout form.
 */
export const trackCheckoutOrderSubmitted = () => {
	pushDataLayer({ event: 'checkout_order_submitted' });
};
