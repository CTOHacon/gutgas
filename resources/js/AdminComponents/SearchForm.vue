<template>
	<div class="d-flex gap-1">
		<input class="form-control" placeholder="Search..." v-model="search" />
		<div class="btn-group">
			<button class="btn btn-primary" @click="onSearch">Пошук</button>
			<button
				v-if="search"
				class="btn btn-primary"
				@click="
					() => {
						search = '';
						onSearch();
					}
				"
			>
				Очистити
			</button>
		</div>
	</div>
</template>

<script setup lang="ts">
import { router, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';

const page = usePage();

const props = defineProps<{
	searchRoute: string;
	baseRoute: string;
}>();

const search = ref(page.props.search ?? '');
const onSearch = () => {
	if (search.value === '') {
		router.get(route(props.baseRoute));
	} else {
		router.get(route(props.searchRoute, { search: search.value }));
	}
	emit('search', search.value);
};

const emit = defineEmits({
	search: (query: string) => true
});
</script>

<style scoped></style>
