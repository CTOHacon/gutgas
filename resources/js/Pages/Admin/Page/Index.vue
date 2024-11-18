<template>
	<AdminLayout>
		<div
			class="d-flex gap-1 flex-wrap p-2 bg-white my-1 sticky-top"
			style="margin-left: -0.5rem; margin-right: -0.5rem"
		>
			<!-- select pageTypes -->
			<div class="dropdown">
				<button
					class="btn btn-outline-primary dropdown-toggle"
					type="button"
					id="dropdownMenuButton1"
					data-bs-toggle="dropdown"
					aria-expanded="false"
				>
					{{ form.type ? form.type : 'Вибрати Тип Сторінки' }}
				</button>
				<ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
					<li v-for="pageType in pageTypes">
						<a
							class="dropdown-item"
							href="#"
							@click="
								form.type = pageType;
								$event.preventDefault();
							"
						>
							{{ pageType }}
						</a>
					</li>
				</ul>
			</div>
			<button
				class="btn btn-primary d-flex gap-2 align-items-center"
				@click="createPage"
			>
				<div class="bi-plus-circle"></div>
				Створити Сторінку
			</button>
			<SearchForm
				class="ms-auto"
				base-route="admin.page.indexAdmin"
				search-route="admin.page.searchAdmin"
			/>
		</div>

		<!-- products table -->
		<div class="table-responsive" v-if="pages">
			<table class="table table-striped table-hover">
				<thead>
					<tr>
						<th>ID</th>
						<th>Заголовок</th>
						<th>Slug</th>
						<th>Тип</th>
						<th class="text-right">Дії</th>
					</tr>
				</thead>
				<tbody>
					<PageTableCard v-for="page in pages.data" :page="page" />
				</tbody>
			</table>
		</div>
		<Pagination :data="pages" v-if="pages" />
	</AdminLayout>
</template>

<script setup lang="ts">
import PageTableCard from '@/AdminComponents/Page/PageTableCard.vue';
import Pagination from '@/AdminComponents/Pagination.vue';
import SearchForm from '@/AdminComponents/SearchForm.vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import useAuth from '@/modules/useAuth';
import useMessages from '@/modules/useMessages';
import { IPaginated } from '@/types/IPaginated';
import { TPage } from '@/types/TPage';
import { useForm } from '@inertiajs/vue3';
import { toRefs } from 'vue';

const props = defineProps<{
	pages: IPaginated<TPage>;
	pageTypes: string[];
}>();

const { pages } = toRefs(props);
const { addMessage } = useMessages();

const form = useForm({
	type: ''
});

const createPage = () => {
	form.post(route('admin.page.store'), {
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
</script>

<style scoped></style>
