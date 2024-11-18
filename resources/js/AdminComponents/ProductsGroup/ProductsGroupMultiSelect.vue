<template>
	<div class="d-flex gap-1 flex-wrap">
		<button
			v-for="group in productsGroups"
			:key="group.id"
			class="btn btn-sm"
			:class="{
				'btn-light': !props.modelValue.includes(group.id),
				'btn-primary': props.modelValue.includes(group.id)
			}"
			@click="onCheck(group.id)"
		>
			{{ _t(group.name) }}
		</button>
	</div>
</template>

<script setup lang="ts">
import { TProductsGroup } from '@/types/TProductsGroup';

const { _t } = useTranslations();

const props = defineProps<{
	productsGroups: TProductsGroup[];
	modelValue: number[];
}>();

const onCheck = (id: number) => {
	emit(
		'update:modelValue',
		props.modelValue.includes(id)
			? props.modelValue.filter(item => item !== id)
			: [...props.modelValue, id]
	);
};

const emit = defineEmits({
	'update:modelValue': (value: number[]) => true
});
</script>

<style scoped></style>
