<?php

if (!function_exists('lebagol_before_content')) {
    /**
     * Before Content
     * Wraps all WooCommerce content in wrappers which match the theme markup
     *
     * @return  void
     * @since   1.0.0
     */
    function lebagol_before_content() {
        echo <<<HTML
<div id="primary" class="content-area">
    <main id="main" class="site-main">
HTML;

    }
}


if (!function_exists('lebagol_after_content')) {
    /**
     * After Content
     * Closes the wrapping divs
     *
     * @return  void
     * @since   1.0.0
     */
    function lebagol_after_content() {
        echo <<<HTML
	</main><!-- #main -->
</div><!-- #primary -->
HTML;

        do_action('lebagol_sidebar');
    }
}

if (!function_exists('lebagol_woo_cart_available')) {
    /**
     * Validates whether the Woo Cart instance is available in the request
     *
     * @return bool
     * @since 2.6.0
     */
    function lebagol_woo_cart_available() {
        $woo = WC();
        return $woo instanceof \WooCommerce && $woo->cart instanceof \WC_Cart;
    }
}

if (!function_exists('lebagol_cart_link_fragment')) {
    /**
     * Cart Fragments
     * Ensure cart contents update when products are added to the cart via AJAX
     *
     * @param array $fragments Fragments to refresh via AJAX.
     *
     * @return array            Fragments to refresh via AJAX
     */
    function lebagol_cart_link_fragment($fragments) {
        ob_start();
        lebagol_cart_link();
        $fragments['a.cart-contents'] = ob_get_clean();

        return $fragments;
    }
}

if (!function_exists('lebagol_cart_link')) {
    /**
     * Cart Link
     * Displayed a link to the cart including the number of items present and the cart total
     *
     * @return void
     * @since  1.0.0
     */
    function lebagol_cart_link() {
        if (!lebagol_woo_cart_available()) {
            return;
        }
        ?>
        <a class="cart-contents" href="<?php echo esc_url(wc_get_cart_url()); ?>" title="<?php esc_attr_e('View your shopping cart', 'lebagol'); ?>">
            <?php if (WC()->cart->get_cart_contents_count() > 0) { ?>
                <span class="count"><?php echo wp_kses_data(sprintf('%02d', WC()->cart->get_cart_contents_count())); ?></span>
            <?php } ?>
        </a>
        <?php
    }
}

class Lebagol_Custom_Walker_Category extends Walker_Category {

    public function start_el(&$output, $category, $depth = 0, $args = array(), $id = 0) {
        /** This filter is documented in wp-includes/category-template.php */
        $cat_name = apply_filters(
            'list_cats',
            esc_attr($category->name),
            $category
        );

        // Don't generate an element if the category name is empty.
        if (!$cat_name) {
            return;
        }

        $link = '<a class="pf-value" href="' . esc_url(get_term_link($category)) . '" data-val="' . esc_attr($category->slug) . '" data-title="' . esc_attr($category->name) . '" ';
        if ($args['use_desc_for_title'] && !empty($category->description)) {
            /**
             * Filters the category description for display.
             *
             * @param string $description Category description.
             * @param object $category Category object.
             *
             * @since 1.2.0
             *
             */
            $link .= 'title="' . esc_attr(strip_tags(apply_filters('category_description', $category->description, $category))) . '"';
        }

        $link .= '>';
        $link .= $cat_name . '</a>';

        if (!empty($args['feed_image']) || !empty($args['feed'])) {
            $link .= ' ';

            if (empty($args['feed_image'])) {
                $link .= '(';
            }

            $link .= '<a href="' . esc_url(get_term_feed_link($category->term_id, $category->taxonomy, $args['feed_type'])) . '"';

            if (empty($args['feed'])) {
                $alt = ' alt="' . sprintf(esc_html__('Feed for all posts filed under %s', 'lebagol'), $cat_name) . '"';
            } else {
                $alt  = ' alt="' . $args['feed'] . '"';
                $name = $args['feed'];
                $link .= empty($args['title']) ? '' : $args['title'];
            }

            $link .= '>';

            if (empty($args['feed_image'])) {
                $link .= $name;
            } else {
                $link .= "<img src='" . $args['feed_image'] . "'$alt" . ' />';
            }
            $link .= '</a>';

            if (empty($args['feed_image'])) {
                $link .= ')';
            }
        }

        if (!empty($args['show_count'])) {
            $link .= ' (' . number_format_i18n($category->count) . ')';
        }
        if ('list' == $args['style']) {
            $output      .= "\t<li";
            $css_classes = array(
                'cat-item',
                'cat-item-' . $category->term_id,
            );

            if (!empty($args['current_category'])) {
                // 'current_category' can be an array, so we use `get_terms()`.
                $_current_terms = get_terms(
                    $category->taxonomy,
                    array(
                        'include'    => $args['current_category'],
                        'hide_empty' => false,
                    )
                );

                foreach ($_current_terms as $_current_term) {
                    if ($category->term_id == $_current_term->term_id) {
                        $css_classes[] = 'current-cat pf-active';
                    } elseif ($category->term_id == $_current_term->parent) {
                        $css_classes[] = 'current-cat-parent';
                    }
                    while ($_current_term->parent) {
                        if ($category->term_id == $_current_term->parent) {
                            $css_classes[] = 'current-cat-ancestor';
                            break;
                        }
                        $_current_term = get_term($_current_term->parent, $category->taxonomy);
                    }
                }
            }

            /**
             * Filters the list of CSS classes to include with each category in the list.
             *
             * @param array $css_classes An array of CSS classes to be applied to each list item.
             * @param object $category Category data object.
             * @param int $depth Depth of page, used for padding.
             * @param array $args An array of wp_list_categories() arguments.
             *
             * @since 4.2.0
             *
             * @see wp_list_categories()
             *
             */
            $css_classes = implode(' ', apply_filters('category_css_class', $css_classes, $category, $depth, $args));

            $output .= ' class="' . $css_classes . '"';
            $output .= ">$link\n";
        } elseif (isset($args['separator'])) {
            $output .= "\t$link" . $args['separator'] . "\n";
        } else {
            $output .= "\t$link<br />\n";
        }
    }
}

