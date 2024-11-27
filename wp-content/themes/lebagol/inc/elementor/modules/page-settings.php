<?php
if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('Lebagol_Elementor_Page_Settings')) :
    /**
     * The main Lebagol_Elementor_Page_Settings class
     */
    class Lebagol_Elementor_Page_Settings {
        public function __construct() {
            add_action('elementor/documents/register_controls', [$this, 'register_controls']);
        }

        public function register_controls( $document ) {

            $document->start_controls_section(
                'lebagol_breadcrumb_settings',
                [
                    'label' => esc_html__('Breadcrumb Settings', 'lebagol'),
                    'tab'   => \Elementor\Controls_Manager::TAB_SETTINGS,
                ]
            );

            $id = get_the_ID();

            $document->add_control(
                'lebagol_breadcrumb_switch',
                [
                    'label' => esc_html__('Hide Breadcrumb', 'lebagol'),
                    'type'  => Elementor\Controls_Manager::SWITCHER,
                    'selectors' => [
                        '.elementor-page-' . $id => '--page-breadcrumb-display: none',
                    ],
                ]
            );

            $document->add_group_control(
                \Elementor\Group_Control_Background::get_type(),
                [
                    'name'     => 'lebagol_breadcrumb_background',
                    'selector' => '.breadcrumb-wrap, .elementor-page-' . $id . ' .breadcrumb-wrap',
                ]
            );


            $document->add_control('lebagol_breadcrumb_background_overlay_heading',[
                'label' => esc_html__('Background Overlay', 'lebagol'),
                'type'  => Elementor\Controls_Manager::HEADING,
                'separator' => 'before'
            ]);

            $document->add_group_control(
                \Elementor\Group_Control_Background::get_type(),
                [
                    'name' => 'lebagol_breadcrumb_background_overlay',
                    'selector' => '.breadcrumb-wrap .breadcrumb-overlay, .elementor-page-' . $id . ' .breadcrumb-wrap .breadcrumb-overlay',
                ]
            );

            $document->add_control(
                'lebagol_breadcrumb_background_overlay_opacity',
                [
                    'label' => esc_html__( 'Opacity', 'lebagol' ),
                    'type' => \Elementor\Controls_Manager::SLIDER,
                    'default' => [
                        'size' => .5,
                    ],
                    'range' => [
                        'px' => [
                            'max' => 1,
                            'step' => 0.01,
                        ],
                    ],
                    'selectors' => [
                        '.breadcrumb-wrap .breadcrumb-overlay, .elementor-page-' . $id . ' .breadcrumb-wrap .breadcrumb-overlay' => 'opacity: {{SIZE}};',
                    ],
                ]
            );

            $document->end_controls_section();
        }
    }
endif;

new Lebagol_Elementor_Page_Settings();
