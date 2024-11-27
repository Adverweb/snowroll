<?php

class Lebagol_Merlin_Config {

    private $wizard;

    public function __construct() {
        $this->init();
        add_filter('merlin_import_files', [$this, 'import_files']);
        add_action('merlin_after_all_import', [$this, 'after_import_setup'], 10, 1);
        add_filter('merlin_generate_child_functions_php', [$this, 'render_child_functions_php']);

        add_action('import_start', function () {
            add_filter('wxr_importer.pre_process.post_meta', [$this, 'fiximport_elementor'], 10, 1);
        });

        add_action('import_end', function () {
            update_option('elementor_experiment-container', 'active');
            update_option('elementor_experiment-nested-elements', 'active');
            update_option('elementor_experiment-container_grid', 'active');
        });
    }

    public function fiximport_elementor($post_meta) {
        if ('_elementor_data' === $post_meta['key']) {
            $post_meta['value'] = wp_slash($post_meta['value']);
        }

        return $post_meta;
    }

    public function import_files(){
            return array(
            array(
                'import_file_name'           => 'home 1',
                'home'                       => 'home-1',
                'local_import_file'          => get_theme_file_path('/dummy-data/content.xml'),
                'homepage'                   => get_theme_file_path('/dummy-data/homepage/home-1.xml'),
                'local_import_widget_file'   => get_theme_file_path('/dummy-data/widgets.json'),
                'import_rev_slider_file_url' => 'http://source.wpopal.com/lebagol/dummy_data/revsliders/home-1/slider-1.zip',
                'import_more_revslider_file_url' => [],
                'import_preview_image_url'   => get_theme_file_uri('/assets/images/oneclick/home_1.jpg'),
                'preview_url'                => 'https://demo2.wpopal.com/lebagol/home-1',
                'themeoptions'               => '{}',
            ),

            array(
                'import_file_name'           => 'home 2',
                'home'                       => 'home-2',
                'local_import_file'          => get_theme_file_path('/dummy-data/content.xml'),
                'homepage'                   => get_theme_file_path('/dummy-data/homepage/home-2.xml'),
                'local_import_widget_file'   => get_theme_file_path('/dummy-data/widgets.json'),
                'import_rev_slider_file_url' => 'http://source.wpopal.com/lebagol/dummy_data/revsliders/home-2/slider-2.zip',
                'import_more_revslider_file_url' => [],
                'import_preview_image_url'   => get_theme_file_uri('/assets/images/oneclick/home_2.jpg'),
                'preview_url'                => 'https://demo2.wpopal.com/lebagol/home-2',
                'themeoptions'               => '{}',
            ),

            array(
                'import_file_name'           => 'home 3',
                'home'                       => 'home-3',
                'local_import_file'          => get_theme_file_path('/dummy-data/content.xml'),
                'homepage'                   => get_theme_file_path('/dummy-data/homepage/home-3.xml'),
                'local_import_widget_file'   => get_theme_file_path('/dummy-data/widgets.json'),
                'import_rev_slider_file_url' => 'http://source.wpopal.com/lebagol/dummy_data/revsliders/home-3/slider-3.zip',
                'import_more_revslider_file_url' => [],
                'import_preview_image_url'   => get_theme_file_uri('/assets/images/oneclick/home_3.jpg'),
                'preview_url'                => 'https://demo2.wpopal.com/lebagol/home-3',
                'themeoptions'               => '{}',
            ),

            array(
                'import_file_name'           => 'home 4',
                'home'                       => 'home-4',
                'local_import_file'          => get_theme_file_path('/dummy-data/content.xml'),
                'homepage'                   => get_theme_file_path('/dummy-data/homepage/home-4.xml'),
                'local_import_widget_file'   => get_theme_file_path('/dummy-data/widgets.json'),
                'import_rev_slider_file_url' => 'http://source.wpopal.com/lebagol/dummy_data/revsliders/home-4/slider-4.zip',
                'import_more_revslider_file_url' => [],
                'import_preview_image_url'   => get_theme_file_uri('/assets/images/oneclick/home_4.jpg'),
                'preview_url'                => 'https://demo2.wpopal.com/lebagol/home-4',
                'themeoptions'               => '{}',
            ),

            array(
                'import_file_name'           => 'home 5',
                'home'                       => 'home-5',
                'local_import_file'          => get_theme_file_path('/dummy-data/content.xml'),
                'homepage'                   => get_theme_file_path('/dummy-data/homepage/home-5.xml'),
                'local_import_widget_file'   => get_theme_file_path('/dummy-data/widgets.json'),
                'import_rev_slider_file_url' => 'http://source.wpopal.com/lebagol/dummy_data/revsliders/home-5/slider-5.zip',
                'import_more_revslider_file_url' => [],
                'import_preview_image_url'   => get_theme_file_uri('/assets/images/oneclick/home_5.jpg'),
                'preview_url'                => 'https://demo2.wpopal.com/lebagol/home-5',
                'themeoptions'               => '{}',
            ),

            array(
                'import_file_name'           => 'home 6',
                'home'                       => 'home-6',
                'local_import_file'          => get_theme_file_path('/dummy-data/content.xml'),
                'homepage'                   => get_theme_file_path('/dummy-data/homepage/home-6.xml'),
                'local_import_widget_file'   => get_theme_file_path('/dummy-data/widgets.json'),
                'import_rev_slider_file_url' => 'http://source.wpopal.com/lebagol/dummy_data/revsliders/home-6/slider-5-1.zip',
                'import_more_revslider_file_url' => [],
                'import_preview_image_url'   => get_theme_file_uri('/assets/images/oneclick/home_6.jpg'),
                'preview_url'                => 'https://demo2.wpopal.com/lebagol/home-6',
                'themeoptions'               => '{}',
            ),
            );           
        }