if (!function_exists('lebagol_show_categories_dropdown')) {
    function lebagol_show_categories_dropdown() {
        static $id = 0;
        $args  = array(
            'hide_empty' => 1,
            'parent'     => 0
        );
        $terms = get_terms('product_cat', $args);
        if (!empty($terms) && !is_wp_error($terms)) {
            ?>
            <div class="search-by-category input-dropdown">
                <div class="input-dropdown-inner lebagol-scroll-content">
                    <!--                    <input type="hidden" name="product_cat" value="0">-->
                    <a href="#" data-val="0"><span><?php esc_html_e('All category', 'lebagol'); ?></span></a>
                    <?php
                    $args_dropdown = array(
                        'id'               => 'product_cat' . $id++,
                        'show_count'       => 0,
                        'class'            => 'dropdown_product_cat_ajax',
                        'show_option_none' => esc_html__('All category', 'lebagol'),
                    );
                    wc_product_dropdown_categories($args_dropdown);
                    ?>
                    <div class="list-wrapper lebagol-scroll">
                        <ul class="lebagol-scroll-content">
                            <li class="d-none">
                                <a href="#" data-val="0"><?php esc_html_e('All category', 'lebagol'); ?></a></li>
                            <?php
                            if (!apply_filters('lebagol_show_only_parent_categories_dropdown', false)) {
                                $args_list = array(
                                    'title_li'           => false,
                                    'taxonomy'           => 'product_cat',
                                    'use_desc_for_title' => false,
                                    'walker'             => new Lebagol_Custom_Walker_Category(),
                                );
                                wp_list_categories($args_list);
                            } else {
                                foreach ($terms as $term) {
                                    ?>
                                    <li>
                                        <a href="#" data-val="<?php echo esc_attr($term->slug); ?>"><?php echo esc_attr($term->name); ?></a>
                                    </li>
                                    <?php
                                }
                            }
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
            <?php
        }
    }
}

if (!function_exists('lebagol_product_search')) {
    /**
     * Display Product Search
     *
     * @return void
     * @uses  lebagol_is_woocommerce_activated() check if WooCommerce is activated
     * @since  1.0.0
     */
    function lebagol_product_search() {
        if (lebagol_is_woocommerce_activated()) {
            static $index = 0;
            $index++;
            ?>
            <div class="site-search ajax-search">
                <div class="widget woocommerce widget_product_search">
                    <div class="ajax-search-result d-none"></div>
                    <form role="search" method="get" class="woocommerce-product-search" action="<?php echo esc_url(home_url('/')); ?>">
                        <label class="screen-reader-text" for="woocommerce-product-search-field-<?php echo isset($index) ? absint($index) : 0; ?>"><?php esc_html_e('Search for:', 'lebagol'); ?></label>
                        <input type="search" id="woocommerce-product-search-field-<?php echo isset($index) ? absint($index) : 0; ?>" class="search-field" placeholder="<?php echo esc_attr__('What are you looking for?', 'lebagol'); ?>" autocomplete="off" value="<?php echo get_search_query(); ?>" name="s"/>
                        <button type="submit" value="<?php echo esc_attr_x('Search', 'submit button', 'lebagol'); ?>">
                            <i class="elementor-button-icon left lebagol-icon-arrow-small-right"></i>
                            <span class="elementor-button-text"><?php echo esc_html_x('Search', 'submit button', 'lebagol'); ?></span>
                            <i class="elementor-button-icon right lebagol-icon-arrow-small-right"> </i>
                        </button>
                        <input type="hidden" name="post_type" value="product"/>
                    </form>
                </div>
            </div>
            <?php
        }
    }
}

if (!function_exists('lebagol_header_cart')) {
    /**
     * Display Header Cart
     *
     * @return void
     * @uses  lebagol_is_woocommerce_activated() check if WooCommerce is activated
     * @since  1.0.0
     */
    function lebagol_header_cart() {
        if (lebagol_is_woocommerce_activated()) {
            if (!lebagol_get_theme_option('show_header_cart', true)) {
                return;
            }
            ?>
            <div class="site-header-cart menu">
                <?php lebagol_cart_link(); ?>
                <?php

                if (!apply_filters('woocommerce_widget_cart_is_hidden', is_cart() || is_checkout())) {

                    if (lebagol_get_theme_option('header_cart_dropdown', 'side') == 'side') {
                        add_action('wp_footer', 'lebagol_header_cart_side');
                    } else {
                        the_widget('WC_Widget_Cart', 'title=');
                    }
                }
                ?>
            </div>
            <?php
        }
    }
}

if (!function_exists('lebagol_header_cart_side')) {
    function lebagol_header_cart_side() {
        if (lebagol_is_woocommerce_activated()) {
            ?>
            <div class="site-header-cart-side">
                <div class="cart-side-heading">
                    <span class="cart-side-title"><?php echo esc_html__('shopping cart', 'lebagol'); ?></span>
                    <a href="#" class="close-cart-side"><?php echo esc_html__('close', 'lebagol') ?></a></div>
                <?php the_widget('WC_Widget_Cart', 'title='); ?>
            </div>
            <div class="cart-side-overlay"></div>
            <?php
        }
    }
}

if (!function_exists('lebagol_product_columns_wrapper')) {
    /**
     * Product columns wrapper
     *
     * @return  void
     * @since   2.2.0
     */
    function lebagol_product_columns_wrapper() {
        $columns = lebagol_loop_columns();
        echo '<div class="columns-' . absint($columns) . '">';
    }
}

if (!function_exists('lebagol_loop_columns')) {
    /**
     * Default loop columns on product archives
     *
     * @return integer products per row
     * @since  1.0.0
     */
    function lebagol_loop_columns() {
        $columns = 3; // 3 products per row

        if (function_exists('wc_get_default_products_per_row')) {
            $columns = wc_get_default_products_per_row();
        }

        return apply_filters('lebagol_loop_columns', $columns);
    }
}

if (!function_exists('lebagol_product_columns_wrapper_close')) {
    /**
     * Product columns wrapper close
     *
     * @return  void
     * @since   2.2.0
     */
    function lebagol_product_columns_wrapper_close() {
        echo '</div>';
    }
}

