<template>
	<AdminLayout>
		<MediaGallery />
	</AdminLayout>
</template>

<script setup lang="ts">
import MediaGallery from '@/AdminComponents/Media/MediaGallery.vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({});
</script>

<style scoped></style>
