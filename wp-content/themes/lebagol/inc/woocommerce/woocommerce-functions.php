<?php
/**
 * Checks if the current page is a product archive
 *
 * @return boolean
 */
function lebagol_is_product_archive() {
    if (is_shop() || is_product_taxonomy() || is_product_category() || is_product_tag()) {
        return true;
    } else {
        return false;
    }
}

/**
 * @param $product WC_Product
 */
function lebagol_product_get_image($product) {
    return $product->get_image();
}

/**
 * @param $product WC_Product
 */
function lebagol_product_get_price_html($product) {
    return $product->get_price_html();
}

/**
 * Retrieves the previous product.
 *
 * @param bool $in_same_term Optional. Whether post should be in a same taxonomy term. Default false.
 * @param array|string $excluded_terms Optional. Comma-separated list of excluded term IDs. Default empty.
 * @param string $taxonomy Optional. Taxonomy, if $in_same_term is true. Default 'product_cat'.
 * @return WC_Product|false Product object if successful. False if no valid product is found.
 * @since 2.4.3
 *
 */
function lebagol_get_previous_product($in_same_term = false, $excluded_terms = '', $taxonomy = 'product_cat') {
    $product = new Lebagol_WooCommerce_Adjacent_Products($in_same_term, $excluded_terms, $taxonomy, true);
    return $product->get_product();
}

/**
 * Retrieves the next product.
 *
 * @param bool $in_same_term Optional. Whether post should be in a same taxonomy term. Default false.
 * @param array|string $excluded_terms Optional. Comma-separated list of excluded term IDs. Default empty.
 * @param string $taxonomy Optional. Taxonomy, if $in_same_term is true. Default 'product_cat'.
 * @return WC_Product|false Product object if successful. False if no valid product is found.
 * @since 2.4.3
 *
 */
function lebagol_get_next_product($in_same_term = false, $excluded_terms = '', $taxonomy = 'product_cat') {
    $product = new Lebagol_WooCommerce_Adjacent_Products($in_same_term, $excluded_terms, $taxonomy);
    return $product->get_product();
}


function lebagol_is_woocommerce_extension_activated($extension = 'WC_Bookings') {
    if ($extension == 'YITH_WCQV') {
        return class_exists($extension) && class_exists('YITH_WCQV_Frontend') ? true : false;
    }

    return class_exists($extension) ? true : false;
}

function lebagol_woocommerce_pagination_args($args) {
    $args['prev_text'] =  esc_html__('', 'lebagol');
    $args['next_text'] =  esc_html__('', 'lebagol') ;
    return $args;
}

add_filter('woocommerce_pagination_args', 'lebagol_woocommerce_pagination_args', 10, 1);

/**
 * Check if a product is a deal
 *
 * @param int|object $product
 *
 * @return bool
 */
function lebagol_woocommerce_is_deal_product($product) {
    $product = is_numeric($product) ? wc_get_product($product) : $product;

    // It must be a sale product first
    if (!$product->is_on_sale()) {
        return false;
    }

    if (!$product->is_in_stock()) {
        return false;
    }

    // Only support product type "simple" and "external"
    if (!$product->is_type('simple') && !$product->is_type('external')) {
        return false;
    }

    $deal_quantity = get_post_meta($product->get_id(), '_deal_quantity', true);

    if ($deal_quantity > 0) {
        return true;
    }

    return false;
}

function woocommerce_template_loop_rating() {

    global $product;
    if (!wc_review_ratings_enabled()) {
        return;
    }
    $count = $product->get_review_count();
    if ($rating_html = wc_get_rating_html($product->get_average_rating())) {
        echo apply_filters('lebagol_woocommerce_rating_html',   '<div class="count-review">' . $rating_html.'<span class="count">('.$count.')</span></div>');
    } else {
        echo '<div class="count-review"><span class="star-rating"></span><span class="count">('.$count.')</span></div>';
    }
}


