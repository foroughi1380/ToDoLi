let mix = require('laravel-mix');

mix.js('resources/home/app.js', 'public/js/home.js')
    .js('resources/profile/app.js' , 'public/js/profile.js')
