<template>
	<AdminLayout>
		<div
			class="d-flex gap-1 flex-wrap p-2 bg-white my-1 sticky-top"
			style="margin-left: -0.5rem; margin-right: -0.5rem"
		>
			<SearchForm
				base-route="admin.order.index"
				search-route="admin.order.search"
			/>
			<!-- show_compleated checkbox -->
			<div class="form-check form-switch">
				<input
					class="form-check-input"
					type="checkbox"
					id="show_compleated"
					:checked="showCompleated ? showCompleated === '1' : false"
					@click="
                    (e: Event)=>{
                        router.get(route('admin.order.index', {show_compleated: (e.target as HTMLInputElement).checked}))
                    }
                    "
				/>
				<label class="form-check-label" for="show_compleated">
					Показувати Завершені Замовлення
				</label>
			</div>
		</div>

		<!-- products table -->
		<div class="table-responsive">
			<table class="table table-striped table-hoshowCompleatedver">
				<thead>
					<tr>
						<th>ID</th>
						<th>Імʼя Клієнта</th>
						<th>Сумма</th>
						<th>Статус</th>
						<th class="text-right">Дії</th>
					</tr>
				</thead>
				<tbody>
					<OrderTableCard
						v-for="order in orders.data"
						:order="order"
					/>
				</tbody>
			</table>
		</div>
		<Pagination :data="orders" />
	</AdminLayout>
</template>

<script setup lang="ts">
import OrderTableCard from '@/AdminComponents/Order/OrderTableCard.vue';
import Pagination from '@/AdminComponents/Pagination.vue';
import SearchForm from '@/AdminComponents/SearchForm.vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { IPaginated } from '@/types/IPaginated';
import { TOrder } from '@/types/TOreder';
import { router } from '@inertiajs/vue3';
import { toRefs } from 'vue';

const props = defineProps<{
	orders: IPaginated<TOrder>;
	showCompleated?: '1' | '0';
}>();

const { orders } = toRefs(props);
</script>

<style scoped></style>
