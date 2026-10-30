<template>
	<AdminLayout>
		<FormFieldWrapper label="Status" class="mb-3">
			<div class="row g-1">
				<div class="col-4">
					<label class="form-label">Статус Замовлення</label>
					<select
						class="form-control"
						:value="form.status_name + '|' + form.status_color"
						@input="onStatusSelectInput"
					>
						<option value="Заявка|warning">Заявка</option>
						<option value="В обробці|dark">В обробці</option>
						<option value="Підтверджено|primary">
							Підтверджено
						</option>
						<option value="Підтверджено сплачено|primary">
							Підтверджено сплачено
						</option>
						<option
							value="Підтверджено оплата при отриманні|primary"
						>
							Підтверджено оплата при отриманні
						</option>
						<option value="Відправлено|info">Відправлено</option>
						<option value="Отримано|success">Отримано</option>
						<option value="Wasted|danger">Wasted</option>
					</select>
					<!-- <label class="form-label">Назва Статусу</label> -->
				</div>
				<!-- <div class="col-4">
					<label class="form-label">Колір Статусу</label>
					<select
						v-model="form.status_color"
						class="form-control"
						placeholder="Колір Статусу"
					>
						<option value="success">Зелений</option>
						<option value="danger">Червоний</option>
						<option value="warning">Жовтий</option>
						<option value="secondary">Сірий</option>
						<option value="primary">Синій</option>
						<option value="info">Світло Синій</option>
						<option value="dark">Чорний</option>
					</select>
					<FormError :error="form.errors.status_color" />
				</div> -->
				<!-- compleated checkbox -->
				<div class="col-4">
					<div
						class="d-flex align-content-center justify-content-center h-100"
					>
						<div class="form-check mt-4">
							<input
								class="form-check-input"
								type="checkbox"
								v-model="form.compleated"
								id="flexCheckDefault"
							/>
							<label
								class="form-check-label"
								for="flexCheckDefault"
							>
								Замовлення Завершене
							</label>
						</div>
					</div>
				</div>
			</div>
		</FormFieldWrapper>
		<FormFieldWrapper label="Client" class="mb-3">
			<div class="row g-1">
				<div class="col-6">
					<div class="mb-3">
						<label class="form-label">Імʼя</label>
						<input
							class="form-control"
							v-model="form.client_name"
							placeholder="Імʼя"
						/>
						<FormError :error="form.errors.client_name" />
					</div>
					<div class="mb-3">
						<label class="form-label">Прізвище</label>
						<input
							class="form-control"
							v-model="form.client_surname"
							placeholder="Прізвище"
						/>
						<FormError :error="form.errors.client_surname" />
					</div>
					<div class="mb-3">
						<label class="form-label">Телефон</label>
						<input
							class="form-control"
							v-model="form.client_phone"
							placeholder="Phone"
						/>
						<FormError :error="form.errors.client_phone" />
					</div>
					<div class="mb-0">
						<label class="form-label">Email</label>
						<input
							class="form-control"
							v-model="form.client_email"
							placeholder="Email"
						/>
					</div>
				</div>
				<div class="col-6">
					<div class="mb-0 h-100">
						<textarea
							class="form-control h-100"
							v-model="form.client_message"
							placeholder="Повідомлення"
						></textarea>
					</div>
				</div>
			</div>
		</FormFieldWrapper>
		<FormFieldWrapper label="Content" class="mb-3">
			<table class="table table-responsive table-bordered mb-3">
				<thead>
					<tr>
						<th>id</th>
						<th>Зображення</th>
						<th>Назва</th>
						<th>SKU</th>
						<th>Кількість</th>
						<th>Загалом</th>
					</tr>
				</thead>
				<tbody>
					<tr v-for="item in order.cart_content" :key="item.id">
						<td>{{ item.id }}</td>
						<td>
							<img
								:src="item.media_file?.thumbnail?.url"
								style="
									width: 50px;
									height: 50px;
									object-fit: cover;
								"
							/>
						</td>
						<td>{{ _t(item.name) }}</td>
						<td>{{ item.sku }}</td>
						<td>{{ item.quantity }}</td>
						<td>{{ item.price * item.quantity }} ₴</td>
					</tr>
				</tbody>
				<tfoot>
					<tr>
						<td colspan="5" class="text-right"></td>
						<td>
							<strong>
								{{
									order.cart_content.reduce(
										(acc, item) =>
											acc + item.price * item.quantity,
										0
									)
								}}
								₴
							</strong>
						</td>
					</tr>
				</tfoot>
			</table>
		</FormFieldWrapper>
		<FormFieldWrapper
			label="Shipping"
			class="mb-3"
			v-if="order.shipping_method && order.shipping_method !== 'other'"
		>
			<table class="table table-bordered mb-0">
				<tbody>
					<tr>
						<th>Спосіб</th>
						<td>{{ shippingMethodLabels[order.shipping_method] }}</td>
					</tr>
					<tr v-if="order.shipping_city">
						<th>Місто</th>
						<td>
							{{ order.shipping_city }}
							<span v-if="order.shipping_area">
								({{ order.shipping_area }})
							</span>
						</td>
					</tr>
					<tr v-if="order.shipping_warehouse">
						<th>Відділення / Поштомат</th>
						<td>{{ order.shipping_warehouse }}</td>
					</tr>
					<tr v-if="order.shipping_address">
						<th>Адреса</th>
						<td>{{ order.shipping_address }}</td>
					</tr>
					<tr v-if="order.locale">
						<th>Мова замовлення</th>
						<td>{{ order.locale }}</td>
					</tr>
				</tbody>
			</table>
		</FormFieldWrapper>
		<FormFieldWrapper
			label="Shipping"
			class="mb-3"
			v-if="form.shipping_message"
		>
			<div class="mb-0">
				<textarea
					class="form-control h-100"
					v-model="form.shipping_message"
					placeholder="Доставка"
				></textarea>
			</div>
		</FormFieldWrapper>
		<FormFieldWrapper label="CRM" class="mb-3">
			<table class="table table-bordered mb-3">
				<tbody>
					<tr>
						<th>ID в CRM</th>
						<td>{{ order.crm_order_id ?? '—' }}</td>
					</tr>
					<tr>
						<th>Відправлено</th>
						<td>{{ order.crm_sent_at ?? '—' }}</td>
					</tr>
					<tr v-if="order.crm_error">
						<th>Помилка</th>
						<td class="text-danger">{{ order.crm_error }}</td>
					</tr>
				</tbody>
			</table>
			<button
				v-if="!order.crm_order_id"
				type="button"
				class="btn btn-outline-primary"
				@click="onPushToCrm"
			>
				Відправити в CRM
			</button>
		</FormFieldWrapper>
		<button class="btn btn-primary" @click="onSubmitForm">
			Зберегти Зміни
		</button>
	</AdminLayout>