if (!function_exists('lebagol_ajax_search_products')) {
    function lebagol_ajax_search_products() {
        global $woocommerce;
        $search_keyword = $_REQUEST['query'];
        check_ajax_referer('lebagol_ajax_nonce', 'security_search');
        $ordering_args = $woocommerce->query->get_catalog_ordering_args('date', 'desc');
        $suggestions   = array();

        $args_cat = array(
            'taxonomy'   => 'product_cat',
            'hide_empty' => false,
            'name__like' => apply_filters('lebagol_ajax_search_products_search_query', $search_keyword),
        );

        $categories = get_terms($args_cat);

        if (!empty($categories)) {
            $suggestions[] = array(
                'value' => esc_html__('Categories', 'lebagol'),
                'type'  => 'title',
            );
            foreach ($categories as $category) {
                $suggestions[] = array(
                    'value' => strip_tags($category->name),
                    'url'   => get_term_link($category),
                    'type'  => 'cat',
                );
            }
        }

        $args = array(
            's'                   => apply_filters('lebagol_ajax_search_products_search_query', $search_keyword),
            'post_type'           => 'product',
            'post_status'         => 'publish',
            'ignore_sticky_posts' => 1,
            'orderby'             => $ordering_args['orderby'],
            'order'               => $ordering_args['order'],
            'posts_per_page'      => apply_filters('lebagol_ajax_search_products_posts_per_page', 8),

        );

        $args['tax_query']['relation'] = 'AND';

        if (!empty($_REQUEST['product_cat'])) {
            $args['tax_query'][] = array(
                'taxonomy' => 'product_cat',
                'field'    => 'slug',
                'terms'    => strip_tags($_REQUEST['product_cat']),
                'operator' => 'IN'
            );
        }

        $products = get_posts($args);

        if (!empty($products)) {
            $suggestions[] = array(
                'value' => esc_html__('Products', 'lebagol'),
                'type'  => 'title',
            );
            foreach ($products as $post) {
                $product       = wc_get_product($post);
                $product_image = wp_get_attachment_image_src(get_post_thumbnail_id($product->get_id()));

                $suggestions[] = apply_filters('lebagol_suggestion', array(
                    'value' => strip_tags($product->get_title()),
                    'url'   => $product->get_permalink(),
                    'img'   => esc_url($product_image[0]),
                    'price' => $product->get_price_html(),
                    'type'  => 'product',
                ), $product);
            }
        }

        wp_reset_postdata();

        $args = array(
            'posts_per_page'  => -1,
            'post_type'       => 'product',
            'meta_query' => array(
                array(
                    'key' => '_sku',
                    'value' => $search_keyword,
                    'compare' => 'LIKE'
                )
            )
        );

        $posts = get_posts($args);
        if(!empty($posts)){
            $suggestions[] = array(
                'value' => esc_html__('Products By Sku', 'lebagol'),
                'type'  => 'title',
            );
            foreach($posts as $post){
                $product       = wc_get_product($post);
                $product_image = wp_get_attachment_image_src(get_post_thumbnail_id($product->get_id()));

                $suggestions[] = apply_filters('lebagol_suggestion', array(
                    'value' => strip_tags($product->get_title()),
                    'url'   => $product->get_permalink(),
                    'img'   => esc_url($product_image[0]),
                    'price' => $product->get_price_html(),
                    'type'  => 'product',
                ), $product);
            }
        }

        wp_reset_postdata();


        if (empty($suggestions)) {
            $suggestions[] = array(
                'value' => esc_html__('No results', 'lebagol'),
                'type'  => 'title',
            );
        }

        echo json_encode($suggestions);
        die();
    }
}

add_action('wp_ajax_lebagol_ajax_search_products', 'lebagol_ajax_search_products');
add_action('wp_ajax_nopriv_lebagol_ajax_search_products', 'lebagol_ajax_search_products');

if (!function_exists('lebagol_get_review_counting')) {
    function lebagol_get_review_counting() {

        global $post;
        $output = array();

        for ($i = 1; $i <= 5; $i++) {
            $args = array(
                'post_id'    => ($post->ID),
                'meta_query' => array(
                    array(
                        'key'   => 'rating',
                        'value' => $i
                    )
                ),
                'count'      => true
            );
            $output[$i] = get_comments($args);
        }

        return $output;
    }
}