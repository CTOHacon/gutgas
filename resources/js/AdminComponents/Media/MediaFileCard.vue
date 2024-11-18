<template>
	<div
		:class="[
			'media-file-card',
			'border',
			'rounded',
			{ disabled: disabled }
		]"
	>
		<div class="media-file-card__top">
			<div class="media-file-card__thumb">
				<img v-if="imageUrl && isImage" :src="imageUrl" />
				<i v-else class="bi bi-file-earmark-post fs-1"></i>
			</div>
			<div class="media-file-card__controls">
				<slot name="controls"></slot>
			</div>
		</div>
		<div class="p-2" v-if="name || type">
			<h6 v-if="name" class="media-file-card__name mb-0">{{ name }}</h6>
			<small class="text-muted">{{ type }}</small>
		</div>
	</div>
</template>

<script setup lang="ts">
import { computed } from 'vue';
const props = defineProps<{
	imageUrl?: string | null;
	name?: string | null;
	type?: string | null;
	disabled?: boolean;
}>();

const isImage = computed(() => {
	if (!props.type)
		return ['jpg', 'png', 'jpeg', 'svg', 'webp'].some(ext =>
			props.imageUrl?.toLowerCase().endsWith(ext)
		);
	return props.type.includes('image');
});
</script>

<style lang="scss" scoped>
.media-file-card {
	overflow: hidden;
	&.disabled {
		opacity: 0.5;
		pointer-events: none;
	}
	&__thumb {
		height: 150px;
		width: 100%;
		background-color: #f1f1f1;
		display: flex;
		justify-content: center;
		align-items: center;
		img {
			width: 100%;
			height: 100%;
			object-fit: cover;
		}
	}
	&__top {
		position: relative;
	}
	&__controls {
		position: absolute;
		top: 0;
		right: 0;
		width: 100%;
		height: 100%;
		background-color: rgba(0, 0, 0, 0.5);
		opacity: 0;

		display: flex;
		justify-content: center;
		align-items: center;
		flex-wrap: wrap;
		gap: 0.5rem;
		padding: 20px;
		transition: opacity 0.2s ease-in-out;
	}

	&:hover &__controls {
		opacity: 1;
	}
	&.selected {
		outline: 2px solid #0d6efd !important;
	}
}
</style>
