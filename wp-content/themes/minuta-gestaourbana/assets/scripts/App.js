/*global define */
define(['jquery', 'Minuta', 'RelatedContent', 'easyModal'], function (jQuery, Minuta, RelatedContent) {
    'use strict';

    var App = {
        init : function () {
            // use app here
            jQuery('#modal-content-box').easyModal({
                top:0,
                autoOpen: false,
                overlayOpacity: 0.7,
                overlayColor: '#000',
                onOpen: function () {
                    jQuery('body').css({'overflow':'hidden','height':jQuery(window).height()});
                },
                onClose: function () {
                    jQuery('body').css({'overflow':'auto','height':'auto'});
                    jQuery('#modal-content-box').html('');
                }
            });
            this.setupPage();
        },
        setupPage : function () {
            var el = document.body;
            if (this.isHomePage(el)) {
                Minuta.init();
            } else if (this.isRelatedContentPage(el)) {
                RelatedContent.setupPage();
            }
        },
        isHomePage : function (el) {
            return this.isPage(el, 'home');
        },
        isRelatedContentPage : function (el) {
            return this.isPage(el, 'related-content');
        },
        isPage : function (el,pageName) {
            return (el.className.indexOf(pageName) > -1) && (el.className.indexOf('page') > -1);
        }
    };

    return App;
});