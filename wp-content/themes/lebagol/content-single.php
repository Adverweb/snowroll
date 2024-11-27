<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <div class="single-content">
        <?php
        /**
         * Functions hooked in to lebagol_single_post_top action
         *
         * @see lebagol_post_header         - 5
         * @see lebagol_post_thumbnail        - 10
         */
        do_action('lebagol_single_post_top');

        /**
         * Functions hooked in to lebagol_single_post action
         * @see lebagol_post_content        - 30
         */
        do_action('lebagol_single_post');

        /**
         * Functions hooked in to lebagol_single_post_bottom action
         *
         * @see lebagol_post_taxonomy      - 5
         * @see lebagol_single_author      - 10
         * @see lebagol_post_nav            - 15
         * @see lebagol_display_comments    - 20
         */
        do_action('lebagol_single_post_bottom');
        ?>

    </div>

</article><!-- #post-## -->
