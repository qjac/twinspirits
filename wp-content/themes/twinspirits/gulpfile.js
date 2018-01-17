const gulp = require('gulp');
const plumber = require('gulp-plumber');
const sass = require('gulp-sass');
const postcss = require('gulp-postcss');
const autoprefixer = require('autoprefixer');
const groupmq = require('gulp-group-css-media-queries');

const SASS_SOURCES = [
    './*.scss', // This picks up our style.scss file at the root of the theme
    'sass/**/*.scss', // All other Sass files in the /sass directory
];

/**
 * Compile Sass files
 */
gulp.task('compile:sass', function() {
    gulp.src(SASS_SOURCES, {base: './'})
        .pipe(plumber()) // Prevent termination on error
        .pipe(sass({
            indentType: 'tab',
            indentWidth: 1,
            outputStyle: 'expanded', // Expanded so that our CSS is readable
        })).on('error', sass.logError)
        .pipe(postcss([
            autoprefixer({
                browsers: ['last 2 versions'],
                cascade: false,
            })
        ]))
        .pipe(groupmq()) // Group media queries!
        .pipe(gulp.dest('.')) // Output compiled files in the same dir as Sass sources
});

gulp.watch(SASS_SOURCES, ['compile:sass']);

/**
 * Default task executed by running `gulp`
 */
gulp.task('default', ['compile:sass']);
