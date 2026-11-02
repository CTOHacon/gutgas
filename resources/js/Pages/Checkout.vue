<template>
	<Head>
		<!-- noindex -->
		<title>{{ __('checkoutTitle') }}</title>
		<!-- /noindex -->
		<meta name="robots" content="noindex, follow" />
	</Head>
	<WebsitePage>
		<PageHeadSpacer class="mb-60" />
		<BaseBreadcrumbs class="mt-32 mb-40" />
		<div class="container">
			<h1 class="fs-h4 mb-60">{{ __('checkoutTitle') }}</h1>
			<form class="g-layout mb-116" @submit.prevent="">
				<div class="g-col-8 g-tablet-col-12">
					<!-- first form -->
					<div class="g-layout mb-48">
						<div class="g-col-6 g-large-mobile-col-12">
							<BaseFormInput
								:placeholder="__('formName')"
								class="mb-12"
								v-model="checkoutData.client_name"
								:error-message="checkoutData.errors.client_name"
							/>
							<BaseFormInput
								:placeholder="__('formSurname')"
								class="mb-12"
								v-model="checkoutData.client_surname"
								:error-message="checkoutData.errors.client_surname"
							/>
							<BaseFormInput
								:placeholder="phonePlaceholder"
								class="mb-12"
								:model-value="checkoutData.client_phone"
								@update:model-value="onPhoneInput"
								:error-message="checkoutData.errors.client_phone"
								type="tel"
							/>
							<BaseFormInput
								:placeholder="__('formEmail')"
								v-model="checkoutData.client_email"
								:error-message="checkoutData.errors.client_email"
								type="mail"
							/>
						</div>
						<div class="g-col-6 g-large-mobile-col-12">
							<BaseFormTextarea
								:placeholder="__('formMessage')"
								class="mb-8"
								v-model="checkoutData.client_message"
								:error-message="checkoutData.errors.client_message"
							/>
						</div>
					</div>
					<!-- end first form -->

					<h2
						class="fs-large mb-32"
						v-if="requiredProducts.length > 0"
					>
						{{ __('requiredComponents') }}
					</h2>
					<ProductsSlider
						v-if="requiredProducts.length > 0"
						:products="requiredProducts"
						class="mb-48"
					/>
					<h2 class="fs-large mb-8">
						{{ __('shippingAndPayment') }}
					</h2>
					<p class="mb-32 color-danger">
						{{ __('shippingDescription') }}
					</p>
					<BaseTabs
						v-model="checkoutData.shipping_method"
						:controls-bordered="true"
						class="mb-32"
					>
						<BaseTab
							:label="__('shippingNpDepartment')"
							name="np_department"
							:icon="novaPoshtaTabIcon"
						/>
						<BaseTab
							:label="__('shippingNpPostalMachine')"
							name="np_postal_machine"
							:icon="novaPoshtaTabIcon"
						/>
						<BaseTab
							:label="__('shippingNpCourier')"
							name="np_courier"
							:icon="novaPoshtaTabIcon"
						/>
						<BaseTab :label="__('shippingOther')" name="other" />
					</BaseTabs>

					<div
						class="g-layout shipping-fields-layout"
						v-if="isNovaPoshta"
					>
						<div class="g-col-6 g-large-mobile-col-12">
							<BaseFormAutocomplete
								v-model="cityQuery"
								:placeholder="__('shippingCity')"
								:options="cityOptions"
								:loading="isCitiesLoading"
								:loading-label="__('shippingSearching')"
								:empty-label="
									cityQuery.length >= 2
										? __('shippingNothingFound')
										: undefined
								"
								:is-valid="!!checkoutData.shipping_city_ref"
								:error-message="
									checkoutData.errors.shipping_city ||
									checkoutData.errors.shipping_city_ref
								"
								@update:model-value="onCityInput"
								@select="onCitySelect"
							/>
						</div>
						<div
							class="g-col-6 g-large-mobile-col-12"
							v-if="hasWarehouse"
						>
							<BaseFormAutocomplete
								v-model="warehouseQuery"
								:placeholder="
									checkoutData.shipping_method ===
									'np_postal_machine'
										? __('shippingPostalMachine')
										: __('shippingDepartment')
								"
								:options="warehouseOptions"
								:loading="isWarehousesLoading"
								:loading-label="__('shippingSearching')"
								:empty-label="__('shippingNothingFound')"
								:disabled="!checkoutData.shipping_city_ref"
								:is-valid="!!checkoutData.shipping_warehouse_ref"
								:error-message="
									checkoutData.errors.shipping_warehouse ||
									checkoutData.errors.shipping_warehouse_ref
								"
								@update:model-value="onWarehouseInput"
								@focus="loadWarehouses"
								@select="onWarehouseSelect"
							/>
						</div>
						<div class="g-col-6 g-large-mobile-col-12" v-else>
							<BaseFormInput
								:placeholder="__('shippingAddress')"
								v-model="checkoutData.shipping_address"
								:error-message="checkoutData.errors.shipping_address"
							/>
						</div>
					</div>
					<BaseFormInput
						v-else
						:placeholder="__('shippingExample')"
						class="mb-8"
						v-model="checkoutData.shipping_message"
						:error-message="checkoutData.errors.shipping_message"
					/>
				</div>
				<div class="g-col-4 g-tablet-col-12">
					<div
						class="checkout-sidebar pv-24 ph-20"
						:style="{
							top: headerHeight + 8 + 'px'
						}"
					>
						<SimpleCartProductsList class="products-list" />
						<div class="totals g-layout-6 gap-1 mt-32 mb-32 pv-24">
							<p class="g-col-3 fs-medium fw-600">
								{{ __('totalPrice') }}
							</p>
							<strong
								class="g-col-3 fs-medium fw-600 color-secondary text-right"
							>
								{{ cartTotal }} {{ __('uah') }}
							</strong>
							<p class="g-col-3">
								{{ __('shipping') }}
							</p>
							<strong class="g-col-3 color-secondary text-right">
								{{ __('shippingByCarrierRates') }}
							</strong>
						</div>
						<!-- Every server error, so a rejected order is never silent
						     (cart items, expired page, fields hidden by the method) -->
						<ul class="color-danger mb-12" v-if="errorMessages.length">
							<li v-for="message in errorMessages" :key="message">
								{{ message }}
							</li>
						</ul>
						<BaseButton
							type="submit"
							:variation="'default'"
							class="p-16 fs-mediaum"
							@click="submitCheckout"
							:disabled="!canSubmit"
						>
							{{ __('formSend') }}
						</BaseButton>
					</div>
				</div>
			</form>
		</div>
	</WebsitePage>
