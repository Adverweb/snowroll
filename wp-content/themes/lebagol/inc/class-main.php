<?php

if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('Lebagol')) :

    /**
     * The main Lebagol class
     */
    class Lebagol {

        /**
         * Setup class.
         *
         * @since 1.0
         */
        public function __construct() {
            add_action('after_setup_theme', array($this, 'setup'));
            add_action('wp_head', [$this, 'preload'], 1);
            add_action('widgets_init', array($this, 'widgets_init'));
            add_filter('lebagol_theme_sidebar', array($this, 'set_sidebar'), 20);
            add_action('wp_enqueue_scripts', array($this, 'register_scripts_addon'), 5);
            add_action('wp_enqueue_scripts', array($this, 'scripts'), 10);
            add_action('wp_enqueue_scripts', array($this, 'child_scripts'), 30); // After WooCommerce.
            add_action('enqueue_block_assets', array($this, 'block_assets'));
            add_filter('body_class', array($this, 'body_classes'));
            add_filter('wp_page_menu_args', array($this, 'page_menu_args'));
            add_filter('navigation_markup_template', array($this, 'navigation_markup_template'));
            add_filter('block_editor_settings_all', array($this, 'custom_editor_settings'), 10, 2);
            add_action('tgmpa_register', [$this, 'register_required_plugins']);

            add_action('admin_enqueue_scripts', [$this, 'admin_scripts']);

            add_filter('gutenberg_use_widgets_block_editor', '__return_false');
            add_filter('use_widgets_block_editor', '__return_false');

            add_filter('dynamic_sidebar_params', [$this, 'check_sidebar_params']);
        }

        public function preload() {
            get_template_part('template-parts/preload');
        }

        public function register_required_plugins() {
            /**
             * Array of plugin arrays. Required keys are name and slug.
             * If the source is NOT from the .org repo, then source is also required.
             */
            $plugins = array(
                array(
                    'name'     => 'Woocommerce',
                    'slug'     => 'woocommerce',
                    'required' => true,
                ),
                //====start_premium
                array(
                    'name'     => 'Revslider',
                    'slug'     => 'revslider',
                    'required' => true,
                    'source'   => esc_url('http://source.wpopal.com/plugins/new/revslider.zip'),
                ),
                //====end_premium
                array(
                    'name'     => 'Elementor',
                    'slug'     => 'elementor',
                    'required' => true,
                ),
                array(
                    'name'     => 'Elementor – Header, Footer & Blocks Template',
                    'slug'     => 'header-footer-elementor',
                    'required' => true,
                ),
                array(
                    'name'     => 'Mailchimp For Wordpress',
                    'slug'     => 'mailchimp-for-wp',
                    'required' => true,
                ),
                array(
                    'name'     => 'Contact Form 7',
                    'slug'     => 'contact-form-7',
                    'required' => true,
                ),
                //====start_premium
                array(
                    'name'     => 'WOOCS - WooCommerce Currency Switcher',
                    'slug'     => 'woocommerce-currency-switcher',
                    'required' => false,
                ),
                array(
                    'name'     => 'WPC Smart Wishlist for WooCommerce',
                    'slug'     => 'woo-smart-wishlist',
                    'required' => false,
                ),
                array(
                    'name'     => 'WPC Smart Quick View for WooCommerce',
                    'slug'     => 'woo-smart-quick-view',
                    'required' => false,
                ),
                array(
                    'name'     => 'WPC Smart Compare for WooCommerce',
                    'slug'     => 'woo-smart-compare',
                    'required' => false,
                ),
                array(
                    'name'     => 'Variation Swatches for WooCommerce',
                    'slug'     => 'woo-variation-swatches',
                    'required' => false,
                ),
                //====end_premium
            );

            /*
             * Array of configuration settings. Amend each line as needed.
             *
             * TGMPA will start providing localized text strings soon. If you already have translations of our standard
             * strings available, please help us make TGMPA even better by giving us access to these translations or by
             * sending in a pull-request with .po file(s) with the translations.
             *
             * Only uncomment the strings in the config array if you want to customize the strings.
             */
            $config = array(
                'id'           => 'lebagol',
                // Unique ID for hashing notices for multiple instances of TGMPA.
                'default_path' => '',
                // Default absolute path to bundled plugins.
                'menu'         => 'tgmpa-install-plugins',
                // Menu slug.
                'has_notices'  => true,
                // Show admin notices or not.
                'dismissable'  => true,
                // If false, a user cannot dismiss the nag message.
                'dismiss_msg'  => '',
                // If 'dismissable' is false, this message will be output at top of nag.
                'is_automatic' => false,
                // Automatically activate plugins after installation or not.
                'message'      => '',
            );

            tgmpa($plugins, $config);
        }

        /**
         * Sets up theme defaults and registers support for various WordPress features.
         *
         * Note that this function is hooked into the after_setup_theme hook, which
         * runs before the init hook. The init hook is too late for some features, such
         * as indicating support for post thumbnails.
         */
        public function setup() {

            // Loads wp-content/themes/child-theme-name/languages/lebagol.mo.
            load_theme_textdomain('lebagol', get_stylesheet_directory() . '/languages');

            // Loads wp-content/themes/lebagol/languages/lebagol.mo.
            load_theme_textdomain('lebagol', get_template_directory() . '/languages');

            /**
             * Add default posts and comments RSS feed links to head.
             */
            add_theme_support('automatic-feed-links');

            /*
             * Enable support for Post Thumbnails on posts and pages.
             *
             * @link https://developer.wordpress.org/reference/functions/add_theme_support/#Post_Thumbnails
             */
            add_theme_support('post-thumbnails');
            set_post_thumbnail_size(1410, 720, true);
            add_image_size('lebagol-post-grid', 820, 620, ['center', 'center']);


            /**
             * Register menu locations.
             */
            register_nav_menus(
                apply_filters(
                    'lebagol_register_nav_menus', array(
                        'primary'             => esc_html__('Primary Menu', 'lebagol'),
                        'handheld'            => esc_html__('Handheld Menu', 'lebagol'),
                        'handheld_categories' => esc_html__('Handheld Categories Menu', 'lebagol'),
                    )
                )
            );

            // Add theme support for Custom Logo.
            add_theme_support('custom-logo', array(
                'width'       => 300,
                'height'      => 200,
                'flex-width'  => true,
                'flex-height' => true,
            ));

            /*
             * Switch default core markup for search form, comment form, comments, galleries, captions and widgets
             * to output valid HTML5.
             */
            add_theme_support(
                'html5', apply_filters(
                    'lebagol_html5_args', array(
                        'search-form',
                        'comment-form',
                        'comment-list',
                        'gallery',
                        'caption',
                        'widgets',
                        'script',
                        'style',
                    )
                )
            );

            /**
             * Declare support for title theme feature.
             */
            add_theme_support('title-tag');

            /**
             * Declare support for selective refreshing of widgets.
             */
            add_theme_support('customize-selective-refresh-widgets');

            /**
             * Add support for Block Styles.
             */
            add_theme_support('wp-block-styles');

            /**
             * Add support for full and wide align images.
             */
            add_theme_support('align-wide');

            /**
             * Add support for editor styles.
             */
            add_theme_support('editor-styles');

            /**
             * Enqueue editor styles.
             */
            add_editor_style(array('assets/css/base/gutenberg-editor.css', $this->google_fonts()));

            /**
             * Add support for responsive embedded content.
             */
            add_theme_support('responsive-embeds');
        }

        /**
         * Register widget area.
         *
         * @link https://codex.wordpress.org/Function_Reference/register_sidebar
         */
        public function widgets_init() {
            $sidebar_args['sidebar'] = array(
                'name'        => esc_html__('Sidebar Archive', 'lebagol'),
                'id'          => 'sidebar-blog',
                'description' => '',
            );

            $sidebar_args['sidebar-single'] = array(
                'name'        => esc_html__('Sidebar Single Post', 'lebagol'),
                'id'          => 'sidebar-single',
                'description' => '',
            );

            $sidebar_args = apply_filters('lebagol_sidebar_args', $sidebar_args);

            foreach ($sidebar_args as $sidebar => $args) {
                $widget_tags = array(
                    'before_widget' => '<div id="%1$s" class="widget %2$s">',
                    'after_widget'  => '</div>',
                    'before_title'  => '<h5 class="sigma widget-title">',
                    'after_title'   => '</h5>',
                );

                $filter_hook = sprintf('lebagol_%s_widget_tags', $sidebar);
                $widget_tags = apply_filters($filter_hook, $widget_tags);

                if (is_array($widget_tags)) {
                    register_sidebar($args + $widget_tags);
                }
            }
        }

        public function check_sidebar_params($params) {
            global $wp_registered_widgets;
            $settings_getter = $wp_registered_widgets[$params[0]['widget_id']]['callback'][0];
            $settings        = $settings_getter->get_settings();
            $settings        = $settings[$params[1]['number']];
            if ($params[0]['after_widget'] == '</div>' && isset($settings['title'])) {
                if (in_array($settings_getter->id_base, ['search', 'text', 'calendar', 'woocommerce_product_search', 'opal-wp_template'])) {
                    if (!empty($settings['title'])) {
                        $params[0]['after_title']  .= '<div class="widget-content">';
                        $params[0]['after_widget'] .= '</div>';
                    }
                } else {
                    $params[0]['after_title']  .= '<div class="widget-content">';
                    $params[0]['after_widget'] .= '</div>';
                }
            }

            return $params;
        }

        public function register_scripts_addon() {

            $suffix = (defined('SCRIPT_DEBUG') && SCRIPT_DEBUG) ? '' : '.min';
            wp_register_style('tooltipster', get_template_directory_uri() . '/assets/css/libs/tooltipster.bundle.min.css', '', LEBAGOL_VERSION);
            wp_register_script('tooltipster', get_template_directory_uri() . '/assets/js/vendor/tooltipster.min.js', array(), LEBAGOL_VERSION, true);
            wp_register_script('isotope', get_template_directory_uri() . '/assets/js/vendor/isotope.pkgd.min.js', array(), LEBAGOL_VERSION, true);
            wp_register_style('magnific-popup', get_template_directory_uri() . '/assets/css/libs/magnific-popup.css', '', LEBAGOL_VERSION);
            wp_register_script('magnific-popup', get_template_directory_uri() . '/assets/js/vendor/jquery.magnific-popup.min.js', array('jquery'), LEBAGOL_VERSION, true);
            wp_register_script('spritespin', get_template_directory_uri() . '/assets/js/vendor/spritespin.js', array('jquery'), LEBAGOL_VERSION, true);
            wp_register_script('sticky-kit', get_template_directory_uri() . '/assets/js/vendor/jquery.sticky-kit.min.js', array('jquery'), LEBAGOL_VERSION, true);
            wp_register_script('lebagol-waypoints', get_template_directory_uri() . '/assets/js/vendor/waypoints.js', array('jquery'), LEBAGOL_VERSION, true);
            wp_register_script('imageloaded', get_template_directory_uri() . '/assets/js/vendor/imagesloaded.pkgd.min.js', array('jquery'), LEBAGOL_VERSION, true);
            wp_register_script('gsap', get_template_directory_uri() . '/assets/js/vendor/gsap.min.js', array('jquery'), LEBAGOL_VERSION, true);
        }

        public function admin_scripts() {
            wp_enqueue_style('lebagol-admin-style', get_theme_file_uri('assets/css/admin/admin.css'));
        }

        /**
         * Enqueue scripts and styles.
         *
         * @since  1.0.0
         */
        public function scripts() {


            /**
             * Styles
             */
            wp_enqueue_style('lebagol-style', get_template_directory_uri() . '/style.css', '', LEBAGOL_VERSION);
            wp_style_add_data('lebagol-style', 'rtl', 'replace');

            // Google Fonts Default
            wp_enqueue_style('lebagol-fonts', $this->google_fonts(), array(), null);

            /**
             * Scripts
             */
            $suffix = (defined('SCRIPT_DEBUG') && SCRIPT_DEBUG) ? '' : '.min';
            $nonce  = wp_create_nonce('lebagol_ajax_nonce');
            wp_enqueue_script('lebagol-theme', get_template_directory_uri() . '/assets/js/frontend/main' . $suffix . '.js', array(
                'jquery',
                'wp-util'
            ), LEBAGOL_VERSION, true);
            wp_localize_script('lebagol-theme', 'lebagolAjax',
                array(
                    'ajaxurl' => admin_url('admin-ajax.php'),
                    'nonce'   => $nonce
                )
            );
            wp_enqueue_script('imagesloaded');

            wp_enqueue_script('lebagol-skip-link-focus-fix', get_template_directory_uri() . '/assets/js/skip-link-focus-fix' . $suffix . '.js', array(), '20130115', true);


            if (is_singular() && comments_open() && get_option('thread_comments')) {
                wp_enqueue_script('comment-reply');
            }

            wp_register_script('lebagol-text-editor', get_template_directory_uri() . '/assets/js/frontend/text-editor' . $suffix . '.js', array('jquery'), LEBAGOL_VERSION, true);
            wp_register_script('lebagol-nav-mobile', get_template_directory_uri() . '/assets/js/frontend/nav-mobile' . $suffix . '.js', array('jquery'), LEBAGOL_VERSION, true);
            wp_register_script('lebagol-search-popup', get_template_directory_uri() . '/assets/js/frontend/search-popup' . $suffix . '.js', array('jquery'), LEBAGOL_VERSION, true);
            wp_enqueue_script('lebagol-search-popup');
            wp_enqueue_script('sticky-kit');

            if (lebagol_is_elementor_activated()) {
                wp_enqueue_script('lebagol-text-editor');
            }


            if (isset(get_nav_menu_locations()['handheld'])) {
                wp_enqueue_script('lebagol-nav-mobile');
            }
        }


        /**
         * Register Google fonts.
         *
         * @return string Google fonts URL for the theme.
         * value : 'londrina-solid' => 'Londrina+Solid:300,400,900',
         * @since 2.4.0
         */
        public function google_fonts() {
            $google_fonts = apply_filters('lebagol_google_font_families', [
                'DM Sans' => 'DM+Sans:opsz,wght@9..40,400..500',
                'Kalnia'  => 'Kalnia:wght@500',
            ]);

            if (count($google_fonts) <= 0) {
                return false;
            }

            $query_args = array(
                'family'  => implode('&family=', $google_fonts),
                'subset'  => rawurlencode('latin,latin-ext'),
                'display' => 'swap',
            );

            $fonts_url = add_query_arg($query_args, 'https://fonts.googleapis.com/css2');

            return $fonts_url;
        }

        /**
         * Enqueue block assets.
         *
         * @since 2.5.0
         */
        public function block_assets() {


            // Styles.
            wp_enqueue_style('lebagol-gutenberg-blocks', get_template_directory_uri() . '/assets/css/base/gutenberg-blocks.css', '', LEBAGOL_VERSION);
            wp_style_add_data('lebagol-gutenberg-blocks', 'rtl', 'replace');
        }

        /**
         * Enqueue child theme stylesheet.
         * A separate function is required as the child theme css needs to be enqueued _after_ the parent theme
         * primary css and the separate WooCommerce css.
         *
         * @since  1.5.3
         */
        public function child_scripts() {
            if (is_child_theme()) {
                $child_theme = wp_get_theme(get_stylesheet());
                wp_enqueue_style('lebagol-child-style', get_stylesheet_uri(), array(), $child_theme->get('Version'));
            }
        }

        /**
         * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
         *
         * @param array $args Configuration arguments.
         *
         * @return array
         */
        public function page_menu_args($args) {
            $args['show_home'] = true;

            return $args;
        }

        /**
         * Adds custom classes to the array of body classes.
         *
         * @param array $classes Classes for the body element.
         *
         * @return array
         */
        public function body_classes($classes) {
            global $is_lynx, $is_gecko, $is_IE, $is_opera, $is_NS4, $is_safari, $is_chrome, $is_iphone;
            if ($is_lynx) {
                $classes[] = 'lynx';
            } elseif ($is_gecko) {
                $classes[] = 'gecko';
            } elseif ($is_opera) {
                $classes[] = 'opera';
            } elseif ($is_NS4) {
                $classes[] = 'ns4';
            } elseif ($is_safari) {
                $classes[] = 'safari';
            } elseif ($is_chrome) {
                $classes[] = 'chrome';
            } elseif ($is_IE) {
                $classes[] = 'ie';
            }

            if ($is_iphone) {
                $classes[] = 'iphone';
            }

            // Adds a class to blogs with more than 1 published author.
            if (is_multi_author()) {
                $classes[] = 'group-blog';
            }

            if (lebagol_get_theme_option('header_type', 1) == 'side') {
                $classes[] = 'lebagol-header-side';
            }

            /**
             * Adds a class when WooCommerce is not active.
             *
             * @todo Refactor child themes to remove dependency on this class.
             */
            $classes[] = 'no-wc-breadcrumb';
            $sidebar   = lebagol_get_theme_option('blog_archive_sidebar', 'right');
            if (is_singular('post')) {
                if (!is_active_sidebar('sidebar-single')) {
                    $classes[] = 'lebagol-full-width-content';
                } else {
                    if ($sidebar == 'left') {
                        $classes[] = 'lebagol-sidebar-left';
                    }
                    if ($sidebar == 'right') {
                        $classes[] = 'lebagol-sidebar-right';
                    }
                }

            } else {
                if (is_home() || is_category() || is_date() || is_tag() || is_author() || is_search()) {
                    if (!is_active_sidebar('sidebar-blog')) {
                        $classes[] = 'lebagol-full-width-content';
                    } else {
                        if ($sidebar == 'left') {
                            $classes[] = 'lebagol-sidebar-left';
                        }
                        if ($sidebar == 'right') {
                            $classes[] = 'lebagol-sidebar-right';
                        }
                    }
                }
            }

            // Add class when using homepage template + featured image.
            if (has_post_thumbnail()) {
                $classes[] = 'has-post-thumbnail';
            }

            return $classes;
        }

        public function set_sidebar($name) {
            if (is_singular('post')) {
                if (is_active_sidebar('sidebar-single')) {
                    $name = 'sidebar-single';
                }
            } else {
                if (is_archive() || is_home() || is_category() || is_tag() || is_author() || is_search()) {
                    if (is_active_sidebar('sidebar-blog') && (!is_post_type_archive('lebagol_portfolio') && !is_tax('lebagol_portfolio_cat'))) {
                        $name = 'sidebar-blog';
                    }
                }
            }

            return $name;
        }

        /**
         * Adds a custom parameter to the editor settings that is used
         * to track whether the main sidebar has widgets.
         *
         * @param array $settings Default editor settings.
         * @param WP_Post $post Post being edited.
         *
         * @return array Filtered block editor settings.
         * @since 2.4.3
         *
         */
        public function custom_editor_settings($settings, $post) {
            $settings['mainSidebarActive'] = false;

            if (is_active_sidebar('sidebar-blog')) {
                $settings['mainSidebarActive'] = true;
            }

            return $settings;
        }

        /**
         * Custom navigation markup template hooked into `navigation_markup_template` filter hook.
         */
        public function navigation_markup_template() {
            $template = '<nav id="post-navigation" class="navigation %1$s" role="navigation" aria-label="' . esc_attr__('Post Navigation', 'lebagol') . '">';
            $template .= '<h2 class="screen-reader-text">%2$s</h2>';
            $template .= '<div class="nav-links">%3$s</div>';
            $template .= '</nav>';

            return apply_filters('lebagol_navigation_markup_template', $template);
        }
    }
endif;

return new Lebagol();
