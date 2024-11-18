<template>
	<div class="container-lg">
		<div class="separator mb-12"></div>
		<ToTopButton class="mb-12" />
		<div class="footer">
			<div class="pt-56 ph-88 pb-48">
				<div class="g-layout-12 mb-40 align-center">
					<div class="f- g-col-3 align-center g-large-mobile-col-12">
						<img src="@/assets/footer-logo.svg" alt="Gutgas" />
						<LocaleSelect class="ml-32 fs-medium" />
					</div>
					<div
						class="g-col-end-9 g-col-start-5 g-large-mobile-col-12"
					>
						<SocialLinksGroup class="fs-large" />
					</div>
					<div class="g-col-3 g-col-end-13 g-large-mobile-col-12">
						<FooterPhone class="text-right phone" />
					</div>
				</div>
				<div class="g-layout-12">
					<div
						class="g-col-3 g-tablet-col-12 fs-small color-secondary"
					>
						<p class="mb-16 lh-125">
							{{ getGlobalSetting('footerText') }}
						</p>
						<a
							:href="`mailto:${getGlobalSetting('email')}`"
							class="underline"
							>{{ getGlobalSetting('email') }}</a
						>
					</div>
					<div
						class="g-col-start-5 g-col-end-13 g-tablet-col-start-1 g-layout-6"
					>
						<ul
							class="color-secondary g-col-2 g-large-mobile-col-6 ls--1"
							v-for="(menuSection, index) in footerMenu"
							:key="index"
						>
							<li class="color-white fs-medium mb-12">
								{{ menuSection.name }}
							</li>
							<li
								v-for="(menuItem, index) in menuSection.items"
								:key="index"
							>
								<Link
									:href="menuItem.url"
									class="f- hover-underline mb-8"
									>{{ menuItem.title }}</Link
								>
							</li>
						</ul>
					</div>
				</div>
			</div>
			<div class="copyright color-secondary p-16 text-center fs-small">
				{{ new Date().getFullYear() }} Copyright | All Rights reserved
			</div>
		</div>
	</div>
</template>

<script setup lang="ts">
import LocaleSelect from './LocaleSelect.vue';
import SocialLinksGroup from './SocialLinksGroup.vue';
import ToTopButton from './ToTopButton.vue';
import FooterPhone from './FooterPhone.vue';
import { getGlobalSetting } from '@/utils/getGlobalSetting';
import { TFooterMenuSection } from '@/types/TFooterMenu';
import { Link } from '@inertiajs/vue3';

const footerMenu = getGlobalSetting<TFooterMenuSection[]>('footerMenu') ?? [];
</script>

<style scoped lang="scss">
@import '@/styles/variables.scss';
.separator {
	border-bottom: 1px solid #1a1a1a;
}
.footer {
	background: rgba($color: #fff, $alpha: 0.01);
	@media (max-width: $large-mobile-width) {
		margin-bottom: 90px;
	}
}

.copyright {
	background: rgba($color: #fff, $alpha: 0.04);
}

.phone {
	@media (max-width: $large-mobile-width) {
		text-align: left !important;
	}
}
</style>