    public function after_import_setup($selected_import) {
        $selected_import = ($this->import_files())[$selected_import];
        $check_oneclick  = get_option('lebagol_check_oneclick', []);

        $this->set_demo_menus();

        if (!isset($check_oneclick[$selected_import['home']])) {
            $this->wizard->importer->import(get_parent_theme_file_path('dummy-data/homepage/' . $selected_import['home'] . '.xml'));
            $check_oneclick[$selected_import['home']] = true;
        }

        // setup Home page
        $home = get_page_by_path($selected_import['home']);
        if ($home) {
            update_option('show_on_front', 'page');
            update_option('page_on_front', $home->ID);
        }

        // Setup Options
        $options = $this->get_all_options();

        // Elementor
        if ( ! isset( $check_oneclick['elementor-options'] ) ) {
            $active_kit_id = Elementor\Plugin::$instance->kits_manager->get_active_id();
            update_post_meta( $active_kit_id, '_elementor_page_settings', $options['elementor'] );
            $check_oneclick['elementor-options'] = true;
        }

        // Options
        $theme_options = $options['options'];
        foreach ($theme_options as $key => $option) {
            update_option($key, $option);
        }

        //Mailchimp
        if (!isset($check_oneclick['mailchip'])) {
            $mailchimp = $this->get_mailchimp_id();
            if ($mailchimp) {
                update_option('mc4wp_default_form_id', $mailchimp);
            }
            $check_oneclick['mailchip'] = true;
        }

        // Header Footer Builder
        $this->reset_header_footer();
        $this->set_hf($selected_import['home']);

        // WooCommerce
        if (!isset($check_oneclick['woocommerce'])) {
            update_option('woocommerce_single_image_width', 800);
            update_option('woocommerce_thumbnail_image_width', 450);
            update_option('woocommerce_thumbnail_cropping', 'uncropped');
            $this->update_woocommerce_page();
            $check_oneclick['woocommerce'] = true;
        }

        if (!isset($check_oneclick['logo'])) {
            set_theme_mod('custom_logo', $this->get_attachment('_logo'));
            $check_oneclick['logo'] = true;
        }

        if(!isset($check_oneclick['menu-item'])){
            $this->update_nav_menu_item();
            $check_oneclick['menu-item'] = true;
        }

        update_option('lebagol_check_oneclick', $check_oneclick);

        \Elementor\Plugin::instance()->files_manager->clear_cache();
    }

    private function update_nav_menu_item() {
        $params = array(
            'posts_per_page' => -1,
            'post_type'      => [
                'nav_menu_item',
            ],
        );
        $query  = new WP_Query($params);
        while ($query->have_posts()): $query->the_post();
            wp_update_post(array(
                // Update the `nav_menu_item` Post Title
                'ID'         => get_the_ID(),
                'post_title' => get_the_title()
            ));
        endwhile;

    }

