<template>
	<AdminLayout>
		<div class="row">
			<div class="col-9">
				<KeepAlive>
					<template v-if="currentTab === 'contacts'">
						<FormFieldWrapper label="Phone" class="mb-1">
							<input
								type="text"
								placeholder="Телефон"
								class="form-control"
								v-model="form.globalSettings.phone"
							/>
						</FormFieldWrapper>
						<FormFieldWrapper label="Робочий Час" class="mb-1">
							<input
								type="text"
								placeholder="Робочий Час"
								class="form-control"
								v-model="form.globalSettings.workTime"
							/>
						</FormFieldWrapper>
						<FormFieldWrapper label="Email" class="mb-1">
							<input
								type="text"
								placeholder="Email"
								class="form-control"
								v-model="form.globalSettings.email"
							/>
						</FormFieldWrapper>
						<FormFieldWrapper label="Whatsapp" class="mb-1">
							<input
								type="text"
								placeholder="Whatsapp"
								class="form-control"
								v-model="form.globalSettings.whatsapp"
							/>
						</FormFieldWrapper>
						<FormFieldWrapper label="Facebook" class="mb-1">
							<input
								type="text"
								placeholder="Facebook"
								class="form-control"
								v-model="form.globalSettings.facebook"
							/>
						</FormFieldWrapper>
						<FormFieldWrapper label="Instagram" class="mb-1">
							<input
								type="text"
								placeholder="Instagram"
								class="form-control"
								v-model="form.globalSettings.instagram"
							/>
						</FormFieldWrapper>
						<FormFieldWrapper label="Telegram" class="mb-1">
							<input
								type="text"
								placeholder="Telegram"
								class="form-control"
								v-model="form.globalSettings.telegram"
							/>
						</FormFieldWrapper>
						<FormFieldWrapper
							class="mb-3"
							label="Контактна Інформація"
						>
							<ContactsEditBlock
								v-model="form.globalSettings.contactItems"
							/>
						</FormFieldWrapper>
					</template>
				</KeepAlive>
				<KeepAlive>
					<template v-if="currentTab === 'header'">
						<FormFieldWrapper label="Меню В Хедері" class="mb-1">
							<div
								class="card mb-1"
								v-for="item in form.globalSettings.headerMenu"
							>
								<div class="card-body">
									<div class="d-flex gap-1">
										<div class="flex-grow-1">
											<input
												type="text"
												placeholder="Заголовок"
												class="form-control"
												v-model="item.title"
											/>
											<input
												type="text"
												placeholder="Посилання"
												class="form-control mt-1"
												v-model="item.url"
											/>
										</div>
										<button
											class="btn btn-danger w-auto"
											@click="
												form.globalSettings.headerMenu =
													form.globalSettings.headerMenu.filter(
														i => i !== item
													)
											"
										>
											<i class="bi bi-trash"></i>
										</button>
									</div>
								</div>
							</div>
							<button
								class="btn btn-success mt-1"
								@click="
									form.globalSettings.headerMenu.push({
										title: '',
										url: ''
									})
								"
							>
								Додати
							</button>
						</FormFieldWrapper>
					</template>
				</KeepAlive>
				<KeepAlive>
					<template v-if="currentTab === 'footer'">
						<FormFieldWrapper label="Текст у Футері" class="mb-1">
							<textarea
								class="form-control"
								v-model="form.globalSettings.footerText"
							></textarea>
						</FormFieldWrapper>
						<FormFieldWrapper
							label="Меню в Футері"
							class="mb-1"
							:data-getter="() => form.globalSettings.footerMenu"
							:data-setter="
								value =>
									(form.globalSettings.footerMenu = value)
							"
						>
							<FooterMenuEdit
								v-model="form.globalSettings.footerMenu"
							/>
						</FormFieldWrapper>
					</template>
				</KeepAlive>
				<KeepAlive>
					<template v-if="currentTab === 'pages'">
						<FormFieldWrapper label="Slug Каталогу Товарів">
							<input
								type="text"
								placeholder="Slug Каталогу Товарів"
								class="form-control"
								v-model="
									form.globalSettings.productsCatalogSlug
								"
							/>
						</FormFieldWrapper>
						<FormFieldWrapper
							:dataGetter="
								() => form.globalSettings.ourAdvantages
							"
							:dataSetter="
								value =>
									(form.globalSettings.ourAdvantages = value)
							"
							label="Переваги"
						>
							<AdvantagesEditBlock
								v-model="form.globalSettings.ourAdvantages"
							/>
						</FormFieldWrapper>
					</template>
				</KeepAlive>
				<KeepAlive>
					<template v-if="currentTab === 'microdata'">
						<FormFieldWrapper
							label="Додаткові Глобальні Мікродані"
							class="mb-1"
						>
							<textarea
								class="form-control"
								rows="10"
								v-model="form.globalSettings.extraMicrodata"
							></textarea>
						</FormFieldWrapper>
					</template>
				</KeepAlive>
			</div>
			<div class="col-3">
				<div class="sticky-top">
					<div class="p-2 bg-light">
						<button
							@click="currentTab = 'contacts'"
							class="btn w-100 mb-1"
							:class="{
								'btn-outline-primary':
									currentTab !== 'contacts',
								'btn-primary': currentTab === 'contacts'
							}"
						>
							Контакти
						</button>
						<button
							@click="currentTab = 'header'"
							class="btn w-100 mb-1"
							:class="{
								'btn-outline-primary': currentTab !== 'header',
								'btn-primary': currentTab === 'header'
							}"
						>
							Хедер
						</button>
						<button
							@click="currentTab = 'footer'"
							class="btn w-100 mb-1"
							:class="{
								'btn-outline-primary': currentTab !== 'footer',
								'btn-primary': currentTab === 'footer'
							}"
						>
							Футер
						</button>
						<button
							@click="currentTab = 'pages'"
							class="btn w-100 mb-1"
							:class="{
								'btn-outline-primary': currentTab !== 'pages',
								'btn-primary': currentTab === 'pages'
							}"
						>
							Сторінки
						</button>
						<button
							@click="currentTab = 'microdata'"
							class="btn w-100 mb-1"
							:class="{
								'btn-outline-primary':
									currentTab !== 'microdata',
								'btn-primary': currentTab === 'microdata'
							}"
						>
							Мікродані
						</button>
					</div>
					<button
						class="btn btn-success w-100 mt-3"
						@click="onSubmit"
					>
						Зберегти
					</button>
				</div>
			</div>
		</div>
	</AdminLayout>
