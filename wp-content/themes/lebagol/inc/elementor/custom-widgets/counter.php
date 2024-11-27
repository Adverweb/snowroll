<?php
// Counter
use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
add_action('elementor/element/counter/section_number/before_section_end', function ($element, $args) {
    $element->add_responsive_control(
        'number_alignment',
        [
            'label' => esc_html__('Alignment', 'lebagol'),
            'type' => Controls_Manager::CHOOSE,
            'options' => [
                'flex-start' => [
                    'title' => esc_html__('Left', 'lebagol'),
                    'icon' => 'eicon-text-align-left',
                ],
                'center' => [
                    'title' => esc_html__('Center', 'lebagol'),
                    'icon' => 'eicon-text-align-center',
                ],
                'flex-end' => [
                    'title' => esc_html__('Right', 'lebagol'),
                    'icon' => 'eicon-text-align-right',
                ],
            ],
            'default' => 'center',
            'selectors' => [
                '{{WRAPPER}} .elementor-counter-number-wrapper' => 'justify-content: {{VALUE}}',
            ],
        ]
    );
    $element->add_control(
        'number_gradient',
        [
            'label' => esc_html__('Gradient', 'lebagol'),
            'type' => Controls_Manager::SWITCHER,
            'default' => '',
            'prefix_class' => 'number-gradient-',
        ]
    );

    $element->add_group_control(
        Group_Control_Background::get_type(),
        [
            'name'           => 'select_gradient',
            'label'          => esc_html__('Color', 'lebagol'),
            'types'          => ['gradient'],
            'selector'       => '{{WRAPPER}} .elementor-counter-number-prefix .elementor-counter-number .elementor-counter-number-suffix',
            'condition'  => [
                'number_gradient' => 'yes',
            ],
        ]
    );
}, 10, 2);

add_action('elementor/element/counter/section_title/before_section_end', function ($element, $args) {
    $element->add_responsive_control(
        'title_margin',
        [
            'label'      => esc_html__('Margin', 'lebagol'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em', '%'],
            'selectors'  => [
                '{{WRAPPER}} .elementor-counter-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]
    );
    $element->add_responsive_control(
        'title_align',
        [
            'label'     => esc_html__('Alignment', 'lebagol'),
            'type'      => Controls_Manager::CHOOSE,
            'options'   => [
                'left' => [
                    'title' => esc_html__('Left', 'lebagol'),
                    'icon'  => 'eicon-text-align-left',
                ],
                'center'     => [
                    'title' => esc_html__('Center', 'lebagol'),
                    'icon'  => 'eicon-text-align-center',
                ],
                'right'   => [
                    'title' => esc_html__('Right', 'lebagol'),
                    'icon'  => 'eicon-text-align-right',
                ],
            ],
            'default'   => 'center',
            'selectors' => [
                '{{WRAPPER}} .elementor-counter-title' => 'text-align: {{VALUE}};',
            ],
        ]
    );
}, 10, 2);