</template>

<script setup lang="ts">
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { TOrder, TOrderAdminForm } from '@/types/TOreder';
import { router, useForm } from '@inertiajs/vue3';
import FormFieldWrapper from '@/AdminComponents/FormFieldWrapper.vue';
import FormError from '@/AdminComponents/FormError.vue';
import useMessages from '@/modules/useMessages';

const { addMessage } = useMessages();

const props = defineProps<{ order: TOrder }>();
const { _t } = useTranslations();

const shippingMethodLabels: Record<string, string> = {
	np_department: 'Відділення Нової Пошти',
	np_postal_machine: 'Поштомат Нової Пошти',
	np_courier: 'Кур\'єр Нової Пошти'
};

const form = useForm<TOrderAdminForm>({
	client_email: props.order.client_email,
	client_phone: props.order.client_phone,
	client_message: props.order.client_message,
	client_name: props.order.client_name,
	client_surname: props.order.client_surname,
	shipping_message: props.order.shipping_message,
	status_color: props.order.status_color,
	status_name: props.order.status_name,
	compleated: props.order.compleated === 1
});

const onSubmitForm = () => {
	form.clearErrors();
	form.put(route('admin.order.update', props.order.id), {
		preserveScroll: true,
		onSuccess: () => {},
		onError: () => {
			Object.entries(form.errors).forEach(([key, value]) => {
				addMessage({
					type: 'error',
					text: value
				});
			});
		}
	});
};

const onPushToCrm = () => {
	router.post(route('admin.order.crm', props.order.id), {}, { preserveScroll: true });
};

const onStatusSelectInput = (event: Event) => {
	const input = event.target as HTMLSelectElement;

	let value = input.value.split('|');
	if (value.length === 2) {
		form.status_name = value[0];
		form.status_color = value[1];
	}
};
</script>

<style scoped></style>
