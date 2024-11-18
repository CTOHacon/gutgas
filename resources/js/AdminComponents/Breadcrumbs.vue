<template>
	<div class="d-flex gap-2 align-items-center mb-2">
		<button class="btn btn-link p-1" @click="back">
			<i class="bi bi-arrow-down-left-square me-1" />Back
		</button>

		<ol class="breadcrumb m-0">
			<li v-for="breadcrumb in breadcrumbs" class="breadcrumb-item">
				<Link
					v-if="currentUrl !== breadcrumb.slug"
					:href="breadcrumb.slug || ''"
					>{{ breadcrumb.title }}</Link
				>
				<span v-else>{{ breadcrumb.title }}</span>
			</li>
		</ol>
	</div>
</template>

<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';
const {
	props: { breadcrumbs }
} = usePage();

const back = () => {
	window.history.back();
};
const currentUrl = window.location.href;
</script>

<style scoped></style>
