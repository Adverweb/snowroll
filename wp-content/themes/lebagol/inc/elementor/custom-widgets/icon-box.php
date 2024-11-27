<?php

use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;

add_action('elementor/element/icon-box/section_style_icon/before_section_end', function ($element, $args) {
    /** @var \Elementor\Element_Base $element */
    $element->add_control(
        'border_color',
        [
            'label' => esc_html__( 'Border Color', 'lebagol' ),
            'type' => Controls_Manager::COLOR,
            'default' => '',
            'selectors' => [
                '{{WRAPPER}}.elementor-view-framed .elementor-icon' => 'border-color: {{VALUE}};',
            ],
            'global' => [
                'default' => Global_Colors::COLOR_PRIMARY,
            ],
            'condition' => [
                'view' => 'framed',
            ],
        ]
    );
}, 10, 2);

add_action('elementor/element/icon-box/section_icon/before_section_end', function ($element, $args) {
    /** @var \Elementor\Element_Base $element */
    $element->add_control(
        'show_icon_effect',
        [
            'label' => esc_html__('Show Icon Effect', 'lebagol'),
            'type' => Controls_Manager::SWITCHER,
            'condition' => [
                'selected_icon[value]!' => '',
            ],
            'prefix_class' => 'icon-box-style-lebagol-',
        ]
    );
}, 10, 2);