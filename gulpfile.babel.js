import { src, dest, watch, series, parallel } from 'gulp';
import yargs from 'yargs';
import gulpif from 'gulp-if';
import del from 'del';
import sass from 'gulp-sass';
import cleanCSS from 'gulp-clean-css';
import sourcemaps from 'gulp-sourcemaps';
import postcss from 'gulp-postcss';
import glob from 'gulp-css-globbing';
import autoprefixer from 'autoprefixer';
import imagemin from 'gulp-imagemin';
import uglify from 'gulp-uglify';
import browserSync from 'browser-sync';
import zip from 'gulp-zip';
import info from './package.json';
import project from './project.json';
import wpPot from 'gulp-wp-pot';
import minify from 'gulp-minify';

const PRODUCTION = yargs.argv.prod;
const server = browserSync.create();

const paths = {
	styles: {
		src: [
			'src/sass/**/*.scss',
		],
		dest: 'assets/css',
	},
	images: {
		src: 'src/images/**/*.{jpg,jpeg,png,svg,gif}',
		dest: 'assets/images',
	},
	others: {
		src: ['src/**/*','!src/{images,js,sass}','!src/{images,js,sass}/**/*'],
		dest: 'assets',
	},
	scripts: {
		src: 'src/js/**/*.js',
		dest: 'assets/js',
	},
};

export const styles = () => {
	return src(paths.styles.src)
		.pipe( glob({
			extensions: ['.scss']
		}))
		.pipe(gulpif(!PRODUCTION, sourcemaps.init()))
		.pipe(sass({ outputStyle: 'expanded'} ).on('error', sass.logError))
		.pipe(gulpif(PRODUCTION, postcss([ autoprefixer ])))
		.pipe(gulpif(PRODUCTION, cleanCSS({ compatibility: 'ie8' })))
		.pipe(dest(paths.styles.dest))
		.pipe(server.stream());
};


export const images = () => {
	return src(paths.images.src)
		.pipe(gulpif(PRODUCTION, imagemin()))
		.pipe(dest(paths.images.dest));
};

export const copy = () => {
	return src(paths.others.src)
		.pipe(dest(paths.others.dest));
};

export const clean = () => del(['dist']);

export const scripts = () => {
	return src(paths.scripts.src)
		.pipe(gulpif(PRODUCTION, uglify()))
		.pipe(minify({
			ext: {
				min: '.min.js'
			},
			ignoreFiles: ['-min.js']
		}))
		.pipe(dest(paths.scripts.dest));
};


export const serve = done => {
	server.init({
		proxy: project.projectUrl, // put your local website here
		notify: false,
	});
	done();
};

export const reload = done => {
	server.reload();
	done();
};

export const compress = () => {
	return src([
		'**/*',
		'!node_modules{,/**}',
		'!dist{,/**}',
		'!src{,/**}',
		'!.babelrc',
		'!.gitignore',
		'!.editorconfig',
		'!gulpfile.babel.js',
		'!package.json',
		'!package-lock.json',
		'!project.json'
	])
		.pipe(zip(`${info.name}.zip`))
		.pipe(dest('dist'));
};

export const pot = () => {
    return src('**/*.php')
        .pipe(
            wpPot({
                domain: 'boostifysizeguide',
                package: info.name,
            })
        )
        .pipe(dest(`languages/boostify-size-guide.pot`));
};

export const watchForChanges = () => {
	watch('src/sass/**/*.scss', styles);
	watch(paths.images.src, series(images, reload));
	watch(paths.others.src, series(copy, reload));
	watch(paths.scripts.src, series(scripts, reload));
    watch('**/*.php', reload);
};

export const dev = series(clean, parallel(styles, images, copy, scripts, pot), serve, watchForChanges);
export const build = series(clean, parallel(styles, images, copy, scripts), pot, compress);

export default dev;
