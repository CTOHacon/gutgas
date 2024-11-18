<template>
	<div class="variation-picker">
		<BaseButton
			v-for="variation in props.variations"
			:active="variation.id === activeVariation"
			variation="dark-toggleable"
			:key="variation.id"
			class="variation-button"
			@click="
				activeVariation = variation.id;
				emit('change', variation.id);
			"
		>
			{{ variation.name }}
		</BaseButton>
	</div>
</template>

<script setup lang="ts">
import { ref, watch, watchEffect } from 'vue';
import BaseButton from './BaseButton.vue';

// DOCS:
// If value is not provided
// activeVariation will be set to the first variation id
// otherwise it will be set to the value

const props = defineProps<{
	value?: number;
	variations: {
		id: number;
		name: string;
	}[];
}>();

const activeVariation = ref(props.value || props.variations[0].id);
watchEffect(() => {
	if (props.value) activeVariation.value = props.value;
});

const emit = defineEmits({
	change: (id: number) => true
});
</script>

<style scoped lang="scss">
.variation-picker {
	display: flex;
	align-items: center;
}

.variation-button {
	padding: 8px 16px;
	&:not(:last-child) {
		margin-right: 8px;
	}
}
</style>
