<template>
	<AdminLayout>
		<div
			class="d-flex gap-1 flex-wrap p-2 bg-white my-1 sticky-top"
			style="margin-left: -0.5rem; margin-right: -0.5rem"
		>
			<SearchForm
				base-route="admin.productRegistration.index"
				search-route="admin.productRegistration.search"
			/>
		</div>

		<div class="table-responsive">
			<table class="table table-striped">
				<thead>
					<tr>
						<th>ID</th>
						<th>Клієнт</th>
						<th>Телефон</th>
						<th>Модель / Серійний номер</th>
						<th>Дата покупки</th>
						<th>Статус</th>
						<th>Створено</th>
						<th class="text-right">Дії</th>
					</tr>
				</thead>
				<tbody>
					<tr v-for="item in registrations.data" :key="item.id">
						<td>{{ item.id }}</td>
						<td>{{ item.first_name }} {{ item.last_name }}</td>
						<td>{{ item.phone }}</td>
						<td>
							<span v-if="item.model">{{ item.model }} / </span>
							{{ item.serial_number }}
						</td>
						<td>{{ item.purchase_date }}</td>
						<td>
							<span
								v-if="item.confirmed_at"
								class="status status--confirmed"
								:title="formatDate(item.confirmed_at)"
							>
								Підтверджено
							</span>
							<span v-else class="status status--pending">
								Очікує
							</span>
						</td>
						<td>{{ formatDate(item.created_at) }}</td>
						<td class="text-right">
							<div class="btn-group">
								<Link
									:href="
										route(
											'admin.productRegistration.show',
											item.id
										) || ''
									"
									class="btn btn-primary border-dark"
								>
									Переглянути
								</Link>
								<button
									class="btn btn-danger"
									@click="onDelete(item.id)"
								>
									Видалити
								</button>
							</div>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
		<Pagination :data="registrations" />
	</AdminLayout>
</template>

<script setup lang="ts">
import Pagination from '@/AdminComponents/Pagination.vue';
import SearchForm from '@/AdminComponents/SearchForm.vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { IPaginated } from '@/types/IPaginated';
import { TProductRegistration } from '@/types/TProductRegistration';
import { Link, router } from '@inertiajs/vue3';

defineProps<{
	registrations: IPaginated<TProductRegistration>;
}>();

const formatDate = (value: string) => new Date(value).toLocaleString('uk-UA');

const onDelete = (id: number) => {
	if (confirm('Are you sure?')) {
		router.delete(route('admin.productRegistration.destroy', id));
	}
};
</script>

<style scoped>
/* Own classes, not bootstrap .badge/.bg-*: purgecss may drop newly used utilities from long-cached CSS */
.status {
	display: inline-block;
	padding: 0.2em 0.6em;
	border-radius: 0.375rem;
	font-size: 0.8rem;
	font-weight: 600;
	white-space: nowrap;
	vertical-align: middle;
}
.status--confirmed {
	background: #d1e7dd;
	color: #0f5132;
}
.status--pending {
	background: #fff3cd;
	color: #664d03;
}
</style>
