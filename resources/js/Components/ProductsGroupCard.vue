<template>
	<div
		class="products-group-card"
		:class="{
			active: active
		}"
		@click="$emit('click', $event)"
	>
		<span class="head mb-16">
			<img class="icon icon-default" :src="icon" :alt="name" />
			<img
				v-if="iconHover"
				class="icon icon-hover"
				:src="iconHover"
				:alt="name"
			/>
			<img
				v-if="iconActive"
				class="icon icon-active"
				:src="iconActive"
				:alt="name"
			/>
		</span>
		<Link v-if="link" :href="link" class="name fs-medium" @click.prevent="">
			{{ name }}
		</Link>
		<span class="name fs-medium" v-else>{{ name }}</span>
	</div>
</template>

<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { onMounted } from 'vue';
import { ref } from 'vue';
const { _t } = useTranslations();

const props = defineProps<{
	name: string;
	icon: string;
	active: boolean;
	iconHover?: string;
	iconActive?: string;
	link?: string;
}>();

const onClick = defineEmits({
	click: (e: Event) => true
});

const currentPath = ref<string>(hasWindow() ? window.location.pathname : '');
onMounted(() => {
	currentPath.value = window.location.pathname;
});
</script>

<style scoped lang="scss">
@import '@/styles/variables.scss';
.products-group-card {
	display: flex;
	flex-direction: column;
	align-items: center;
	text-align: center;
	cursor: pointer;
}
.head {
	position: relative;
	height: 104px;
	width: 100%;
	@media (max-width: $tablet-width) {
		height: 80px;
	}
}
.icon {
	height: 100%;
	width: auto;
	position: absolute;
	top: 0;
	left: 50%;
	transform: translateX(-50%);
	transition: opacity 0.2s ease-in-out;
	z-index: 1;
	&-hover {
		opacity: 0;
		z-index: 2;
	}
	&-active {
		opacity: 0;
		z-index: 3;
	}
}

.products-group-card:not(.active):hover {
	.icon-default {
		opacity: 0;
	}
	.icon-hover {
		opacity: 1;
	}
}
.active .icon {
	opacity: 0 !important;
	&-active {
		opacity: 1 !important;
	}
}
.name {
	color: #838d97;
	font-weight: 600;
	transition: color 0.2s ease-in-out;
	width: min-content;
}
.active .name {
	color: #f24942;
}
</style>
