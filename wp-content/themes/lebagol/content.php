<article id="post-<?php the_ID(); ?>" <?php post_class('article-default'); ?>>
    <div class="post-inner">
        <?php
        lebagol_post_thumbnail('post-thumbnail', true);
        ?>
        <div class="post-content">
            <?php
            /**
             * Functions hooked in to lebagol_loop_post action.
             *
             * @see lebagol_post_header          - 15
             * @see lebagol_post_content         - 30
             */
            do_action('lebagol_loop_post');
            ?>
        </div>
    </div>
</article><!-- #post-## -->