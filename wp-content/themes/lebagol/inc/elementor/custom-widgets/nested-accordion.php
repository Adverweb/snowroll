<?php
// nested-tabs
use Elementor\Controls_Manager;

add_action('elementor/element/nested-accordion/section_accordion_style/before_section_end', function ($element, $args) {
    /** @var \Elementor\Element_Base $element */
    $element->add_responsive_control(
        'header_height',
        [
            'label'     => __('Height', 'lebagol'),
            'type'      => Controls_Manager::SLIDER,
            'range'     => [
                'px' => [
                    'min' => 0,
                    'max' => 100,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .e-n-accordion-item-title' => 'height: {{SIZE}}{{UNIT}};',
            ],
        ]
    );
}, 10, 2);