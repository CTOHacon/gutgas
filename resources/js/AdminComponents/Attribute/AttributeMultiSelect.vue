<template>
	<table class="table table table-bordered mb-0">
		<tbody>
			<tr v-for="group in attributeGroups" :key="group.id">
				<td>{{ _t(group.name) }}</td>
				<td>
					<!-- checkboxes -->
					<div class="d-flex gap-1 flex-wrap">
						<button
							v-for="attribute in group.attributes"
							class="btn btn-sm"
							:class="{
								'btn-light': !props.modelValue.includes(
									attribute.id
								),
								'btn-primary': props.modelValue.includes(
									attribute.id
								)
							}"
							@click="onCheck(attribute.id)"
						>
							{{ _t(attribute.name) }}
						</button>
					</div>
				</td>
			</tr>
		</tbody>
	</table>
</template>

<script setup lang="ts">
import { TAttribute } from '@/types/TAttribute';
import { TAttributeGroup } from '@/types/TAttributeGroup';
import { computed } from 'vue';

const { _t } = useTranslations();

const props = defineProps<{
	attributes: TAttribute[];
	modelValue: number[];
}>();

const attributeGroups = computed(() => {
	const groups: TAttributeGroup[] = [];
	props.attributes.forEach(attribute => {
		const group = groups.find(
			group => group.id === attribute.attribute_group_id
		);
		if (group) {
			group.attributes?.push(attribute);
		} else {
			if (!attribute.attribute_group) return;

			groups.push({
				...attribute.attribute_group,
				attributes: [attribute]
			});
		}
	});

	return groups;
});

const onCheck = (id: number) => {
	emit(
		'update:modelValue',
		props.modelValue.includes(id)
			? props.modelValue.filter(existingId => existingId !== id)
			: [...props.modelValue, id]
	);
};

const emit = defineEmits({
	'update:modelValue': (value: number[]) => true
});
</script>

<style scoped></style>
@/types/TAttributeGroup
