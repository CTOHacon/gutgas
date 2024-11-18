<template>
	<div class="phone-field" @click="valueInput?.focus()">
		<span class="country-code">+380</span>
		<template v-for="(groupIndexes, groupIndex) in digitGroupsIndexes">
			<div class="digits-group">
				<template
					v-for="(digit, groupLocalDigitIndex) in digits.filter(
						(_, index) => groupIndexes.includes(index)
					)"
					:key="groupLocalDigitIndex"
				>
					<span
						class="digit"
						:class="{
							filled: digit !== emptyDigit,
							current:
								currentDigitIndex ===
									groupIndexes[groupLocalDigitIndex] &&
								isActive
						}"
					>
						{{ digit }}
					</span>
				</template>
			</div>
			<span
				class="digit-separator"
				v-if="groupIndex !== digitGroups.length - 1"
				>-</span
			>
		</template>
		<input
			class="input"
			type="tel"
			ref="valueInput"
			@focus="isActive = true"
			@blur="isActive = false"
			:value="modelValue"
			@input="onInput"
		/>
	</div>
</template>

<script setup lang="ts">
import { computed, ref, watch } from 'vue';

const props = defineProps<{
	modelValue: string;
}>();

const valueInput = ref<HTMLInputElement | null>(null);

watch(
	() => props.modelValue,
	() => {
		const digitsArray = props.modelValue.split('');
		digits.value = digits.value.map((digit, index) => {
			return digitsArray[index] || emptyDigit;
		});
	}
);

const onInput = () => {
	let newValue = valueInput.value?.value || '';

	newValue = newValue.replace(/\D/g, '').slice(0, maxDigitsCount);

	if (valueInput.value?.selectionStart === maxDigitsCount) {
		currentDigitIndex.value = valueInput.value?.selectionStart - 1;
	} else {
		currentDigitIndex.value = valueInput.value?.selectionStart || 0;
	}
	emit('update:modelValue', newValue);
};
const isActive = ref(false);

const currentDigitIndex = ref(0);
const emptyDigit = '–';
const maxDigitsCount = 9;
const digits = ref<string[]>(Array(maxDigitsCount).fill(emptyDigit));
const digitGroupsIndexes = [
	[0, 1],
	[2, 3, 4],
	[5, 6],
	[7, 8]
];
const digitGroups = computed(() => {
	return digitGroupsIndexes.map(group => {
		return group.map(digit => digits.value[digit]);
	});
});

const emit = defineEmits(['update:modelValue']);
</script>

<style scoped lang="scss">
@import '@/styles/variables.scss';
.phone-field {
	padding: 0.61em 1.1em;
	display: flex;
	align-items: center;
	cursor: text;
	position: relative;
	input {
		width: 0;
		height: 0;
		opacity: 0;
	}
}
.country-code {
	margin-right: 0.72em;
	@media (max-width: $large-mobile-width) {
		margin-right: 0.5em;
	}
}

.digits-group {
	display: flex;
}
.digit {
	opacity: 0.4;
	&:not(:last-child) {
		margin-right: 2px;
	}
	position: relative;
	display: block;

	&::before {
		content: '';
		position: absolute;
		bottom: 0;
		left: 0;
		width: 100%;
		height: 2px;
		background-color: #f24942;
		transform-origin: bottom;
		display: none;
		animation: digitUnderlineBlinking 2s infinite;
	}
	&.current {
		opacity: 1;
		&::before {
			display: block;
		}
	}
	&.filled {
		opacity: 1;
		margin-right: 0;
	}
}
.digit-separator {
	margin: 0 0.5rem;
}
@keyframes digitUnderlineBlinking {
	0% {
		opacity: 0;
	}
	50% {
		opacity: 1;
	}
	100% {
		opacity: 0;
	}
}
</style>
