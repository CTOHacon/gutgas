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
