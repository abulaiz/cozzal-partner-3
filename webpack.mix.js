const mix = require('laravel-mix');
const _res = 'resources/js/view';
const _assets = 'public/js/view';
/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

// mix.js('resources/js/app.js', 'public/js')
//    .sass('resources/sass/app.scss', 'public/css');

// mix.js(_res+'/bank/index.js', _assets+'/bank/index.js').sourceMaps()
// mix.js(_res+'/booking_via/index.js', _assets+'/booking_via/index.js').sourceMaps()
// mix.js(_res+'/apartment/index.js', _assets+'/apartment/index.js').sourceMaps()
// mix.js(_res+'/unit/index.js', _assets+'/unit/index.js').sourceMaps()
// mix.js(_res+'/unit/owner.js', _assets+'/unit/owner.js').sourceMaps()
// mix.js(_res+'/unit/calendar.js', _assets+'/unit/calendar.js').sourceMaps()
// mix.js(_res+'/unit/manage.js', _assets+'/unit/manage.js').sourceMaps()
// mix.js(_res+'/cash/index.js', _assets+'/cash/index.js').sourceMaps()
// mix.js(_res+'/expenditure/create.js', _assets+'/expenditure/create.js').sourceMaps()
// mix.js(_res+'/expenditure/index.js', _assets+'/expenditure/index.js').sourceMaps()
// mix.js(_res+'/expenditure/owner.js', _assets+'/expenditure/owner.js').sourceMaps()
mix.js(_res+'/expenditure/approval.js', _assets+'/expenditure/approval.js').sourceMaps()
// mix.js(_res+'/booking/create.js', _assets+'/booking/create.js').sourceMaps()
// mix.js(_res+'/booking/index.js', _assets+'/booking/index.js').sourceMaps()
// mix.js(_res+'/reservation/confirmed.js', _assets+'/reservation/confirmed.js').sourceMaps()
// mix.js(_res+'/reservation/canceled.js', _assets+'/reservation/canceled.js').sourceMaps()
// mix.js(_res+'/reservation/report.js', _assets+'/reservation/report.js').sourceMaps()
// mix.js(_res+'/payment/index.js', _assets+'/payment/index.js').sourceMaps()
// mix.js(_res+'/payment/invoice.js', _assets+'/payment/invoice.js').sourceMaps()
// mix.js(_res+'/payment/report.js', _assets+'/payment/report.js').sourceMaps()
// mix.js(_res+'/payment/paid.js', _assets+'/payment/paid.js').sourceMaps()
// mix.js(_res+'/dashboard/index.js', _assets+'/dashboard/index.js').sourceMaps()
// mix.js(_res+'/dashboard/owner.js', _assets+'/dashboard/owner.js').sourceMaps()
// mix.js(_res+'/reservation/invoice.js', _assets+'/reservation/invoice.js').sourceMaps()
mix.js(_res+'/sidebar.js', _assets+'/sidebar.js').sourceMaps()