if (!function_exists('lebagol_shop_messages')) {
    /**
     * ThemeBase shop messages
     *
     * @since   1.4.4
     * @uses    lebagol_do_shortcode
     */
    function lebagol_shop_messages() {
        if (!is_checkout()) {
            echo lebagol_do_shortcode('woocommerce_messages');
        }
    }
}

if (!function_exists('lebagol_woocommerce_pagination')) {
    /**
     * ThemeBase WooCommerce Pagination
     * WooCommerce disables the product pagination inside the woocommerce_product_subcategories() function
     * but since ThemeBase adds pagination before that function is excuted we need a separate function to
     * determine whether or not to display the pagination.
     *
     * @since 1.4.4
     */
    function lebagol_woocommerce_pagination() {
        if (woocommerce_products_will_display()) {
            woocommerce_pagination();
        }
    }
}


if (!function_exists('lebagol_single_product_pagination')) {
    /**
     * Single Product Pagination
     *
     * @since 2.3.0
     */
    function lebagol_single_product_pagination() {

        // Show only products in the same category?
        $in_same_term   = apply_filters('lebagol_single_product_pagination_same_category', true);
        $excluded_terms = apply_filters('lebagol_single_product_pagination_excluded_terms', '');
        $taxonomy       = apply_filters('lebagol_single_product_pagination_taxonomy', 'product_cat');

        $previous_product = lebagol_get_previous_product($in_same_term, $excluded_terms, $taxonomy);
        $next_product     = lebagol_get_next_product($in_same_term, $excluded_terms, $taxonomy);

        if ((!$previous_product && !$next_product) || !is_product()) {
            return;
        }

        ?>
        <div class="lebagol-product-pagination-wrap">
            <nav class="lebagol-product-pagination" aria-label="<?php esc_attr_e('More products', 'lebagol'); ?>">
                <?php if ($previous_product) : ?>
                    <a href="<?php echo esc_url($previous_product->get_permalink()); ?>" rel="prev">
                        <span class="pagination-prev "><i class="lebagol-icon-angle-left"></i><?php echo esc_html__('Prev', 'lebagol'); ?></span>
                        <div class="product-item">
                            <?php echo sprintf('%s', $previous_product->get_image()); ?>
                            <div class="lebagol-product-pagination-content">
                                <h4 class="lebagol-product-pagination__title"><?php echo sprintf('%s', $previous_product->get_name()); ?></h4>
                                <?php if ($price_html = $previous_product->get_price_html()) :
                                    printf('<span class="price">%s</span>', $price_html);
                                endif; ?>
                            </div>
                        </div>
                    </a>
                <?php endif; ?>

                <?php if ($next_product) : ?>
                    <a href="<?php echo esc_url($next_product->get_permalink()); ?>" rel="next">
                        <span class="pagination-next"><?php echo esc_html__('Next', 'lebagol'); ?><i class="lebagol-icon-angle-right"></i></span>
                        <div class="product-item">
                            <?php echo sprintf('%s', $next_product->get_image()); ?>
                            <div class="lebagol-product-pagination-content">
                                <h4 class="lebagol-product-pagination__title"><?php echo sprintf('%s', $next_product->get_name()); ?></h4>
                                <?php if ($price_html = $next_product->get_price_html()) :
                                    printf('<span class="price">%s</span>', $price_html);
                                endif; ?>
                            </div>
                        </div>
                    </a>
                <?php endif; ?>
            </nav><!-- .lebagol-product-pagination -->
        </div>
        <?php


    }
}

if (!function_exists('lebagol_sticky_single_add_to_cart')) {
    /**
     * Sticky Add to Cart
     *
     * @since 2.3.0
     */
    function lebagol_sticky_single_add_to_cart() {
        global $product;

        if (!is_product()) {
            return;
        }

        $show = false;

        if ($product->is_purchasable() && $product->is_in_stock()) {
            $show = true;
        } else if ($product->is_type('external')) {
            $show = true;
        }

        if (!$show) {
            return;
        }

        $params = apply_filters(
            'lebagol_sticky_add_to_cart_params', array(
                'trigger_class' => 'entry-summary',
            )
        );

        wp_localize_script('lebagol-sticky-add-to-cart', 'lebagol_sticky_add_to_cart_params', $params);
        ?>

        <section class="lebagol-sticky-add-to-cart">
            <div class="col-fluid">
                <div class="lebagol-sticky-add-to-cart__content">
                    <?php echo woocommerce_get_product_thumbnail(); ?>
                    <div class="lebagol-sticky-add-to-cart__content-product-info">
						<span class="lebagol-sticky-add-to-cart__content-title"><?php esc_attr_e('You\'re viewing:', 'lebagol'); ?>
							<strong><?php the_title(); ?></strong></span>
                        <span class="lebagol-sticky-add-to-cart__content-price"><?php echo sprintf('%s', $product->get_price_html()); ?></span>
                        <?php echo wc_get_rating_html($product->get_average_rating()); ?>
                    </div>
                    <a href="<?php echo esc_url($product->add_to_cart_url()); ?>" class="lebagol-sticky-add-to-cart__content-button button alt">
                        <?php echo esc_attr($product->add_to_cart_text()); ?>
                    </a>
                </div>
            </div>
        </section><!-- .lebagol-sticky-add-to-cart -->
        <?php
    }
}

if (!function_exists('lebagol_woocommerce_product_loop_action')) {
    function lebagol_woocommerce_product_loop_action() {
        ?>
        <div class="group-action">
            <div class="shop-action">
                <?php do_action('lebagol_woocommerce_product_loop_action'); ?>
            </div>
        </div>
        <?php
    }
}
if (!function_exists('lebagol_single_product_loop_action')) {
    function lebagol_single_product_loop_action() {
        ?>
        <div class="single-product-group-action">
            <?php do_action('lebagol_woocommerce_single_product_loop_action'); ?>
            <div class="right">
                <?php
                lebagol_wishlist_button();
                lebagol_compare_button();
                ?>
            </div>
        </div>
        <?php
    }
}

if (!function_exists('lebagol_single_product_summary_top')) {
    function lebagol_single_product_summary_top() {
        global $product;
        ?>
        <div class="product-labels single-product-labels">
            <?php woocommerce_show_product_loop_sale_flash();
            Lebagol_Woocommerce_Product_Labels::instance()->render_labels($product, true);
            ?>
        </div>
        <?php
    }
}

