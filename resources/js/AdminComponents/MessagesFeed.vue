<template>
	<div class="messages-feed">
		<div class="messages-feed__inner">
			<TransitionGroup name="card-fade">
				<div
					v-for="message in messages"
					:key="message.text"
					class="alert alert-primary"
					:class="{
						'alert-success': message.type === 'success',
						'alert-danger': message.type === 'error',
						'alert-warning': message.type === 'warning'
					}"
					role="alert"
				>
					{{ message.text }}
					<slot />
				</div>
			</TransitionGroup>
		</div>
	</div>
</template>

<script setup lang="ts">
import { IMessage } from '@/types/IMessage';
const props = defineProps<{
	messages: IMessage[];
}>();
</script>

<style scoped lang="scss">
.messages-feed {
	position: fixed;
	top: 2rem;
	left: 50%;
	max-width: 400px;
	width: 100%;
	transform: translateX(-50%);
	z-index: 99999;
	&__inner {
		position: relative;
		display: flex;
		flex-direction: column;
	}
}

.card-fade {
	&-enter-active,
	&-leave-active {
		transition: transform 0.3s, opacity 0.3s;
	}
	&-leave-active {
		position: absolute;
	}
	&-enter-from,
	&-leave-to {
		opacity: 0;
		transform: scale(0.8);
	}
	&-enter-to,
	&-leave-from {
		opacity: 1;
		transform: scale(1);
	}
	&-move {
		transition: all 0.3s ease-in-out;
	}
}
</style>
