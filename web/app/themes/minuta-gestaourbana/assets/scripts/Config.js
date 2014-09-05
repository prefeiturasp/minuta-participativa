/*global define, window */
define([], function () {
    'use strict';

    var Config = {
        // configuration, change things here
        'spinjs' : {
            lines: 11, // The number of lines to draw
            length: 0, // The length of each line
            width: 8, // The line thickness
            radius: 14, // The radius of the inner circle
            corners: 1, // Corner roundness (0..1)
            rotate: 38, // The rotation offset
            direction: 1, // 1: clockwise, -1: counterclockwise
            color: '#d7322f', // #rgb or #rrggbb
            speed: 1.2, // Rounds per second
            trail: 50, // Afterglow percentage
            shadow: false, // Whether to render a shadow
            hwaccel: false, // Whether to use hardware acceleration
            className: 'spinner', // The CSS class to assign to the spinner
            zIndex: 2e9, // The z-index (defaults to 2000000000)
            top: 'auto', // Top position relative to parent in px
            left: 'auto' // Left position relative to parent in px
        },
        templateUrl : window.templateUrl,
        blogUrl : window.blogUrl,

    };
    return Config;
        /*// start of main code
        init : function () {
            console.log(this.defaults);
        },

        // ... more methods and other code ...

        // this.defaultsuration changes

        set : function (o) {
            var reg = /\./g;
            if(this.isObj(o)){
                for(var i in o) {
                    if(i.indexOf('.')!== -1) {
                        var str = '["' + i.replace(reg,'"]["') + '"]';
                        var val = this.getValue(o[i]);
                        eval('this.defaults' + str + '=' + val);
                    } else {
                        this.findProperty(this.defaults,i,o[i]);
                    }
                }
            }
        },

        findProperty : function (o,p,v) {
            for(var i in o){
                if(this.isObj(o[i])){
                    this.findProperty(o[i],p,v);
                } else {
                    if (i === p) {
                        o[p] = v;
                    }
                }
            }
        },

        isObj : function (o) {
            return (typeof o === 'object' && typeof o.splice !== 'function');
        },
        getValue : function (v) {
            switch(typeof v){
            case 'string':
                return '"'+v+'"';
            case 'number':
                return v;
            case 'object':
                if (typeof v.splice === 'function') {
                    return '['+v+']';
                } else {
                    return '{'+v+'}';
                }
                break;
            case NaN:
                break;
            }
        }*/
    /*
        module.init({
            'container':'header',
            'CSS.classes.active':'now',
            'timeout':1000
        });
    */
});