if (!function_exists('lebagol_single_product_summary_content_start')) {
    function lebagol_single_product_summary_content_start() {
        echo <<<HTML
<div class="entry-summary-content">
HTML;
    }
}

if (!function_exists('lebagol_single_product_summary_content_end')) {
    function lebagol_single_product_summary_content_end() {
        echo <<<HTML
</div>
HTML;
    }
}

if (!function_exists('lebagol_button_grid_list_layout')) {
    function lebagol_button_grid_list_layout() {
        ?>
        <div class="gridlist-toggle desktop-hide-down">
            <a href="<?php echo esc_url(add_query_arg('layout', 'grid')); ?>" id="grid" class="<?php echo isset($_GET['layout']) && $_GET['layout'] == 'list' ? '' : 'active'; ?>" title="<?php echo esc_html__('Grid View', 'lebagol'); ?>"><i class="lebagol-icon-grid1"></i></a>
            <a href="<?php echo esc_url(add_query_arg('layout', 'list')); ?>" id="list" class="<?php echo isset($_GET['layout']) && $_GET['layout'] == 'list' ? 'active' : ''; ?>" title="<?php echo esc_html__('List View', 'lebagol'); ?>"><i class="lebagol-icon-list1"></i></a>
        </div>
        <?php
    }
}

if (!function_exists('lebagol_product_label')) {
    function lebagol_product_label() {
        global $product;

        $output = array();

        if ($product->is_on_sale()) {

            $percentage = '';

            if ($product->get_type() == 'variable') {

                $available_variations = $product->get_variation_prices();
                $max_percentage       = 0;

                foreach ($available_variations['regular_price'] as $key => $regular_price) {
                    $sale_price = $available_variations['sale_price'][$key];

                    if ($sale_price < $regular_price) {
                        $percentage = round((($regular_price - $sale_price) / $regular_price) * 100);

                        if ($percentage > $max_percentage) {
                            $max_percentage = $percentage;
                        }
                    }
                }

                $percentage = $max_percentage;
            } elseif (($product->get_type() == 'simple' || $product->get_type() == 'external')) {
                $percentage = round((($product->get_regular_price() - $product->get_sale_price()) / $product->get_regular_price()) * 100);
            }

            if ($percentage) {
                $output[] = '<span class="product-label onsale">' . '-' . $percentage . '%</span>';
            } else {
                $output[] = '<span class="product-label onsale">' . esc_html__('Sale', 'lebagol') . '</span>';
            }
        }

        if ($output) {
            echo implode('', $output);
        }
    }
}

add_filter('woocommerce_sale_flash', 'lebagol_product_label', 10);

if (!function_exists('lebagol_woocommerce_get_product_label_stock')) {
    function lebagol_woocommerce_get_product_label_stock() {
        /**
         * @var $product WC_Product
         */
        global $product;
        if (!$product->managing_stock()) {
            if (!$product->is_in_stock()) {
                echo '<span class="inventory_status out-of-stock">' . esc_html__('Out Of Stock', 'lebagol') . '</span>';
            } elseif ($product->is_on_backorder()) {
                echo '<span class="inventory_status backorder">' . esc_html__('Available on backorder', 'lebagol') . '</span>';
            } else {
                echo '<span class="inventory_status">' . esc_html__('In stock', 'lebagol') . '</span>';
            }
        } else {
            if (!$product->is_in_stock()) {
                echo '<span class="inventory_status out-of-stock">' . esc_html__('Out Of Stock', 'lebagol') . '</span>';
            } elseif ($product->is_on_backorder(1) && $product->backorders_require_notification()) {
                echo '<span class="inventory_status backorder">' . esc_html__('Available on backorder', 'lebagol') . '</span>';
            } else {
                echo '<span class="inventory_status">' . esc_html__('In stock', 'lebagol') . '</span>';
            }
        }
    }
}

if (!function_exists('lebagol_woocommerce_single_brand')) {
    function lebagol_woocommerce_single_brand() {
        $id = get_the_ID();

        $terms = get_the_terms($id, 'product_brand');

        if (is_wp_error($terms)) {
            return $terms;
        }

        if (empty($terms)) {
            return false;
        }

        $links = array();

        foreach ($terms as $term) {
            $link = get_term_link($term, 'product_brand');
            if (is_wp_error($link)) {
                return $link;
            }
            $links[] = '<a href="' . esc_url($link) . '" rel="tag">' . $term->name . '</a>';
        }
        echo '<div class="product-brand">' . esc_html__('Brands: ', 'lebagol') . join('', $links) . '</div>';
    }
}
if (!function_exists('lebagol_single_product_after_title')) {
    function lebagol_single_product_after_title() {
        ?>
        <div class="product_after_title">
            <?php
            lebagol_woocommerce_single_brand();
            woocommerce_template_single_rating();
            lebagol_woocommerce_get_product_label_stock();
            lebagol_single_product_pagination();
            ?>
        </div>
        <?php
    }
}

if (!function_exists('lebagol_template_loop_product_thumbnail')) {
    function lebagol_template_loop_product_thumbnail($size = 'woocommerce_thumbnail') {
        global $product;
        if (!$product) {
            return '';
        }
        $gallery    = $product->get_gallery_image_ids();
        $hover_skin = lebagol_get_theme_option('woocommerce_product_hover', 'none');

        if ($hover_skin == 'none' || count($gallery) <= 0) {
            echo '<div class="product-image image-main">' . $product->get_image('woocommerce_thumbnail') . '</div>';

            return '';
        }
        $image_featured = '<div class="product-image image-main">' . $product->get_image('woocommerce_thumbnail') . '</div>';
        $image_featured .= '<div class="product-image second-image">' . wp_get_attachment_image($gallery[0], 'woocommerce_thumbnail') . '</div>';

        echo <<<HTML
<div class="product-img-wrap {$hover_skin}">
    <div class="inner">
        {$image_featured}
    </div>
</div>
HTML;
    }
}


if (!function_exists('lebagol_woocommerce_single_product_image_thumbnail_html')) {
    function lebagol_woocommerce_single_product_image_thumbnail_html($image, $attachment_id) {
        return wc_get_gallery_image_html($attachment_id, true);
    }
}

