/**
 * Concaténation automatique des fichiers .backend.js en backend.min.js
 * dans le dossier core/asset/js/.
 *
 * Concaténation automatique des fichiers .frontend.js en frontend.min.js
 * dans le dossier core/asset/js/.
 *
 * SCSS to CSS, Concaténation, minification, autoprefixer des scss se trouvant
 * dans le dossier path.scss_backend en backend.min.css dans le dossier
 * core/asset/css/.
 *
 * SCSS to CSS, Concaténation, minification, autoprefixer des scss se trouvant
 * dans le dossier path.scss_frontend en frontend.min.css dans le dossier
 * core/asset/css/.
 *
 * @since 0.1.0
 * @version 1.0.0
 */

var gulp         = require('gulp');
var watch        = require('gulp-watch');
var rename       = require("gulp-rename");
var concat       = require('gulp-concat');
var uglify       = require('gulp-uglify');
var sass         = require('gulp-sass');
var autoprefixer = require('gulp-autoprefixer');

var paths = {
	scss_plugin: ['core/assets/css/scss/**/*.scss', 'core/assets/css/'],
	js_frontend_plugin: ['core/assets/js/init.js', '**/*.frontend.js'],
	js_backend_plugin: ['core/assets/js/init.js', '**/*.backend.js'],
	js_global_plugin: ['core/assets/js/init-global.js', '**/*.global.js']
};

// Scss Backend
gulp.task( 'build_scss_plugin', function() {
	return gulp.src( paths.scss_plugin[0] )
		.pipe( sass( { 'outputStyle': 'expanded' } ).on( 'error', sass.logError ) )
		.pipe( autoprefixer({
			browsers: ['last 2 versions'],
			cascade: false
		}) )
		.pipe( gulp.dest( paths.scss_plugin[1] ) )
		.pipe( sass({outputStyle: 'compressed'}).on( 'error', sass.logError ) )
		.pipe( rename( './style.min.css' ) )
		.pipe( gulp.dest( paths.scss_plugin[1] ) );
});

// JS Backend
gulp.task('build_js_backend', function() {
	return gulp.src( paths.js_backend_plugin )
		.pipe(concat('backend.min.js'))
		.pipe(gulp.dest('core/assets/js/'))
});

// JS Frontend
gulp.task('build_js_frontend', function() {
	return gulp.src( paths.js_frontend_plugin )
		.pipe(concat('frontend.min.js'))
		.pipe(gulp.dest('core/assets/js/'))
});

// JS Global
gulp.task('build_js_global', function() {
	return gulp.src( paths.js_global_plugin )
		.pipe(concat('global.min.js'))
		.pipe( uglify() )
		.pipe(gulp.dest('core/assets/js/'))
});

gulp.task( 'default', function() {
	gulp.watch( paths.scss_plugin[0], gulp.series('build_scss_plugin') );
	gulp.watch( paths.js_backend_plugin, gulp.series('build_js_backend') );
	gulp.watch( paths.js_global_plugin, gulp.series('build_js_global') );
	gulp.watch( paths.js_frontend_plugin, gulp.series('build_js_frontend') );
});