    private function update_woocommerce_page() {
        $pages = array(
            array(
                "ID"           => wc_get_page_id('cart'),
                "post_content" => "<!-- wp:shortcode -->[woocommerce_cart]<!-- /wp:shortcode -->"
            ),
            array(
                "ID"           => wc_get_page_id('checkout'),
                "post_content" => "<!-- wp:shortcode -->[woocommerce_checkout]<!-- /wp:shortcode -->"
            ),
            array(
                "ID"           => wc_get_page_id('my-account'),
                "post_content" => "<!-- wp:shortcode -->[woocommerce_my_account]<!-- /wp:shortcode -->"
            )
        );
        foreach ($pages as $page) {
            if ($page['ID']) {
                wp_update_post($page);
            }
        }
    }

    private function get_mailchimp_id() {
        $params = array(
            'post_type'      => 'mc4wp-form',
            'posts_per_page' => 1,
        );
        $post   = get_posts($params);

        return isset($post[0]) ? $post[0]->ID : 0;
    }

    private function get_attachment($key) {
        $params = array(
            'post_type'      => 'attachment',
            'post_status'    => 'inherit',
            'posts_per_page' => 1,
            'meta_key'       => $key,
        );
        $post   = get_posts($params);
        if ($post) {
            return $post[0]->ID;
        }

        return 0;
    }

