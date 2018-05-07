var gulp = require('gulp');
var concat = require('gulp-concat');
var minify = require('gulp-minify');
var cleanCss = require('gulp-clean-css');
var config = require('./gulp.config')();
var sass = require('gulp-sass');
var rename = require('gulp-rename');

// common
gulp.task('common-js', function () {
    return gulp.src(config.common_js)
        .pipe(concat('common.js'))
        .pipe(minify())
        .pipe(gulp.dest(config.dist));
});

gulp.task('common-css', function () {
    return gulp.src(config.common_css)
        .pipe(concat('style.css'))
        .pipe(cleanCss({level: {1: {specialComments: 0}}}))
        .pipe(gulp.dest(config.dist_twig));
});

gulp.task('homepage-css', function () {
    return gulp.src(config.homepage_css)
        .pipe(concat('homepage.css'))
        .pipe(cleanCss({level: {1: {specialComments: 0}}}))
        .pipe(gulp.dest(config.dist_twig));
});

gulp.task('login-css', function () {
    return gulp.src(config.login_css)
        .pipe(concat('login.css'))
        .pipe(cleanCss({level: {1: {specialComments: 0}}}))
        .pipe(gulp.dest(config.dist_twig));
});

gulp.task('register-css', function () {
    return gulp.src(config.register_css)
        .pipe(concat('register.css'))
        .pipe(cleanCss({level: {1: {specialComments: 0}}}))
        .pipe(gulp.dest(config.dist_twig));
});

gulp.task('advertise-css', function () {
    return gulp.src(config.advertise_css)
        .pipe(concat('advertise.css'))
        .pipe(cleanCss({level: {1: {specialComments: 0}}}))
        .pipe(gulp.dest(config.dist_twig));
});

gulp.task('project-css', function () {
    return gulp.src(config.project_css)
        .pipe(concat('project.css'))
        .pipe(cleanCss({level: {1: {specialComments: 0}}}))
        .pipe(gulp.dest(config.dist_twig));
});

gulp.task('ico-css', function () {
    return gulp.src(config.ico_css)
        .pipe(concat('ico.css'))
        .pipe(cleanCss({level: {1: {specialComments: 0}}}))
        .pipe(gulp.dest(config.dist_twig));
});

gulp.task('submission-css', function () {
    return gulp.src(config.submission_css)
        .pipe(concat('submission.css'))
        .pipe(cleanCss({level: {1: {specialComments: 0}}}))
        .pipe(gulp.dest(config.dist_twig));
});

gulp.task('stats-css', function () {
    return gulp.src(config.stats_css)
        .pipe(concat('stats.css'))
        .pipe(cleanCss({level: {1: {specialComments: 0}}}))
        .pipe(gulp.dest(config.dist_twig));
});

gulp.task('profile-css', function () {
    return gulp.src(config.profile_css)
        .pipe(concat('profile.css'))
        .pipe(cleanCss({level: {1: {specialComments: 0}}}))
        .pipe(gulp.dest(config.dist_twig));
});

gulp.task('listing-css', function () {
    return gulp.src(config.listing_css)
        .pipe(concat('listing.css'))
        .pipe(cleanCss({level: {1: {specialComments: 0}}}))
        .pipe(gulp.dest(config.dist_twig));
});

// new design
gulp.task('comp-sass', function () {
    return gulp.src(config.src + '/v2/css/*.scss')
        .pipe(sass())
        .pipe(rename('new-main.css'))
        .pipe(gulp.dest('public/dist'));
});

gulp.task('sass-layout', function () {
    return gulp.src(config.src + '/v2/compile/*.scss')
        .pipe(sass())
        .pipe(rename('layout.css'))
        .pipe(gulp.dest('public/dist'));
});

gulp.task('new-common-js', function () {
    return gulp.src(config.new_common_js)
        .pipe(concat('new-common.js'))
        .pipe(minify())
        .pipe(gulp.dest(config.dist));
});

gulp.task('new-layout-js', function () {
    return gulp.src(config.new_layout_js)
        .pipe(concat('new-layout.js'))
        .pipe(minify())
        .pipe(gulp.dest(config.dist));
});

gulp.task('new_full_css', function () {
    return gulp.src(config.new_full_css)
        .pipe(concat('new_full.css'))
        .pipe(cleanCss({level: {1: {specialComments: 0}}}))
        .pipe(gulp.dest(config.dist_twig));
});

gulp.task('new_layout_css', function () {
    return gulp.src(config.new_layout_css)
        .pipe(concat('new_layout.css'))
        .pipe(cleanCss({level: {1: {specialComments: 0}}}))
        .pipe(gulp.dest(config.dist_twig));
});

gulp.task('new_detail_css', function () {
    return gulp.src(config.new_detail_css)
        .pipe(concat('new_detail.css'))
        .pipe(cleanCss({level: {1: {specialComments: 0}}}))
        .pipe(gulp.dest(config.dist_twig));
});

gulp.task('new_detail_js', function () {
    return gulp.src(config.new_detail_js)
        .pipe(concat('new_detail.js'))
        .pipe(minify())
        .pipe(gulp.dest(config.dist));
});

gulp.task('people_css', function () {
    return gulp.src(config.people_css)
        .pipe(concat('people.css'))
        .pipe(cleanCss({level: {1: {specialComments: 0}}}))
        .pipe(gulp.dest(config.dist_twig));
});



gulp.task('scss', ['comp-sass', 'sass-layout']);

gulp.task('common', ['common-js', 'common-css', 'homepage-css', 'login-css', 'stats-css', 'profile-css', 'listing-css',
    'register-css', 'advertise-css', 'project-css', 'ico-css', 'submission-css', 'new-common-js', 'new-layout-js',
    'new_full_css', 'new_layout_css','new_detail_css','new_detail_js', 'people_css']);