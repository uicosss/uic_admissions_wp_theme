const { series, src, dest, watch, parallel } = require('gulp');
const { resolve } = require('path');
const panini = require('panini');
const replace = require('gulp-replace');
const cache = require('gulp-cache');
const clean = require('gulp-clean');
const { readJSON, exists } = require('fs-extra');
const rename = require('gulp-rename');
const imagemin = require('gulp-imagemin');
const imageminMozjpeg = require('imagemin-mozjpeg');
const browserSync = require('browser-sync').create();

const ROOT_DIR = resolve(__dirname, '..')

const globs = {
	php: [
		resolve(ROOT_DIR, '*.php'),
		resolve(ROOT_DIR, 'inc/**/*.php'),
		resolve(ROOT_DIR, 'template-parts/**/*.php')
	],
	bundledCSS: resolve(ROOT_DIR,  'dist/css/**/*.css'),
	bundledJS: resolve(ROOT_DIR,  'dist/js/**/*.js'),
	builtPaniniHbs: resolve(ROOT_DIR, 'dist/prototype/**/*.hbs'),
	builtPaniniHtml: resolve(ROOT_DIR, 'dist/prototype/**/*.html'),
	images: resolve(ROOT_DIR,  'images/**/*.+(png|jpeg|jpg|svg|ico)'),
	prototype: resolve(ROOT_DIR,  'styleguide/**/*.hbs'),
	panini: resolve(ROOT_DIR,  'styleguide/pages/**/*.+(html|hbs)')
};

const manifestPath = resolve(ROOT_DIR, 'dist/manifest.json');

const destPaths = {
	root: resolve(ROOT_DIR,  'dist/'),
	css: resolve(ROOT_DIR,  'dist/css/'),
	js: resolve(ROOT_DIR,  'dist/js/'),
	images: resolve(ROOT_DIR,  'dist/images/'),
	panini: resolve(ROOT_DIR,  'dist/prototype/'),
};

const buildPanini = () => {
	panini.refresh();
	return (src(globs.panini)
		.pipe(panini({
			root: resolve(ROOT_DIR,  'styleguide/pages/'),
			// panini only understands these relative paths
			// absolute paths will not work!
			layouts: '../styleguide/layouts/',
			partials: [
				'../styleguide/components/',
				'../styleguide/partials/',
				'../styleguide/blocks/'
			],
			helpers: '../styleguide/helpers/',
			data: '../styleguide/data/'
		}))
		.pipe(dest(destPaths.panini))
	);
};

const sleep = (ms) => new Promise((resolve) => setTimeout(resolve, ms));

const patchPanini = () => new Promise(async (resolve, reject) => {
	while (await exists(manifestPath) === false) {
		console.log('waiting for manifest to generate...');
		await sleep(1000);
	}

	const manifest = await readJSON(manifestPath);
	(src(globs.builtPaniniHtml)
		.pipe(replace(/css\/vendor(?:\.[A-Za-z0-9]+)?\.css/g, manifest['vendor.css']))
		.pipe(replace(/css\/client(?:\.[A-Za-z0-9]+)?\.css/g, manifest['client.css']))
		.pipe(replace(/js\/runtime(?:\.[A-Za-z0-9]+)?\.js/g, manifest['runtime.js']))
		.pipe(replace(/js\/vendor(?:\.[A-Za-z0-9]+)?\.js/g, manifest['vendor.js']))
		// uses the panini js entrypoint which is the same as the client js entrypoint but has a few extra things which mutates
		// dom so the output works like the server normally does without requiring tedious maintenance of static html.
		// Specifically: icon-cards.ts splits the card text <span>into</span> <span>discrete</span> <span>span</span>  <span>tags</span>
		.pipe(replace(/js\/panini(?:\.[A-Za-z0-9]+)?\.js/g, manifest['panini.js']))
		.pipe(dest(destPaths.panini))
		.on('finish', resolve)
		.on('error', reject)
	);
});

const extRename = () => (
	(src(globs.builtPaniniHbs)
		.pipe(clean({ force: true }))
		.pipe(rename((path) => {
			path.extname = ".html";
		}))
		.pipe(dest(destPaths.panini))
	)
);

const buildImages = () => (
    (src(globs.images)
        .pipe(
            cache(
                imagemin([
                    imageminMozjpeg({ quality: 75, progressive: true }),
                    imagemin.optipng({ optimizationLevel: 5 }),
                    imagemin.svgo({
                        plugins: [{ removeViewBox: true }, { cleanupIDs: false }],
                    })
                ])
            )
        )
        .pipe(dest(destPaths.images))
    )
);

const cleanBuildDirectory = () => (
    (src(destPaths.root, { read: true, allowEmpty: true })
        .pipe(clean({ force: true }))
    )
);

exports.buildImages = buildImages;

exports.buildPanini = series(
    // cleanBuildDirectory,
    buildPanini,
    extRename,
	patchPanini,
    buildImages
);



exports.watchPanini = series(
    buildPanini,
    extRename,
	patchPanini,
    buildImages,
	parallel(
		() => browserSync.init({
			proxy: 'http://uic-admissions-2023.lndo.site',
			open: false
		}),
		() => watch([
			globs.prototype
		], series(
			buildPanini,
			extRename,
			patchPanini
		)).on('change', () => browserSync.reload()),
		() => watch([
			globs.images,
		], buildImages).on('change', () => browserSync.reload()),
		() => watch([
			// watch for webpack to drop new files
			globs.bundledCSS,
			globs.bundledJS,
			manifestPath
		], patchPanini).on('change', () => browserSync.reload()),
		() => watch([
			// also watch for edits to php files
			...globs.php
		], extRename /** essentially NOOP */).on('change', () => browserSync.reload()),
	)
)
