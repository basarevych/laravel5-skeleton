process.env.DISABLE_NOTIFIER = true;

var elixir = require('laravel-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Less
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function(mix) {
    mix.less('app.less');

    mix.scripts(
        [
            '../bower_components/jquery/dist/jquery.js',
            '../bower_components/bootstrap/dist/js/bootstrap.js',
            '../bower_components/jquery.cookie/jquery.cookie.js',
            '../bower_components/jquery-form/jquery.form.js',
            '../bower_components/moment/min/moment-with-locales.js',
            'src/*.js'
        ],
        'public/js/app.js'
    );

    mix.copy("resources/assets/bower_components/bootstrap/fonts", "public/fonts");
});

/*
 |--------------------------------------------------------------------------
 | Frontend Testing
 |--------------------------------------------------------------------------
 |
 | Testing task with Jasmine, jQuery and PhantomJS
 |
 */

var gulp = require('gulp');
var jasmine = require('gulp-jasmine-phantom');

gulp.task('test', function() {
    return gulp.src('resources/assets/js/tests/specs/*.js')
            .pipe(jasmine({
                integration: true,
                vendor: [
                    'public/js/app.js',
                    'node_modules/jasmine-jquery/lib/jasmine-jquery.js',
                ]
            }));
});
