<?php
/**
 * =================================================
 * Hook lebagol_page
 * =================================================
 */
add_action('lebagol_page', 'lebagol_page_header', 10);
add_action('lebagol_page', 'lebagol_page_content', 20);

/**
 * =================================================
 * Hook lebagol_single_post_top
 * =================================================
 */
add_action('lebagol_single_post_top', 'lebagol_post_header', 5);
add_action('lebagol_single_post_top', 'lebagol_post_thumbnail', 10);

/**
 * =================================================
 * Hook lebagol_single_post
 * =================================================
 */
add_action('lebagol_single_post', 'lebagol_post_content', 30);

/**
 * =================================================
 * Hook lebagol_single_post_bottom
 * =================================================
 */
add_action('lebagol_single_post_bottom', 'lebagol_post_taxonomy', 5);
add_action('lebagol_single_post_bottom', 'lebagol_single_author', 10);
add_action('lebagol_single_post_bottom', 'lebagol_post_nav', 15);
add_action('lebagol_single_post_bottom', 'lebagol_display_comments', 20);

/**
 * =================================================
 * Hook lebagol_loop_post
 * =================================================
 */
add_action('lebagol_loop_post', 'lebagol_post_header', 15);
add_action('lebagol_loop_post', 'lebagol_post_content', 30);

/**
 * =================================================
 * Hook lebagol_footer
 * =================================================
 */
add_action('lebagol_footer', 'lebagol_footer_default', 20);

/**
 * =================================================
 * Hook lebagol_after_footer
 * =================================================
 */

/**
 * =================================================
 * Hook wp_footer
 * =================================================
 */
add_action('wp_footer', 'lebagol_template_account_dropdown', 1);
add_action('wp_footer', 'lebagol_mobile_nav', 1);
add_action('wp_footer', 'render_html_back_to_top', 1);

/**
 * =================================================
 * Hook wp_head
 * =================================================
 */
add_action('wp_head', 'lebagol_pingback_header', 1);

/**
 * =================================================
 * Hook lebagol_before_header
 * =================================================
 */

/**
 * =================================================
 * Hook lebagol_before_content
 * =================================================
 */

/**
 * =================================================
 * Hook lebagol_content_top
 * =================================================
 */

/**
 * =================================================
 * Hook lebagol_post_content_before
 * =================================================
 */

/**
 * =================================================
 * Hook lebagol_post_content_after
 * =================================================
 */

/**
 * =================================================
 * Hook lebagol_sidebar
 * =================================================
 */
add_action('lebagol_sidebar', 'lebagol_get_sidebar', 10);

/**
 * =================================================
 * Hook lebagol_loop_after
 * =================================================
 */
add_action('lebagol_loop_after', 'lebagol_paging_nav', 10);

/**
 * =================================================
 * Hook lebagol_page_after
 * =================================================
 */
add_action('lebagol_page_after', 'lebagol_display_comments', 10);

/**
 * =================================================
 * Hook lebagol_woocommerce_before_shop_loop_item
 * =================================================
 */

/**
 * =================================================
 * Hook lebagol_woocommerce_before_shop_loop_item_title
 * =================================================
 */

/**
 * =================================================
 * Hook lebagol_woocommerce_after_shop_loop_item
 * =================================================
 */
