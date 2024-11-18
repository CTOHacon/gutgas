<template>
	<div class="admin-layout container-fluid">
		<div class="admin-layout__inner row">
			<AdminSidebar class="col-2" />
			<!-- Main Content -->
			<main
				role="main"
				class="main col-md-9 ml-sm-auto col-lg-10 p-2 px-4"
			>
				<Breadcrumbs />
				<MessagesFeed :messages="messages" />
				<slot />
			</main>
		</div>

		<KeepAlive>
			<Modal
				v-if="mediaGalleryModalOpened"
				@close="mediaGalleryModalOpened = false"
				title="Select Зображення(s)"
			>
				<MediaGallery
					:select-mode="true"
					:selection="mediaSelectionValue"
					:selection-limit="mediaGallerySelectionLimit"
					@submit-selected-files="mediaSelectionSubmitCallback"
				/>
			</Modal>
		</KeepAlive>
	</div>
</template>

<script setup lang="ts">
import 'bootstrap-icons/font/bootstrap-icons.css';
import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap';
import '@/styles/admin/style.scss';
import '@vueup/vue-quill/dist/vue-quill.snow.css';

import Breadcrumbs from '@/AdminComponents/Breadcrumbs.vue';
import AdminSidebar from '@/AdminComponents/AdminSidebar.vue';
import MessagesFeed from '@/AdminComponents/MessagesFeed.vue';
import useMessages from '@/modules/useMessages';
import Modal from '@/AdminComponents/Modal.vue';
import MediaGallery from '@/AdminComponents/Media/MediaGallery.vue';
import { useMediaSelectModal } from '@/modules/useMediaSelectModal';

const {
	mediaGalleryModalOpened,
	mediaSelectionValue,
	mediaSelectionSubmitCallback,
	mediaGallerySelectionLimit
} = useMediaSelectModal();

const { messages } = useMessages();
</script>

<style scoped lang="scss">
.admin-layout__inner {
	min-height: 100vh;
}
</style>
