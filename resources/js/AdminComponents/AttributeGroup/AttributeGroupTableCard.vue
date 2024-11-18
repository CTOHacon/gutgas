<template>
	<tr>
		<td>
			{{ _t(attributeGroup.name) }}
		</td>
		<td>
			{{ attributeGroup.attributes_count }}
		</td>
		<td>
			<div
				class="btn-group"
				v-if="hasPermissions('admin.attributeGroup.manage')"
			>
				<Link
					class="btn btn-primary"
					:href="
						route('admin.attributeGroup.edit', attributeGroup.id) ||
						''
					"
				>
					Редагувати
				</Link>
				<button @click="onDelete" class="btn btn-danger">
					Видалити
				</button>
			</div>
		</td>
	</tr>
</template>

<script setup lang="ts">
import { TAttributeGroup } from '@/types/TAttributeGroup';
import useAuth from '@/modules/useAuth';
import { Link, router, useForm } from '@inertiajs/vue3';

const { _t } = useTranslations();

const props = defineProps<{
	attributeGroup: TAttributeGroup;
}>();

// delete form
const onDelete = () => {
	if (
		confirm(
			'Are you sure you want to delete this attribute group? This action cannot be undone.'
		)
	) {
		router.delete(
			route('admin.attributeGroup.destroy', props.attributeGroup.id)
		);
	}
};

const { hasPermissions } = useAuth();
</script>

<style scoped></style>
@/types/TAttributeGroup