if (!function_exists('lebagol_woocommerce_single_product_image_thumbnail_html_sticky')) {
    function lebagol_woocommerce_single_product_image_thumbnail_html_sticky($image, $attachment_id) {
        return '<div class="swiper-slide">' . wc_get_gallery_image_html($attachment_id, true) . '</div>';
    }
}

if (!function_exists('woocommerce_template_loop_product_title')) {

    /**
     * Show the product title in the product loop.
     */
    function woocommerce_template_loop_product_title() {
        echo '<h3 class="woocommerce-loop-product__title"><a href="' . esc_url_raw(get_the_permalink()) . '">' . get_the_title() . '</a></h3>';
    }
}

if (!function_exists('lebagol_woocommerce_get_product_category')) {
    function lebagol_woocommerce_get_product_category() {
        global $product;
        echo wc_get_product_category_list($product->get_id(), ', ', '<div class="posted-in">', '</div>');
    }
}

if (!function_exists('lebagol_header_wishlist')) {
    function lebagol_header_wishlist() {
        if (function_exists('yith_wcwl_count_all_products')) {
            if (!lebagol_get_theme_option('show_header_wishlist', true)) {
                return;
            }
            ?>
            <div class="site-header-wishlist">
                <a class="header-wishlist" href="<?php echo esc_url(get_permalink(get_option('yith_wcwl_wishlist_page_id'))); ?>">
                    <i class="lebagol-icon-wishlist"></i>
                    <span class="count">(<?php echo esc_html(yith_wcwl_count_all_products()); ?>)</span>
                </a>
            </div>
            <?php
        } elseif (function_exists('woosw_init')) {
            if (!lebagol_get_theme_option('show_header_wishlist', true)) {
                return;
            }
            $key   = WPCleverWoosw::get_key();
            $class = WPCleverWoosw::get_count($key) > 0 ? 'count' : 'count hide';
            ?>
            <div class="site-header-wishlist">
                <a class="header-wishlist" href="<?php echo esc_url(WPCleverWoosw::get_url($key, true)); ?>">
                    <i class="lebagol-icon-wishlist"></i>
                    <span class="<?php echo esc_attr($class); ?>"><?php echo sprintf('%02d', WPCleverWoosw::get_count($key)); ?></span>
                </a>
            </div>
            <?php
        }
    }
}

if (!function_exists('lebagol_woocommerce_quantity_progress')) {
    function lebagol_woocommerce_quantity_progress() {
        global $product;
        if ($product->get_stock_quantity() && $product->get_stock_quantity() > 0) {
            $total_sales    = $product->get_total_sales();
            $stock_quantity = $product->get_stock_quantity();
            $stock_quantity = is_null($stock_quantity) ? 0 : $stock_quantity;
            $total_quantity = $total_sales + $stock_quantity;
            ?>
            <div class="quantity-progress">
                <div class="quantity-progress-text">
                    <span><?php echo esc_html__('Sold: ', 'lebagol'); ?></span><span class="value"><?php echo esc_html($total_sales); ?>/<?php echo esc_html($total_quantity); ?></span>
                </div>
                <div class="quantity-progress-bar">
                    <div class="progress-bar">
                        <div class="progress-value" style="width: <?php echo trim($total_sales / $total_quantity * 100) ?>%"></div>
                    </div>
                </div>
            </div>
            <?php
        }
    }
}
if (!function_exists('lebagol_single_product_time_sale')) {
    function lebagol_single_product_time_sale() {
        /**
         * @var $product WC_Product
         */
        global $product;

        if (!$product->is_on_sale()) {
            return;
        }

        $time_sale = '';
        $show      = '';
        $type      = 'simple';

        if ($product->is_type('variable')) {
            $time_sale            = [];
            $available_variations = $product->get_available_variations();
            foreach ($available_variations as $variation) {
                $variation_id = $variation['variation_id'];
                $time         = intval(get_post_meta($variation_id, '_sale_price_dates_to', true)) + (get_option('gmt_offset') * HOUR_IN_SECONDS);
                if ($time) {
                    $time_sale[$variation_id] = intval(get_post_meta($variation_id, '_sale_price_dates_to', true)) + (get_option('gmt_offset') * HOUR_IN_SECONDS);
                }
            }
            $show      = 'display:none;';
            $type      = 'variable';
            $time_sale = json_encode($time_sale);
        } else {
            $time = intval(get_post_meta($product->get_id(), '_sale_price_dates_to', true)) + (get_option('gmt_offset') * HOUR_IN_SECONDS);
            if ($time) {
                $time_sale = $time;
            }
        }
        if (!empty($time_sale)) {
            wp_enqueue_script('lebagol-countdown-single');

            ?>
            <div class="time-sale" style="<?php echo esc_attr($show); ?>">
                <div class="title"><?php echo esc_html__('Hurry up! Sale ends in', 'lebagol'); ?></div>
                <div class="lebagol-countdown <?php echo esc_attr($type); ?>" data-countdown="true" data-date="<?php echo esc_attr($time_sale); ?>">
                    <div class="countdown-item">
                        <span class="countdown-digits countdown-days"></span>
                        <span class="countdown-label"><?php echo esc_html__('days', 'lebagol'); ?></span>
                    </div>
                    <div class="countdown-item">
                        <span class="countdown-digits countdown-hours"></span>
                        <span class="countdown-label"><?php echo esc_html__('hrs', 'lebagol'); ?></span>
                    </div>
                    <div class="countdown-item">
                        <span class="countdown-digits countdown-minutes"></span>
                        <span class="countdown-label"><?php echo esc_html__('mins', 'lebagol'); ?></span>
                    </div>
                    <div class="countdown-item">
                        <span class="countdown-digits countdown-seconds"></span>
                        <span class="countdown-label"><?php echo esc_html__('secs', 'lebagol'); ?></span>
                    </div>
                </div>
            </div>
            <?php
        }
    }
}


