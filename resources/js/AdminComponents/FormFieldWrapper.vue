<template>
	<div class="border border-dark border-opacity-25 rounded">
		<!-- label -->
		<div
			v-if="props.label || props.dataGetter || props.dataSetter"
			class="d-flex justify-content-between align-items-center p-2 border-bottom"
		>
			<h3 class="mb-0">{{ props.label }}</h3>
			<div class="btn-group">
				<button
					class="btn btn-sm btn-outline-secondary"
					v-if="dataGetter"
					@click="onCopy"
				>
					Скопіювати
				</button>
				<button
					class="btn btn-sm btn-outline-secondary"
					v-if="dataSetter"
					@click="onPaste"
				>
					Втавити
				</button>
			</div>
		</div>
		<!-- slot -->
		<div class="p-2">
			<slot />
		</div>
	</div>
</template>

<script setup lang="ts">
import useMessages from '@/modules/useMessages';

const props = defineProps<{
	label?: string;
	dataGetter?: () => any;
	dataSetter?: (data: any) => void;
}>();

const { addMessage } = useMessages();

const onCopy = () => {
	if (!props.dataGetter) return;

	const data = props.dataGetter();
	addMessage({
		type: 'success',
		text: `Data from ${props.label} copied to clipboard`
	});
	navigator.clipboard.writeText(JSON.stringify(data));
};

const onPaste = () => {
	if (!props.dataSetter) return;

	navigator.clipboard.readText().then(text => {
		if (!props.dataSetter) return;
		try {
			const data = JSON.parse(text);
			if (
				!confirm(
					`Are you sure you want to paste this data \n ${JSON.stringify(
						data,
						null,
						4
					)} \n to ${props.label}?`
				)
			)
				return;
			props.dataSetter(data);
		} catch (e) {
			alert('Invalid data');
		}
	});
};
</script>

<style scoped></style>
