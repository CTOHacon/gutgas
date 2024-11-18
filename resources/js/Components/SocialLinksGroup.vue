<template>
	<div class="social-links-group">
		<BaseSocialLink
			v-if="whatsapp"
			:href="whatsapp"
			:icon="whatsappIcon"
			name="Whatsapp"
		/>
		<BaseSocialLink
			v-if="facebook"
			:href="facebook"
			:icon="facebookIcon"
			name="Facebook"
		/>
		<BaseSocialLink
			v-if="instagram"
			:href="instagram"
			:icon="instagramIcon"
			name="Instagram"
		/>
		<BaseSocialLink
			v-if="telegram"
			:href="telegram"
			:icon="telegramIcon"
			name="Telegram"
		/>
	</div>
</template>

<script setup lang="ts">
import { getGlobalSetting } from '@/utils/getGlobalSetting';
import BaseSocialLink from './BaseSocialLink.vue';

import whatsappIcon from '../assets/social-whatsapp.svg';
import facebookIcon from '../assets/social-facebook.svg';
import instagramIcon from '../assets/social-instagram.svg';
import telegramIcon from '../assets/social-telegram.svg';

const whatsapp = getGlobalSetting('whatsapp');
const facebook = getGlobalSetting('facebook');
const instagram = getGlobalSetting('instagram');
const telegram = getGlobalSetting('telegram');

const socialLinks = [
	{
		name: 'Whatsapp',
		href: '#',
		icon: '../assets/social-whatsapp.svg'
	},
	{
		name: 'Facebook',
		href: '#',
		icon: '../assets/social-facebook.svg'
	},
	{
		name: 'Instagram',
		href: '#',
		icon: '../assets/social-instagram.svg'
	},
	{
		name: 'Telegram',
		href: '#',
		icon: '../assets/social-telegram.svg'
	}
];
</script>

<style scoped lang="scss">
.social-links-group {
	display: flex;
	gap: 0.44em;
}
</style>