if (!function_exists('lebagol_woocommerce_get_product_short_description')) {
    function lebagol_woocommerce_get_product_short_description() {
        global $post;
        $short_description = wp_trim_words(apply_filters('woocommerce_short_description', $post->post_excerpt), 20);
        if ($short_description) {
            ?>
            <p class="short-description">
                <?php echo sprintf('%s', $short_description); ?>
            </p>
            <?php
        }
    }
}
if (!function_exists('lebagol_woocommerce_group_action')) {
    function lebagol_woocommerce_group_action($position = 'left') {
        $class = 'tooltip-' . $position . ' ' . 'group-action'
        ?>
        <div class="<?php echo esc_attr($class); ?>">
            <div class="shop-action">
                <?php
                lebagol_wishlist_button();
                lebagol_compare_button();
                lebagol_quickview_button();
                ?>
            </div>
        </div>
        <?php
    }
}

if (!function_exists('lebagol_single_product_extra')) {
    function lebagol_single_product_extra() {
        global $product;
        $product_extra = lebagol_get_theme_option('single_product_content_meta', '');
        $product_extra = get_post_meta($product->get_id(), '_extra_info', true) !== '' ? get_post_meta($product->get_id(), '_extra_info', true) : $product_extra;
        if ($product_extra !== '') {
            echo '<div class="lebagol-single-product-extra">' . html_entity_decode($product_extra) . '</div>';
        }
    }
}

if (!function_exists('lebagol_button_shop_canvas')) {
    function lebagol_button_shop_canvas() {
        if (is_active_sidebar('sidebar-woocommerce-shop')) { ?>
            <a href="#" class="filter-toggle" aria-expanded="false">
                <i class="lebagol-icon-equalizer-line"></i><span><?php esc_html_e('Filter By', 'lebagol'); ?></span>
            </a>
            <?php
        }
    }
}

if (!function_exists('lebagol_render_woocommerce_shop_canvas')) {
    function lebagol_render_woocommerce_shop_canvas() {
        if (is_active_sidebar('sidebar-woocommerce-shop') && lebagol_is_product_archive()) {
            ?>
            <div id="lebagol-canvas-filter" class="lebagol-canvas-filter">
                <div class="lebagol-canvas-header">
                    <span class="filter-close">
                        <svg class="close-icon" xmlns="http://www.w3.org/2000/svg" width="23.691" height="22.723" viewBox="0 0 23.691 22.723">
                        <g transform="translate(-126.154 -143.139)">
                            <line x2="23" y2="22" transform="translate(126.5 143.5)" fill="none" stroke="CurrentColor" stroke-width="1"></line>
                            <path d="M0,22,23,0" transform="translate(126.5 143.5)" fill="none" stroke="CurrentColor" stroke-width="1"></path>
                        </g>
                    </svg>
                    </span>
                </div>

                <div class="lebagol-canvas-filter-wrap">
                    <?php if (lebagol_get_theme_option('woocommerce_archive_layout') == 'canvas' || lebagol_get_theme_option('woocommerce_archive_layout') == 'menu' || lebagol_get_theme_option('woocommerce_archive_layout') == 'fullwidth') {
                        dynamic_sidebar('sidebar-woocommerce-shop');
                    }
                    ?>
                </div>
            </div>
            <div class="lebagol-overlay-filter"></div>
            <?php
        }
    }
}

if (!function_exists('lebagol_render_woocommerce_shop_dropdown')) {
    function lebagol_render_woocommerce_shop_dropdown() {
        ?>
        <div id="lebagol-dropdown-filter" class="lebagol-dropdown-filter">
            <div class="lebagol-dropdown-filter-wrap">
                <?php
                dynamic_sidebar('sidebar-woocommerce-shop');
                ?>
            </div>
        </div>
        <?php
    }
}


if (!function_exists('lebagol_render_woocommerce_shop_menu')) {
    function lebagol_render_woocommerce_shop_menu() {
        ?>
        <div id="lebagol-menu-filter" class="lebagol-menu-filter">
            <div class="lebagol-menu-filter-wrap">
                <?php
                dynamic_sidebar('sidebar-woocommerce-shop');
                ?>
            </div>
        </div>
        <?php
    }
}

if (!function_exists('woocommerce_checkout_order_review_start')) {

    function woocommerce_checkout_order_review_start() {
        echo '<div class="checkout-review-order-table-wrapper">';
    }
}

if (!function_exists('woocommerce_checkout_order_review_end')) {

    function woocommerce_checkout_order_review_end() {
        echo '</div>';
    }
}

if (!function_exists('lebagol_output_product_data_accordion')) {
    function lebagol_output_product_data_accordion() {
        $product_tabs = apply_filters('woocommerce_product_tabs', array());
        if (!empty($product_tabs)) : ?>
            <div id="lebagol-accordion-container" class="woocommerce-tabs wc-tabs-wrapper product-accordions">
                <?php $_count = 0; ?>
                <?php foreach ($product_tabs as $key => $tab) : ?>
                    <div class="accordion-item">
                        <div class="accordion-head <?php echo esc_attr($key); ?>_tab js-btn-accordion" id="tab-title-<?php echo esc_attr($key); ?>">
                            <div class="accordion-title">
                                <?php echo apply_filters('woocommerce_product_' . $key . '_tab_title', esc_html($tab['title']), $key); ?>
                            </div>
                        </div>
                        <div class="accordion-body js-card-body">
                            <?php call_user_func($tab['callback'], $key, $tab); ?>
                        </div>
                    </div>
                    <?php $_count++; ?>
                <?php endforeach; ?>
            </div>
        <?php endif;
    }
}

if (!function_exists('lebagol_quickview_button')) {
    function lebagol_quickview_button() {
        if (function_exists('woosq_init')) {
            echo do_shortcode('[woosq]');
        }
    }
}

if (!function_exists('lebagol_compare_button')) {
    function lebagol_compare_button() {
        if (function_exists('woosc_init')) {
            echo do_shortcode('[woosc]');
        }
    }
}

if (!function_exists('lebagol_wishlist_button')) {
    function lebagol_wishlist_button() {
        if (function_exists('woosw_init')) {
            echo do_shortcode('[woosw]');
        }
    }
}

function lebagol_ajax_add_to_cart_add_fragments($fragments) {
    $all_notices  = WC()->session->get('wc_notices', array());
    $notice_types = apply_filters('woocommerce_notice_types', array('error', 'success', 'notice'));

    ob_start();
    foreach ($notice_types as $notice_type) {
        if (wc_notice_count($notice_type) > 0) {
            wc_get_template("notices/{$notice_type}.php", array(
                'notices' => array_filter($all_notices[$notice_type]),
            ));
        }
    }
    $fragments['notices_html'] = ob_get_clean();

    wc_clear_notices();

    return $fragments;
}

