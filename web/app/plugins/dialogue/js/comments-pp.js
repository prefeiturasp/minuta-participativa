jQuery.noConflict();

/* Filled when a form is clicked and No! The user *CAN'T* select more
 * than one form at the same time. */
var SELECTED_P = null;

/* Function to validate comment form */
function validateForm (formObj) {
    var counter = 0;

    var opinionType = jQuery('select[name=opiniao]', formObj).val();
    if (opinionType == 'concordo') {
        return true;
    } else {
        var contrib = jQuery('textarea[name=contribuicao]', formObj);
        var justificativa = jQuery('textarea[name=justificativa]', formObj);
        var proposta = jQuery('input[name=proposta]:checked', formObj).val();
        var label = jQuery('#labelContribuicao', formObj);
        if (proposta == undefined) {
            label.css('border', 'solid 1px #d47627');
            return false;
        } else
            label.css('border', '');
        if (proposta == 'alteracao' || proposta == 'acrescimo')
            contrib.addClass('required');
        else
            contrib.removeClass('required');

        justificativa.addClass('required');
    }

    jQuery('.required', formObj).each (
        function () {
            if (jQuery.trim(jQuery(this).val()) == '') {
                jQuery(this).css('borderColor', '#d47627');
                jQuery(this).focus();
                counter++;
            } else {
                jQuery(this).css('borderColor', '');
            }
        }
    );

    return (counter == 0);
}

function hideForm(button) {
    jQuery(button).parents('div.comment-form').fadeOut();
}
function sendForm(button) {
    jQuery(button).parents('div.comment-form').find('form').submit();
}

jQuery(document).ready (
    function () {
        /* Setting up comment textareas */
        jQuery('.comment-pp p').click (
            function () {
                var parent = jQuery(this).parent();
                var obj = jQuery('div.comment-form', parent);
                var shouldShow = obj.css('display') == 'none';

                /* Fixing a possible changed opacity */
                //jQuery('form', obj).css('opacity', 1);

                /* Caching selected form. It's very useful to handle
                 * ui interaction after posting this form. */
                SELECTED_P = parent;

                /* Time to toggle display state of the selected form
                 * and focus its main textarea */
                if (shouldShow)
                    obj.fadeIn();
                else
                    obj.fadeOut();
                jQuery('textarea.comment', parent).focus();
            }
        );

        /* Setting up comment forms */
        var optCommentForm = {
            dataType: 'html',

            beforeSubmit: function (data, formObj, options) {
                var valid = validateForm(formObj);
                if (valid) {
                    formObj.parent().css('height', '60px');
                    jQuery('div.loading', formObj.parent()).show();
                }
                return valid;
            },

            success: function (response, responseText) {
                var p = SELECTED_P;
                var form = jQuery('form', p);
                var msg = jQuery('div.message', p);

                /* Everything worked fine, let's reset field values */
                jQuery('textarea', form).val('');
                jQuery('input[type=text]', form).val('');
                jQuery('div.comment-form', p).css('height', 'auto');
                jQuery('div.comment-form', p).hide();
                jQuery('div.loading', form.parent()).hide();
                jQuery('input[type=checkbox]:checked', form)
                    .attr('checked', '');

                /* Time to inform to the user that everything worked */
                msg.html('O Governo Federal agradece sua proposta, que após a ' +
                         'moderação será disponibilizada nesta plataforma.');
                msg.fadeIn();
                window.setTimeout(function () { msg.fadeOut('slow'); }, 10000);
            },

            error: function (xhr, err) {
                var p = SELECTED_P;
                var msg = jQuery('div.message', p);

                jQuery('div.loading').hide();
                jQuery('div.comment-form', p).css('height', 'auto');

                msg.html("Ocorreu um erro ao tentar postar o seu comentário =(");
                msg.fadeIn();
                window.setTimeout(function () { msg.fadeOut('slow'); }, 10000);
            }
        };
        jQuery('.comment-form form').ajaxForm(optCommentForm);

        /* Hardcoded behaviour for a specific project... someday we'll
         * clear it and make it generic. */

        jQuery('select[name=opiniao]').change(function () {
            var parent = jQuery(this).parents('ol');

            jQuery('.contribContainer', parent).hide();

            if (jQuery(this).val() != 'concordo') {
                jQuery('.agreed', parent).show();
            } else {
                jQuery('.agreed', parent).hide();
            }

            if (jQuery(this).val() == 'concordo-com-ressalvas') {
                jQuery('li.concordo-com-ressalvas', parent).show()
            } else {
                jQuery('li.concordo-com-ressalvas', parent).hide()
            }

            if (jQuery(this).val() == 'nao-concordo') {
                jQuery('.nao-concordo', parent).show()
            } else {
                jQuery('.nao-concordo', parent).hide()
            }
        });

        jQuery('input[name=proposta]').click(function () {
            /* I'm sure you found it really ugly... I'd love to
             * receive a patch that fixes it. */
            var container = jQuery(this)
                .parent()       // label
                .parent()       // li
                .parent()       // ol
                .parent()       // li.agreed
                .parent()       // ol
                .find('.contribContainer');
            if (jQuery(this).val() == 'alteracao') {
                jQuery('label', container).html('Proposta de alteração');
                container.show();
            } else if (jQuery(this).val() == 'acrescimo') {
                jQuery('label', container).html('Dispositivo a ser acrescentado');
                container.show();
            } else {
                container.hide();
            }
        });
    }
);


function dialogue_expand_comment(id, text) {
    jQuery("#comment-area-"+id).toggle();
    jQuery("#comment-full-"+id).toggle();
}
