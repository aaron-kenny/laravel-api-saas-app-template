window._ = require('lodash');

try {
    window.Popper = require('popper.js').default;
    window.$ = window.jQuery = require('jquery');

    require('bootstrap');
} catch (e) {}

window.axios = require('axios');
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';



// NAVIGATION APP
///////////////////////////////////////////////////
$('#navigationOpen').click(function(event) {
    event.preventDefault();
    $('#navigationApp').toggleClass('is-visible');
    $('#navigationOverlay').toggleClass('is-visible');
});
$('#navigationClose, #navigationOverlay').click(function(event) {
    event.preventDefault();
    $('#navigationApp, #navigationOverlay').removeClass('is-visible');
});