    private function init() {
        $this->wizard = new Merlin(
            $config = array(
                // Location / directory where Merlin WP is placed in your theme.
                'merlin_url'         => 'merlin',
                // The wp-admin page slug where Merlin WP loads.
                'parent_slug'        => 'themes.php',
                // The wp-admin parent page slug for the admin menu item.
                'capability'         => 'manage_options',
                // The capability required for this menu to be displayed to the user.
                'dev_mode'           => true,
                // Enable development mode for testing.
                'license_step'       => false,
                // EDD license activation step.
                'license_required'   => false,
                // Require the license activation step.
                'license_help_url'   => '',
                'directory'          => '/inc/merlin',
                // URL for the 'license-tooltip'.
                'edd_remote_api_url' => '',
                // EDD_Theme_Updater_Admin remote_api_url.
                'edd_item_name'      => '',
                // EDD_Theme_Updater_Admin item_name.
                'edd_theme_slug'     => '',
                // EDD_Theme_Updater_Admin item_slug.
            ),
            $strings = array(
                'admin-menu'          => esc_html__('Theme Setup', 'lebagol'),

                /* translators: 1: Title Tag 2: Theme Name 3: Closing Title Tag */
                'title%s%s%s%s'       => esc_html__('%1$s%2$s Themes &lsaquo; Theme Setup: %3$s%4$s', 'lebagol'),
                'return-to-dashboard' => esc_html__('Return to the dashboard', 'lebagol'),
                'ignore'              => esc_html__('Disable this wizard', 'lebagol'),

                'btn-skip'                 => esc_html__('Skip', 'lebagol'),
                'btn-next'                 => esc_html__('Next', 'lebagol'),
                'btn-start'                => esc_html__('Start', 'lebagol'),
                'btn-no'                   => esc_html__('Cancel', 'lebagol'),
                'btn-plugins-install'      => esc_html__('Install', 'lebagol'),
                'btn-child-install'        => esc_html__('Install', 'lebagol'),
                'btn-content-install'      => esc_html__('Install', 'lebagol'),
                'btn-import'               => esc_html__('Import', 'lebagol'),
                'btn-license-activate'     => esc_html__('Activate', 'lebagol'),
                'btn-license-skip'         => esc_html__('Later', 'lebagol'),

                /* translators: Theme Name */
                'license-header%s'         => esc_html__('Activate %s', 'lebagol'),
                /* translators: Theme Name */
                'license-header-success%s' => esc_html__('%s is Activated', 'lebagol'),
                /* translators: Theme Name */
                'license%s'                => esc_html__('Enter your license key to enable remote updates and theme support.', 'lebagol'),
                'license-label'            => esc_html__('License key', 'lebagol'),
                'license-success%s'        => esc_html__('The theme is already registered, so you can go to the next step!', 'lebagol'),
                'license-json-success%s'   => esc_html__('Your theme is activated! Remote updates and theme support are enabled.', 'lebagol'),
                'license-tooltip'          => esc_html__('Need help?', 'lebagol'),

                /* translators: Theme Name */
                'welcome-header%s'         => esc_html__('Welcome to %s', 'lebagol'),
                'welcome-header-success%s' => esc_html__('Hi. Welcome back', 'lebagol'),
                'welcome%s'                => esc_html__('This wizard will set up your theme, install plugins, and import content. It is optional & should take only a few minutes.', 'lebagol'),
                'welcome-success%s'        => esc_html__('You may have already run this theme setup wizard. If you would like to proceed anyway, click on the "Start" button below.', 'lebagol'),

                'child-header'         => esc_html__('Install Child Theme', 'lebagol'),
                'child-header-success' => esc_html__('You\'re good to go!', 'lebagol'),
                'child'                => esc_html__('Let\'s build & activate a child theme so you may easily make theme changes.', 'lebagol'),
                'child-success%s'      => esc_html__('Your child theme has already been installed and is now activated, if it wasn\'t already.', 'lebagol'),
                'child-action-link'    => esc_html__('Learn about child themes', 'lebagol'),
                'child-json-success%s' => esc_html__('Awesome. Your child theme has already been installed and is now activated.', 'lebagol'),
                'child-json-already%s' => esc_html__('Awesome. Your child theme has been created and is now activated.', 'lebagol'),

                'plugins-header'         => esc_html__('Install Plugins', 'lebagol'),
                'plugins-header-success' => esc_html__('You\'re up to speed!', 'lebagol'),
                'plugins'                => esc_html__('Let\'s install some essential WordPress plugins to get your site up to speed.', 'lebagol'),
                'plugins-success%s'      => esc_html__('The required WordPress plugins are all installed and up to date. Press "Next" to continue the setup wizard.', 'lebagol'),
                'plugins-action-link'    => esc_html__('Advanced', 'lebagol'),

                'import-header'      => esc_html__('Import Content', 'lebagol'),
                'import'             => esc_html__('Let\'s import content to your website, to help you get familiar with the theme.', 'lebagol'),
                'import-action-link' => esc_html__('Advanced', 'lebagol'),

                'ready-header'      => esc_html__('All done. Have fun!', 'lebagol'),

                /* translators: Theme Author */
                'ready%s'           => esc_html__('Your theme has been all set up. Enjoy your new theme by %s.', 'lebagol'),
                'ready-action-link' => esc_html__('Extras', 'lebagol'),
                'ready-big-button'  => esc_html__('View your website', 'lebagol'),
                'ready-link-1'      => sprintf('<a href="%1$s" target="_blank">%2$s</a>', 'https://wordpress.org/support/', esc_html__('Explore WordPress', 'lebagol')),
                'ready-link-2'      => sprintf('<a href="%1$s" target="_blank">%2$s</a>', 'https://themebeans.com/contact/', esc_html__('Get Theme Support', 'lebagol')),
                'ready-link-3'      => sprintf('<a href="%1$s">%2$s</a>', admin_url('customize.php'), esc_html__('Start Customizing', 'lebagol')),
            )
        );
        if (lebagol_is_elementor_activated()) {
            add_action('widgets_init', [$this, 'widgets_init']);
        }

    }

    public function widgets_init() {
        require_once get_parent_theme_file_path('/inc/merlin/includes/recent-post.php');
        register_widget('Lebagol_WP_Widget_Recent_Posts');
        if (lebagol_is_woocommerce_activated()) {
            require_once get_parent_theme_file_path('/inc/merlin/includes/class-wc-widget-layered-nav.php');
            register_widget('Lebagol_Widget_Layered_Nav');

            require_once get_parent_theme_file_path('/inc/merlin/includes/class-wc-widget-status.php');
            register_widget('Lebagol_Widget_Product_Status');
        }
    }

