
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

// require('./bootstrap');

window.Vue = require('vue');

window.VueGallery = require('vue-gallery');

window.Cropper = require('cropperjs');
window.Cropper = 'default' in window.Cropper ? window.Cropper['default'] : window.Cropper;
window.toastr = require('toastr');
require('dropzone');

require('nestable2');



// const app = new Vue({
//     el: '#app'
// });
