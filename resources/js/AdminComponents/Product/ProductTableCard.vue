<template>
	<tr>
		<td>
			{{ product.sorting_index }}
		</td>
		<td>
			{{ product.id }}
		</td>
		<td>
			<Link :href="route('admin.product.edit', product.id) || ''">
				<img
					v-if="product.media_file"
					:src="getMediaFileUrl(product.media_file, 'thumbnail')"
					:alt="_t(product.name)"
					style="width: 50px; height: 50px"
				/>
				<i
					v-else
					class="bi bi-image-fill"
					style="font-size: 50px; color: #ccc"
				></i>
			</Link>
		</td>
		<td>{{ _t(product.name) }}</td>
		<td>{{ product.sku }}</td>
		<td>{{ product.price }}</td>
		<td>{{ product.stock }}</td>
		<td class="text-right">
			<div class="btn-group">
				<Link
					:href="route('admin.product.edit', product.id) || ''"
					class="btn btn-primary border-dark"
				>
					Про товар
				</Link>
				<Link
					:href="route('admin.productPage.edit', product.id) || ''"
					class="btn btn-primary border-dark"
				>
					Сторінка Товару
				</Link>
				<Link
					v-if="product.seo_entity"
					:href="
						route(
							'admin.seoEntity.forProductPage.edit',
							product.seo_entity?.id
						) || ''
					"
					class="btn btn-primary border-dark"
				>
					SEO Налаштування
				</Link>
				<button
					class="btn btn-danger border-dark"
					@click="onProductDelete"
				>
					Видалити
				</button>
			</div>
		</td>
	</tr>
</template>

<script setup lang="ts">
import { TProduct } from '@/types/TProduct';
import getMediaFileUrl from '@/modules/helpers/getMediaFileUrl';
import { Link, router } from '@inertiajs/vue3';
const props = defineProps<{
	product: TProduct;
}>();

const { _t } = useTranslations();

const onProductDelete = () => {
	if (confirm('Are you sure you want to delete this product?')) {
		router.delete(route('admin.product.destroy', props.product.id));
	}
};
</script>

<style scoped></style>
