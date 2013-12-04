/*global define */
define(['jquery', 'countable'], function (jQuery, Countable) {
    'use strict';

    var FormComments = {

        /* Filled when a form is clicked and No! The user *CAN'T* select more
         * than one form at the same time. */
        SELECTED_P : null,

        /* Function to validate comment form */
        validateForm : function (formObj) {
            var counter = 0;

            var opinionType = jQuery('select[name=opiniao]', formObj).val();
            if (opinionType === 'concordo') {
                return true;
            } else {
                var contrib = jQuery('textarea[name=contribuicao]', formObj);
                var justificativa = jQuery('textarea[name=justificativa]', formObj);
                var proposta = jQuery('input[name=proposta]:checked', formObj).val();
                var label = jQuery('#labelContribuicao', formObj);

                if (proposta === undefined) {
                    label.css('border', 'solid 1px #d47627');
                    return false;
                } else {
                    label.css('border', '');
                }
                if (proposta === 'alteracao' || proposta === 'acrescimo') {
                    contrib.addClass('required');
                } else {
                    contrib.removeClass('required');
                }

                justificativa.addClass('required');
            }

            jQuery('.required', formObj).each (
                function () {
                    if (jQuery.trim(jQuery(this).val()) === '') {
                        jQuery(this).css('borderColor', '#d47627');
                        jQuery(this).focus();
                        counter++;
                    } else {
                        jQuery(this).css('borderColor', '');
                    }
                }
            );

            return (counter === 0);
        },

        hideForm : function (e) {
            e.preventDefault();
            jQuery(e.currentTarget).parents('div.comment-form').slideToggle();
        },
        sendForm : function (e) {
            e.preventDefault();
            var a = jQuery(e.currentTarget).parents('div.comment-pp').addClass('submitted');

            jQuery(e.currentTarget).parents('div.comment-form form').submit();
        },

        init : function () {
            var self = this;

            /* Setting up comment textareas */
            jQuery('.comment-pp p').on('click', function () {
                var parent = jQuery(this).parent();
                var obj = jQuery('div.comment-form', parent);
                var shouldShow = obj.css('display') === 'none';

                if (obj.find('form select').size() === 0) {
                    obj.find('form').append(
                    '    <ol>'+
                    '        <li>'+
                    '          <label for="opiniao">'+
                    '            Sua opinião sobre o dispositivo'+
                    '          </label>'+
                    '          <select name="opiniao">'+
                    '            <option value="concordo">Concordo com o Dispositivo</option>'+
                    '            <option value="concordo-com-ressalvas">Concordo com o Dispositivo com ressalvas</option>'+
                    '            <option value="nao-concordo">Discordo do Dispositivo</option>'+
                    '          </select>'+
                    '        </li>'+

                    '        <li class="agreed">'+
                    '          <label id="labelContribuicao">Tipo de contribuição</label>'+
                    '          <ol>'+
                    '            <li class="concordo-com-ressalvas">'+
                    '              <label>'+
                    '                <input type="radio" name="proposta" value="alteracao" />'+
                    '                Alteração na redação'+
                    '              </label>'+
                    '            </li>'+
                    '            <li class="concordo-com-ressalvas">'+
                    '              <label>'+
                    '                <input type="radio" name="proposta" value="acrescimo" />'+
                    '                Acréscimo de novo dispositivo'+
                    '              </label>'+
                    '            </li>'+
                    '            <li class="nao-concordo">'+
                    '              <label>'+
                    '                <input type="radio" name="proposta" value="exclusao" />'+
                    '                Exclusão'+
                    '              </label>'+
                    '            </li>'+
                    '            <li class="nao-concordo">'+
                    '              <label>'+
                    '                <input type="radio" name="proposta" value="retorno" />'+
                    '                Proposta de nova redação'+
                    '              </label>'+
                    '            </li>'+
                    '          </ol>'+
                    '        </li>'+

                    '        <li class="contribContainer">'+
                    '          <label>Contribuição</label>'+
                    '          <textarea name="contribuicao" id="text_Contribuicao"></textarea>'+
                    '          <p id="count_Contribuicao"><span class="label">Caracteres restantes: </span><span class="total"></span></p>'+
                    '        </li>'+

                    '        <li>'+
                    '          <label>Justificativa</label>'+
                    '          <textarea name="justificativa" id="text_Justificativa"></textarea>'+
                    '          <p id="count_Justificativa"><span class="label">Caracteres restantes: </span><span class="total"></span></p>'+
                    '        </li>'+

                    '        <li class="last">'+
                    '            <a href="#" class="cancel-comment"><i class="icon-cancel"></i>cancel</a>'+
                    '            <a href="#" class="send-comment"><i class="icon-arrow-rigth"></i>enviar comentário</a>'+
                    '        </li>'+
                    '    </ol>');
                    self.bindFormActions(obj);
                }

                /* Fixing a possible changed opacity */
                //jQuery('form', obj).css('opacity', 1);

                /* Caching selected form. It's very useful to handle
                 * ui interaction after posting this form. */
                this.SELECTED_P = parent;



                /* Time to toggle display state of the selected form
                 * and focus its main textarea */
                if (shouldShow) {
                    var otherActive = jQuery('.comment-pp.active');
                    if (otherActive.size() > 0) {
                        otherActive.find('.comment-form').slideUp();
                    }
                    obj.slideDown();
                } else {
                    obj.slideUp();
                }

                jQuery('textarea.comment', parent).focus();
            });

            /* Setting up comment forms */
            var optCommentForm = {
                dataType: 'html',

                beforeSubmit: function (data, formObj) {
                    var valid = self.validateForm(formObj);
                    if (valid) {
                        formObj.parent().animate({'height': '60px'});
                        jQuery('div.loading', formObj.parent()).show();
                        formObj.hide();
                    }
                    return valid;
                },

                success: function (response, responseText) {
                    var p = jQuery('.submitted');
                    var form = jQuery('form', p);
                    var msg = jQuery('div.message', p);
                    form.show();

                    /* Everything worked fine, let's reset field values */
                    jQuery('textarea', form).val('');
                    jQuery('input[type=text]', form).val('');
                    jQuery('div.comment-form', p).css('height', 'auto');
                    jQuery('div.comment-form', p).hide();
                    jQuery('div.loading', form.parent()).hide();
                    jQuery('input[type=checkbox]:checked', form).attr('checked', '');

                    /* Time to inform to the user that everything worked */
                    msg.html('<p>Agradecemos sua proposta, após a ' +
                             'moderação será disponibilizada nesta plataforma.</p>');
                    msg.fadeIn();
                    window.setTimeout(function () {
                        msg.fadeOut('slow');
                        p.removeClass('submitted');
                    }, 1000 * 5);
                },

                error: function (xhr, err) {
                    var p = jQuery('.submitted');
                    var msg = jQuery('div.message', p);

                    jQuery('div.loading').hide();
                    jQuery('div.comment-form', p).css('height', 'auto');

                    msg.html('<p>Ocorreu um erro ao tentar enviar o seu comentário. Tente novamente mais tarde.</p>');
                    msg.fadeIn();
                    window.setTimeout(function () {
                        msg.fadeOut('slow');
                        p.removeClass('submitted');
                    }, 1000 * 5);
                }
            };
            jQuery('.comment-form form').ajaxForm(optCommentForm);
        },

        manageFormFields : function () {
            var parent = jQuery(this).parents('ol');
            var opiniaoValue = jQuery(this).val();

            jQuery('.contribContainer', parent).hide();

            if (opiniaoValue !== 'concordo') {
                jQuery('.agreed', parent).show();
            } else {
                jQuery('.agreed', parent).hide();
            }

            if (opiniaoValue === 'concordo-com-ressalvas') {
                jQuery('.concordo-com-ressalvas', parent).show();
            } else {
                jQuery('.concordo-com-ressalvas', parent).hide();
            }

            if (opiniaoValue === 'nao-concordo') {
                jQuery('.nao-concordo', parent).show();
            } else {
                jQuery('.nao-concordo', parent).hide();
            }
        },

        reloadLabelOpniao : function () {
            /* I'm sure you found it really ugly... I'd love to
             * receive a patch that fixes it. */
            var container = jQuery(this)
                .parent()       // label
                .parent()       // li
                .parent()       // ol
                .parent()       // li.agreed
                .parent()       // ol
                .find('.contribContainer');
            if (jQuery(this).val() === 'alteracao') {
                jQuery('label', container).html('Proposta de alteração');
                container.show();
            } else if (jQuery(this).val() === 'acrescimo') {
                jQuery('label', container).html('Dispositivo a ser acrescentado');
                container.show();
            } else if (jQuery(this).val() === 'retorno') {
                jQuery('label', container).html('Nova redação');
                container.show();
            } else {
                container.hide();
            }
        },

        bindFormActions : function (obj) {
            jQuery('select').minimalect({ theme: 'bubble', placeholder: 'Sua opinião'});

            var text_Justificativa = obj.find('#text_Justificativa');
            var text_Contribuicao = obj.find('#text_Contribuicao');

            var count_Justificativa = obj.find('#count_Justificativa');
            var count_Contribuicao = obj.find('#count_Contribuicao');

            var max_char = 1000;

            Countable.live(text_Justificativa[0], function (counter) {
                //console.log(this, counter);
                if (counter.all > max_char) {
                    this.value = this.value.substr(0, max_char);
                }
                count_Justificativa.find('.total').html(max_char - counter.all);
            });

            Countable.live(text_Contribuicao[0], function (counter) {
                //console.log(this, counter);
                if (counter.all > max_char) {
                    this.value = this.value.substr(0, max_char);
                }
                count_Contribuicao.find('.total').html(max_char - counter.all);
            });

            jQuery('.comment-form .cancel-comment').on('click', this.hideForm);
            jQuery('.comment-form .send-comment').on('click', this.sendForm);

            /* Hardcoded behaviour for a specific project... someday we'll
             * clear it and make it generic. */

            jQuery('select[name=opiniao]').on('change', this.manageFormFields);

            jQuery('input[name=proposta]').on('click', this.reloadLabelOpniao);
        },

        dialogue_expand_comment : function(id, text) {
            jQuery('#comment-area-'+id).toggle();
            jQuery('#comment-full-'+id).toggle();
        }
    };

    return FormComments;
});