    private function get_all_header_footer() {
        return [
            'home-1'  => [
                'header' => [
                    [
                        'slug'                         => 'global-header',
                        'ehf_target_include_locations' => ['rule' => ['basic-global'], 'specific' => []],
                    ]
                ],
                'footer' => [
                    [
                        'slug'                         => 'global-footer',
                        'ehf_target_include_locations' => ['rule' => ['basic-global'], 'specific' => []],
                    ]
                ]
            ],
            'home-2'  => [
                'header' => [
                    [
                        'slug'                         => 'header-2',
                        'ehf_target_include_locations' => ['rule' => ['basic-global'], 'specific' => []],
                    ]
                ],
                'footer' => [
                    [
                        'slug'                         => 'footer-2',
                        'ehf_target_include_locations' => ['rule' => ['basic-global'], 'specific' => []],
                    ]
                ]
            ],
            'home-3'  => [
                'header' => [
                    [
                        'slug'                         => 'header-3',
                        'ehf_target_include_locations' => ['rule' => ['basic-global'], 'specific' => []],
                    ]
                ],
                'footer' => [
                    [
                        'slug'                         => 'footer-3',
                        'ehf_target_include_locations' => ['rule' => ['basic-global'], 'specific' => []],
                    ]
                ]
            ],
            'home-4'  => [
                'header' => [
                    [
                        'slug'                         => 'header-4',
                        'ehf_target_include_locations' => ['rule' => ['basic-global'], 'specific' => []],
                    ]
                ],
                'footer' => [
                    [
                        'slug'                         => 'footer-4',
                        'ehf_target_include_locations' => ['rule' => ['basic-global'], 'specific' => []],
                    ]
                ]
            ],
            'home-5'  => [
                'header' => [
                    [
                        'slug'                         => 'header-5',
                        'ehf_target_include_locations' => ['rule' => ['basic-global'], 'specific' => []],
                    ]
                ],
                'footer' => [
                    [
                        'slug'                         => 'footer-3',
                        'ehf_target_include_locations' => ['rule' => ['basic-global'], 'specific' => []],
                    ]
                ]
            ],
            'home-6'  => [
                'header' => [
                    [
                        'slug'                         => 'header-4',
                        'ehf_target_include_locations' => ['rule' => ['basic-global'], 'specific' => []],
                    ]
                ],
                'footer' => [
                    [
                        'slug'                         => 'footer-5',
                        'ehf_target_include_locations' => ['rule' => ['basic-global'], 'specific' => []],
                    ]
                ]
            ]
        ];
    }

    private function reset_header_footer() {
        $footer_args = array(
            'post_type'      => 'elementor-hf',
            'posts_per_page' => -1,
            'meta_query'     => array(
                array(
                    'key'     => 'ehf_template_type',
                    'compare' => 'IN',
                    'value'   => ['type_footer', 'type_header']
                ),
            )
        );
        $footer      = new WP_Query($footer_args);
        while ($footer->have_posts()) : $footer->the_post();
            update_post_meta(get_the_ID(), 'ehf_target_include_locations', []);
            update_post_meta(get_the_ID(), 'ehf_target_exclude_locations', []);
        endwhile;
        wp_reset_postdata();
    }

    public function set_demo_menus() {
        $main_menu = get_term_by('name', 'Main Menu', 'nav_menu');

        set_theme_mod(
            'nav_menu_locations',
            array(
                'primary'  => $main_menu->term_id,
                'handheld' => $main_menu->term_id,
            )
        );
    }

    private function set_hf($home) {
        $all_hf = $this->get_all_header_footer();
        $datas  = $all_hf[$home];
        foreach ($datas as $item) {
            foreach ($item as $object) {
                $hf = get_page_by_path($object['slug'], OBJECT, 'elementor-hf');
                if ($hf) {
                    update_post_meta($hf->ID, 'ehf_target_include_locations', $object['ehf_target_include_locations']);
                    if (isset($object['ehf_target_exclude_locations'])) {
                        update_post_meta($hf->ID, 'ehf_target_exclude_locations', $object['ehf_target_exclude_locations']);
                    }
                }
            }
        }
    }

    public function render_child_functions_php() {
        $output
            = "<?php
/**
 * Theme functions and definitions.
 */
		 ";

        return $output;
    }

