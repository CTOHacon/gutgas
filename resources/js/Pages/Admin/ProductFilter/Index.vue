<template>
	<AdminLayout>
		<div class="row g-3">
			<div class="col-9">
				<div
					class="p-1 mb-1 border rounded"
					v-for="productFilter in productFilters"
					:key="productFilter.id"
				>
					<div class="row">
						<div class="col-3">
							<ProductFilterEditor
								:productFilter="productFilter"
							/>
						</div>
						<div class="col-9">
							<div
								class="p-1 mb-1 border rounded"
								v-for="productFilterValue in productFilter.product_filter_values"
								:key="productFilterValue.id"
							>
								<ProductFilterValueEditor
									:productFilterValue="productFilterValue"
								/>
							</div>
							<ProductFilterValueCreator
								:productFilter="productFilter"
							/>
						</div>
					</div>
				</div>
			</div>
			<div class="col-3">
				<div class="p-1 mb-1 border rounded">
					<ProductFilterForm :form="productFilterCreateForm" />
					<button
						class="btn btn-success mt-1"
						@click="onProductFilterCreateFormSubmit"
					>
						Додати Фільтр
					</button>
				</div>
			</div>
		</div>
	</AdminLayout>
</template>

<script setup lang="ts">
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { toRefs } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';
import { TProductFilter, TProductFilterForm } from '@/types/TProductFilter';
import ProductFilterForm from '@/AdminComponents/ProductFilter/ProductFilterForm.vue';
import ProductFilterEditor from '@/AdminComponents/ProductFilter/ProductFilterEditor.vue';
import ProductFilterValueForm from '@/AdminComponents/ProductFilterValue/ProductFilterValueForm.vue';
import ProductFilterValueCreator from '@/AdminComponents/ProductFilterValue/ProductFilterValueCreator.vue';
import ProductFilterValueEditor from '@/AdminComponents/ProductFilterValue/ProductFilterValueEditor.vue';

const props = defineProps<{
	productFilters: TProductFilter[];
}>();

const { __, _t } = useTranslations();

const { productFilters } = toRefs(props);

const productFilterCreateForm = useForm<TProductFilterForm>({
	name: '',
	sequence: 0
});
const onProductFilterCreateFormSubmit = () => {
	productFilterCreateForm.post(route('admin.productFilter.store'), {
		preserveScroll: true,
		preserveState: false
	});
};
</script>

<style scoped></style>
