<template>
	<tr>
		<td>
			{{ _t(requiredProductsGroup.name) }}
		</td>
		<td class="text-right">
			<div
				class="btn-group"
				v-if="hasPermissions('admin.requiredProductsGroup.manage')"
			>
				<Link
					class="btn btn-primary"
					:href="
						route(
							'admin.requiredProductsGroup.edit',
							requiredProductsGroup.id
						) || ''
					"
				>
					Редагувати
				</Link>
				<Link
					class="btn btn-danger"
					:href="
						route(
							'admin.requiredProductsGroup.destroy',
							requiredProductsGroup.id
						) || ''
					"
					method="delete"
					as="button"
				>
					Видалити
				</Link>
			</div>
		</td>
	</tr>
</template>

<script setup lang="ts">
import useAuth from '@/modules/useAuth';
import { Link } from '@inertiajs/vue3';
import { TRequiredProductsGroup } from '@/types/TRequiredProductsGroup';

const props = defineProps<{
	requiredProductsGroup: TRequiredProductsGroup;
}>();

const { _t } = useTranslations();

const { hasPermissions } = useAuth();
</script>

<style scoped lang="scss"></style>
