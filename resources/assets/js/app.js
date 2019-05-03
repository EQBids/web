
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('jquery');
require('./bootstrap');
require('parsleyjs');
require('select2');
var alertify=require('alertifyjs');
require('jquery-mask-plugin');
var Inputmask = require('inputmask');
require( 'datatables.net' );
require( 'datatables.net-bs4' );
require( 'datatables.net-responsive-bs4' );
require( 'summernote/dist/summernote-bs4');
var Switch = require('weatherstar-switch');
window.Switch=Switch;
Window.dropzone=require('dropzone');

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        'X-Requested-With': 'XMLHttpRequest',
    }
});

$.extend( true, $.fn.dataTable.defaults, {
    responsive:true,
} );

/**
 *
 * notifications
 */
if (typeof window.alertify_notifications !== 'undefined'){
    alertify.set('notifier','position', 'top-right');
    for(var index=0;index<window.alertify_notifications.length;index++){
        var notification = window.alertify_notifications[index];
        alertify.notify(notification.text,notification.type,notification.wait);
    }
}


/**
 * frontend validations
 */
$('.phone-mask').mask('(000) 000-0000');

window.Parsley.on('form:validated', function(){
    $('select').on('select2:select', function(evt) {

        $(this).parsley().validate();
    });
});

Inputmask({ regex: "(\\d{6})" +
                    "|" +
                    "(" +
                        "([a-zA-Z]\\d[a-zA-Z])" +
                            "\\s?" +
                        "(\\d[a-zA-Z]\\d)" +
                    ")" }).mask('.zip');


var el = document.querySelector('.checkbox-switch');
var mySwitch = new Switch(el);

/**
 * highlight current menus
 *
 */

if (typeof window.highlight_url == 'undefined'){
   var highlight_url = window.location.href.replace(/\/$/, "");
}else{
    var highlight_url = window.highlight_url;
}

$('#menu a[href="'+highlight_url+'"]').parent('li').addClass('active');

if (typeof window.highlight_sidebar == 'undefined'){
    var highlight_sidebar = window.location.href.replace(/\/$/, "");
}else {
    var highlight_sidebar = window.highlight_sidebar;
}
$('#sidebar a[href="'+highlight_sidebar+'"]').addClass('active');
$('#sidebar a[href="' + highlight_sidebar + '"]').parents('.collapse').addClass('show');
$('#sidebar a[href="'+highlight_sidebar+'"] + div.collapse').addClass('show');

if (typeof stepwizard_step != 'undefined') {
    $('.stepwizard .stepwizard-step button[data-step="'+stepwizard_step+'"]').removeClass('btn-secondary').addClass('button')
}



if (typeof window.highlight_categories == 'undefined'){
    var highlight_categories = window.location.href.replace(/\/$/, "");
}else {
    var highlight_categories = window.highlight_categories;
}
$('#categories_menu a[href="'+highlight_categories+'"]').addClass('active');
$('#categories_menu a[href="' + highlight_categories + '"]').parents('div').prev('a').addClass('active');


//window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
/*
Vue.component('example-component', require('./components/ExampleComponent.vue'));

const app = new Vue({
    el: '#app'
});
*/