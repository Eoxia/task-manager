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
  styles: ['core/asset/css/scss/style.scss'],
  all_styles: ['core/asset/css/scss/*.scss'],
	frontend: ['core/asset/css/frontend.scss'],
	frontend_js: ['core/asset/js/lib/init.js', 'core/asset/js/lib/*.js', '**/*.frontend.js'],
  all_js: ['core/asset/js/lib/init.js', '**/*.backend.js'],
	cssPath: "core/asset/css/"
};

gulp.task('build', function() {
	gulp.src(paths.styles)
		.pipe( sass().on( 'error', sass.logError ) )
		.pipe(please({
			minifier: false,
			autoprefixer: {"browsers": ["last 40 versions", "ios 6", "ie 9"]},
			pseudoElements: true,
			sass: true,
			out: 'style.min.css'
		}))

		.pipe( gulp.dest( paths.cssPath ) );
});

gulp.task('build_frontend', function() {
  return gulp.src(paths.styles)
    .pipe(plumber())
    .pipe(less())
    .pipe(please({
        minifier: false,
        autoprefixer: {"browsers": ["last 40 versions", "ios 6", "ie 9"]},
        rem: true,
        pseudoElements: true,
        mqpacker: false,
        opacity : true,
        filters : true
      }))
    // .pipe(cssnano())
    .pipe(rename("frontend.css"))
    .pipe(gulp.dest('core/asset/css/'))
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


gulp.watch(paths.all_styles, ["build"]);
gulp.watch(paths.all_js, ["js"]);
gulp.watch(paths.frontend_js, ["js_frontend"]);
