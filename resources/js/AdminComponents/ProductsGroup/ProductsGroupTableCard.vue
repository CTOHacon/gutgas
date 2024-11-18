<template>
	<tr>
		<td>
			{{ _t(productsGroup.name) }}
		</td>
		<td>
			{{ productsGroup.is_active ? 'Yes' : 'No' }}
		</td>
		<td class="text-right">
			<div
				class="btn-group"
				v-if="hasPermissions('admin.productsGroup.manage')"
			>
				<Link
					class="btn btn-primary"
					:href="
						route('admin.productsGroup.edit', productsGroup.id) ||
						''
					"
				>
					Редагувати
				</Link>
				<Link
					class="btn btn-primary"
					:href="
						route(
							'admin.seoEntity.forProductsGroup.edit',
							productsGroup.seo_entity.id
						) || ''
					"
				>
					SEO Налаштування
				</Link>
				<Link
					class="btn btn-danger"
					:href="
						route(
							'admin.productsGroup.destroy',
							productsGroup.id
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
import { TProductsGroup } from '@/types/TProductsGroup';
import useAuth from '@/modules/useAuth';
import { Link } from '@inertiajs/vue3';

const props = defineProps<{
	productsGroup: TProductsGroup;
}>();

const { _t } = useTranslations();

const { hasPermissions } = useAuth();
</script>

<style scoped lang="scss"></style>
