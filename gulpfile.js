'use strict';
// Use node ->      v8.15.0

var gulp = require('gulp');
var sourcemaps = require('gulp-sourcemaps');
var spritesmith = require('gulp.spritesmith');
var sass =  require('gulp-sass')(require('sass'));
var minifycss =  require('gulp-uglifycss');
var autoprefixer = require('gulp-autoprefixer');
var mmq = require('gulp-merge-media-queries');
var gulpStylelint = require('gulp-stylelint');
var filter = require('gulp-filter');
var lineec = require('gulp-line-ending-corrector');
//var pug = require('gulp-pug');
var imagemin = require('gulp-imagemin');
var browserSync = require('browser-sync');
var jshint = require('gulp-jshint');
var stylish = require('jshint-stylish');
var uglify = require('gulp-uglify');
var plumber = require('gulp-plumber');
var rename = require('gulp-rename');
var include = require('gulp-include');
var notify = require('gulp-notify');


//import gulp from 'gulp';
// import sourcemaps from 'gulp-sourcemaps';
// import spritesmith from 'gulp.spritesmith';

// import sass from 'gulp-sass';
// import minifycss from 'gulp-uglifycss';
// import autoprefixer from 'gulp-autoprefixer';
// import mmq from 'gulp-merge-media-queries';
// import gulpStylelint from 'gulp-stylelint';
// import filter from 'gulp-filter';
// import lineec from 'gulp-line-ending-corrector';
// import pug from 'gulp-pug';
// import imagemin from 'gulp-imagemin';

// import browserSync from 'browser-sync';

// import jshint from 'gulp-jshint';
// import stylish from 'jshint-stylish';
// import uglify from 'gulp-uglify';
// import plumber from "gulp-plumber";
// import rename from "gulp-rename";
// import include from "gulp-include";
// import notify from "gulp-notify";


const AUTOPREFIXER_BROWSERS = [
    'last 2 version',
    '> 1%',
    'ie >= 9',
    'ie_mob >= 10',
    'ff >= 30',
    'chrome >= 34',
    'safari >= 7',
    'opera >= 23',
    'ios >= 7',
    'android >= 4',
    'bb >= 10'
];

'use strict';


// ========================================
// VARIABLES
// ========================================

const dirs = {
    root: '.',
    src: 'wp-content/themes/lms',
    dist: 'wp-content/themes/lms'
};

const htmlPaths = {
    src: '.',
    files: '*.html'
}

const imgPaths = {
    src: 'wp-content/themes/lms/assets/images/',
    dist: 'wp-content/themes/lms/assets/comp',
    spriteImg: 'wp-content/themes/lms/assets/images/icons/*.png',
    spriteRetina: 'wp-content/themes/lms/assets/images/icons/*@2x.png'
}

const sassPaths = {
    src: `${dirs.src}/assets/sass/`,
    files: `${dirs.src}/assets/sass/main.scss`,
    dist: `${dirs.src}/assets/css/`
};


const server = {
    proxyURL: 'http://lms.loc'
}



const isDevelopment = !process.env.NODE_ENV || process.env.NODE_ENV == 'development';

// ========================================
// PUG / HTML
// ========================================

gulp.task('compile-pug', () => {
    return gulp.src(pugPaths.src)
        .pipe(plumber((error) => {
            console.log(error);
            //this.emit('end');
        }))
        .pipe(pug({
            locals: {},
            pretty: true
        }))
        .pipe(gulp.dest(dirs.dest))
        .pipe(browserSync.reload({ stream: true }));
});

gulp.task('pug-rebuild', ['compile-pug'], () => {
    browserSync.reload();
});

// ========================================
// CSS
// ========================================

gulp.task('compile-styles', () => {

    gulp.src(sassPaths.files)
        .pipe(sourcemaps.init())
        .pipe(sass({
            errLogToConsole: true,
            //outputStyle: 'compact',
             outputStyle: 'compressed',
            // outputStyle: 'nested',
            // outputStyle: 'expanded',
            precision: 10
        }))
        .on('error', console.error.bind(console))
        .pipe(sourcemaps.write({ includeContent: false }))
        .pipe(sourcemaps.init({ loadMaps: true }))
        .pipe(autoprefixer(AUTOPREFIXER_BROWSERS))

    .pipe(sourcemaps.write('./'))
        //.pipe( lineec() ) // Consistent Line Endings for non UNIX systems.
        .pipe(gulp.dest(sassPaths.dist))

    .pipe(filter('**/*.css')) // Filtering stream to only css files
        .pipe(mmq({ log: true })) // Merge Media Queries only for .min.css version.

    .pipe(browserSync.stream()) // Reloads style.css if that is enqueued.

    .pipe(rename({ suffix: '.min' }))
        .pipe(minifycss({
            maxLineLen: 10
        }))
        .pipe(lineec()) // Consistent Line Endings for non UNIX systems.
        .pipe(gulp.dest(sassPaths.dist))

    .pipe(filter('**/*.css')) // Filtering stream to only css files
        //.pipe( browserSync.stream() )// Reloads style.min.css if that is enqueued.
        .pipe(browserSync.reload({
            stream: true
        }))
        .pipe(notify({ message: 'TASK: "styles" Completed! 💯', onLast: true }))

});


