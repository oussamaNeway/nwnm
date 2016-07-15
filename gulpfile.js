var jshint = require('gulp-jshint');
var stylish = require('jshint-stylish');// jshint ignore:line
var gulp = require('gulp');
var sass = require('gulp-sass');
var exec = require('child_process').exec;
var less = require('gulp-less');
var minifyCSS = require('gulp-minify-css');
var rename = require("gulp-rename");
var moment = require('moment');
var rimraf = require('rimraf');
var install = require("gulp-install");
var concat = require('gulp-concat');
var chmod = require('gulp-chmod');

gulp.task('pre-commit', ['lint']);
gulp.task('sass-datatables', ['sass-datatables-colreorder', 'sass-datatables-reponsive']);

gulp.task('lint', function() {
    return gulp.src('./!(node_modules|migrations|coverage)/*.js')
    .pipe(jshint({lookup:true}))
    .pipe(jshint.reporter('jshint-stylish'))
        .pipe(jshint.reporter('fail'));
});

gulp.task('dependencies_install', function () {
    gulp.src(['./bower.json'])
    .pipe(install());
});

gulp.task('copy-less', function () {
    gulp.src('./public/bower_components/bootstrap/**/*')
    .pipe(gulp.dest('./public/assets/less/default'));
});

gulp.task('copy-css-color-value', function () {
    gulp.src('./fuel/vendor/netmessage/netmessage-core/src/js/CssColorValue.js')
    .pipe(gulp.dest('./public/assets/js/src/'));
});

gulp.task('copy-css-bootstrap-map', function () {
    gulp.src('./public/bower_components/bootstrap/dist/css/bootstrap.css.map')
    .pipe(gulp.dest('./public/assets/css/dist'));
});

gulp.task('concat-main-less', function () {
    gulp.src(['public/assets/less/brand-id.less', 'public/assets/less/default/less/bootstrap.less', 'public/assets/less/bootstrap-ending.less'])
    .pipe(concat('bootstrap.less'))
    .pipe(gulp.dest('public/assets/less/default/less/'));
});

gulp.task('concat-default-less', function () {
    gulp.src(['public/assets/less/default.variables.less', 'public/assets/less/preview.less'])
    .pipe(concat('default.variables.less'))
    .pipe(gulp.dest('public/assets/less/default/less/'));
});

gulp.task('save-previous-css', function () {
    gulp.src('./public/assets/css/dist/bootstrap.css')
    .pipe(rename(function (path) {
        path.extname = '.'+moment().format('YYYYMMDDhhmmss')+'.css';
    }))
    .pipe(gulp.dest('./public/assets/css/dist/'));
});

gulp.task('delete-current-css', function (callback) {
    rimraf('./public/assets/css/dist/bootstrap.css', function () {
        rimraf('./public/assets/css/dist/bootstrap.min.css', callback);
    });
});

gulp.task('purge-previous-css', function (callback) {
    rimraf('./public/assets/css/dist/bootstrap.*.css', function () {
        rimraf('./public/assets/css/dist/bootstrap.*.min.css', callback);
    });
});

gulp.task('less-to-css', function () {
    gulp.src('./public/assets/less/default/less/bootstrap.less')
    .pipe(less())
    .pipe(gulp.dest('./public/assets/css/dist/'))
    .pipe(minifyCSS())
    .pipe(rename({suffix: '.min'}))
    .pipe(gulp.dest('./public/assets/css/dist/'));
});

gulp.task('chmod-party', function () {
    gulp.src([
        './public/assets/less/*.less',
        './public/assets/css/dist/**/*',
        './public/assets/css/img/**/*',
    ])
    .pipe(chmod(777));
});

gulp.task('sass-datatables-colreorder', function () {
    gulp.src('./public/bower_components/datatables-colreorder/css/colReorder.bootstrap.scss')
    .pipe(sass().on('error', sass.logError))
    .pipe(gulp.dest('./public/bower_components/datatables-colreorder/css'));
});

gulp.task('sass-datatables-reponsive', function () {
    gulp.src('./public/bower_components/datatables-responsive/css/responsive.bootstrap.scss')
    .pipe(sass().on('error', sass.logError))
    .pipe(gulp.dest('./public/bower_components/datatables-responsive/css'));
});

gulp.task('npm_enforce', function (callback) {
   exec("npm config set save-exact true", function (err, stdout, stderr) {
       console.log(stdout);
       console.error(stderr);
       callback(err);
   }); 
});

gulp.task('default', function() {
  console.log('no default task :)');
});

gulp.task(
    'default-less', 
    [
        'copy-less',
        'concat-main-less',
        'concat-default-less',
        'save-previous-css', 
//        'delete-current-css', 
        'less-to-css',
//        'chmod-party'
    ], 
    function() {
        console.log('OK', 'defaultLess done');
    }
);

gulp.task(
    'install', 
    [
        'npm_enforce',
        'dependencies_install', 
        'copy-css-color-value', 
        'copy-css-bootstrap-map', 
        'default-less',
        'sass-datatables-colreorder',
        'sass-datatables-reponsive'
    ], 
    function() {
        console.log('OK', 'defaultLess done');
    }
);