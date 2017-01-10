const elixir = require('laravel-elixir');

require('laravel-elixir-vue-2');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for your application as well as publishing vendor resources.
 |
 */
elixir((mix) => {
    mix.sass('app.scss')
       .webpack('./public/js/fancybox/source/jquery.fancybox.pack.js')
       .scripts(['./resources/assets/js/app.js', './resources/assets/js/main.js']);
});
//elixir(function(mix) {
//    mix.sass('app.scss')
//       .webpack('./resources/assets/js/app.js', './resources/assets/js/main.js')
//       .scripts('resources/assets/js/app.js', './resources/assets/js/main.js');
//});
//elixir(function(mix) {
//    mix.styles([
//        'style.css'
//    ]).scripts('partials/fancybox/source/jquery.fancybox.pack.js');
//});