gulp.task('lint-css', function lintCssTask() {
    return gulp.src(dirs.src + '/assets/sass/**/*.scss')
        //.pipe(cached('css'))
        .pipe(gulpStylelint({
            reporters: [
                { formatter: 'string', console: true }
            ]
        }))
        .pipe(notify({ message: 'css lint task complete' }));
});

// ========================================
// JAVASCRIPT
// ========================================

// Jshint outputs any kind of javascript problems you might have
// Only checks javascript files inside /src directory
gulp.task('jshint', function() {
    return gulp.src(dirs.src + './js/src/*.js')
        .pipe(jshint())
        .pipe(jshint.reporter(stylish))
        .pipe(jshint.reporter('fail'));
})


// Concatenates all files that it finds in the manifest
// and creates two versions: normal and minified.
// It's dependent on the jshint task to succeed.
gulp.task('scripts', ['jshint'], function() {
    return gulp.src(dirs.src + '/assets/js/main.js')
        .pipe(include())
        .pipe(rename({ basename: 'scripts' }))
        .pipe(gulp.dest(dirs.dist + '/assets/js/dist'))
        // Normal done, time to create the minified javascript (scripts.min.js)
        // remove the following 3 lines if you don't want it
        .pipe(uglify())
        .pipe(rename({ suffix: '.min' }))
        .pipe(gulp.dest(dirs.dist + '/assets/js/dist'))
        .pipe(browserSync.reload({ stream: true }))
        .pipe(notify({ message: 'scripts task Completed! 💯' }));
});




// ========================================
// IMAGES
// ========================================

gulp.task('compress-images', () => {
    return gulp.src(imgPaths.src + '/**/*.{jpg,png,svg}')
        .pipe(imagemin([
            imagemin.jpegtran({ progressive: true }),
            imagemin.optipng({ optimizationLevel: 5 })
        ]))
        .pipe(gulp.dest(imgPaths.dist));
});

gulp.task('sprite', function() {
    var spriteData = gulp.src('wp-content/themes/lms/assets/images/icons/*.png').pipe(spritesmith({
        // This will filter out `fork@2x.png`, `github@2x.png`, ... for our retina spritesheet
        //   The normal spritesheet will now receive `fork.png`, `github.png`, ...
        retinaSrcFilter: ['wp-content/themes/lms/assets/images/icons/*@2x.png'],
        imgPath: '../images/sprite.png',
        retinaImgPath: '../images/sprite@2x.png',
        imgName: 'sprite.png',
        retinaImgName: 'sprite@2x.png',
        cssName: '_sprites.scss'
    }));

    spriteData.img.pipe(gulp.dest(imgPaths.src + ''))
    spriteData.css.pipe(gulp.dest(sassPaths.src + 'helpers'))

    .pipe(notify({ message: 'sprites task complete' }));
});
// ========================================
// SERVER
// ========================================

gulp.task('browser-sync', () => {
    browserSync({
        proxy: server.proxyURL,
        browser: true,
        notify: false,
        open: true
    });
});

gulp.task('browser-reload', () => {
    browserSync.reload();
});

// ##########################################
// LIST TASKS
// ##########################################

gulp.task("watch", function() {
    gulp.watch(dirs.src + '/assets/sass/**/*.scss', ['compile-styles']);
    gulp.watch(dirs.src + '/views/**/*.pug', ['pug-rebuild']);
    //gulp.watch([dirs.src + '/templates/**/*.html.twig', '**/*.yml'], ['browser-reload']);
    //gulp.watch(htmlPaths.files).on('change', browserSync.reload);
    gulp.watch('./**/*.php').on('change', browserSync.reload);
    //gulp.watch([dirs.src + '/templates/**/*.html.twig', '**/*.yml'], ['browser-reload']);
    //gulp.watch(imgPaths.src + "/**/*", ['sprite', 'compress-images'])
    gulp.watch(imgPaths.src + "/**/*", ['sprite'])
    gulp.watch([dirs.src + '/assets/js/**/*.js', dirs.src + '/!assets/js/dist/*.js'], ['scripts']);

});

//'compress-images',
gulp.task('dev', ['sprite', 'compile-styles', 'browser-sync', 'watch']);

gulp.task('default', ['compile-styles', 'browser-sync', 'watch']);
