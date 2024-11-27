<?php
if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('Lebagol_WooCommerce')) :

    /**
     * The Lebagol WooCommerce Integration class
     */
    class Lebagol_WooCommerce {

        public $list_shortcodes;

        /**
         * Setup class.
         *
         * @since 1.0
         */
        public function __construct() {
            $this->list_shortcodes = array(
                'recent_products',
                'sale_products',
                'best_selling_products',
                'top_rated_products',
                'featured_products',
                'related_products',
                'product_category',
                'products',
            );
            $this->init_shortcodes();

            add_action('after_setup_theme', array($this, 'setup'));
            add_filter('body_class', array($this, 'woocommerce_body_class'));
            add_action('widgets_init', array($this, 'widgets_init'));
            add_filter('lebagol_theme_sidebar', array($this, 'set_sidebar'), 20);
            add_action('wp_enqueue_scripts', array($this, 'woocommerce_scripts'), 20);
            add_filter('woocommerce_enqueue_styles', '__return_empty_array');
            add_filter('woocommerce_output_related_products_args', array($this, 'related_products_args'));
            add_filter('woocommerce_upsell_display_args', array($this, 'upsell_products_args'));

            if (defined('WC_VERSION') && version_compare(WC_VERSION, '3.3', '<')) {
                add_filter('loop_shop_per_page', array($this, 'products_per_page'));
            }

            // Remove Shop Title
            add_filter('woocommerce_show_page_title', '__return_false');

            add_filter('lebagol_register_nav_menus', [$this, 'add_location_menu']);
            add_filter('wp_nav_menu_items', [$this, 'add_extra_item_to_nav_menu'], 10, 2);

            add_filter('woocommerce_single_product_image_gallery_classes', function ($wrapper_classes) {
                $product_image_single_style = lebagol_get_theme_option('single_product_gallery_layout', 'horizontal');
                if (in_array($product_image_single_style, ['with-sidebar', 'sticky-sidebar'])) {
                    $wrapper_classes[] = 'woocommerce-product-gallery-horizontal';
                }
                if ($product_image_single_style == 'sticky-summary') {
                    $wrapper_classes[] = 'woocommerce-product-gallery-vertical';
                }
                $wrapper_classes[] = 'woocommerce-product-gallery-' . $product_image_single_style;
                return $wrapper_classes;
            });

            add_action('woocommerce_grouped_product_list_before_label', array(
                $this,
                'grouped_product_column_image'
            ), 10, 1);

            // Elementor Admin
            add_action('admin_action_elementor', array($this, 'register_elementor_wc_hook'), 1);

            add_filter('woocommerce_loop_add_to_cart_args', array($this, 'wocommerce_aria_describedby'), 10, 2);

            add_action('wp_enqueue_scripts', [$this, 'woocommerce_custom_css']);

        }

        public function woocommerce_custom_css() {
            $horizontal_ratio = lebagol_get_theme_option('wocommerce_block_horizontal_ratio', '1/1');
            $horizontal_bg    = lebagol_get_theme_option('wocommerce_block_horizontal_bg', '#f5f5f5');
            $css              = '--product-img-hz-ratio:' . $horizontal_ratio . '; --product-img-bg:' . $horizontal_bg . ';';
            $var              = "body{{$css}}";
            wp_add_inline_style('lebagol-style', $var);
        }

        public function wocommerce_aria_describedby($args, $product) {
            if (isset($args['attributes']['aria-describedby'])) {
                unset($args['attributes']['aria-describedby']);
            }
            return $args;
        }

        public function register_elementor_wc_hook() {
            wc()->frontend_includes();
            require_once get_theme_file_path('inc/woocommerce/woocommerce-template-hooks.php');
            require_once get_theme_file_path('inc/woocommerce/template-hooks.php');
            lebagol_include_hooks_product_blocks();
        }

        public function add_extra_item_to_nav_menu($items, $args) {
            if ($args->theme_location == 'my-account') {
                $items .= '<li><a href="' . esc_url(wp_logout_url(home_url())) . '">' . esc_html__('Logout', 'lebagol') . '</a></li>';
            }

            return $items;
        }

        public function add_location_menu($locations) {
            $locations['my-account'] = esc_html__('My Account', 'lebagol');

            return $locations;
        }

        /**
         * Sets up theme defaults and registers support for various WooCommerce features.
         *
         * Note that this function is hooked into the after_setup_theme hook, which
         * runs before the init hook. The init hook is too late for some features, such
         * as indicating support for post thumbnails.
         *
         * @return void
         * @since 2.4.0
         */
        public function setup() {
            add_theme_support(
                'woocommerce', apply_filters(
                    'lebagol_woocommerce_args', array(
                        'product_grid' => array(
                            'default_columns' => 3,
                            'default_rows'    => 4,
                            'min_columns'     => 1,
                            'max_columns'     => 6,
                            'min_rows'        => 1,
                        ),
                    )
                )
            );


            /**
             * Add 'lebagol_woocommerce_setup' action.
             *
             * @since  2.4.0
             */
            do_action('lebagol_woocommerce_setup');
        }


        public function action_woocommerce_before_template_part($template_name, $template_path, $located, $args) {
            $product_style = lebagol_get_theme_option('wocommerce_block_style', 0);
            if ($product_style != 0 && ($template_name == 'single-product/up-sells.php' || $template_name == 'single-product/related.php' || $template_name == 'cart/cross-sells.php')) {
                $template_custom = 'content-product-' . $product_style . '.php';
                add_filter('wc_get_template_part', function ($template, $slug, $name) use ($template_custom) {
                    if ($slug == 'content' && $name == 'product') {
                        return get_theme_file_path('woocommerce/' . $template_custom);
                    } else {
                        return $template;
                    }
                }, 10, 3);
            }
        }

        public function action_woocommerce_after_template_part($template_name, $template_path, $located, $args) {
            $product_style = lebagol_get_theme_option('wocommerce_block_style', 0);
            if ($product_style != 0 && ($template_name == 'single-product/up-sells.php' || $template_name == 'single-product/related.php' || $template_name == 'cart/cross-sells.php')) {
                add_filter('wc_get_template_part', function ($template, $slug, $name) {
                    if ($slug == 'content' && $name == 'product') {
                        return get_theme_file_path('woocommerce/content-product.php');
                    } else {
                        return $template;
                    }
                }, 10, 3);
            }
        }

        private function init_shortcodes() {
            foreach ($this->list_shortcodes as $shortcode) {
                add_filter('shortcode_atts_' . $shortcode, array($this, 'set_shortcode_attributes'), 10, 3);

                add_action('woocommerce_shortcode_before_' . $shortcode . '_loop', array(
                    $this,
                    'shortcode_loop_start'
                ));
                add_action('woocommerce_shortcode_after_' . $shortcode . '_loop', array(
                    $this,
                    'shortcode_loop_end'
                ));
            }
        }

        public function shortcode_loop_end($atts = array()) {

            if (isset($atts['style'])) {
                if ($atts['style'] !== '') {

                    add_filter('wc_get_template_part', function ($template, $slug, $name) {
                        if ($slug == 'content' && $name == 'product') {
                            return get_theme_file_path('woocommerce/content-product.php');
                        } else {
                            return $template;
                        }
                    }, 10, 3);
                }
            }
        }

        public function shortcode_loop_start($atts = array()) {

            if (isset($atts['style'])) {
                if ($atts['style'] !== '') {
                    $template_custom = 'content-product-' . $atts['style'] . '.php';

                    add_filter('wc_get_template_part', function ($template, $slug, $name) use ($template_custom) {
                        if ($slug == 'content' && $name == 'product') {
                            return get_theme_file_path('woocommerce/' . $template_custom);
                        } else {
                            return $template;
                        }
                    }, 10, 3);
                }
            }

            if (isset($atts['product_layout']) && $atts['product_layout'] === 'carousel') {
                wc_set_loop_prop('product-carousel', 'swiper-wrapper');
            }
        }

        public function set_shortcode_attributes($out, $pairs, $atts) {
            $out = wp_parse_args($atts, $out);

            return $out;
        }


        /**
         * Assign styles to individual theme mod.
         *
         * @return void
         * @since 2.1.0
         * @deprecated 2.3.1
         */
        public function set_lebagol_style_theme_mods() {
            if (function_exists('wc_deprecated_function')) {
                wc_deprecated_function(__FUNCTION__, '2.3.1');
            } else {
                _deprecated_function(__FUNCTION__, '2.3.1');
            }
        }

        /**
         * Add WooCommerce specific classes to the body tag
         *
         * @param array $classes css classes applied to the body tag.
         *
         * @return array $classes modified to include 'woocommerce-active' class
         */
        public function woocommerce_body_class($classes) {
            $classes[] = 'woocommerce-active';

            // Remove `no-wc-breadcrumb` body class.
            $key = array_search('no-wc-breadcrumb', $classes, true);

            if (false !== $key) {
                unset($classes[$key]);
            }

            $style   = lebagol_get_theme_option('wocommerce_block_style', 1);
            $layout  = lebagol_get_theme_option('woocommerce_archive_layout', 'default');
            $sidebar = lebagol_get_theme_option('woocommerce_archive_sidebar', 'left');
            $fullwidth = lebagol_get_theme_option('wocommerce_archive_enable_full', '');

            $classes[] = 'product-block-style-' . $style;

            if (lebagol_is_product_archive()) {
                $classes[] = 'lebagol-archive-product';

                if (is_active_sidebar('sidebar-woocommerce-shop')) {

                    if ($layout == 'default') {
                        $classes[] = 'lebagol-sidebar-' . $sidebar;
                    } else {
                        $classes[] = 'lebagol-full-width-content shop_filter_' . $layout;
                    }
                } else {
                    $classes[] = 'lebagol-full-width-content';
                }

                if($fullwidth == '1'){
                    $classes[] = 'lebagol-fluid-width-content';
                }

            }

            if (is_product()) {
                $classes[] = 'lebagol-full-width-content';
                $classes[] = 'single-product-' . lebagol_get_theme_option('single_product_gallery_layout', 'horizontal');
            }
            return $classes;
        }

        /**
         * WooCommerce specific scripts & stylesheets
         *
         * @since 1.0.0
         */
        public function woocommerce_scripts() {

            $suffix = (defined('SCRIPT_DEBUG') && SCRIPT_DEBUG) ? '' : '.min';
            wp_enqueue_script('wc-add-to-cart-variation');
            wp_enqueue_style('lebagol-woocommerce-style', get_template_directory_uri() . '/assets/css/woocommerce/woocommerce.css', array(), LEBAGOL_VERSION);
            wp_style_add_data('lebagol-woocommerce-style', 'rtl', 'replace');

            if (defined('WC_VERSION') && version_compare(WC_VERSION, '3.3', '<')) {
                wp_enqueue_style('lebagol-woocommerce-legacy', get_template_directory_uri() . '/assets/css/woocommerce/woocommerce-legacy.css', array(), LEBAGOL_VERSION);
                wp_style_add_data('lebagol-woocommerce-legacy', 'rtl', 'replace');
            }

            if (is_shop() || is_product() || is_product_taxonomy()) {
                wp_enqueue_script('tooltipster');
                wp_enqueue_style('tooltipster');
                wp_enqueue_script('lebagol-shop-select', get_template_directory_uri() . '/assets/js/woocommerce/shop-select' . $suffix . '.js', array('jquery'), LEBAGOL_VERSION, true);
                wp_enqueue_script('lebagol-shop', get_template_directory_uri() . '/assets/js/woocommerce/shop' . $suffix . '.js', array(
                    'jquery', 'lebagol-waypoints'
                ), LEBAGOL_VERSION, true);
            }
            if (lebagol_elementor_check_type('lebagol-products')) {
                wp_enqueue_script('tooltipster');
                wp_enqueue_style('tooltipster');
            }

            wp_enqueue_script('lebagol-products-ajax-search', get_template_directory_uri() . '/assets/js/woocommerce/product-ajax-search' . $suffix . '.js', array(
                'jquery'
            ), LEBAGOL_VERSION, true);
            wp_enqueue_script('lebagol-products', get_template_directory_uri() . '/assets/js/woocommerce/main' . $suffix . '.js', array(
                'jquery',
            ), LEBAGOL_VERSION, true);

            wp_enqueue_script('lebagol-input-quantity', get_template_directory_uri() . '/assets/js/woocommerce/quantity' . $suffix . '.js', array(), LEBAGOL_VERSION, true);

            if (is_active_sidebar('sidebar-woocommerce-shop')) {
                wp_enqueue_script('lebagol-off-canvas', get_template_directory_uri() . '/assets/js/woocommerce/off-canvas' . $suffix . '.js', array(), LEBAGOL_VERSION, true);
            }
            wp_enqueue_script('lebagol-cart-canvas', get_template_directory_uri() . '/assets/js/woocommerce/cart-canvas' . $suffix . '.js', array(), LEBAGOL_VERSION, true);

            wp_register_script('lebagol-countdown-single', get_template_directory_uri() . '/assets/js/woocommerce/single-countdown' . $suffix . '.js', array('jquery'), LEBAGOL_VERSION, true);

            if (is_product()) {
                wp_enqueue_script('lebagol-sticky-add-to-cart', get_template_directory_uri() . '/assets/js/sticky-add-to-cart' . $suffix . '.js', array(), LEBAGOL_VERSION, true);
                wp_enqueue_script('sticky-kit');
                wp_enqueue_script('magnific-popup');
                wp_enqueue_style('magnific-popup');
                if (lebagol_is_elementor_activated()) {
                    wp_enqueue_script('swiper');
                }

                wp_enqueue_script('lebagol-single-product', get_template_directory_uri() . '/assets/js/woocommerce/single' . $suffix . '.js', array(
                    'jquery',
                    'sticky-kit',
                    'magnific-popup'
                ), LEBAGOL_VERSION, true);

            }

            if (is_cart()) {
                if (lebagol_is_elementor_activated()) {
                    wp_enqueue_script('swiper');
                }
                wp_enqueue_script('wc-cart-fragments');
                wp_enqueue_script('lebagol-product-page-cart', get_template_directory_uri() . '/assets/js/woocommerce/page-cart' . $suffix . '.js', array(
                    'jquery',
                    'swiper',
                    'wc-cart-fragments'
                ), LEBAGOL_VERSION, true);
            }

        }

        /**
         * Related Products Args
         *
         * @param array $args related products args.
         *
         * @return  array $args related products args
         * @since 1.0.0
         */
        public function related_products_args($args) {
            $product_items = 5;
            $args          = apply_filters(
                'lebagol_related_products_args', array(
                    'posts_per_page' => $product_items,
                    'columns'        => $product_items,
                )
            );

            return $args;
        }


        public function upsell_products_args($args) {
            $args['columns'] = apply_filters('lebagol_upsell_products_column', 5);
            return $args;
        }

        /**
         * Products per page
         *
         * @return integer number of products
         * @since  1.0.0
         */
        public function products_per_page() {
            return intval(apply_filters('lebagol_products_per_page', 12));
        }

        /**
         * Query WooCommerce Extension Activation.
         *
         * @param string $extension Extension class name.
         *
         * @return boolean
         */
        public function is_woocommerce_extension_activated($extension = 'WC_Bookings') {
            return class_exists($extension) ? true : false;
        }

        public function widgets_init() {
            register_sidebar(array(
                'name'          => esc_html__('WooCommerce Shop', 'lebagol'),
                'id'            => 'sidebar-woocommerce-shop',
                'description'   => esc_html__('Add widgets here to appear in your sidebar on blog posts and archive pages.', 'lebagol'),
                'before_widget' => '<div id="%1$s" class="widget %2$s lebagol-widget-woocommerce">',
                'after_widget'  => '</div>',
                'before_title'  => '<h2 class="gamma widget-title">',
                'after_title'   => '</h2>',
            ));
            register_sidebar(array(
                'name'          => __('WooCommerce Detail', 'lebagol'),
                'id'            => 'sidebar-woocommerce-detail',
                'description'   => __('Add widgets here to appear in your sidebar on blog posts and archive pages.', 'lebagol'),
                'before_widget' => '<div id="%1$s" class="widget %2$s">',
                'after_widget'  => '</div>',
                'before_title'  => '<span class="gamma widget-title">',
                'after_title'   => '</span>',
            ));
        }

        public function set_sidebar($name) {
            $layout = lebagol_get_theme_option('woocommerce_archive_layout', 'default');
            if (lebagol_is_product_archive()) {
                if (is_active_sidebar('sidebar-woocommerce-shop') && ($layout == 'default' || $layout == 'drawing')) {
                    $name = 'sidebar-woocommerce-shop';
                } else {
                    $name = '';
                }
            }
            if (is_product()) {
                $name = '';
            }
            return $name;
        }

        public function grouped_product_column_image($grouped_product_child) {
            echo '<td class="woocommerce-grouped-product-image">' . $grouped_product_child->get_image('medium') . '</td>';
        }

    }

endif;

return new Lebagol_WooCommerce();
