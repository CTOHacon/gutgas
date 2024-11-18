import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import sassGlobImports from 'vite-plugin-sass-glob-import';
import AutoImport from 'unplugin-auto-import/vite';
import cssPurge from 'vite-plugin-purgecss';

export default defineConfig({
	plugins: [
		sassGlobImports(),
		laravel({
			input: 'resources/js/app.ts',
			ssr: 'resources/js/ssr.ts',
			refresh: true
		}),
		cssPurge({
			contentFunction: sourceInputFile => {
				if (vuePath.test(sourceInputFile)) {
					return [sourceInputFile.replace(vuePath, '.vue')];
				}
				return [
					'resources/js/**/*.vue',
					'resources/views/**/*.blade.php'
				];
			},
			defaultExtractor(content) {
				if (content.startsWith('<template')) {
					content = `${content.split('</template')[0]}</template>`;
				}
				return content.match(/[\w-/:]+(?<!:)/g) || [];
			}
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
	}
});
