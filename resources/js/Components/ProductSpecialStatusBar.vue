<template>
	<span class="price-label">
		<slot />
	</span>
</template>

<script setup lang="ts"></script>

<style scoped lang="scss">
.price-label {
	padding: 0.25rem 0.625rem;
	background: #f24942;
	border-radius: 4.25rem 0.75rem 0.125rem 4.25rem;
	z-index: 3;
	font-weight: 800;
	font-size: 0.875rem;
	line-height: 124%;
	color: rgba(15, 15, 16, 0.7);
}
</style>
