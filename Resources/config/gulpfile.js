var gulp = require('gulp');
var mainBowerFiles = require('main-bower-files');
var jsmin = require('gulp-jsmin');
var rename = require('gulp-rename');

gulp.task('bower-files', function() {
	return gulp.src(mainBowerFiles(), { base: 'bower_components' })
		.pipe(gulp.dest('../public/vendor'));

});

gulp.task('minify', ['bower-files'], function() {
	return gulp.src([
			'../public/vendor/bootbox/bootbox.js',
			'../public/vendor/jquery-form/*.js',
			'../public/vendor/mustache/mustache.js',
			'!../public/vendor/**/*min.js'
		], { base: '../public/vendor' })
		.pipe(jsmin())
		.pipe(rename({suffix: '.min'}))
		.pipe(gulp.dest('../public/vendor'));
});

gulp.task('default', ['minify'])
