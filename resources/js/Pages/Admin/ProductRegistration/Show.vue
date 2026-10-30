<template>
	<AdminLayout>
		<h2 class="mb-3">Реєстрація продукту #{{ registration.id }}</h2>

		<FormFieldWrapper label="Клієнт" class="mb-3">
			<dl class="row mb-0">
				<template v-for="row in clientRows" :key="row.label">
					<dt class="col-sm-3">{{ row.label }}</dt>
					<dd class="col-sm-9">{{ row.value || '—' }}</dd>
				</template>
			</dl>
		</FormFieldWrapper>

		<FormFieldWrapper label="Продукт" class="mb-3">
			<dl class="row mb-0">
				<template v-for="row in productRows" :key="row.label">
					<dt class="col-sm-3">{{ row.label }}</dt>
					<dd class="col-sm-9">{{ row.value || '—' }}</dd>
				</template>
			</dl>
		</FormFieldWrapper>

		<FormFieldWrapper label="Службова інформація" class="mb-3">
			<dl class="row mb-0">
				<template v-for="row in metaRows" :key="row.label">
					<dt class="col-sm-3">{{ row.label }}</dt>
					<dd class="col-sm-9">{{ row.value || '—' }}</dd>
				</template>
			</dl>
		</FormFieldWrapper>

		<div class="btn-group">
			<Link
				:href="route('admin.productRegistration.index') || ''"
				class="btn btn-secondary"
			>
				До списку
			</Link>
			<button class="btn btn-danger" @click="onDelete">Видалити</button>
		</div>
	</AdminLayout>
</template>

<script setup lang="ts">
import AdminLayout from '@/Layouts/AdminLayout.vue';
import FormFieldWrapper from '@/AdminComponents/FormFieldWrapper.vue';
import { TProductRegistration } from '@/types/TProductRegistration';
import { Link, router } from '@inertiajs/vue3';

const props = defineProps<{
	registration: TProductRegistration;
}>();

const r = props.registration;

const clientRows = [
	{ label: "Ім'я", value: r.first_name },
	{ label: 'Прізвище', value: r.last_name },
	{ label: 'Телефон', value: r.phone },
	{ label: 'Email', value: r.email },
	{ label: 'Місто', value: r.city },
	{ label: 'Адреса', value: r.address }
];

const productRows = [
	{ label: 'Номер моделі', value: r.model },
	{ label: 'Серійний номер', value: r.serial_number },
	{ label: 'Дата покупки', value: r.purchase_date },
	{ label: 'Вартість, грн', value: r.price },
	{ label: 'Місце покупки', value: r.purchase_place }
];

const metaRows = [
	{
		label: 'Згода на підписку',
		value: r.subscription_consent ? 'Так' : 'Ні'
	},
	{ label: 'Мова', value: r.locale },
	{ label: 'IP', value: r.ip },
	{ label: 'Створено', value: new Date(r.created_at).toLocaleString('uk-UA') }
];

const onDelete = () => {
	if (confirm('Are you sure?')) {
		router.delete(route('admin.productRegistration.destroy', r.id));
	}
};
</script>
