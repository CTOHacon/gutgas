<template>
	<template v-for="component in components">
		<component
			:is="component.componentReference"
			v-bind="component.props"
		/>
	</template>
</template>

<script setup lang="ts">
import FaqSection from './FaqSection.vue';
import { computed } from 'vue';

const props = defineProps<{
	components: {
		name: 'FaqSection';
		props: Record<string, unknown>;
	}[];
}>();

const getComponentReference = (name: string) => {
	switch (name) {
		case 'FaqSection':
			return FaqSection;
		default:
			return {};
	}
};

const components = computed(() => {
	return props.components.map(component => {
		return {
			componentReference: getComponentReference(component.name),
			props: component.props
		};
	});
});
</script>

<style scoped></style>
