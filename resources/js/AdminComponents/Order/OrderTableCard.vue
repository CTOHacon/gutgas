<template>
	<tr>
		<td>
			{{ order.id }}
		</td>
		<td>
			{{ order.client_name }}
		</td>
		<td>
			<strong>{{ cartTotal }}</strong>
			uah
		</td>
		<td>
			<button class="btn" :class="`btn-${order.status_color}`">
				{{ order.status_name }}
			</button>
		</td>
		<td class="text-right">
			<div class="btn-group">
				<Link
					:href="route('admin.order.edit', order.id) || ''"
					class="btn btn-primary border-dark"
				>
					Керувати
				</Link>
				<button class="btn btn-danger" @click="onDelete">
					Видалити
				</button>
			</div>
		</td>
	</tr>
</template>

<script setup lang="ts">
import { Link, router } from '@inertiajs/vue3';
import { TOrder } from '@/types/TOreder';
import { computed } from 'vue';
const props = defineProps<{
	order: TOrder;
}>();

const cartTotal = computed(() => {
	let cart = props.order.cart_content;

	if (typeof cart === 'string') {
		cart = JSON.parse(cart);
	}

	return cart.reduce((acc, item) => {
		return acc + item.price * item.quantity;
	}, 0);
});

const onDelete = () => {
	if (confirm('Are you sure?')) {
		router.delete(route('admin.order.destroy', props.order.id));
	}
};
</script>

<style scoped></style>
