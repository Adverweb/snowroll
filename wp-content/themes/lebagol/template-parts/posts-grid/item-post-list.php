<article id="post-<?php the_ID(); ?>" <?php post_class('article-default'); ?>>
    <div class="post-inner blog-list">
        <div class="post-content">
            <?php if (has_post_thumbnail()) { ?>
                <div class="post-left">
                    <?php lebagol_post_thumbnail('lebagol-post-grid'); ?>
                </div>
            <?php } ?>
            <div class="post-right  <?php echo has_post_thumbnail() ? esc_attr('post-list-thumbnail') : ''; ?>">
                <div class="entry-meta">
                    <?php lebagol_post_meta(['show_cat' => false, 'show_date' => true, 'show_author' => true, 'show_comment' => false]); ?>
                </div>
                <?php
                the_title('<h3 class="delta entry-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h3>');
                ?>
                <div class="entry-excerpt"><?php the_excerpt(); ?></div>

            </div>
        </div>
    </div>
</article><!-- #post-## -->