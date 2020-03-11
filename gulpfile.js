'use strict';

var gulp   = require('gulp');
var watch  = require('gulp-watch');
var concat = require('gulp-concat');
var sass   = require('gulp-sass');
var rename = require('gulp-rename');

var paths = {
	scss_plugin: ['core/assets/css/scss/**/*.scss', 'core/assets/css/'],
	scss_frontend: ['core/assets/css/scss_frontend/**/*.scss', 'core/assets/css/'],
	js_frontend_plugin: ['core/assets/js/init.js', '**/*.frontend.js'],
	js_backend_plugin: ['core/assets/js/init.js', '**/*.backend.js'],
	js_global_plugin: ['core/assets/js/init-global.js', '**/*.global.js']
};

// SCSS Plugin
gulp.task( 'build_scss_plugin', function() {
	return gulp.src( paths.scss_plugin[0] )
		.pipe( sass().on( 'error', sass.logError ) )
		.pipe( gulp.dest( paths.scss_plugin[1] ) )
		.pipe( sass({outputStyle: 'compressed'}).on( 'error', sass.logError ) )
		.pipe( rename( './style.min.css' ) )
		.pipe( gulp.dest( paths.scss_plugin[1] ) );
});

// SCSS frontend
gulp.task( 'build_scss_frontend', function() {
	return gulp.src( paths.scss_frontend[0] )
		.pipe( sass().on( 'error', sass.logError ) )
		.pipe( rename( 'frontend.css' ) )
		.pipe( gulp.dest( paths.scss_frontend[1] ) )
});

// JS Plugin
gulp.task( 'build_js_backend', function() {
	return gulp.src( paths.js_backend_plugin )
		.pipe( concat( 'backend.min.js' ) )
		.pipe( gulp.dest( 'core/assets/js/' ) );
});

// JS GLOBAL Plugin
gulp.task( 'build_js_global', function() {
	return gulp.src( paths.js_global_plugin )
		.pipe( concat( 'global.min.js' ) )
		.pipe( gulp.dest( 'core/assets/js/' ) );
});

gulp.task( 'build_js_frontend', function() {
	return gulp.src( paths.js_frontend_plugin )
		.pipe( concat( 'frontend.min.js' ) )
		.pipe( gulp.dest( 'core/assets/js/' ) );
});

gulp.task( 'default', function() {
	gulp.watch( paths.scss_plugin[0], gulp.series('build_scss_plugin') );
	gulp.watch( paths.scss_frontend[0], gulp.series('build_scss_frontend') );
	gulp.watch( paths.js_backend_plugin, gulp.series('build_js_backend') );
	gulp.watch( paths.js_global_plugin, gulp.series('build_js_global') );
	gulp.watch( paths.js_frontend_plugin, gulp.series('build_js_frontend') );
});