</template>

<script setup lang="ts">
import BaseFormAutocomplete, {
	TAutocompleteOption
} from '@/Components/BaseFormAutocomplete.vue';
import BaseFormInput from '@/Components/BaseFormInput.vue';
import BaseFormTextarea from '@/Components/BaseFormTextarea.vue';
import PageHeadSpacer from '@/Components/PageHeadSpacer.vue';
import ProductsSlider from '@/Components/ProductsSlider.vue';
import SimpleCartProductsList from '@/Components/SimpleCartProductsList.vue';
import WebsitePage from '@/Layouts/WebsitePage.vue';
import useCart from '@/composables/cart';
import useHeaderElement from '@/composables/headerElement';
import BaseButton from '@/Components/BaseButton.vue';
import BaseTab from '@/Components/BaseTab.vue';
import novaPoshtaTabIcon from '@/assets/nova-poshta-tab-icon.svg';
import BaseTabs from '@/Components/BaseTabs.vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import debounce from 'lodash.debounce';
import { geRequiredProducts } from '@/api/requiredProductsService';
import { computed, ref } from 'vue';
import { onMounted } from 'vue';
import { TProduct } from '@/types/TProduct';
import { watch } from 'vue';
import ICartProduct from '@/types/ICartProduct';
import { TOrderForm } from '@/types/TOreder';
import { formatPhone, usesUaPhoneMask } from '@/utils/formatPhone';
import {
	TNovaPoshtaSettlement,
	TNovaPoshtaWarehouse
} from '@/types/TNovaPoshta';
import {
	getNovaPoshtaWarehouses,
	searchNovaPoshtaSettlements
} from '@/api/novaPoshtaService';
import BaseBreadcrumbs from '@/Components/BaseBreadcrumbs.vue';

