<template>
	<AdminLayout>
		<form @submit.prevent="submitEditForm">
			<div class="mb-3">
				<label class="form-label">Displayed Name</label>
				<input class="form-control fs-2" v-model="form.name" />
				<FormError :error="form.errors.name" />
			</div>
			<div class="mb-3">
				<label class="form-label">User Name</label>
				<input class="form-control" v-model="form.username" />
				<div class="form-text">
					User name is used for login. Should Be Unique
				</div>
				<FormError :error="form.errors.username" />
			</div>
			<div class="mb-3">
				<label class="form-label">User Email</label>
				<input class="form-control" v-model="form.email" />
				<FormError :error="form.errors.email" />
			</div>
			<div class="mb-3">
				<label class="form-label">New Password</label>
				<input
					type="password"
					class="form-control"
					v-model="form.password"
				/>
				<FormError :error="form.errors.password" />
			</div>
			<div class="mb-3">
				<p class="form-label">Roles</p>
				<div class="form-check" v-for="role in roles">
					<input
						class="form-check-input"
						type="checkbox"
						:value="role.id"
						v-model="form.roles"
						:id="`user-role-${role.id}`"
					/>
					<label
						class="form-check-label"
						:for="`user-role-${role.id}`"
					>
						{{ role.name }}
					</label>
				</div>
			</div>
			<button type="submit" class="btn btn-success">Create</button>
		</form>
	</AdminLayout>
</template>

<script setup lang="ts">
import AdminLayout from '@/Layouts/AdminLayout.vue';
import FormError from '@/AdminComponents/FormError.vue';
import { useForm, Link } from '@inertiajs/vue3';

import { PropType } from 'vue';
import { IRole } from '@/types/IRole';

const props = defineProps({
	user: {
		type: Object,
		required: false
	},
	roles: {
		type: Object as PropType<IRole[]>,
		required: true
	}
});

const form = useForm({
	name: '',
	username: '',
	email: '',
	password: '',
	roles: [] as number[]
});

const submitEditForm = () => {
	form.clearErrors();
	form.post(route('admin.user.store'), {
		preserveScroll: true,
		onSuccess: () => {
			form.reset();
		}
	});
};
</script>

<style scoped></style>
