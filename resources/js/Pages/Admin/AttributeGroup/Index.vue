<template>
	<AdminLayout>
		<div class="mb-3">
			<Link
				class="btn btn-primary"
				:href="route('admin.attributeGroup.create') || ''"
			>
				Створити Групу Атрибутів
			</Link>
		</div>
		<div class="table-responsive">
			<table class="table table-striped table-hover">
				<thead>
					<tr>
						<th>Назва</th>
						<th>Кількість Атрибутів</th>
						<th>Дії</th>
					</tr>
				</thead>
				<tbody>
					<AttributeGroupTableCard
						v-for="attributeGroup in attributeGroups"
						:attribute-group="attributeGroup"
						:key="attributeGroup.id"
					/>
				</tbody>
			</table>
		</div>
	</AdminLayout>
</template>

<script setup lang="ts">
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { toRefs } from 'vue';
import { Link } from '@inertiajs/vue3';
import { TAttributeGroup } from '@/types/TAttributeGroup';
import AttributeGroupTableCard from '@/AdminComponents/AttributeGroup/AttributeGroupTableCard.vue';

const props = defineProps<{
	attributeGroups: TAttributeGroup[];
}>();
const { attributeGroups } = toRefs(props);
</script>

<style scoped></style>
@/types/TAttributeGroup
