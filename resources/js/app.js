window._ = require('lodash');

try {
    window.Popper = require('popper.js').default;
    require('jquery');
    window.$ = window.jQuery = require('jquery');
    require('bootstrap');
} catch (e) { }

var Chart = require('chart.js');

var Turbolinks = require("turbolinks")
Turbolinks.start();