add_filter('woocommerce_add_to_cart_fragments', 'lebagol_ajax_add_to_cart_add_fragments');


if (!function_exists('lebagol_ajax_search_result')) {
    function lebagol_ajax_search_result() {
        ?>
        <div class="ajax-search-result d-none">
        </div>
        <?php
    }
}
add_action('pre_get_product_search_form', 'lebagol_ajax_search_result');

if (!function_exists('lebagol_ajax_live_search_template')) {
    function lebagol_ajax_live_search_template() {
        echo <<<HTML
        <script type="text/html" id="tmpl-ajax-live-search-template">
        
        <# if(data.type == 'title'){ #>
        <h5 class="search-title">{{{data.title}}}</h5>
        <# } #>
        <# if(data.type == 'product'){ #>
        <div class="product-item-search">
        <a class="product-link" href="{{{data.url}}}" title="{{{data.title}}}">
            <# if(data.img){#>
            <img src="{{{data.img}}}" alt="{{{data.title}}}">
             <# } #>
            <div class="product-content">
            <h3 class="product-title">{{{data.title}}}</h3>
            <# if(data.price){ #>
            {{{data.price}}}
             <# } #>
            </div>
        </a>
        </div>
        <# } #>
      <# if(data.type == 'cat'){ #>
        <div class="product-item-search">
        <a class="product-link" href="{{{data.url}}}" title="{{{data.title}}}">
            <h3 class="product-title">{{{data.title}}}</h3>
        </a>
        </div>
      <# } #>
        
        </script>
HTML;
    }
}
add_action('wp_footer', 'lebagol_ajax_live_search_template');

if (!function_exists('lebagol_shop_page_link')) {
    function lebagol_shop_page_link($keep_query = false, $taxonomy = '') {
        // Base Link decided by current page
        if (is_post_type_archive('product') || is_page(wc_get_page_id('shop')) || is_shop()) {
            $link = get_permalink(wc_get_page_id('shop'));
        } elseif (is_product_category()) {
            $link = get_term_link(get_query_var('product_cat'), 'product_cat');
        } elseif (is_product_tag()) {
            $link = get_term_link(get_query_var('product_tag'), 'product_tag');
        } else {
            $queried_object = get_queried_object();
            $link           = get_term_link($queried_object->slug, $queried_object->taxonomy);
        }

        if ($keep_query) {

            // Min/Max
            if (isset($_GET['min_price'])) {
                $link = add_query_arg('min_price', wc_clean($_GET['min_price']), $link);
            }

            if (isset($_GET['max_price'])) {
                $link = add_query_arg('max_price', wc_clean($_GET['max_price']), $link);
            }

            // Orderby
            if (isset($_GET['orderby'])) {
                $link = add_query_arg('orderby', wc_clean($_GET['orderby']), $link);
            }

            // Status
            if (isset($_GET['stock_status'])) {
                $link = add_query_arg('stock_status', wc_clean($_GET['stock_status']), $link);
            }

            if (isset($_GET['on_sale'])) {
                $link = add_query_arg('on_sale', wc_clean($_GET['on_sale']), $link);
            }

            if (isset($_GET['woocommerce_catalog_columns'])) {
                $link = add_query_arg('woocommerce_catalog_columns', wc_clean($_GET['woocommerce_catalog_columns']), $link);
            }

            if (isset($_GET['woocommerce_archive_layout'])) {
                $link = add_query_arg('woocommerce_archive_layout', wc_clean($_GET['woocommerce_archive_layout']), $link);
            }

            if (isset($_GET['wocommerce_block_style'])) {
                $link = add_query_arg('wocommerce_block_style', wc_clean($_GET['wocommerce_block_style']), $link);
            }

            /**
             * Search Arg.
             * To support quote characters, first they are decoded from &quot; entities, then URL encoded.
             */
            if (get_search_query()) {
                $link = add_query_arg('s', rawurlencode(wp_specialchars_decode(get_search_query())), $link);
            }

            // Post Type Arg
            if (isset($_GET['post_type'])) {
                $link = add_query_arg('post_type', wc_clean($_GET['post_type']), $link);
            }

            // Min Rating Arg
            if (isset($_GET['min_rating'])) {
                $link = add_query_arg('min_rating', wc_clean($_GET['min_rating']), $link);
            }

            // All current filters
            if ($_chosen_attributes = WC_Query::get_layered_nav_chosen_attributes()) {
                foreach ($_chosen_attributes as $name => $data) {
                    if ($name === $taxonomy) {
                        continue;
                    }
                    $filter_name = sanitize_title(str_replace('pa_', '', $name));
                    if (!empty($data['terms'])) {
                        $link = add_query_arg('filter_' . $filter_name, implode(',', $data['terms']), $link);
                    }
                    if ('or' == $data['query_type']) {
                        $link = add_query_arg('query_type_' . $filter_name, 'or', $link);
                    }
                }
            }
        }

        if (is_string($link)) {
            return $link;
        } else {
            return '';
        }
    }
}


if (!function_exists('lebagol_products_per_page_select')) {

    function lebagol_products_per_page_select() {
        if ((wc_get_loop_prop('is_shortcode') || !wc_get_loop_prop('is_paginated') || !woocommerce_products_will_display())) return;

        $row          = wc_get_default_products_per_row();
        $max_col      = apply_filters('lebagol_products_row_step_max', 6);
        $array_option = [];
        if ($max_col > 2) {
            for ($i = 2; $i <= $max_col; $i++) {
                $array_option[] = $row * $i;
            }
        } else {
            return;
        }

        $col = wc_get_default_product_rows_per_page();

        $products_per_page_options = apply_filters('lebagol_products_per_page_options', $array_option);

        $current_variation = isset($_GET['per_page']) ? $_GET['per_page'] : $col * $row;
        ?>

        <div class="lebagol-products-per-page">

            <label for="per_page" class="per-page-title"><?php esc_html_e('Show', 'lebagol'); ?></label>
            <select name="per_page" id="per_page" class="per_page">
                <?php
                foreach ($products_per_page_options as $key => $value) :

                    ?>
                    <option value="<?php echo add_query_arg('per_page', $value, lebagol_shop_page_link(true)); ?>" <?php echo esc_attr($current_variation == $value ? 'selected' : ''); ?>>
                        <?php echo esc_html($value); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <?php
    }
}


