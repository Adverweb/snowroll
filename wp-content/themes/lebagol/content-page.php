<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php
	/**
	 * Functions hooked in to lebagol_page action
	 *
	 * @see lebagol_page_header          - 10
	 * @see lebagol_page_content         - 20
	 *
	 */
	do_action( 'lebagol_page' );
	?>
</article><!-- #post-## -->
