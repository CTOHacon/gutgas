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
 * Event contract shared with burnhard (bh-frontend/utils/analytics.ts) —
 * keep names and params identical so both GTM containers use the same
 * triggers/variables.
 */
export type LeadFormName = 'contact_us_form' | 'contact_us_small_form' | 'phone_contact_form';

/**
 * Lead form (consultation / callback) accepted by the server.
 * Call only from Inertia `onSuccess` — fires after 2xx + redirect to Thank You page,
 * never on validation errors (422) or throttling (429).
 */
export const trackLeadFormSubmitted = (formName: LeadFormName) => {
	pushDataLayer({ event: 'lead_form_submitted', form_name: formName });
};

/**
 * Checkout order accepted by the server (order stored, redirect to Thank You page).
 * Call only from Inertia `onSuccess` of the checkout form, BEFORE resetCart()
 * so `value` is the real order total.
 */
export const trackCheckoutOrderSubmitted = (order: { orderId?: number | string; value: number }) => {
	pushDataLayer({
		event: 'checkout_order_submitted',
		order_id: order.orderId != null ? String(order.orderId) : undefined,
		value: Math.round(order.value),
		currency: 'UAH'
	});
};
