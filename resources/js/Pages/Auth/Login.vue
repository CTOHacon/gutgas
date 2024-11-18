<script setup lang="ts">
import BaseButton from '@/Components/BaseButton.vue';
import BaseCheckboxField from '@/Components/BaseCheckboxField.vue';
import BaseFormInput from '@/Components/BaseFormInput.vue';
import PageHeadSpacer from '@/Components/PageHeadSpacer.vue';
import WebsitePage from '@/Layouts/WebsitePage.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps<{}>();

const form = useForm({
	identifier: '',
	password: '',
	remember: false
});

const submit = () => {
	form.post(route('login'), {
		onFinish: () => form.reset('password')
	});
};
</script>

<template>
	<WebsitePage>
		<PageHeadSpacer />
		<div class="container">
			<form class="login-form p-16 mt-88 mb-88" @submit.prevent="submit">
				<h2 class="fs-h4 fw-800 mb-20">Login</h2>
				<BaseFormInput
					type="identifier"
					placeholder="Identifier"
					:error-message="form.errors.identifier"
					v-model="form.identifier"
					required
				/>

				<BaseFormInput
					type="password"
					class="mb-20"
					placeholder="Password"
					:error-message="form.errors.password"
					v-model="form.password"
					required
				/>
				<BaseCheckboxField
					label="Remember me"
					class="mb-20"
					name="remember"
					v-model="form.remember"
				/>
				<Link
					:href="route('password.request') || ''"
					class="mb-20 color-secondary"
				>
					Forgot your password?
				</Link>
				<BaseButton
					class="ph-24 pv-8"
					:class="{ 'opacity-25': form.processing }"
					:disabled="form.processing"
				>
					Log in
				</BaseButton>
			</form>
		</div>
	</WebsitePage>
</template>

<style scoped lang="scss">
.login-form {
	max-width: 300px;
	margin-left: auto;
	margin-right: auto;
	border-radius: 4px;
	background: linear-gradient(
		146.9deg,
		rgba(255, 75, 68, 0.22) 0%,
		rgba(255, 75, 68, 0.23) 100%
	);

	display: flex;
	flex-direction: column;
	position: relative;
	& > * {
		position: relative;
		z-index: 1;
	}
	&::before {
		content: '';
		position: absolute;
		z-index: 1;
		top: 1px;
		left: 1px;
		right: 1px;
		bottom: 1px;
		border-radius: 4px;

		background: linear-gradient(
				135deg,
				rgba(236, 35, 71, 0.12) 0%,
				rgba(224, 46, 38, 0) 100%
			),
			#0f0f10;
	}
}
</style>
