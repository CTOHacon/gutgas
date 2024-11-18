<template>
	<form @submit.prevent="emit('submit', isValid)" class="form">
		<slot />
	</form>
</template>

<script setup lang="ts">
import { provide, ref, useSlots } from 'vue';

const isValid = ref(true);
const formData = ref({});
const setData = (key: string, data: any) => {
	formData.value = data;
};

provide('form.isValid', isValid);

const emit = defineEmits({
	submit: (isValid: boolean) => true
});

defineExpose({
	isValid
});
</script>

<style scoped></style>
