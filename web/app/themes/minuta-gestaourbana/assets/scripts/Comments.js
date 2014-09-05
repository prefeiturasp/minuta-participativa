/*global define */
define(['jquery', 'FormComments', 'spinjs', 'Config', 'moment', 'moment_br'],
    function (jQuery, FormComments, Spinner, Config, moment) {
    'use strict';

    var Comments = {
        //init : function () {
        /* Useful values when building urls, using PHP to generate them. */
        blogUrl : Config.blogUrl,
        templateUrl : Config.templateUrl,

        /* Paragraph that is currently loaded in the comments list. It starts
         * empty and is filled by the `loadComments()' function. */
        loadedParagraph : null,
        loadedFeaturedParagraph : null,


        formatDate : function (date) {
            moment.lang('pt-br');
            return moment(date).startOf('hour').fromNow();
        },

        formatOpinion : function (opinion) {
            switch (opinion) {
            case 'concordo':
                return '<i class="icon-agree" title="Concordo com o Dispositivo"></i>';
            case 'concordo-com-ressalvas':
                return '<i class="icon-so-so" title="Concordo com o Dispositivo com ressalvas"></i>';
            case 'nao-concordo':
                return '<i class="icon-not-agree" title="Discordo do Dispositivo"></i>';
            default:
                return opinion;
            }
        },

        formatProposal : function (proposal) {
            switch (proposal) {
            case 'alteracao':
                return '<i class="icon-text-change" title="Alteração no texto"></i>';

            case 'exclusao':
                return '<i class="icon-text-remove" title="Exclusão do dispositivo"></i>';

            case 'retorno':
                return '<i class="icon-text-return" title="Proposta de nova redação"></i>';

            case 'acrescimo':
                return '<i class="icon-text-add" title="Acréscimo de um novo dispositivo"></i>';

            default:
                return proposal;
            }
        },

        formatUser : function (user) {
            var nome = '', organizacao = '';

            if (user.first_name !== '') {
                nome = user.first_name+' '+user.last_name;
            } else {
                nome = user.nickname;
            }

            if ( (typeof user.organization !== 'undefined' ) && (user.organization !== '') ) {
                organizacao = ' ('+user.organization+')';
            }

            return nome + organizacao;
        },

        filterContent : function (content) {
            while (content.indexOf('\n') !== -1) {
                content = content.replace('\n', '<br />');
            }
            return content;
        },

        loadCommentsFeatured : function (paragraphId, postId) {
            var query = '{"method":"get_paragraph_comments_featured","params":["' +
                paragraphId + '",' + postId + ']}';
            var container = jQuery('#commentFeaturedContainer');

            /* Testing if the paragraph asked to be loaded is already the
             * current one, if it's not we set the loaded paragraph. */
            if (this.loadedFeaturedParagraph === paragraphId) {
                return;
            } else {
                this.loadedFeaturedParagraph = paragraphId;
            }

            container.html('');

            var target = document.getElementById('commentFeaturedContainer');
            var spinner = new Spinner(Config.spinjs).spin(target);
            var self = this;

            /* Getting comment list */
            jQuery.getJSON(this.blogUrl, {dialogue_query:query}, function (comments) {
                var i;
                container.html('');
                /* No comments yet */
                if (comments.length === 0) {
                    container.html('<p>Não há propostas para o dispositivo selecionado.</p>');
                    return;
                }

                /* Building the html of the comment list */
                for (i = 0; i < comments.length; i++) {
                    var obj = comments[i];

                    var texto = '';
                    var infobar = self.formatOpinion(obj.meta.opiniao);

                    if (obj.meta.proposta) {
                        infobar += self.formatProposal(obj.meta.proposta);
                    }

                    if (obj.meta.contribuicao) {
                        texto += self.filterContent(obj.meta.contribuicao);
                    }

                    if (obj.meta.justificativa) {
                        texto += self.filterContent(obj.meta.justificativa);
                    }

                    var li = jQuery('<p>'+texto+'</p>');
                    li.appendTo(container);
                }
                container.expander({ expandPrefix: ' (&hellip;) ', expandText: 'Mostrar mais', userCollapseText: 'Mostrar menos', slicePoint: 250 });
                self.startProposals(paragraphId);
            });
        },

        loadComments : function (paragraphId, postId) {
            var query = '{"method":"get_paragraph_comments","params":["' +
                paragraphId + '",' + postId + ']}';
            var container = jQuery('#commentContainer');

            /* Testing if the paragraph asked to be loaded is already the
             * current one, if it's not we set the loaded paragraph. */
            if (this.loadedParagraph === paragraphId) {
                return;
            } else {
                this.loadedParagraph = paragraphId;
            }
            container.html('');
            var target = document.getElementById('commentContainer');
            var spinner = new Spinner(Config.spinjs).spin(target);
            var self = this;
            /* Getting comment list */
            jQuery.getJSON(this.blogUrl, {dialogue_query:query}, function (comments) {
                var i;

                container.html('');
                /* No comments yet */
                if (comments.length === 0) {
                    container.html(
                        '<div class="comment">' +
                        'Não há propostas para o dispositivo selecionado.' +
                        '</div>');
                    return;
                }

                /* Building the html of the comment list */
                for (i = 0; i < comments.length; i++) {
                    var obj = comments[i];

                    var texto = '';
                    var infobar = self.formatOpinion(obj.meta.opiniao);

                    if (obj.meta.proposta) {
                        infobar += self.formatProposal(obj.meta.proposta);
                    }

                    if (obj.meta.contribuicao) {
                        texto += self.filterContent('<strong>Contribuição</strong>: '+obj.meta.contribuicao+'<br/>');
                    }

                    if (obj.meta.justificativa) {
                        texto += self.filterContent('<strong>Justificativa</strong>: '+obj.meta.justificativa+'<br/>');
                    }

                    var li = jQuery(
                        '<div class="comment">'+
                        '    <p class="datetime">'+
                        '        <span class="date">'+self.formatDate(obj.comment_date)+'</span>'+
                        '    </p>'+
                        '    <p class="author">'+self.formatUser(obj.user_meta)+'</p>'+
                        '    <p class="infobar">'+
                        infobar +
                        '    </p>'+
                        '    <div class="text-comment">'+
                        texto+
                        '    </div>'+
                        '</div>');
                    li.appendTo(container);
                }
                container.find('.text-comment').expander({ expandPrefix: ' (&hellip;) ', expandText: 'Mostrar mais', userCollapseText: 'Mostrar menos', slicePoint: 250 });

                self.updateTotalComments(paragraphId);
            });
        },

        init : function () {
            var self = this;

            jQuery('.comment-pp').on('click', function () {
                var postId = jQuery('input[name=comment_post_ID]', jQuery(this)).val();
                var paragraphId =
                    jQuery('input[name=dialogue_comment_paragraph]', jQuery(this)).val();
                self.loadComments(paragraphId, postId);
                self.loadCommentsFeatured(paragraphId, postId);

                /* marking selected paragraph as the active, comments of this
                 * `selected' paragraph are going to be shown in the right
                 * column. */
                jQuery('.comment-pp').removeClass('active');
                jQuery(this).addClass('active');


                /* Moving the comments column to near the clicked post */
                var margin = jQuery(document).scrollTop()-90;
                jQuery('.sidebar').animate({'padding-top': margin});
            });

            /* seta a altura da div de comentarios (lateral direita) de acordo
                com a altura da div do post (lateral esquerda)
            */

            //jQuery("#commentContainer").height(jQuery('.post').height()-jQuery('#proposta').height()-jQuery('#navegaComments').height());

            /* Loading comments from the first paragraph */
            var postIdExpr = jQuery('input[name=comment_post_ID]');
            var paragraphIdExpr = jQuery('input[name=dialogue_comment_paragraph]');
            if (postIdExpr.length > 0 && paragraphIdExpr.length > 0) {
                var paragraphId = jQuery(paragraphIdExpr[0]);

                /* Marking comment paragraph as the active one */
                paragraphId.parents('div.comment-pp').addClass('active');

                /* Actually loading comments from the found paragraph */
                self.loadComments(paragraphId.val(), jQuery(postIdExpr[0]).val());
                self.loadCommentsFeatured(paragraphId.val(), jQuery(postIdExpr[0]).val());
            }
            FormComments.init();
        },

        startProposals : function (paragraphId) {
            //console.log(paragraphId);
        },

        updateTotalComments : function (paragraphId) {
            //jQuery('.featured-comment')
            console.log(paragraphId);
        }
    };

    return Comments;
});