// load all the things

    // Gulp
    var gulp = require('gulp');

    // Sass/CSS stuff
    var sass = require('gulp-sass');
    var prefix = require('gulp-autoprefixer');
    var minifycss = require('gulp-minify-css');

    // javascript
    var uglify = require('gulp-uglify');

    // Stats and things...
    var size = require('gulp-stats');
    require('gulp-stats')(gulp);

//

    gulp.task('sass', function() {
        gulp.src(['./assets/sass/*.scss'])
        .pipe(sass({
            includePaths: ['./assets/sass'],
            outputStyle: 'expanded'
        }))
        .pipe(prefix(
            "last 1 version", "> 1%", "ie 8", "ie 7"
            ))
        .pipe(gulp.dest('./assets/css'))
        .pipe(minifycss())
        .pipe(gulp.dest('./assets/css/min'));
    });

    gulp.task('uglify', function() {
        gulp.src('./assets/js/*.js')
        .pipe(uglify())
        .pipe(gulp.dest('./assets/js/min'));
    });

//

gulp.task('default', function() {

        // look at the style
        gulp.watch("./assets/sass/**/*.scss", function(event){
            gulp.run('sass');
        });
        // make ugly javascript more ugly
        gulp.watch("./assets/js/**/*.js", function(event){
            gulp.run('uglify');
        });
    });