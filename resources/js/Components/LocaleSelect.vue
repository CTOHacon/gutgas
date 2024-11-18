<template>
	<div
		class="language-switch"
		:class="{
			active: isActive
		}"
		@click="toggle"
	>
		<div class="top f-centered">
			<span class="current-option">
				{{ localeCaption(currentLocale) }}
			</span>
			<svg
				class="carret"
				:class="{ active: isActive }"
				width="10"
				height="6"
				viewBox="0 0 10 6"
			>
				<path
					d="M1 1L5 5L9 1"
					fill="#B73E38"
					stroke="#B73E38"
					stroke-linecap="round"
					stroke-linejoin="round"
				/>
			</svg>
		</div>
		<BaseVerticalSlidable
			class="options background-semi-black"
			:class="{ active: isActive, reverse }"
			:is-opened="isActive"
		>
			<ul class="options__inner" ref="optionsInnerRef">
				<li
					v-for="locale in otherLocales"
					:key="locale[0]"
					class="option"
				>
					<a
						:href="
							currentUrl.replace(
								usePage().props.locale,
								locale[0]
							)
						"
						class="option-link"
						@click.prevent="
							router.get(
								currentUrl.replace(
									usePage().props.locale,
									locale[0]
								)
							);
							toggle();
						"
					>
						{{ localeCaption(locale[0]) }}</a
					>
				</li>
			</ul>
		</BaseVerticalSlidable>
	</div>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import BaseVerticalSlidable from '@/Components/BaseVerticalSlidable.vue';
import { router, useForm, usePage } from '@inertiajs/vue3';
import { onMounted } from 'vue';
import localeCaption from '@/utils/localeCaption';

const {
	props: { locales, locale: currentLocale }
} = usePage();

const props = defineProps<{
	reverse?: boolean;
}>();

const reverse = ref(props.reverse ?? false);

const form = useForm({
	locale: currentLocale
});
const otherLocales = ref(
	Object.entries(locales).filter(([key]) => key !== currentLocale)
);
const isActive = ref(false);

const currentUrl = ref('');
onMounted(() => {
	currentUrl.value = window.location.href.replace(window.location.origin, '');
});

const toggle = () => {
	isActive.value = !isActive.value;
};
</script>

<style scoped lang="scss">
.language-switch {
	position: relative;
	user-select: none;
	cursor: pointer;
	width: 56px;
}
.carret {
	transition: transform 0.3s ease-in-out;
	&.active {
		transform: scaleY(-1);
	}
}
.top {
	display: flex;
	align-items: center;
}
.current-option {
	margin-right: 8px;
}
.options {
	position: absolute;
	top: 100%;
	min-width: calc(100% + 16px);
	text-align: center;
	left: 0;
	border-radius: 2px;
	margin-top: -4px;
	margin-left: -8px;
	overflow: hidden;
	height: 0;
	transition: height 0.2s ease-in-out, transform 0.2s ease-in-out;
	user-select: none;
	&__inner {
		display: block;
		padding: 8px;
	}
	&.active {
		transform: translateY(12px);
	}
	&.reverse {
		bottom: 100% !important;
		top: unset !important;
	}
	&.reverse.active {
		transform: translateY(-12px);
	}
}
.option {
	transition: color 0.2s ease-in-out;
	display: block;
	&:hover {
		color: #b73e38;
	}
	&:not(:last-child) {
		margin-bottom: 4px;
	}
}
</style>
