require.config({
    paths: {
        jquery: '../bower_components/jquery/jquery',
        minimalect: '../bower_components/minimalect/jquery.minimalect',
        expander: '../bower_components/jquery-expander/jquery.expander',
        easyModal: '../bower_components/easyModal.js/jquery.easyModal',
        form: '../bower_components/jquery-form/jquery.form',
        spinjs: '../bower_components/spin.js/spin',
        moment: '../bower_components/moment/min/moment.min',
        moment_br: '../bower_components/moment/min/lang/pt-br',
        introjs : '../bower_components/intro.js/intro',
        cookie : '../bower_components/jquery.cookie/jquery.cookie',
        countable : '../bower_components/Countable/Countable'
    },

    shim : {
        expander: {
            deps : ['jquery'],
            exports : 'jQuery'
        },
        minimalect: {
            deps : ['jquery'],
            exports : 'jQuery'
        },
        easyModal: {
            deps : ['jquery'],
            exports : 'jQuery'
        },
        form: {
            deps : ['jquery'],
            exports : 'jQuery'
        },
        spinjs: {
            deps : ['jquery'],
            exports : 'jQuery'
        },
        cookie : {
            deps : ['jquery'],
            exports : 'jQuery'
        }
    }
});

require(['App', 'minimalect', 'expander', 'form'], function (App) {
    'use strict';
    //jQuery(document).on('load', function () {
        App.init();
    //});
});