    public function get_all_options(){
        $options = [];
        $options['options']   = json_decode('{"lebagol_options_woocommerce_product_hover":"swap"}', true);
        $options['elementor']   = json_decode('{"colors_enable_styleguide_preview":"yes","system_colors":[{"_id":"primary","title":"Primary","color":"#ED1C27"},{"_id":"primary_hover","title":"Primary Hover","color":"#D51923"},{"_id":"text","title":"Text","color":"#555555"},{"_id":"text_light","title":"Text light","color":"#999999"},{"_id":"accent","title":"Accent","color":"#000000"},{"_id":"highlights","title":"Highlights","color":"#FF1C6A"},{"_id":"border","title":"Border","color":"#E2E0E0"},{"_id":"background","title":"Background","color":"#FFFFFF"},{"_id":"bg_light","title":"Background light","color":"#FAF2E7"}],"custom_colors":[],"system_typography":[{"_id":"primary","title":"Primary","typography_typography":"custom"},{"_id":"secondary","title":"Secondary","typography_typography":"custom"},{"_id":"accent","title":"Accent","typography_typography":"custom"},{"_id":"text","title":"Text","typography_typography":"custom"},{"_id":"heading_title","title":"Heading Title","typography_typography":"custom","typography_font_weight":"500","typography_letter_spacing":{"unit":"px","size":0,"sizes":[]},"typography_font_size":{"unit":"px","size":48,"sizes":[]},"typography_line_height":{"unit":"em","size":1.125,"sizes":[]},"typography_font_size_mobile":{"unit":"px","size":32,"sizes":[]},"typography_line_height_mobile":{"unit":"px","size":"","sizes":[]},"typography_font_size_tablet":{"unit":"px","size":40,"sizes":[]},"typography_text_transform":"capitalize","typography_font_size_tablet_extra":{"unit":"px","size":42,"sizes":[]}}],"custom_typography":[],"default_generic_fonts":"Sans-serif","site_name":"Lebagol","site_description":"Ice Cream Shop WordPress Theme","page_title_selector":"h1.entry-title","activeItemIndex":1,"active_breakpoints":["viewport_mobile","viewport_mobile_extra","viewport_tablet","viewport_tablet_extra","viewport_laptop"],"viewport_md":768,"viewport_lg":1025,"container_width":{"unit":"px","size":1320,"sizes":[]},"space_between_widgets":{"column":"0","row":"0","isLinked":true,"unit":"px","size":0,"sizes":[]},"typography_enable_styleguide_preview":"yes","woocommerce_catalog_rows":4,"woo_carousel_slides_to_show":4,"woocommerce_catalog_gutter":{"unit":"px","size":30,"sizes":[]},"lebagol_options_woocommerce_product_hover":"swap","woo_carousel_slides_to_show_laptop":"4","woo_carousel_slides_to_show_tablet_extra":"3","woo_carousel_slides_to_show_tablet":"3","woo_carousel_slides_to_show_mobile_extra":"2","woo_carousel_slides_to_show_mobile":"2","woo_carousel_spaceBetween":{"unit":"px","size":30,"sizes":[]},"woo_carousel_spaceBetween_laptop":{"unit":"px","size":30,"sizes":[]},"woo_carousel_spaceBetween_tablet":{"unit":"px","size":30,"sizes":[]},"woo_carousel_spaceBetween_mobile_extra":{"unit":"px","size":30,"sizes":[]},"woo_carousel_spaceBetween_mobile":{"unit":"px","size":5,"sizes":[]},"woocommerce_catalog_columns_laptop":"4","woocommerce_catalog_columns_tablet_extra":"3","woocommerce_catalog_columns_tablet":"3","woocommerce_catalog_columns_mobile_extra":"2","woocommerce_catalog_columns_mobile":"1","woocommerce_catalog_gutter_laptop":{"unit":"px","size":30,"sizes":[]},"woocommerce_catalog_gutter_tablet_extra":{"unit":"px","size":30,"sizes":[]},"woocommerce_catalog_gutter_tablet":{"unit":"px","size":30,"sizes":[]},"woocommerce_catalog_gutter_mobile_extra":{"unit":"px","size":30,"sizes":[]},"woocommerce_catalog_gutter_mobile":{"unit":"px","size":30,"sizes":[]}}', true);
        return $options;
    } // end get_all_options
}

return new Lebagol_Merlin_Config();
