<template>
	<!-- Sidebar -->
	<div class="bg-light">
		<div class="py-2 d-none d-md-flex flex-column admin-sidebar sticky-top">
			<Link
				:href="route('admin.home')"
				class="d-flex align-items-center p-3 mb-1 text-decoration-none"
			>
				<i class="bi bi-box fs-3 me-2" />
				<span class="fs-5 fw-semibold">Gutgas</span>
			</Link>
			<LocaleSelect class="mb-1" />
			<ul
				class="nav nav-pills flex-column flex-nowrap mb-auto admin-sidebar__menu"
			>
				<template v-for="item in adminSidebarMenuData">
					<li v-if="item.permited">
						<Link
							:href="item.link || ''"
							class="nav-link link-body-emphasis"
							:class="{ active: item.active }"
						>
							<i
								v-if="item.icon"
								:class="['bi', item.icon, 'me-2']"
							/>
							{{ item.title }}
						</Link>
					</li>
				</template>
			</ul>
			<!-- user and logout -->
			<div class="" v-if="user">
				<a
					href="#"
					class="nav-link d-flex align-items-center justify-content-center text-center border-top p-2 bg-primary text-white rounded"
					data-bs-toggle="dropdown"
				>
					<i class="bi bi-person-circle me-2" />
					{{ user.name }}
				</a>
				<ul class="dropdown-menu dropdown-menu-dark text-small shadow">
					<!-- <li>
						<a class="dropdown-item" href="#"> Settings </a>
					</li>
					<li>
						<a class="dropdown-item" href="#"> Profile </a>
					</li>
					<li>
						<hr class="dropdown-divider" />
					</li> -->
					<li>
						<Link
							:href="route('logout') || ''"
							method="post"
							as="button"
							class="dropdown-item"
						>
							Sign out
						</Link>
					</li>
				</ul>
			</div>
		</div>
	</div>
</template>

<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import useAdminSidebarNavigation from '@/modules/useAdminNavigation';
import useAuth from '@/modules/useAuth';
import LocaleSelect from './LocaleSelect.vue';

const { user } = useAuth();
const { items: adminSidebarMenuData } = useAdminSidebarNavigation();
</script>

<style scoped lang="scss">
.admin-sidebar {
	max-height: 100vh;
	height: 100%;
	&__menu {
		overflow: auto;
		flex: 1;
	}
}
</style>
