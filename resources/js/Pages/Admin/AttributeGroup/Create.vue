<template>
	<AdminLayout>
		<AttributeGroupForm :form="form" />
		<button type="submit" class="btn btn-success" @click="submitForm">
			Створити
		</button>
	</AdminLayout>
</template>

<script setup lang="ts">
import AttributeGroupForm from '@/AdminComponents/AttributeGroup/AttributeGroupForm.vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { TAttributeGroupForm } from '@/types/TAttributeGroup';
import { useForm } from '@inertiajs/vue3';

const form = useForm<TAttributeGroupForm>({
	name: ''
});

const submitForm = () => {
	form.clearErrors();
	form.post(route('admin.attributeGroup.store'));
};
</script>

<style scoped></style>
