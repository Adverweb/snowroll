<?php
// nested-tabs
use Elementor\Controls_Manager;

add_action('elementor/element/nested-tabs/section_tabs/before_section_end', function ($element, $args) {
    /** @var \Elementor\Element_Base $element */
    $element->add_control(
        'tabs_style',
        [
            'label'        => esc_html__('Style', 'lebagol'),
            'type'         => Controls_Manager::SELECT,
            'default'      => '',
            'options'      => [
                ''   => esc_html__('default', 'lebagol'),
                '1' => esc_html__('Style 1', 'lebagol'),
            ],
            'prefix_class' => 'elementor-tabs-style',
        ]
    );
}, 10, 2);

add_action('elementor/element/nested-tabs/section_title_style/before_section_end', function ($element, $args) {
    /** @var \Elementor\Element_Base $element */
    $element->add_responsive_control(
        'titles_margin',
        [
            'label'      => esc_html__('Margin', 'lebagol'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em', '%'],
            'selectors'  => [
                '{{WRAPPER}} .e-n-tabs-heading' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]
    );
}, 10, 2);