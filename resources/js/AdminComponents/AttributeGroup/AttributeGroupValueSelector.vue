<template>
	<div></div>
</template>

<script setup lang="ts">
import { TAttributeGroup } from '@/types/TAttributeGroup';

const props = defineProps<{
	attributeGroup: TAttributeGroup;
	modelValue: number | null;
}>();
</script>

<style scoped lang="scss"></style>
