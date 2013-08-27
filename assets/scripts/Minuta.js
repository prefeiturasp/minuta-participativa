/*global define */
define(['jquery', 'Comments', 'RelatedContent', 'Proposals', 'spinjs', 'introjs', 'Config', 'cookie'], function (jQuery, Comments, RelatedContent, Proposals, Spinner, introJs, Config) {
    'use strict';

    var  Minuta = {
        introJsObj : null,

        init : function () {
            Comments.init();
            Proposals.init();
            RelatedContent.init();

            this.introJsObj = introJs().setOptions({
                'nextLabel': 'Próximo',
                'prevLabel': 'Anterior',
                'skipLabel': 'Ignorar',
                'doneLabel': 'Fechar'
            }).onexit(function() {
                jQuery.cookie('sawHelp', 'SIM', { expires: 21 });
            });

            if ($.cookie('sawHelp') !== 'SIM') {
                this.introJsObj.start();
            }

            jQuery('.menubar .secondary li a.help-button').on('click', this.startIntro);
            this.loadComplementaryText();
        },

        loadComplementaryText : function () {
            var el = jQuery('.sidebar .sidebox.sub-featured .text-content'),
                url = el.data('url');

            jQuery.ajax({
                beforeSend:function () {
                    el.append('<div id="loading"></div>');
                    var target = document.getElementById('loading');
                    new Spinner(Config.spinjs).spin(target);
                },
                dataType: 'json',
                url: url
            }).done(function (page) {
                el.html(page.content);
            });
        },

        startIntro : function (e) {
            e.preventDefault();

            jQuery('.comment-pp:eq(0)').attr('data-intro', 'Estes são os trechos da Minuta de Anteprojeto de Lei. O texto foi desenvolvido pela Prefeitura de São Paulo considerando as propostas feitas pela população da cidade. Ao clicar sobre um trecho, você verá uma atualização do lado direito do site. Clique em próximo e entenda.');
            jQuery('.comment-pp:eq(0)').attr('data-step', '2');
            jQuery('.comment-pp:eq(0)').attr('data-position', 'top');

            Minuta.introJsObj.start();
        }
    };

    return Minuta;
});