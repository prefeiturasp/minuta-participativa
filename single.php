<div class="pure-u-1">
    <div class="pure-g-r">
    <?php if (have_posts()) : ?>
        <?php while (have_posts()) : ?>
        <?php the_post(); ?>
        <div class="pure-u-1 titlebar">
            <h2><?php the_title(); ?></h2>
            <ul>
                <li><a href="<?php echo get_post_meta(get_the_ID(), 'download', true); ?>" target="_blank"><i class="icon-arrow-down"></i>Fazer Download</a></li>
                <li><a href="javascript:void(0);" class="close"><i class="icon-close"></i>Fechar</a></li>
            </ul>
        </div>
        <div class="pure-u-2-3 image-content">
            <?php the_content(); ?>
        </div>
        <div class="pure-u-1-3 sidebar">
            <div class="sidebox featured-comment">
                <h2><i class="icon-pencil"></i>comentar este conteúdo</h2>
                <?php comment_form(array(
                'title_reply'=>'',
                'logged_in_as'=>'',
                'comment_notes_after'=> '<a href="javascript:void(0);" class="send-comment"><i class="icon-arrow-rigth"></i>enviar</a>',
                'comment_field' =>  '<p class="comment-form-comment"><label for="comment">' . _x( 'Deixe seu comentário aqui:', 'noun' ) .
    '</label><textarea id="comment" name="comment" cols="28" rows="8" aria-required="true">' .
    '</textarea></p>',
                'label_submit'      => __( 'Enviar' )
                )); ?>
            </div>
            <div class="sidebox comments">
                <h2><i class="icon-comment"></i>comentários</h2>

                <?php
                    $comments = get_comments('post_id='.get_the_ID());

                    foreach($comments as $comment) : ?>
                        <div class="comment">
                        <p class="datetime">
                            <span class="date"><?php echo $comment->comment_date ; ?></span>
                        </p>
                        <p class="author"><?php echo $comment->comment_author ; ?></p>
                        <div class="text-comment">
                        <?php echo $comment->comment_content ; ?>
                        </div>
                        </div>
                    <?php endforeach;
                    //wp_list_comments(array(), $comments);
                ?>

            </div>
        </div>
        <?php endwhile; ?>
    <?php endif; ?>
    </div>
</div>