const props = defineProps<{
	checkoutTranslations: Record<string, string>;
}>();

const { __ } = useTranslations([props.checkoutTranslations]);

const { cartTotal, cartProducts, resetCart } = useCart();

const toOrderItems = () =>
	cartProducts.value.map(({ id, quantity }) => ({ id, quantity }));

// Only product ids and quantities are sent: prices and names are rebuilt on the server
const checkoutData = useForm<TOrderForm>({
	client_name: '',
	client_surname: '',
	client_phone: '',
	client_email: '',
	client_message: '',
	locale: usePage().props.locale as string,
	cart_content: toOrderItems(),
	shipping_method: 'np_department',
	shipping_city: '',
	shipping_city_ref: '',
	shipping_area: '',
	shipping_warehouse: '',
	shipping_warehouse_ref: '',
	shipping_address: '',
	shipping_message: ''
});
watch(cartProducts, () => {
	checkoutData.cart_content = toOrderItems();
});

// Includes keys outside the form type: page_expired (419 handler) and
// cart_content.N.* from the validator
const errorMessages = computed(() => [
	...new Set(
		Object.values(
			checkoutData.errors as Record<string, string | undefined>
		).filter((message): message is string => !!message)
	)
]);

// A field's server error goes away as soon as the customer edits it
watch(
	() => checkoutData.data(),
	(current, previous) => {
		(Object.keys(current) as (keyof TOrderForm)[])
			.filter(key => current[key] !== previous[key])
			.forEach(key => checkoutData.clearErrors(key));
	}
);

const isNovaPoshta = computed(() => checkoutData.shipping_method !== 'other');
const hasWarehouse = computed(
	() =>
		checkoutData.shipping_method === 'np_department' ||
		checkoutData.shipping_method === 'np_postal_machine'
);

// Nova Poshta autocompletes
const cityQuery = ref('');
const cities = ref<TNovaPoshtaSettlement[]>([]);
const isCitiesLoading = ref(false);
const cityOptions = computed<TAutocompleteOption[]>(() =>
	cities.value.map(city => ({ key: city.cityRef, label: city.label }))
);

const warehouseQuery = ref('');
const warehouses = ref<TNovaPoshtaWarehouse[]>([]);
const isWarehousesLoading = ref(false);
const warehouseOptions = computed<TAutocompleteOption[]>(() =>
	warehouses.value.map(warehouse => ({
		key: warehouse.ref,
		label: warehouse.label
	}))
);

const resetWarehouse = () => {
	checkoutData.shipping_warehouse = '';
	checkoutData.shipping_warehouse_ref = '';
	warehouseQuery.value = '';
	warehouses.value = [];
};

const fetchCities = debounce(async (query: string) => {
	try {
		cities.value = await searchNovaPoshtaSettlements(query);
	} catch (error) {
		cities.value = [];
	} finally {
		isCitiesLoading.value = false;
	}
}, 300);

const onCityInput = (query: string) => {
	// typing invalidates the previously selected city and its warehouse
	checkoutData.shipping_city = '';
	checkoutData.shipping_city_ref = '';
	checkoutData.shipping_area = '';
	resetWarehouse();

	if (query.trim().length < 2) {
		fetchCities.cancel();
		cities.value = [];
		isCitiesLoading.value = false;
		return;
	}
	isCitiesLoading.value = true;
	fetchCities(query.trim());
};

