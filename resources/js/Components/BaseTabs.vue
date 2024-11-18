<template>
	<div class="tabs">
		<div
			class="controls"
			:class="{
				'border-bottom': controlsBordered
			}"
		>
			<button
				v-for="tab in tabs"
				:key="tab.name"
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
import getImageUrl from '@/utils/getImageUrl';
import { provide, ref, useSlots } from 'vue';

const slots = useSlots();
const props = defineProps<{
	controlsBordered?: boolean;
	controlsBackground?: 'none' | '#141414' | string;
}>();

const tabs =
	slots
		.default?.()
		.filter(slot => slot.props?.name)
		.map((slot, index) => {
			return {
				name: slot.props?.name,
				label: slot.props?.label || index,
				icon: slot.props?.icon ? getImageUrl(slot.props.icon) : null
			};
		}) || [];

const activeTabName = ref(tabs[0].name);
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
</style>
