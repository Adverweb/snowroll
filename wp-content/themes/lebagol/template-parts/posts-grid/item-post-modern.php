<div class="post-inner blog-modern">
    <?php lebagol_post_thumbnail('post-thumbnail', false); ?>
    <div class="post-content">
        <div class="entry-header">
            <div class="entry-meta">
                <?php lebagol_post_meta(['show_cat' => false, 'show_date' => true, 'show_author' => true, 'show_comment' => false]); ?>
            </div>
            <?php
            the_title('<h3 class="omega entry-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h3>');
            ?>
        </div>
    </div>
</div>