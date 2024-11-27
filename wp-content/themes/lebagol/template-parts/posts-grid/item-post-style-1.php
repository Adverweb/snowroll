<div class="post-inner blog-grid">
    <div class="post-content">
        <?php
        lebagol_post_thumbnail('lebagol-post-grid', true);
        ?>
        <div class="entry-header">
            <div class="entry-meta">
                <?php lebagol_post_meta(['show_cat' => false, 'show_date' => true, 'show_author' => true, 'show_comment' => false]); ?>
            </div>
            <?php the_title('<h3 class="omega entry-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h3>'); ?>
        </div>
        <div class="entry-content">
            <div class="entry-excerpt"><?php the_excerpt(); ?></div>
            <?php
            echo '<div class="more-link-wrap"><a class="more-link" href="' . get_permalink() . '"><i class="icon-left lebagol-icon-arrow-small-right"></i><span>' . esc_html__('Read More', 'lebagol') . '</span><i class="icon-right lebagol-icon-arrow-small-right"></i></a></div>';
            ?>
        </div>
    </div>
</div>