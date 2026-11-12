<template>
	<div class="tabs">
		<div
			class="controls"
			:class="[
				`controls--${variant ?? 'underline'}`,
				{ 'border-bottom': controlsBordered && variant !== 'chips' }
			]"
		>
			<button
				v-for="tab in tabs"
				:key="tab.name"
				type="button"
				class="control-button"
				:class="{ active: activeTabName === tab.name }"
				@click="activeTabName = tab.name"
			>
				<template v-if="tab.icon">
					<img :src="tab.icon" />
				</template>
				{{ tab.label || tab.name }}
			</button>
		</div>
		<slot />
	</div>
</template>

<script setup lang="ts">
import { provide, ref, useSlots, watch } from 'vue';

const slots = useSlots();
const props = defineProps<{
	controlsBordered?: boolean;
	controlsBackground?: 'none' | '#141414' | string;
	modelValue?: string;
	/** chips: wrapping pill buttons that never squeeze labels onto two lines */
	variant?: 'underline' | 'chips';
}>();

const emit = defineEmits<{
	(e: 'update:modelValue', value: string): void;
}>();

const tabs =
	slots
		.default?.()
		.filter(slot => slot.props?.name)
		.map((slot, index) => {
			return {
				name: slot.props?.name,
				label: slot.props?.label || index,
				icon: slot.props?.icon ?? null
			};
		}) || [];

const activeTabName = ref<string>(props.modelValue ?? tabs[0].name);
watch(activeTabName, name => emit('update:modelValue', name));
watch(
	() => props.modelValue,
	name => {
		if (name && name !== activeTabName.value) activeTabName.value = name;
	}
);
provide('activeTabName', activeTabName);
</script>

<style scoped lang="scss">
@import '@/styles/variables.scss';
.controls {
	display: flex;
	background: v-bind('props.controlsBackground');
	&.border-bottom {
		border-bottom: 1px solid rgba(131, 141, 151, 0.24);
	}
}
.control-button {
	padding: 1rem 0 1.5rem 0;
	display: flex;
	align-items: flex-end;
	justify-content: center;
	img {
		margin-right: 8px;
		margin-bottom: 3px;
	}
	flex: 1;
	color: #bdbdbd;
	position: relative;
	transition: color 0.3s ease;
	&::before {
		content: '';
		position: absolute;
		bottom: 0;
		left: 0;
		width: 100%;
		height: 2px;
		border-radius: 2px;
		background: #f24942;
		transform: scaleX(0);
		transition: transform 0.3s ease;
	}
	&.active {
		color: #fff;
		&::before {
			transform: scaleX(1);
		}
	}
	@media (max-width: $mobile-width) {
		padding: 0.8rem 0 0.8rem 0;
		font-size: 1rem;
	}
}
.controls--chips {
	flex-wrap: wrap;
	gap: 0.5rem;
	.control-button {
		flex: 0 0 auto;
		align-items: center;
		white-space: nowrap;
		font-size: 0.9375rem;
		padding: 0.625rem 0.875rem;
		border: 1px solid rgba(131, 141, 151, 0.4);
		border-radius: 999px;
		transition: color 0.3s ease, border-color 0.3s ease,
			background-color 0.3s ease;
		img {
			margin-bottom: 0;
			width: 1.25rem;
			height: 1.25rem;
		}
		&::before {
			display: none;
		}
		&:hover {
			color: #fff;
		}
		&.active {
			border-color: #f24942;
			background-color: rgba(242, 73, 66, 0.12);
		}
		@media (max-width: $large-mobile-width) {
			flex: 1 1 auto;
			justify-content: center;
			font-size: 0.9375rem;
			padding: 0.5rem 0.875rem;
		}
	}
}
</style>
