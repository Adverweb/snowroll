<?php
//Heading
use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
add_action( 'elementor/element/heading/section_title_style/before_section_end', function ( $element, $args ) {
    $element->add_control(
        'title_gradient',
        [
            'label' => esc_html__('Gradient', 'lebagol'),
            'type' => Controls_Manager::SWITCHER,
            'default' => '',
            'prefix_class' => 'title-gradient-',
        ]
    );

    $element->add_group_control(
        Group_Control_Background::get_type(),
        [
            'name'           => 'select_gradient',
            'label'          => esc_html__('Color', 'lebagol'),
            'types'          => ['gradient'],
            'selector'       => '{{WRAPPER}} .elementor-heading-title',
            'condition'  => [
                'title_gradient' => 'yes',
            ],
        ]
    );

}, 10, 2 );
