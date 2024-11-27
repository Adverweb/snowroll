<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

class Lebagol_Elementor_Header_Group extends Elementor\Widget_Base {

    public function get_name() {
        return 'lebagol-header-group';
    }

    public function get_title() {
        return esc_html__('Lebagol Header Group', 'lebagol');
    }

    public function get_icon() {
        return 'eicon-lock-user';
    }

    public function get_categories() {
        return array('lebagol-addons');
    }

    protected function register_controls() {

        $this->start_controls_section(
            'header_group_config',
            [
                'label' => esc_html__('Config', 'lebagol'),
            ]
        );

        $this->add_control(
            'show_search',
            [
                'label' => esc_html__('Show search', 'lebagol'),
                'type'  => Controls_Manager::SWITCHER,
            ]
        );

        $this->add_control(
            'show_account',
            [
                'label' => esc_html__('Show account', 'lebagol'),
                'type'  => Controls_Manager::SWITCHER,
            ]
        );

        $this->add_control(
            'display_content_acc',
            [
                'label'        => esc_html__('Show Content', 'lebagol'),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'prefix_class' => 'hidden-lebagol-content-acc-',
                'condition'  => [
                        'show_account' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'show_wishlist',
            [
                'label' => esc_html__('Show wishlist', 'lebagol'),
                'type'  => Controls_Manager::SWITCHER,
            ]
        );

        $this->add_control(
            'show_cart',
            [
                'label' => esc_html__('Show cart', 'lebagol'),
                'type'  => Controls_Manager::SWITCHER,
            ]
        );

        $this->add_control(
            'hide_dropdown',
            [
                'label' => esc_html__('Hide dropdown', 'lebagol'),
                'type'  => Controls_Manager::SWITCHER,
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'header_group_icon',
            [
                'label' => esc_html__('Icon', 'lebagol'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'icon_color',
            [
                'label'     => esc_html__('Color', 'lebagol'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .elementor-header-group-wrapper .header-group-action > div a:not(:hover) i:before' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .elementor-header-group-wrapper .header-group-action > div a:not(:hover):before'   => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'icon_color_hover',
            [
                'label'     => esc_html__('Color Hover', 'lebagol'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .elementor-header-group-wrapper .header-group-action > div a:hover i:before' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .elementor-header-group-wrapper .header-group-action > div a:hover:before'   => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'wrap_background',
            [
                'label'     => esc_html__('Background Color', 'lebagol'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .header-group-action > div' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_size',
            [
                'label'     => esc_html__('Font Size', 'lebagol'),
                'type'      => Controls_Manager::SLIDER,
                'range'     => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-header-group-wrapper .header-group-action > div a i:before' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .elementor-header-group-wrapper .header-group-action > div a:before'   => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_padding',
            [
                'label'      => esc_html__('Padding', 'lebagol'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .header-group-action > div a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );


        $this->add_control(
            'icon-border',
            [
                'label'        => esc_html__('Icon Border', 'lebagol'),
                'type'         => Controls_Manager::SWITCHER,
                'prefix_class' => 'icon-border-',
            ]
        );

        $this->add_responsive_control(
            'border_width',
            [
                'label'      => esc_html__('Border Width', 'lebagol'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .header-group-action > div' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition'  => [
                    'icon-border' => 'yes',
                ],
            ]
        );
        $this->add_control(
            'border_radius',
            [
                'label'      => esc_html__('Border Radius', 'lebagol'),
                'type'      => Controls_Manager::SLIDER,
                'range'     => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .header-group-action > div' => 'border-radius: {{SIZE}}{{UNIT}}',
                ],
                'condition'  => [
                    'icon-border' => 'yes',
                ],
            ]
        );
        $this->add_control(
            'border_color',
            [
                'label'     => esc_html__('Border Color', 'lebagol'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .header-group-action > div' => 'border-color: {{VALUE}};',
                ],
                'condition' => [
                    'icon-border' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_min-width',
            [
                'label'     => esc_html__('Min Height', 'lebagol'),
                'type'      => Controls_Manager::SLIDER,
                'range'     => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}.icon-border-yes .header-group-action > div' => 'min-width: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'icon-border' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_min-height',
            [
                'label'     => esc_html__('Min Height', 'lebagol'),
                'type'      => Controls_Manager::SLIDER,
                'range'     => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}.icon-border-yes .header-group-action > div' => 'min-height: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'icon-border' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'header_group_count',
            [
                'label' => esc_html__('Count', 'lebagol'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'count_color',
            [
                'label'     => esc_html__('Color', 'lebagol'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .elementor-header-group-wrapper .header-group-action div span.count' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'count_background_color',
            [
                'label'     => esc_html__('Background Color', 'lebagol'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .elementor-header-group-wrapper .header-group-action .count' => 'background-color: {{VALUE}};',
                ],
            ]
        );


        $this->end_controls_section();

    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $this->add_render_attribute('wrapper', 'class', 'elementor-header-group-wrapper');
        if ('yes' == $settings['hide_dropdown']) {

            $this->add_render_attribute('wrapper', 'class', 'header-group-hide-dropdown');
        }
        ?>
        <div <?php $this->print_render_attribute_string('wrapper'); ?>>
            <div class="header-group-action">
                <?php if ($settings['show_search'] === 'yes') {
                    lebagol_header_search_button();
                }
                if ($settings['show_account'] === 'yes') {
                    if (lebagol_is_woocommerce_activated()) {
                        $account_link = get_permalink(get_option('woocommerce_myaccount_page_id'));
                    } else {
                        $account_link = wp_login_url();
                    }
                    ?>
                    <div class="site-header-account">
                        <a href="<?php echo esc_url($account_link); ?>">
                            <i class="lebagol-icon-user"></i>
                        </a>
                        <div class="account-dropdown">

                        </div>
                    </div>
                    <?php
                }
                if ($settings['show_wishlist'] === 'yes' && lebagol_is_woocommerce_activated()) {
                    lebagol_header_wishlist();
                }
                if ($settings['show_cart'] === 'yes' && lebagol_is_woocommerce_activated()) {
                    lebagol_header_cart();
                }
                ?>

            </div>
        </div>
        <?php
    }
}

$widgets_manager->register(new Lebagol_Elementor_Header_Group());