</template>

<script setup lang="ts">
import AdvantagesEditBlock from '@/AdminComponents/ContentBlocks/AdvantagesEditBlock.vue';
import ContactsEditBlock from '@/AdminComponents/ContentBlocks/ContactsEditBlock.vue';
import FooterMenuEdit from '@/AdminComponents/FooterMenuEdit.vue';
import FormFieldWrapper from '@/AdminComponents/FormFieldWrapper.vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import useMessages from '@/modules/useMessages';
import { TAdvantageCardProps } from '@/types/TAdvantageCardProps';
import { TContactItemProps } from '@/types/TContactItemProps';
import { TFooterMenuSection } from '@/types/TFooterMenu';
import { TGlobalSetting } from '@/types/TGlobalSetting';
import { THeaderMenuItem } from '@/types/THeaderMenu';
import { getGlobalSetting } from '@/utils/getGlobalSetting';
import { useForm } from '@inertiajs/vue3';
import { onMounted } from 'vue';
import { watch } from 'vue';
import { ref } from 'vue';

const props = defineProps<{
	globalSettings: TGlobalSetting[];
}>();

const { addMessage } = useMessages();

const currentTab = ref<
	'contacts' | 'header' | 'footer' | 'pages' | 'microdata'
>('contacts');
watch(currentTab, () => {
	localStorage.setItem('currentGlobalSettingsTab', currentTab.value);
});

onMounted(() => {
	const tab = localStorage.getItem('currentGlobalSettingsTab');
	if (tab) {
		currentTab.value = tab as any;
	}
});

const form = useForm({
	globalSettings: {
		phone: getGlobalSetting('phone') ?? '',
		workTime: getGlobalSetting('workTime') ?? '',
		email: getGlobalSetting('email') ?? '',
		whatsapp: getGlobalSetting('whatsapp') ?? '',
		facebook: getGlobalSetting('facebook') ?? '',
		instagram: getGlobalSetting('instagram') ?? '',
		telegram: getGlobalSetting('telegram') ?? '',
		contactItems:
			getGlobalSetting<TContactItemProps[]>('contactItems') ?? [],
		ourAdvantages:
			getGlobalSetting<TAdvantageCardProps[]>('ourAdvantages') ?? [],
		headerMenu: getGlobalSetting<THeaderMenuItem[]>('headerMenu') ?? [],
		footerMenu: getGlobalSetting<TFooterMenuSection[]>('footerMenu') ?? [],

		footerText: getGlobalSetting('footerText') ?? '',

		extraMicrodata:
			JSON.stringify(getGlobalSetting('extraMicrodata')) ?? '',

		productsCatalogSlug: getGlobalSetting('productsCatalogSlug') ?? ''
	},
	translatableGlobalSettings: [
		'headerMenu',
		'workTime',
		'footerMenu',
		'footerText',
		'contactItems',
		'ourAdvantages'
	]
});

const onSubmit = () => {
	form.put(route('admin.globalSettings.updateAll'), {
		preserveScroll: true,

		onError: () => {
			Object.entries(form.errors).forEach(([key, value]) => {
				addMessage({
					type: 'error',
					text: value
				});
			});
		}
	});
};
</script>

<style scoped></style>