if (isset($_GET['per_page'])) {
    add_filter('loop_shop_per_page', 'lebagol_loop_shop_per_page', 20);
}

function lebagol_loop_shop_per_page($cols) {

    $cols = isset($_GET['per_page']) ? $_GET['per_page'] : $cols;

    return $cols;
}

if (!function_exists('lebagol_active_filters')) {
    function lebagol_active_filters() {
        global $wp;
        $url             = home_url(add_query_arg(array(), $wp->request));
        $link_remove_all = strtok($url, '?');
        echo '<div class="lebagol-active-filters">';
        the_widget('WC_Widget_Layered_Nav_Filters');

        $on_sale      = isset($_GET['on_sale']) ? wc_clean(wp_unslash($_GET['on_sale'])) : 0;
        $stock_status = isset($_GET['stock_status']) ? wc_clean(wp_unslash($_GET['stock_status'])) : 0;
        $base_link    = lebagol_shop_page_link(true);
        if ($on_sale || $stock_status) {
            echo '<div class="widget woocommerce widget_layered_nav_filters"><ul>';
            if ($on_sale) {
                $link = remove_query_arg('on_sale', $base_link);
                echo '<li class="chosen"><a rel="nofollow" aria-label="' . esc_attr__('Remove filter', 'lebagol') . '" href="' . esc_url($link) . '">' . esc_html__('On Sale', 'lebagol') . '</a></li>';
            }

            if ($stock_status) {
                $link = remove_query_arg('stock_status', $base_link);
                echo '<li class="chosen"><a rel="nofollow" aria-label="' . esc_attr__('Remove filter', 'lebagol') . '" href="' . esc_url($link) . '">' . esc_html__('In Stock', 'lebagol') . '</a></li>';
            }
            echo '</ul></div>';
        }


        echo '<a class="clear-all" href="' . esc_url($link_remove_all) . '">' . esc_html__('Clear All', 'lebagol') . '</a></div>';
    }
}

if (!function_exists('lebagol_add_technical_specs_product_tab')) {

    function lebagol_add_technical_specs_product_tab($tabs) {
        global $product;
        if ($product->get_meta('_technical_specs') !== '') {

            $tabs['additional_information'] = array(
                'title'    => esc_html__('Additional information', 'lebagol'),
                'priority' => 20,
                'callback' => 'lebagol_display_technical_specs_product_tab_content'

            );
        }

        return $tabs;
    }
}

add_filter('woocommerce_product_tabs', 'lebagol_add_technical_specs_product_tab', 20, 1);

if (!function_exists('lebagol_display_technical_specs_product_tab_content')) {
    function lebagol_display_technical_specs_product_tab_content() {
        global $product;
        echo '<div class="wrapper-technical_specs">' . lebagol_parse_text_editor($product->get_meta('_technical_specs')) . '</div>';
    }
}

if (!function_exists('lebagol_single_product_no_tab')) {
    function lebagol_single_product_no_tab() {
        $product_tabs = apply_filters('woocommerce_product_tabs', array());
        if (!empty($product_tabs)) :
            ?>
            <div class="lebagol-product-no-tabs">
                <?php foreach ($product_tabs as $key => $product_tab) : ?>
                    <div id="tab-<?php echo esc_attr($key); ?>" class="section">
                        <?php printf('<h2>%s</h2>', $product_tab['title']) ?>
                        <div class="content">
                            <?php
                            if (isset($product_tab['callback'])) {
                                call_user_func($product_tab['callback'], $key, $product_tab);
                            }
                            ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif;
    }
}

if (!function_exists('lebagol_single_product_related_products')) {
    function lebagol_single_product_related_products() {
        if (is_singular('product')) {
            ?>
            <div class="lebagol-related-products">
                <div class="col-full">
                    <?php
                    woocommerce_upsell_display();
                    woocommerce_output_related_products();
                    ?>
                </div>
            </div>
            <?php
        }
    }
}

if (!function_exists('lebagol_add_quantity_field')) {
    function lebagol_add_quantity_field() {
        global $product;
        if (!$product->is_sold_individually() && 'variable' != $product->get_type() && $product->is_in_stock()) {
            woocommerce_quantity_input(array('min_value' => 1, 'max_value' => $product->backorders_allowed() ? '' : $product->get_stock_quantity()));
        }
    }
}

if (!function_exists('lebagol_single_product_no_tab')) {
    function lebagol_single_product_no_tab() {
        $product_tabs = apply_filters('woocommerce_product_tabs', array());
        if (!empty($product_tabs)) :
            ?>
            <div class="lebagol-product-no-tabs">
                <?php foreach ($product_tabs as $key => $product_tab) : ?>
                    <div id="tab-<?php echo esc_attr($key); ?>" class="section">
                        <?php printf('<h2>%s</h2>', $product_tab['title']) ?>
                        <div class="content">
                            <?php
                            if (isset($product_tab['callback'])) {
                                call_user_func($product_tab['callback'], $key, $product_tab);
                            }
                            ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif;
    }
}

if (!function_exists('lebagol_single_product_ask_a_question')) {
    function lebagol_single_product_ask_a_question() {
        if (lebagol_is_contactform_activated()) {
            $ask_a_question_form = lebagol_get_theme_option('single_product_ask', '');
            if (!empty($ask_a_question_form)) {
                echo '<div class="ask-a-question-wrap">';
                echo '<a href="#product-ask-a-question-popup" class="ask-a-question-button" data-effect="mfp-move-horizontal"><i class="lebagol-icon-messages-question"></i><span>' . esc_html__('Ask a Question', 'lebagol') . '</span></a>';
                echo '<div id="product-ask-a-question-popup" class="mfp-hide single-product-popup mfp-with-anim"><div class="popup-content">' . lebagol_do_shortcode('contact-form-7', ['id' => $ask_a_question_form]) . '</div></div>';
                echo '</div>';
            }
        }
    }
}

add_action('lebagol_woocommerce_single_product_loop_action', 'lebagol_single_product_ask_a_question', 5);