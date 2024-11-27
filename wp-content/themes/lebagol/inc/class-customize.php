<?php
if (!defined('ABSPATH')) {
    exit;
}
if (!class_exists('Lebagol_Customize')) {

    class Lebagol_Customize {


        public function __construct() {
            add_action('customize_register', array($this, 'customize_register'));
        }

        /**
         * @param $wp_customize WP_Customize_Manager
         */
        public function customize_register($wp_customize) {

            /**
             * Theme options.
             */
            require_once get_theme_file_path('inc/customize-control/editor.php');
            require_once get_theme_file_path('inc/customize-control/color.php');
            require_once get_theme_file_path('inc/customize-control/button-switch.php');

            $this->init_lebagol_blog($wp_customize);

            $this->init_lebagol_social($wp_customize);

            if (lebagol_is_woocommerce_activated()) {
                $this->init_woocommerce($wp_customize);
            }

            do_action('lebagol_customize_register', $wp_customize);
        }


        /**
         * @param $wp_customize WP_Customize_Manager
         *
         * @return void
         */
        public function init_lebagol_blog($wp_customize) {

            $wp_customize->add_section('lebagol_blog_archive', array(
                'title' => esc_html__('Blog', 'lebagol'),
            ));

            // =========================================
            // Select Style
            // =========================================

            $wp_customize->add_setting('lebagol_options_blog_style', array(
                'type'              => 'option',
                'default'           => 'standard',
                'sanitize_callback' => 'sanitize_text_field',
            ));

            $wp_customize->add_control('lebagol_options_blog_style', array(
                'section' => 'lebagol_blog_archive',
                'label'   => esc_html__('Blog style', 'lebagol'),
                'type'    => 'select',
                'choices' => array(
                    'standard' => esc_html__('Blog Standard', 'lebagol'),
                    //====start_premium
                    'style-1'  => esc_html__('Blog Grid', 'lebagol'),
                    'list'     => esc_html__('Blog List', 'lebagol'),
                    'modern'   => esc_html__('Blog Modern', 'lebagol'),
                    //====end_premium
                ),
            ));

            $wp_customize->add_setting('lebagol_options_blog_columns', array(
                'type'              => 'option',
                'default'           => 1,
                'sanitize_callback' => 'sanitize_text_field',
            ));

            $wp_customize->add_control('lebagol_options_blog_columns', array(
                'section' => 'lebagol_blog_archive',
                'label'   => esc_html__('Colunms', 'lebagol'),
                'type'    => 'select',
                'choices' => array(
                    1 => esc_html__('1', 'lebagol'),
                    2 => esc_html__('2', 'lebagol'),
                    3 => esc_html__('3', 'lebagol'),
                    4 => esc_html__('4', 'lebagol'),
                ),
            ));

            $wp_customize->add_setting('lebagol_options_blog_archive_sidebar', array(
                'type'              => 'option',
                'default'           => 'right',
                'sanitize_callback' => 'sanitize_text_field',
            ));

            $wp_customize->add_control('lebagol_options_blog_archive_sidebar', array(
                'section' => 'lebagol_blog_archive',
                'label'   => esc_html__('Sidebar Position', 'lebagol'),
                'type'    => 'select',
                'choices' => array(
                    'left'  => esc_html__('Left', 'lebagol'),
                    'right' => esc_html__('Right', 'lebagol'),
                ),
            ));
        }

        /**
         * @param $wp_customize WP_Customize_Manager
         *
         * @return void
         */
        public function init_lebagol_social($wp_customize) {

            $wp_customize->add_section('lebagol_social', array(
                'title' => esc_html__('Socials', 'lebagol'),
            ));
            $wp_customize->add_setting('lebagol_options_social_share', array(
                'type'              => 'option',
                'capability'        => 'edit_theme_options',
                'sanitize_callback' => 'sanitize_text_field',
            ));

            $wp_customize->add_control('lebagol_options_social_share', array(
                'type'    => 'checkbox',
                'section' => 'lebagol_social',
                'label'   => esc_html__('Show Social Share', 'lebagol'),
            ));
            $wp_customize->add_setting('lebagol_options_social_share_facebook', array(
                'type'              => 'option',
                'capability'        => 'edit_theme_options',
                'sanitize_callback' => 'sanitize_text_field',
            ));

            $wp_customize->add_control('lebagol_options_social_share_facebook', array(
                'type'    => 'checkbox',
                'section' => 'lebagol_social',
                'label'   => esc_html__('Share on Facebook', 'lebagol'),
            ));
            $wp_customize->add_setting('lebagol_options_social_share_twitter', array(
                'type'              => 'option',
                'capability'        => 'edit_theme_options',
                'sanitize_callback' => 'sanitize_text_field',
            ));

            $wp_customize->add_control('lebagol_options_social_share_twitter', array(
                'type'    => 'checkbox',
                'section' => 'lebagol_social',
                'label'   => esc_html__('Share on Twitter', 'lebagol'),
            ));
            $wp_customize->add_setting('lebagol_options_social_share_linkedin', array(
                'type'              => 'option',
                'capability'        => 'edit_theme_options',
                'sanitize_callback' => 'sanitize_text_field',
            ));

            $wp_customize->add_control('lebagol_options_social_share_linkedin', array(
                'type'    => 'checkbox',
                'section' => 'lebagol_social',
                'label'   => esc_html__('Share on Linkedin', 'lebagol'),
            ));
            $wp_customize->add_setting('lebagol_options_social_share_google-plus', array(
                'type'              => 'option',
                'capability'        => 'edit_theme_options',
                'sanitize_callback' => 'sanitize_text_field',
            ));

            $wp_customize->add_control('lebagol_options_social_share_google-plus', array(
                'type'    => 'checkbox',
                'section' => 'lebagol_social',
                'label'   => esc_html__('Share on Google+', 'lebagol'),
            ));

            $wp_customize->add_setting('lebagol_options_social_share_pinterest', array(
                'type'              => 'option',
                'capability'        => 'edit_theme_options',
                'sanitize_callback' => 'sanitize_text_field',
            ));

            $wp_customize->add_control('lebagol_options_social_share_pinterest', array(
                'type'    => 'checkbox',
                'section' => 'lebagol_social',
                'label'   => esc_html__('Share on Pinterest', 'lebagol'),
            ));
            $wp_customize->add_setting('lebagol_options_social_share_email', array(
                'type'              => 'option',
                'capability'        => 'edit_theme_options',
                'sanitize_callback' => 'sanitize_text_field',
            ));

            $wp_customize->add_control('lebagol_options_social_share_email', array(
                'type'    => 'checkbox',
                'section' => 'lebagol_social',
                'label'   => esc_html__('Share on Email', 'lebagol'),
            ));
        }

        /**
         * @param $wp_customize WP_Customize_Manager
         *
         * @return void
         */
        public function init_woocommerce($wp_customize) {

            $wp_customize->add_panel('woocommerce', array(
                'title' => esc_html__('Woocommerce', 'lebagol'),
            ));

            $wp_customize->add_section('lebagol_woocommerce_archive', array(
                'title'      => esc_html__('Archive', 'lebagol'),
                'capability' => 'edit_theme_options',
                'panel'      => 'woocommerce',
                'priority'   => 1,
            ));

            if (class_exists('Lebagol_Customize_Control_Button_Switch')) {

                $wp_customize->add_setting('lebagol_options_wocommerce_archive_enable_full', array(
                    'type'              => 'option',
                    'default'           => '',
                    'sanitize_callback' => 'lebagol_sanitize_button_switch',
                ));
                $wp_customize->add_control(new Lebagol_Customize_Control_Button_Switch($wp_customize, 'lebagol_options_wocommerce_archive_enable_full', array(
                    'section'   => 'lebagol_woocommerce_archive',
                    'transport' => 'refresh',
                    'label'     => esc_html__('Enable Full Width', 'lebagol'),
                )));
            }

            $wp_customize->add_setting('lebagol_options_woocommerce_archive_layout', array(
                'type'              => 'option',
                'default'           => 'default',
                'sanitize_callback' => 'sanitize_text_field',
            ));

            $wp_customize->add_control('lebagol_options_woocommerce_archive_layout', array(
                'section' => 'lebagol_woocommerce_archive',
                'label'   => esc_html__('Layout Style', 'lebagol'),
                'type'    => 'select',
                'choices' => array(
                    'default'  => esc_html__('Sidebar', 'lebagol'),
                    //====start_premium
                    'canvas'   => esc_html__('Canvas Filter', 'lebagol'),
                    'menu'     => esc_html__('Menu Filter', 'lebagol'),
                    'dropdown' => esc_html__('Dropdown Filter', 'lebagol'),
                    'drawing'  => esc_html__('Drawing Filter', 'lebagol'),
                    //====end_premium
                ),
            ));

            $wp_customize->add_setting('lebagol_options_woocommerce_archive_sidebar', array(
                'type'              => 'option',
                'default'           => 'left',
                'sanitize_callback' => 'sanitize_text_field',
            ));

            $wp_customize->add_control('lebagol_options_woocommerce_archive_sidebar', array(
                'section' => 'lebagol_woocommerce_archive',
                'label'   => esc_html__('Sidebar Position', 'lebagol'),
                'type'    => 'select',
                'choices' => array(
                    'left'  => esc_html__('Left', 'lebagol'),
                    'right' => esc_html__('Right', 'lebagol'),

                ),
            ));

            $wp_customize->add_setting('lebagol_options_woocommerce_shop_pagination', array(
                'type'              => 'option',
                'default'           => 'default',
                'sanitize_callback' => 'sanitize_text_field',
            ));

            $wp_customize->add_control('lebagol_options_woocommerce_shop_pagination', array(
                'section' => 'lebagol_woocommerce_archive',
                'label'   => esc_html__('Products pagination', 'lebagol'),
                'type'    => 'select',
                'choices' => array(
                    'default'  => esc_html__('Pagination', 'lebagol'),
                    'more-btn' => esc_html__('Load More', 'lebagol'),
                    'infinit'  => esc_html__('Infinit Scroll', 'lebagol'),
                ),
            ));

            // =========================================
            // Single Product
            // =========================================

            $wp_customize->add_section('lebagol_woocommerce_single', array(
                'title'      => esc_html__('Single Product', 'lebagol'),
                'capability' => 'edit_theme_options',
                'panel'      => 'woocommerce',
            ));

            $wp_customize->add_setting('lebagol_options_single_product_gallery_layout', array(
                'type'              => 'option',
                'default'           => 'horizontal',
                'transport'         => 'refresh',
                'sanitize_callback' => 'sanitize_text_field',
            ));
            $wp_customize->add_control('lebagol_options_single_product_gallery_layout', array(
                'section' => 'lebagol_woocommerce_single',
                'label'   => esc_html__('Layout Style', 'lebagol'),
                'type'    => 'select',
                'choices' => array(
                    'horizontal'     => esc_html__('Horizontal', 'lebagol'),
                    'vertical'       => esc_html__('Vertical', 'lebagol'),
                    'sticky'         => esc_html__('Sticky', 'lebagol'),
                    'gallery'        => esc_html__('Gallery', 'lebagol'),
                    'with-sidebar'   => esc_html__('With Sidebar', 'lebagol'),
                    'sticky-sidebar' => esc_html__('Sticky Sidebar', 'lebagol'),
                    'sticky-summary' => esc_html__('Sticky Summary', 'lebagol'),
                ),
            ));
            if (lebagol_is_contactform_activated()) {
                $cf7               = get_posts('post_type="wpcf7_contact_form"&numberposts=-1');
                $contact_forms[''] = esc_html__('None', 'lebagol');
                if ($cf7) {
                    foreach ($cf7 as $cform) {
                        $contact_forms[$cform->ID] = $cform->post_title;
                    }
                } else {
                    $contact_forms[0] = esc_html__('No contact forms found', 'lebagol');
                }

                $wp_customize->add_setting('lebagol_options_single_product_ask', array(
                    'type'              => 'option',
                    'default'           => '',
                    'transport'         => 'refresh',
                    'sanitize_callback' => 'sanitize_text_field',
                ));

                $wp_customize->add_control('lebagol_options_single_product_ask', array(
                    'section' => 'lebagol_woocommerce_single',
                    'label'   => esc_html__('Ask a question form', 'lebagol'),
                    'type'    => 'select',
                    'choices' => $contact_forms,
                ));

            }

            $wp_customize->add_setting('lebagol_options_single_product_content_meta', array(
                'type'              => 'option',
                'capability'        => 'edit_theme_options',
                'sanitize_callback' => 'lebagol_sanitize_editor',
            ));

            $wp_customize->add_control(new Lebagol_Customize_Control_Editor($wp_customize, 'lebagol_options_single_product_content_meta', array(
                'section' => 'lebagol_woocommerce_single',
                'label'   => esc_html__('Single extra description', 'lebagol'),
            )));

            $wp_customize->add_section('lebagol_woocommerce_product', array(
                'title'      => esc_html__('Product Block', 'lebagol'),
                'capability' => 'edit_theme_options',
                'panel'      => 'woocommerce',
            ));

            $wp_customize->add_setting('lebagol_options_woocommerce_product_hover', array(
                'type'              => 'option',
                'default'           => 'none',
                'transport'         => 'refresh',
                'sanitize_callback' => 'sanitize_text_field',
            ));
            $wp_customize->add_control('lebagol_options_woocommerce_product_hover', array(
                'section' => 'lebagol_woocommerce_product',
                'label'   => esc_html__('Animation Image Hover', 'lebagol'),
                'type'    => 'select',
                'choices' => array(
                    'none'          => esc_html__('None', 'lebagol'),
                    'bottom-to-top' => esc_html__('Bottom to Top', 'lebagol'),
                    'top-to-bottom' => esc_html__('Top to Bottom', 'lebagol'),
                    'right-to-left' => esc_html__('Right to Left', 'lebagol'),
                    'left-to-right' => esc_html__('Left to Right', 'lebagol'),
                    'swap'          => esc_html__('Swap', 'lebagol'),
                    'fade'          => esc_html__('Fade', 'lebagol'),
                    'zoom-in'       => esc_html__('Zoom In', 'lebagol'),
                    'zoom-out'      => esc_html__('Zoom Out', 'lebagol'),
                ),
            ));
        }
    }
}
return new Lebagol_Customize();
