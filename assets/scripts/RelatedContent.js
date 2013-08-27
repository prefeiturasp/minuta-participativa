/*global define */
define(['jquery', 'spinjs', 'Config', 'easyModal'], function (jQuery, Spinner, Config) {
    'use strict';

    var RelatedContent = {
        init : function () {
            var self = this;
            jQuery('a[rel="comentario-conteudo-relacionado"]').each (function (k,v) {
                var relatedContent = v;

                jQuery(relatedContent).on('click', function (e) {
                    e.preventDefault();

                    self.loadContent(e.currentTarget);
                });

                /*if (relatedContent.className === 'mapa') {
                    console.log('mapa');
                } else if (relatedContent.className === 'quadro') {
                    console.log('quadro');
                }*/
            });
        },
        loadContent : function (elTarget) {
            var urlContent = elTarget.href,
                box = jQuery('#modal-content-box');

            box.trigger('openModal');

            jQuery.ajax({ url : urlContent, beforeSend : function () {
                box.html('');
                var spinConfig = {};
                jQuery.extend(spinConfig,Config.spinjs);
                spinConfig.color = '#fff';
                var spinner = new Spinner(spinConfig).spin(document.getElementById('modal-content-box'));
            }}).done(function (data) {
                box.html(data);
            });

            this.bindActions();
        }
    };

    return RelatedContent;
});