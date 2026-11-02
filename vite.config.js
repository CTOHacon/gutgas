import { defineConfig } from 'vite';
import { builtinModules } from 'node:module';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import sassGlobImports from 'vite-plugin-sass-glob-import';
import AutoImport from 'unplugin-auto-import/vite';

// Vite 4 has no `isSsrBuild` config flag; detect the `vite build --ssr` invocation.
const isSsrBuild = process.argv.includes('--ssr');

export default defineConfig(() => ({
	plugins: [
		sassGlobImports(),
		laravel({
			input: 'resources/js/app.ts',
			ssr: 'resources/js/ssr.ts',
			refresh: true
		}),
		vue({
			template: {
				transformAssetUrls: {
					base: '/assets/',
					includeAbsolute: false
				}
			}
		}),
		AutoImport({
			imports: [
				{
					from: '@/modules/useTranslations',
					imports: ['useTranslations']
				},
				{
					from: '@/utils/hasWindow',
					imports: ['hasWindow']
				},
				{
					from: '@/utils/getMeta',
					imports: ['getMeta']
				}
			]
		})
	],
	optimizeDeps: {
		exclude: ['maska']
	},
	// Bundle all npm dependencies into the SSR build so `node bootstrap/ssr/ssr.js`
	// runs without node_modules (the production image ships only the built bundle).
	// Node built-ins (http, etc. used by @inertiajs/core's SSR server) must stay
	// external — Rollup can't bundle them and Node provides them at runtime.
	ssr: {
		noExternal: true
	},
	build: isSsrBuild
		? {
				rollupOptions: {
					external: [
						...builtinModules,
						...builtinModules.map(m => `node:${m}`)
					]
				}
		  }
		: {
				rollupOptions: {
					output: {
						// "-v2": one-off rename when purgecss was dropped (2026-11). Purge ran
						// after the content hash, so same-named CSS files had different content
						// and browsers kept stale copies (assets are cached immutable for 1y).
						// The suffix forces fresh URLs once; hashes now match content.
						assetFileNames: info =>
							info.name?.endsWith('.css')
								? 'assets/[name]-[hash]-v2[extname]'
								: 'assets/[name]-[hash][extname]'
					}
				}
		  }
}));
