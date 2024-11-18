<template>
	<div class="btn-group">
		<button
			v-for="(localeName, locale) in locales"
			:key="locale"
			type="button"
			class="btn btn-sm"
			:class="{
				'btn-primary': locale === currentLocale,
				'btn-outline-secondary': locale !== currentLocale
			}"
			@click="
				form.get(route('admin.set-locale', locale), {
					preserveState: false,
					preserveScroll: true
				})
			"
		>
			{{ localeName }}
		</button>
	</div>
</template>

<script setup lang="ts">
import { router, useForm, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';

const { props } = usePage();

const form = useForm({
	locale: props.locale
});

const currentLocale = ref(props.locale);
const locales = ref(props.locales);
</script>

<style scoped></style>
