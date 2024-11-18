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
								:error-message="
									!checkoutData.client_name
										? checkoutData.errors.client_name
										: undefined
								"
							/>
							<BaseFormInput
								:placeholder="__('formPhone')"
								mask="+38 (###) ###-##-##"
								class="mb-12"
								v-model="checkoutData.client_phone"
								:error-message="
									!checkoutData.client_phone
										? checkoutData.errors.client_phone
										: undefined
								"
								type="tel"
							/>
							<BaseFormInput
								:placeholder="__('formEmail')"
								v-model="checkoutData.client_email"
								:error-message="
									!emailRegexp.test(checkoutData.client_email)
										? checkoutData.errors.client_email
										: undefined
								"
								type="mail"
							/>
						</div>
						<div class="g-col-6 g-large-mobile-col-12">
							<BaseFormTextarea
								:placeholder="__('formMessage')"
								class="mb-8"
								v-model="checkoutData.client_message"
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
					<BaseFormInput
						:placeholder="__('shippingExample')"
						class="mb-8"
						v-model="checkoutData.shipping_message"
						:error-message="
							!checkoutData.shipping_message
								? checkoutData.errors.shipping_message
								: undefined
						"
					/>
					<!-- <BaseTabs :controls-bordered="true">
						<BaseTab label="Самовивіз" name="self-pickup">
							<div class="g-layout shipping-fields-layout">
								<BaseFormSelectField class="g-col-6" />
							</div>
						</BaseTab>
						<BaseTab
							label="Нова Пошта"
							name="nova-poshta"
							icon="../assets/nova-poshta-tab-icon.svg"
						>
						</BaseTab>
						<BaseTab label="Інше" name="other"> </BaseTab>
					</BaseTabs> -->
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
								{{ __('free') }}
							</strong>
						</div>
						<BaseButton
							type="submit"
							:variation="'default'"
							class="p-16 fs-mediaum"
							@click="submitCheckout"
							:disabled="
								!checkoutData.client_name ||
								!checkoutData.client_phone ||
								!checkoutData.client_email ||
								!checkoutData.shipping_message
							"
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
import BaseFormInput from '@/Components/BaseFormInput.vue';
import BaseFormTextarea from '@/Components/BaseFormTextarea.vue';
import PageHeadSpacer from '@/Components/PageHeadSpacer.vue';
import ProductsSlider from '@/Components/ProductsSlider.vue';
import SimpleCartProductsList from '@/Components/SimpleCartProductsList.vue';
import WebsitePage from '@/Layouts/WebsitePage.vue';
import useCart from '@/composables/cart';
import useHeaderElement from '@/composables/headerElement';
import BaseButton from '@/Components/BaseButton.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { geRequiredProducts } from '@/api/requiredProductsService';
import { ref } from 'vue';
import { onMounted } from 'vue';
import { TProduct } from '@/types/TProduct';
import { watch } from 'vue';
import ICartProduct from '@/types/ICartProduct';
import { TOrderForm } from '@/types/TOreder';
import BaseBreadcrumbs from '@/Components/BaseBreadcrumbs.vue';

const props = defineProps<{
	checkoutTranslations: Record<string, string>;
}>();

const { __ } = useTranslations([props.checkoutTranslations]);

const { cartTotal, cartProducts, resetCart } = useCart();

const checkoutData = useForm<TOrderForm>({
	client_name: '',
	client_phone: '',
	client_email: '',
	client_message: '',
	cart_content: cartProducts.value,
	shipping_message: '',
	status_name: 'pending',
	status_color: 'primary',
	compleated: false
});
watch(cartProducts, () => {
	checkoutData.cart_content = cartProducts.value;
});

const { headerHeight } = useHeaderElement();

const requiredProducts = ref<TProduct[]>([]);

const submitCheckout = () => {
	checkoutData.post(route('order.store'), {
		preserveScroll: true,
		onSuccess: () => {
			checkoutData.reset();
			resetCart();
		}
	});
};

const emailRegexp = new RegExp(/^[^\s@]+@[^\s@]+\.[^\s@]+$/);

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
