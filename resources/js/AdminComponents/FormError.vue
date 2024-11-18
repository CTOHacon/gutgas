<template>
	<p v-if="error" class="alert alert-danger mt-1 p-2">
		{{ error }}
		<button
			type="button"
			class="btn-close"
			data-bs-dismiss="alert"
			aria-label="Закрити"
			@click="close"
		></button>
	</p>
</template>

<script setup lang="ts">
import { toRefs } from 'vue';
const props = defineProps<{ error: string | undefined }>();
const { error } = toRefs(props);

const close = () => {
	error.value = undefined;
};
</script>

<style scoped lang="scss">
.alert {
	display: flex;
	align-items: center;
	justify-content: space-between;
}
</style>
