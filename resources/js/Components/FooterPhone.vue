<template>
	<p class="f-column">
		<strong class="fw-700 lh-150 fs-medium">{{
			getGlobalSetting('phone')
		}}</strong>
		<small class="fs-semi-small color-secondary">{{
			getGlobalSetting('workTime')
		}}</small>
	</p>
</template>

<script setup lang="ts">
import { getGlobalSetting } from '@/utils/getGlobalSetting';
</script>

<style scoped lang="scss"></style>
