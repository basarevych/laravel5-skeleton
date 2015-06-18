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
            '*.js'
        ],
        'public/js/app.js'
    );

    mix.version([ "css/app.css", "js/app.js" ]);

    mix.copy("resources/assets/bower_components/bootstrap/fonts", "public/build/fonts");
});
