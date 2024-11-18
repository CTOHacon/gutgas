<template>
	<div>
		<div class="row">
			<div class="col-12 mb-3" v-for="(contactItem, index) in items">
				<div class="card-body border p-1" :key="index">
					<div class="row">
						<div class="col-4">
							<input
								class="form-control mb-1"
								v-model="contactItem.name"
								placeholder="Назва Контакту"
							/>
							<button
								class="btn btn-danger"
								@click="items.splice(index, 1)"
							>
								Видалити
							</button>
						</div>
						<div class="col-8">
							<div
								class="col-12 mb-3"
								v-for="(
									contactItemValue, index
								) in contactItem.values"
							>
								<div class="card-body" :key="index">
									<input
										class="form-control mb-1"
										v-model="contactItemValue.value"
										placeholder="Значення"
									/>
									<input
										class="form-control mb-1"
										v-model="contactItemValue.comment"
										placeholder="Примітка"
									/>
									<input
										class="form-control mb-1"
										v-model="contactItemValue.link"
										placeholder="Посилання"
									/>
									<button
										class="btn btn-danger"
										@click="
											contactItem.values.splice(index, 1)
										"
									>
										Видалити
									</button>
								</div>
							</div>
							<button
								class="btn btn-primary"
								@click="
									contactItem.values.push({
										value: '',
										comment: '',
										link: ''
									})
								"
							>
								Додати Значення контакту
							</button>
						</div>
					</div>
				</div>
			</div>
		</div>
		<button
			class="btn btn-primary"
			@click="items.push({ name: '', values: [] })"
		>
			Додати Елемент
		</button>
	</div>
</template>

<script setup lang="ts">
import { TContactItemProps } from '@/types/TContactItemProps';

import { toRef, watch } from 'vue';

const props = defineProps<{
	modelValue: TContactItemProps[];
}>();

const items = toRef(props, 'modelValue');

watch(
	() => items.value,
	value => {
		emit('update:modelValue', value);
	},
	{ deep: true }
);

const emit = defineEmits({
	'update:modelValue': (value: TContactItemProps[]) => true
});
</script>

<style scoped></style>
