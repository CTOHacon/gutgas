<template>
	<button
		@click="emit('click')"
		class="button"
		:class="[
			{
				active: active
			},
			variation
		]"
		:disabled="disabled"
	>
		<span class="inner">
			<slot />
		</span>
		<span
			class="dark-background"
			v-if="variation === 'dark-toggleable'"
		></span>
		<span class="default-background"></span>
		<span class="hover-background"></span>
		<span class="active-background"></span>
	</button>
</template>

<script setup lang="ts">
const props = defineProps<{
	disabled?: boolean;
	active?: boolean;
	variation?: 'default' | 'dark-toggleable' | 'primary-bordered';
	toggleableActive?: boolean | null;
}>();

const emit = defineEmits({
	click: () => true
});
</script>

<style scoped lang="scss">
.button {
	position: relative;
	display: flex;
	justify-content: center;
	align-items: center;
	cursor: pointer;
	transition: opacity 0.3s ease-in-out, color 0.3s ease-in-out;
	&:hover .default-background {
		opacity: 0;
	}
	&:hover .hover-background {
		opacity: 1;
		color: #fff;
	}
	&:active .default-background {
		opacity: 0;
	}
	&:active .hover-background {
		opacity: 0;
	}
	&:active .active-background {
		opacity: 1;
	}
	&:disabled {
		// opacity: 0.6;
		pointer-events: none;
		color: #838d97 !important;
		[class*='-background'] {
			border-color: #838d97 !important;
			background: none;
		}
	}
	&.dark-toggleable:not(.avtive) {
		[class*='-background'] {
			opacity: 0;
		}
		.dark-background {
			opacity: 1;
		}
		&:hover .default-background {
			opacity: 1;
		}
	}
	&.dark-toggleable.active {
		.hover-background {
			opacity: 0;
		}
		.default-background {
			opacity: 1;
		}
	}

	&.primary-bordered {
		color: #b73e38;
		.default-background {
			background: none;
			border: 1px solid rgba(255, 75, 68, 0.56);
		}
		.hover-background {
			background: none;
			border: 1px solid #ff4b44;
		}
		&:active {
			color: #fff;
		}
		.active-background {
			border: 1px solid rgba(255, 75, 68, 0.32);
			background: #b73e38;
		}
	}
}
.inner {
	position: relative;
	display: flex;
	justify-content: center;
	align-items: center;
	z-index: 2;
}
[class*='-background'] {
	position: absolute;
	top: 0;
	left: 0;
	width: 100%;
	height: 100%;
	border-radius: 106px;
	opacity: 0;
	transition: opacity 0.3s ease-in-out;
	backdrop-filter: blur(3px);
}
.dark-background {
	background: none;
	backdrop-filter: blur(3px);
	border: 1px solid #1a1a1a;
}
.default-background {
	opacity: 1;
	background: radial-gradient(
			100% 633.13% at 85.74% 0%,
			rgba(236, 35, 71, 0.04) 0%,
			rgba(224, 46, 38, 0) 100%
		),
		linear-gradient(
			266.55deg,
			rgba(241, 73, 66, 0.04) 0%,
			rgba(241, 87, 66, 0.04) 84.62%
		);
	border: 1px solid rgba(255, 75, 68, 0.32);
}
.hover-background {
	background: radial-gradient(
			100% 633.13% at 85.74% 0%,
			rgba(236, 35, 71, 0.32) 0%,
			rgba(224, 46, 38, 0) 100%
		),
		linear-gradient(
			266.55deg,
			rgba(241, 73, 66, 0.32) 0%,
			rgba(241, 87, 66, 0.32) 84.62%
		);
	border: 1px solid #ff4b44;
}
.active-background {
	background: radial-gradient(
			100% 633.13% at 85.74% 0%,
			rgba(236, 35, 71, 0.08) 0%,
			rgba(224, 46, 38, 0) 100%
		),
		linear-gradient(
			266.55deg,
			rgba(241, 73, 66, 0.08) 0%,
			rgba(241, 87, 66, 0.08) 84.62%
		);
	border: 1px solid rgba(255, 75, 68, 0.32);
	backdrop-filter: blur(3px);
}
</style>
