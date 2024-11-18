<template>
	<a :href="file.url || ''" target="_blank" class="attached-file-card">
		<div class="icon-container mb-24">
			<img src="@/assets/pdf-file.svg" alt="File" class="icon" />
		</div>
		<span class="name fw-400 color-secondary">
			{{ file.name }}
		</span>
	</a>
</template>

<script setup lang="ts">
import { IMediaFile } from '@/types/IMediaFile';

const props = defineProps<{
	file: IMediaFile;
}>();
</script>

<style scoped lang="scss">
.attached-file-card {
	border-radius: 8px;
	border: 1px solid var(--card-background, #1a1a1a);
	background: linear-gradient(
		135deg,
		rgba(221, 221, 221, 0) 0%,
		rgba(240, 239, 239, 0.12) 100%
	);
	display: flex;
	flex-direction: column;
	justify-content: center;
	align-items: center;
	text-align: center;
	padding: 14.5px;
}

.icon-container {
	display: flex;
	justify-content: center;
	align-items: center;
	width: 160px;
	height: 160px;
}
</style>
