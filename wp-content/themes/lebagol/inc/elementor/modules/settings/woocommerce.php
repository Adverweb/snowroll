<?php

use Elementor\Core\Base\Document;
use Elementor\Controls_Manager;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Lebagol_Elementor_Settings_Woocommerce extends Elementor\Core\Kits\Documents\Tabs\Tab_Base {

    public function get_id() {
        return 'settings-woocommerce';
    }

    public function get_title() {
        return esc_html__('Lebagol WooCommerce', 'lebagol');
    }

    public function get_group() {
        return 'settings';
    }

    public function get_icon() {
        return 'eicon-woocommerce';
    }

    protected function register_tab_controls() {
        $this->start_controls_section(
            'section_' . $this->get_id(),
            [
                'label' => $this->get_title(),
                'tab'   => $this->get_id(),
            ]
        );

        $this->add_control(
            $this->get_id() . '_refresh_notice',
            [
                'type'            => Controls_Manager::RAW_HTML,
                'raw'             => esc_html__('Changes will be reflected in the preview only after the page reloads.', 'lebagol'),
                'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
            ]
        );

        $this->add_control(
            'woocommerce_product_catalog',
            [
                'label' => esc_html__( 'Product Catalog', 'lebagol' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'woocommerce_catalog_columns',
            [
                'label'     => esc_html__('Products Columns', 'lebagol'),
                'type'      => \Elementor\Controls_Manager::SELECT,
                'default'   => 3,
                'options'   => [1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5, 6 => 6],
                'selectors' => [
                    'ul.lebagol-products.products:not(.products-list)' => 'grid-template-columns: repeat({{VALUE}}, 1fr)',
                ],
            ]
        );

        $this->add_responsive_control(
            'woocommerce_catalog_gutter',
            [
                'label'      => esc_html__('Gutter Width (px)', 'lebagol'),
                'type'       => Controls_Manager::SLIDER,
                'range'      => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'size' => 0
                ],
                'size_units' => ['px'],
                'selectors'  => [
                    'ul.lebagol-products.products:not(.products-list)' => 'grid-gap:{{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_control(
            'woocommerce_catalog_rows',
            [
                'label'   => esc_html__('Products per row', 'lebagol'),
                'type'    => \Elementor\Controls_Manager::TEXT,
                'default' => 4,
            ]
        );

        $this->add_control(
            'lebagol_options_woocommerce_product_hover',
            [
                'label'     => esc_html__('Product Hover', 'lebagol'),
                'type'      => \Elementor\Controls_Manager::SELECT,
                'default'   => 'none',
                'options' => [
                    'fade'  => esc_html__( 'Fade', 'lebagol' ),
                    'zoom-in' => esc_html__( 'Zoom in', 'lebagol' ),
                    'swap' => esc_html__( 'Swap', 'lebagol' ),
                    'top-to-bottom' => esc_html__( 'Top to bottom', 'lebagol' ),
                    'bottom-to-top' => esc_html__( 'Bottom to Top', 'lebagol' ),
                    'left-to-right' => esc_html__( 'Left to right', 'lebagol' ),
                    'right-to-left' => esc_html__( 'Right to left', 'lebagol' ),
                    'none' => esc_html__( 'None', 'lebagol' ),
                ],
            ]
        );

        $this->add_control(
            'woocommerce_product_related_carousel',
            [
                'label' => esc_html__( 'Product Carousel Global', 'lebagol' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $slides_to_show = range(1, 10);
        $slides_to_show = array_combine($slides_to_show, $slides_to_show);

        $this->add_responsive_control(
            'woo_carousel_slides_to_show',
            [
                'label'              => esc_html__('Slides to Show', 'lebagol'),
                'type'               => Controls_Manager::TEXT,
                'frontend_available' => true,
                'default'            => 4,
                'render_type'        => 'template',
                'selectors'          => [
                    '.related .swiper' . $this->swiper_class() . ':not(.swiper-initialized) .swiper-slide' => 'flex: 0 0 calc(100% / {{VALUE}}); width: calc(100% / {{VALUE}}); margin-right:{{woo_carousel_spaceBetween.SIZE}}{{woo_carousel_spaceBetween.UNIT}}',
                    '.upsells .swiper' . $this->swiper_class() . ':not(.swiper-initialized) .swiper-slide' => 'flex: 0 0 calc(100% / {{VALUE}}); width: calc(100% / {{VALUE}}); margin-right:{{woo_carousel_spaceBetween.SIZE}}{{woo_carousel_spaceBetween.UNIT}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'woo_carousel_slides_to_scroll',
            [
                'label'              => esc_html__('Slides to Scroll', 'lebagol'),
                'type'               => Controls_Manager::SELECT,
                'description'        => esc_html__('Set how many slides are scrolled per swipe.', 'lebagol'),
                'options'            => [
                                            '' => esc_html__('Default', 'lebagol'),
                                        ] + $slides_to_show,
                'frontend_available' => true,
                'condition'          => [
                    'woo_carousel_slides_to_show!' => '1'
                ],
            ]
        );

        $this->add_responsive_control(
            'woo_carousel_spaceBetween',
            [
                'label'              => esc_html__('Space Between', 'lebagol'),
                'type'               => Controls_Manager::SLIDER,
                'range'              => [
                    'px' => [
                        'min' => 0,
                        'max' => 60,
                    ],
                ],
                'default'            => [
                    'size' => 20
                ],
                'size_units'         => ['px'],
                'frontend_available' => true,
            ]
        );


        $this->add_control(
            'woo_carousel_navigation',
            [
                'label'              => esc_html__('Navigation', 'lebagol'),
                'type'               => Controls_Manager::SELECT,
                'default'            => 'dots',
                'options'            => [
                    'both'   => esc_html__('Arrows and Dots', 'lebagol'),
                    'arrows' => esc_html__('Arrows', 'lebagol'),
                    'dots'   => esc_html__('Dots', 'lebagol'),
                    'none'   => esc_html__('None', 'lebagol'),
                ],
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'woo_carousel_autoplay',
            [
                'label'              => esc_html__('Autoplay', 'lebagol'),
                'type'               => Controls_Manager::SELECT,
                'default'            => 'no',
                'options'            => [
                    'yes' => esc_html__('Yes', 'lebagol'),
                    'no'  => esc_html__('No', 'lebagol'),
                ],
                'frontend_available' => true,
            ]
        );
        $this->add_control(
            'woo_carousel_autoplay_speed',
            [
                'label'              => esc_html__('Autoplay Speed', 'lebagol'),
                'type'               => Controls_Manager::NUMBER,
                'default'            => 5000,
                'condition'          => [
                    'woo_carousel_autoplay' => 'yes',
                ],
                'render_type'        => 'none',
                'frontend_available' => true,
            ]
        );

        $this->end_controls_section();
    }

    public function swiper_class() {
        return Elementor\Plugin::$instance->experiments->is_feature_active('e_swiper_latest') ? '' : '-container';
    }

    public function on_save($data) {
        if (
            !isset($data['settings']['post_status'])
            || Document::STATUS_PUBLISH !== $data['settings']['post_status']
            || // Should check for the current action to avoid infinite loop
            // when updating options like: "blogname" and "blogdescription".
            strpos(current_action(), 'update_option_') === 0
        ) {
            return;
        }

        if (isset($data['settings']['woocommerce_catalog_columns'])) {
            update_option('woocommerce_catalog_columns', $data['settings']['woocommerce_catalog_columns']);
        }
        if (isset($data['settings']['woocommerce_catalog_rows'])) {
            update_option('woocommerce_catalog_rows', $data['settings']['woocommerce_catalog_rows']);
        }

        if (isset($data['settings']['lebagol_options_woocommerce_product_hover'])) {
            update_option('lebagol_options_woocommerce_product_hover', $data['settings']['lebagol_options_woocommerce_product_hover']);
        }

        if (isset($data['settings']['woocommerce_product_style'])) {
            update_option('lebagol_options_woocommerce_product_style', $data['settings']['woocommerce_product_style']);
        }

    }
}
