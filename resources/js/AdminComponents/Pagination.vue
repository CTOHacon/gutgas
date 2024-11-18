<template>
	<nav v-if="links.length > 1" aria-label="Page navigation example">
		<ul class="pagination">
			<li class="page-item" v-if="data.current_page !== 1">
				<Link
					:href="data.prev_page_url || ''"
					class="page-link"
					aria-label="Previous"
				>
					Попереднє
				</Link>
			</li>

			<li class="page-item" v-for="link in links">
				<Link
					:href="link.url || ''"
					class="page-link"
					:class="{ active: link.active }"
					v-html="link.label"
				>
				</Link>
			</li>

			<li class="page-item" v-if="data.current_page !== data.last_page">
				<Link
					:href="data.next_page_url || ''"
					class="page-link"
					aria-label="Next"
				>
					Наступне
				</Link>
			</li>
		</ul>
	</nav>
</template>

<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { ref } from 'vue';
const props = defineProps({
	data: {
		type: Object,
		required: true
	}
});
const links = ref(props.data.links.slice(1, -1));
// go throught links and add current get query to each link
</script>
<style scoped></style>
