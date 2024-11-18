<template>
	<tr>
		<td>{{ page.id }}</td>
		<td>
			<a
				v-if="page.seo_entity.slug"
				target="_blank"
				:href="route('seo-entity', page.seo_entity.slug)"
			>
				{{ _t(page.title) || 'Без Заголовка' }}
			</a>
			<span v-else>{{ _t(page.title) || 'Без Заголовка' }}</span>
		</td>
		<td>
			{{ page.seo_entity.slug || 'Без Шляху' }}
		</td>
		<td>
			{{ page.type || 'Без Типу' }}
		</td>
		<td class="text-right">
			<div class="btn-group">
				<Link
					:href="route('admin.page.edit', page.id) || ''"
					class="btn btn-primary border-dark"
				>
					Налаштування Контенту
				</Link>
				<Link
					:href="
						route(
							'admin.seoEntity.forPage.edit',
							page.seo_entity?.id
						) || ''
					"
					class="btn btn-primary border-dark"
				>
					Налаштування SEO
				</Link>
				<button
					class="btn btn-danger border-dark"
					@click="onPageDelete"
				>
					Видалити
				</button>
			</div>
		</td>
	</tr>
</template>

<script setup lang="ts">
import { TPage } from '@/types/TPage';
import { Link, router } from '@inertiajs/vue3';

const { _t } = useTranslations();

const props = defineProps<{
	page: TPage;
}>();

const onPageDelete = () => {
	if (confirm('Are you sure you want to delete this page?')) {
		router.delete(route('admin.page.destroy', props.page.id));
	}
};
</script>

<style scoped></style>
