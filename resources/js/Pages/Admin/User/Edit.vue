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
				<input
					disabled
					class="form-control disabled"
					:value="user.username"
				/>
				<div class="form-text">
					User name is used for login. Should Be Unique
				</div>
			</div>
			<div class="mb-3">
				<label class="form-label">User Email</label>
				<input
					disabled
					class="form-control disabled"
					:value="user.email"
				/>
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
			<!-- group buttons sublimt + delete -->
			<div class="d-flex gap-1">
				<button type="submit" class="btn btn-success">
					Save Changes
				</button>
				<button
					type="button"
					class="btn btn-danger"
					@click="deleteUser"
				>
					Видалити
				</button>
			</div>
		</form>
	</AdminLayout>
</template>

<script setup lang="ts">
import AdminLayout from '@/Layouts/AdminLayout.vue';
import FormError from '@/AdminComponents/FormError.vue';
import { useForm } from '@inertiajs/vue3';
import { PropType } from 'vue';
import { IRole } from '@/types/IRole';

const props = defineProps({
	user: {
		type: Object,
		required: true
	},
	roles: {
		type: Object as PropType<IRole[]>,
		required: true
	}
});

const form = useForm({
	name: props.user.name,
	password: null,
	roles: props.user.roles.map((role: IRole) => role.id)
});

const submitEditForm = () => {
	form.clearErrors();
	form.put(route('admin.user.update', props.user.id));
};

const deleteUser = () => {
	if (confirm('Are you sure you want to delete this user?')) {
		form.delete(route('admin.user.destroy', props.user.id));
	}
};
</script>

<style scoped></style>
