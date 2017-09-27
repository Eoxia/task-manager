var gulp = require('gulp');
var please = require('gulp-pleeease');
var less = require('gulp-less');
var watch = require('gulp-watch');
var plumber = require('gulp-plumber');
var rename = require("gulp-rename");
var cssnano = require('gulp-cssnano');
var concat = require('gulp-concat');
var glob = require("glob");
var uglify = require('gulp-uglify');
var sass = require('gulp-sass');

var paths = {
	scss_backend: [ 'core/asset/css/scss/**/*.scss', 'core/asset/css/' ],
	frontend_js: ['core/asset/js/lib/init.js', 'core/asset/js/lib/*.js', '**/*.frontend.js'],
  all_js: ['core/asset/js/lib/init.js', '**/*.backend.js'],
};

gulp.task('build_scss_backend', function() {
	gulp.src(paths.scss_backend[0])
		.pipe( sass().on( 'error', sass.logError ) )
		.pipe(please({
			minifier: false,
			autoprefixer: {"browsers": ["last 40 versions", "ios 6", "ie 9"]},
			pseudoElements: true,
			sass: true,
		}))
		.pipe( gulp.dest( paths.scss_backend[1] ) );
});


gulp.task('js', function() {
	return gulp.src(paths.all_js)
		.pipe(concat('backend.min.js'))
		.pipe(gulp.dest('core/asset/js/'))
})

gulp.task('js_frontend', function() {
	return gulp.src(paths.frontend_js)
		.pipe(concat('frontend.min.js'))
		.pipe(gulp.dest('core/asset/js/'))
})


gulp.watch(paths.scss_backend[0], ["build_scss_backend"]);
gulp.watch(paths.all_js, ["js"]);
gulp.watch(paths.frontend_js, ["js_frontend"]);