const onCitySelect = (option: TAutocompleteOption) => {
	const city = cities.value.find(city => city.cityRef === option.key);
	if (!city) return;

	cityQuery.value = city.label;
	checkoutData.shipping_city = city.value;
	checkoutData.shipping_city_ref = city.cityRef;
	checkoutData.shipping_area = city.area;
	resetWarehouse();
};

const loadWarehouses = async () => {
	if (!checkoutData.shipping_city_ref || !hasWarehouse.value) return;

	const requestedCityRef = checkoutData.shipping_city_ref;
	isWarehousesLoading.value = true;
	try {
		const options = await getNovaPoshtaWarehouses(
			requestedCityRef,
			checkoutData.shipping_method === 'np_postal_machine'
				? 'postal_machine'
				: 'department',
			warehouseQuery.value.trim()
		);
		// ignore stale answers if the city changed meanwhile
		if (requestedCityRef === checkoutData.shipping_city_ref)
			warehouses.value = options;
	} catch (error) {
		warehouses.value = [];
	} finally {
		isWarehousesLoading.value = false;
	}
};
const debouncedLoadWarehouses = debounce(loadWarehouses, 300);

const onWarehouseInput = () => {
	checkoutData.shipping_warehouse = '';
	checkoutData.shipping_warehouse_ref = '';
	debouncedLoadWarehouses();
};

const onWarehouseSelect = (option: TAutocompleteOption) => {
	const warehouse = warehouses.value.find(
		warehouse => warehouse.ref === option.key
	);
	if (!warehouse) return;

	warehouseQuery.value = warehouse.label;
	checkoutData.shipping_warehouse = warehouse.value;
	checkoutData.shipping_warehouse_ref = warehouse.ref;
};

// department list differs from postomat list
watch(
	() => checkoutData.shipping_method,
	() => {
		resetWarehouse();
	}
);

const phonePlaceholder = computed(() =>
	usesUaPhoneMask(checkoutData.locale) ? __('formPhone') : __('phonePlaceholderIntl')
);

const onPhoneInput = (value?: string) => {
	checkoutData.client_phone = formatPhone(value ?? '', checkoutData.locale);
};

const canSubmit = computed(() => {
	const baseFilled =
		!!checkoutData.client_name &&
		!!checkoutData.client_surname &&
		!!checkoutData.client_phone &&
		!!checkoutData.client_email &&
		cartProducts.value.length > 0;

	switch (checkoutData.shipping_method) {
		case 'np_department':
		case 'np_postal_machine':
			return (
				baseFilled &&
				!!checkoutData.shipping_city_ref &&
				!!checkoutData.shipping_warehouse_ref
			);
		case 'np_courier':
			return (
				baseFilled &&
				!!checkoutData.shipping_city_ref &&
				!!checkoutData.shipping_address
			);
		default:
			return baseFilled && !!checkoutData.shipping_message;
	}
});

const { headerHeight } = useHeaderElement();

const requiredProducts = ref<TProduct[]>([]);

const submitCheckout = () => {
	checkoutData.post(route('order.store'), {
		preserveScroll: true,
		onSuccess: () => {
			checkoutData.reset();
			cityQuery.value = '';
			resetWarehouse();
			resetCart();
		}
	});
};

onMounted(async () => {
	const responce = await geRequiredProducts(cartProducts.value);
	requiredProducts.value = responce;
});
watch(cartProducts, async () => {
	const responce = await geRequiredProducts(cartProducts.value);
	requiredProducts.value = responce;
});
</script>

<style scoped lang="scss">
.shipping-fields-layout {
	column-gap: 1.5rem;
}

.checkout-sidebar {
	position: sticky;
	top: 100px;
	height: 100%;
	max-height: calc(100vh - 160px);
	border-radius: 8px;
	background: rgba(21, 21, 21, 0.5);
	display: flex;
	flex-direction: column;
}
.products-list {
	flex: 1;
}

.totals {
	border-top: 1px dashed rgba($color: #838d97, $alpha: 0.5);
	border-bottom: 1px dashed rgba($color: #838d97, $alpha: 0.5);
}
</style>
