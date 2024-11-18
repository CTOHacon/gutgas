import { createApp, h, DefineComponent } from 'vue';
import { createInertiaApp, router } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
	title: title => `${title}`,
	resolve: name =>
		resolvePageComponent(
			`./Pages/${name}.vue`,
			import.meta.glob<DefineComponent>('./Pages/**/*.vue')
		),
	setup({ el, App, props, plugin }) {
		createApp({ render: () => h(App, props) })
			.use(plugin)
			.use(ZiggyVue, Ziggy)
			.mount(el);
	},
	progress: {
		color: '#f24942'
	}
});

// on each router change. If the route contains /admin and previous route does not, then we reload the page
const location = hasWindow() ? window.location : null;
router.on('success', to => {
	if (
		(to.detail.page.url.includes('/admin') &&
			!location?.pathname.includes('/admin')) ||
		(location?.pathname.includes('/admin') &&
			!to.detail.page.url.includes('/admin'))
	) {
		location?.reload();
	}
});
