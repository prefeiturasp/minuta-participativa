<?php /* Template Name: Consulta */ ?>

<?php get_header(); ?>

<script type="text/javascript">
/* I hate to type jQuery all the time */
$ = jQuery;

/* Useful values when building urls, using PHP to generate them. */
blogUrl = "<?php bloginfo('url'); ?>";
templateUrl = "<?php bloginfo('template_url'); ?>";

/* Paragraph that is currently loaded in the comments list. It starts
 * empty and is filled by the `loadComments()' function. */
loadedParagraph = null;


function formatDate(date) {
    return date;
}

function filterContent(content) {
    while (content.indexOf('\n') != -1)
        content = content.replace('\n', '<br />');
    return content;
}

function loadComments(paragraphId, postId) {
    var query = '{"method":"get_paragraph_comments","params":["' +
        paragraphId + '",' + postId + ']}'
    var container = $('#commentContainer');

    /* Testing if the paragraph asked to be loaded is already the
     * current one, if it's not we set the loaded paragraph. */
    if (loadedParagraph == paragraphId)
        return;
    else
        loadedParagraph = paragraphId;

    /* Clearing up comment list */
    container.html(
        '<li class="comment">' +
        '<img src="' + templateUrl + '/images/loading.gif" />' +
        '</li>');

    /* Getting comment list */
    $.getJSON(blogUrl, {dialogue_query:query}, function (comments) {
        var i;
        container.html('');

        /* No comments yet */
        if (comments.length == 0) {
            container.html(
                '<li class="comment">' +
                'Não há propostas para o dispositivo selecionado.' +
                '</li>');
            return;
        }

        /* Building the html of the comment list */
        for (i = 0; i < comments.length; i++) {
            var obj = comments[i];
            var infoUser = $('<div>')
                .addClass('infoUser')
                .append($('<span>')
                        .addClass('date')
                        .html(formatDate(obj.comment_date)))
                .append($('<span>')
                        .addClass('user')
                        .html(obj.comment_author));
            var li = $('<li>')
                .addClass('comment')
                .append(infoUser)
                .append('<strong>Proposta</strong>')
                .append($('<p>').html(filterContent(obj.comment_content)));

            if (obj.meta.justificativa) {
                li.append('<strong>Justificativa</strong>');
                li.append($('<p>').html(filterContent(obj.meta.justificativa)));
            }

            if (obj.tags) {
                var ul = $('<ul>');
                li.append('<strong>Tags</strong>');
                for (var t = 0; t < obj.tags.length; t++)
                    ul.append($('<li>').append(obj.tags[t].name));
                li.append(ul);
            }

            li.appendTo(container);
        }
    });
}

$(document).ready(function () {
    $('.comment-pp').click(function () {
        var postId = $('input[name=comment_post_ID]', $(this)).val();
        var paragraphId =
            $('input[name=dialogue_comment_paragraph]', $(this)).val();
        loadComments(paragraphId, postId);

        /* marking selected paragraph as the active, comments of this
         * `selected' paragraph are going to be shown in the right
         * column. */
        $('.comment-pp').removeClass('active');
        $(this).addClass('active');
    });

    /* Loading comments from the first paragraph */
    var postIdExpr = $('input[name=comment_post_ID]');
    var paragraphIdExpr = $('input[name=dialogue_comment_paragraph]');
    if (postIdExpr.length > 0 && paragraphIdExpr.length > 0) {
        var paragraphId = $(paragraphIdExpr[0]);

        /* Marking comment paragraph as the active one */
        paragraphId.parents('div.comment-pp').addClass('active');

        /* Actually loading comments from the found paragraph */
        loadComments(paragraphId.val(), $(postIdExpr[0]).val());
    }
});
</script>

<div id="content">
  <?php
    query_posts('category_name=Consulta');
    while (have_posts()) : the_post();
  ?>
  <div class="post">
    <span class="title">Consulta</span>
    <?php the_content(); ?>
  </div>
  <?php endwhile; ?>

  <div id="comments">
    <div id="navegaComments">
      <h3>Lista de páginas com comentários</h3>
      <ol>
        <li><a href="#" title="Página 1">1</a></li>
        <li><a href="#" title="Página 2">2</a></li>
        <li><a href="#" title="Página 3">3</a></li>
        <li><a href="#" title="Página 4">4</a></li>	
        <li><a href="#" title="Página 5">5</a></li>
      </ol>
      <form action="get">
        <label for="">Campo busca de comentários</label>
        <input type="text" name="busca nos comentários" />
      </form>
    </div><!--fim #navegaComments-->
    <ul id="commentContainer"></ul>
  </div><!--fim #comments-->

</div>

<?php get_footer(); ?>
