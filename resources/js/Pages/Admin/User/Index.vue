<template>
	<AdminLayout>
		<Link
			class="btn btn-primary mb-3"
			:href="route('admin.user.create') || ''"
		>
			Create User
		</Link>
		<div class="table-responsive" style="min-width: 100%">
			<table class="table caption-top">
				<thead>
					<tr>
						<th>#</th>
						<th>Назва</th>
						<th>User Name</th>
						<th>Roles</th>
						<th class="text-end">Controls</th>
					</tr>
				</thead>
				<tbody>
					<tr v-for="user in users.data" class="align-middle">
						<th scope="row">{{ user.id }}</th>
						<td>{{ user.name }}</td>
						<td>{{ user.username }}</td>
						<td>
							<p class="d-flex gap-2 m-0">
								<span
									v-for="role in user?.roles"
									class="list-group-item"
								>
									{{ role.name }}
								</span>
							</p>
						</td>
						<td>
							<div
								class="d-flex justify-content-end gap-1"
								v-if="hasPermissions('admin.user.manage')"
							>
								<Link
									class="btn btn-primary"
									:href="
										route('admin.user.edit', user.id) || ''
									"
								>
									Edit
								</Link>
								<Link
									class="btn btn-danger"
									:href="
										route('admin.user.destroy', user.id) ||
										''
									"
									method="delete"
									as="button"
								>
									Видалити
								</Link>
							</div>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
		<Pagination :data="users" class="mt-3 d-flex justify-content-center" />
	</AdminLayout>
</template>

<script setup lang="ts">
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Pagination from '@/AdminComponents/Pagination.vue';
import { Link } from '@inertiajs/vue3';
import useAuth from '@/modules/useAuth';
import { IPaginated } from '@/types/IPaginated';
import { IUser } from '@/types/IUser';
const { hasPermissions } = useAuth();

const props = defineProps<{
	users: IPaginated<IUser>;
}>();
</script>

<style scoped></style>
