<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Repeater;
use Elementor\Group_Control_Image_Size;

class Lebagol_Elementor_ImgBoxs extends Lebagol_Base_Widgets_Swiper {
    /**
     * Get widget name.
     *
     * Retrieve imgbox widget name.
     *
     * @return string Widget name.
     * @since  1.0.0
     * @access public
     *
     */
    public function get_name() {
        return 'lebagol-imgbox';
    }

    /**
     * Get widget title.
     *
     * Retrieve imgbox widget title.
     *
     * @return string Widget title.
     * @since  1.0.0
     * @access public
     *
     */
    public function get_title() {
        return esc_html__('Lebagol Image Box Carousel', 'lebagol');
    }

    /**
     * Get widget icon.
     *
     * Retrieve imgbox widget icon.
     *
     * @return string Widget icon.
     * @since  1.0.0
     * @access public
     *
     */
    public function get_icon() {
        return 'eicon-carousel';
    }

    public function get_script_depends() {
        return ['lebagol-elementor-imgbox', 'lebagol-elementor-swiper'];
    }

    public function get_categories() {
        return array('lebagol-addons');
    }

    /**
     * Register imgbox widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since  1.0.0
     * @access protected
     */
    protected function register_controls() {
        $this->start_controls_section(
            'section_imgbox',
            [
                'label' => esc_html__('ImgBoxs', 'lebagol'),
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'imgbox_title',
            [
                'label'   => esc_html__('Title', 'lebagol'),
                'type'    => Controls_Manager::TEXT,
                'default'     => '',
            ]
        );

        $repeater->add_control(
            'imgbox_des',
            [
                'label' => esc_html__( 'Description', 'lebagol' ),
                'type' => Controls_Manager::TEXTAREA,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'lebagol' ),
                'placeholder' => esc_html__( 'Enter your description', 'lebagol' ),
                'separator' => 'none',
                'rows' => 5,
            ]
        );

        $repeater->add_control(
            'imgbox_link',
            [
                'label'       => esc_html__('Link to', 'lebagol'),
                'placeholder' => esc_html__('https://your-link.com', 'lebagol'),
                'type'        => Controls_Manager::URL,
            ]
        );

        $repeater->add_control(
            'imgbox_image',
            [
                'label'      => esc_html__('Choose Image', 'lebagol'),
                'type'       => Controls_Manager::MEDIA,
                'show_label' => false,
            ]
        );

        $repeater->add_control(
            'imgbox_bgcolor_item',
            [
                'label' => __('Background Item Color', 'lebagol'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}.imgbox-wrapper' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $repeater->add_control(
            'imgbox_bgcolor_image',
            [
                'label' => __('Background Image Color', 'lebagol'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .imgbox-image:before' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'imgboxs',
            [
                'label'       => esc_html__('Items', 'lebagol'),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'title_field' => '{{{ imgbox_title }}}',
            ]
        );

        $this->add_group_control(
            Elementor\Group_Control_Image_Size::get_type(),
            [
                'name'      => 'imgbox_image',
                'default'   => 'full',
                'separator' => 'none',
            ]
        );

        $this->add_responsive_control(
            'column',
            [
                'label'        => esc_html__('Columns', 'lebagol'),
                'type'         => \Elementor\Controls_Manager::SELECT,
                'default'      => 1,
                'options'      => [1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5, 6 => 6],
                'selectors' => [
                    '{{WRAPPER}} .d-grid' => 'grid-template-columns: repeat({{VALUE}}, 1fr)',
                ],
                'condition' => ['enable_carousel!' => 'yes']
            ]
        );

        $this->add_responsive_control(
            'gutter',
            [
                'label'      => esc_html__('Gutter', 'lebagol'),
                'type'       => Controls_Manager::SLIDER,
                'range'      => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'size_units' => ['px'],
                'selectors'  => [
                    '{{WRAPPER}} .d-grid' => 'grid-gap:{{SIZE}}{{UNIT}}',
                ],
                'condition'  => ['enable_carousel!' => 'yes']
            ]
        );

        $this->add_responsive_control(
            'imgbox_alignment',
            [
                'label'       => esc_html__('Alignment', 'lebagol'),
                'type'        => Controls_Manager::CHOOSE,
                'options'     => [
                    'left'   => [
                        'title' => esc_html__('Left', 'lebagol'),
                        'icon'  => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'lebagol'),
                        'icon'  => 'eicon-text-align-center',
                    ],
                    'right'  => [
                        'title' => esc_html__('Right', 'lebagol'),
                        'icon'  => 'eicon-text-align-right',
                    ],
                ],
                'label_block' => false,
                'default'      => 'left',
                'selectors'   => [
                    '{{WRAPPER}} .imgbox-image' => 'justify-content: {{VALUE}};',
                    '{{WRAPPER}} .imgbox-description, .imgbox-title, .imgbox-button' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'enable_carousel',
            [
                'label' => esc_html__('Enable Carousel', 'lebagol'),
                'type'  => Controls_Manager::SWITCHER,
            ]
        );

        $this->add_control(
            'view',
            [
                'label'   => esc_html__('View', 'lebagol'),
                'type'    => Controls_Manager::HIDDEN,
                'default' => 'traditional',
            ]
        );
        $this->end_controls_section();


        // WRAPPER STYLE
        $this->start_controls_section(
            'section_style_imgbox_wrapper',
            [
                'label' => esc_html__('Wrapper', 'lebagol'),
                'tab'   => Controls_Manager::TAB_STYLE,

            ]
        );

        $this->add_responsive_control(
            'height_imgbox_wrapper',
            [
                'label'          => esc_html__('Height', 'lebagol'),
                'type'           => Controls_Manager::SLIDER,
                'default'        => [
                    'unit' => 'px',
                ],
                'tablet_default' => [
                    'unit' => 'px',
                ],
                'mobile_default' => [
                    'unit' => 'px',
                ],
                'size_units'     => ['px', 'vh'],
                'range'          => [
                    'px' => [
                        'min' => 1,
                        'max' => 500,
                    ],
                    'vh' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
                'selectors'      => [
                    '{{WRAPPER}} .imgbox-wrapper' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'padding_imgbox_wrapper',
            [
                'label'      => esc_html__('Padding', 'lebagol'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .imgbox-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'margin_imgbox_wrapper',
            [
                'label'      => esc_html__('Margin', 'lebagol'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .imgbox-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs('tabs_wrapper');

        $this->start_controls_tab(
            'tab_wrapper_normal',
            [
                'label' => esc_html__('Normal', 'lebagol'),
            ]
        );

        $this->add_control(
            'imgbox_wrapper',
            [
                'label'     => esc_html__('Background Color', 'lebagol'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .imgbox-wrapper' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'label'     => esc_html__('Boxshadow', 'lebagol'),
                'name'     => 'wrapper_box_shadow',
                'selector' => '{{WRAPPER}} .imgbox-wrapper',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_wrapper_hover',
            [
                'label' => esc_html__('Hover', 'lebagol'),
            ]
        );

        $this->add_control(
            'imgbox_wrapper_hover',
            [
                'label'     => esc_html__('Background Color', 'lebagol'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .imgbox-wrapper:hover' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'label'     => esc_html__('Boxshadow', 'lebagol'),
                'name'     => 'wrapper_box_shadow_hover',
                'selector' => '{{WRAPPER}} .imgbox-wrapper:hover',
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();

        // Image style
        $this->start_controls_section(
            'section_style_imgbox_image',
            [
                'label' => esc_html__('Image', 'lebagol'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );


        $this->add_responsive_control(
            'image_width',
            [
                'label'          => esc_html__('Width', 'lebagol'),
                'type'           => Controls_Manager::SLIDER,
                'default'        => [
                    'unit' => '%',
                ],
                'tablet_default' => [
                    'unit' => '%',
                ],
                'mobile_default' => [
                    'unit' => '%',
                ],
                'size_units'     => ['%', 'px', 'vw'],
                'range'          => [
                    '%'  => [
                        'min' => 1,
                        'max' => 100,
                    ],
                    'px' => [
                        'min' => 1,
                        'max' => 200,
                    ],
                    'vw' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
                'selectors'      => [
                    '{{WRAPPER}} .imgbox-image img' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'image_height',
            [
                'label'          => esc_html__('Height', 'lebagol'),
                'type'           => Controls_Manager::SLIDER,
                'default'        => [
                    'unit' => 'px',
                ],
                'tablet_default' => [
                    'unit' => 'px',
                ],
                'mobile_default' => [
                    'unit' => 'px',
                ],
                'size_units'     => ['px', 'vh'],
                'range'          => [
                    'px' => [
                        'min' => 1,
                        'max' => 500,
                    ],
                    'vh' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
                'selectors'      => [
                    '{{WRAPPER}} .imgbox-image img' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'background_size_image',
            [
                'label'          => esc_html__('Background Size', 'lebagol'),
                'type'           => Controls_Manager::SLIDER,
                'default'        => [
                    'unit' => 'px',
                ],
                'tablet_default' => [
                    'unit' => 'px',
                ],
                'mobile_default' => [
                    'unit' => 'px',
                ],
                'size_units'     => ['px', 'vh'],
                'range'          => [
                    'px' => [
                        'min' => 1,
                        'max' => 500,
                    ],
                    'vh' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
                'selectors'      => [
                    '{{WRAPPER}} .imgbox-image:before' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'image_border_radius',
            [
                'label'      => esc_html__('Border Radius', 'lebagol'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .imgbox-image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_margin',
            [
                'label'      => esc_html__('Margin', 'lebagol'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .imgbox-image' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Title.
        $this->start_controls_section(
            'section_style_imgbox_title',
            [
                'label' => esc_html__('Title', 'lebagol'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'title_text_color',
            [
                'label'     => esc_html__('Color', 'lebagol'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .imgbox-title a' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .imgbox-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'title_text_color_hover',
            [
                'label'     => esc_html__('Color Hover', 'lebagol'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .imgbox-title a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'title_typography',
                'selector' => '{{WRAPPER}} .imgbox-title',
            ]
        );

        $this->add_responsive_control(
            'title_spacing',
            [
                'size_units' => ['px', 'em', '%'],
                'label'      => esc_html__('Spacing', 'lebagol'),
                'type'       => Controls_Manager::SLIDER,
                'range'      => [
                    'px' => [
                        'min' => 0,
                        'max' => 300,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .imgbox-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        //Description
        $this->start_controls_section(
            'section_style_imgbox_des',
            [
                'label' => esc_html__('Description', 'lebagol'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'des_text_color',
            [
                'label'     => esc_html__('Color', 'lebagol'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .imgbox-description' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'des_typography',
                'selector' => '{{WRAPPER}} .imgbox-description',
            ]
        );

        $this->add_responsive_control(
            'des_spacing',
            [
                'size_units' => ['px', 'em', '%'],
                'label'      => esc_html__('Spacing', 'lebagol'),
                'type'       => Controls_Manager::SLIDER,
                'range'      => [
                    'px' => [
                        'min' => 0,
                        'max' => 300,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .imgbox-description' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Carousel options
        $this->add_control_carousel(['enable_carousel' => 'yes']);

    }

    /**
     * Render imgbox widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since  1.0.0
     * @access protected
     */
    protected function render() {
        $settings = $this->get_settings_for_display();
        if (!empty($settings['imgboxs']) && is_array($settings['imgboxs'])) {
            $this->add_render_attribute('wrapper', 'class', 'elementor-imgbox-item-wrapper');
            $this->add_render_attribute('row', 'class', 'alignment-' . esc_attr($settings['imgbox_alignment']));
            // Carousel
            $this->get_data_elementor_columns();
            // Item
            $this->add_render_attribute('item', 'class', 'elementor-imgbox-item');

            ?>
            <div <?php $this->print_render_attribute_string('wrapper'); // WPCS: XSS ok. ?>>
                <div <?php $this->print_render_attribute_string('row'); // WPCS: XSS ok. ?>>
                    <?php foreach ($settings['imgboxs'] as $index => $imgbox): ?>
                        <?php
                        $class_item = 'elementor-repeater-item-' . $imgbox['_id'];
                        $tab_title_setting_key = $this->get_repeater_setting_key('item', 'items', $index);
                        $this->add_render_attribute($tab_title_setting_key, ['class' => ['imgbox-wrapper', $class_item],]); ?>

                        <div <?php $this->print_render_attribute_string('item'); // WPCS: XSS ok. ?>>
                            <div <?php $this->print_render_attribute_string($tab_title_setting_key); ?>>
                                <?php $this->render_image($settings, $imgbox); ?>
                                <div class="imgbox-content">
                                    <?php $imgbox_title = $imgbox['imgbox_title'];
                                    if (!empty($imgbox['imgbox_link']['url'])) {
                                        $imgbox_title = '<a href="' . esc_url($imgbox['imgbox_link']['url']) . '">' . esc_html($imgbox_title) . '</a>';
                                    }
                                    printf('<div class="imgbox-title">%s</div>', $imgbox_title);
                                    ?>
                                    <?php if ($imgbox['imgbox_des']) { ?>
                                        <div class="imgbox-description"><?php echo sprintf('%s', $imgbox['imgbox_des']); ?></div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php $this->render_swiper_pagination_navigation();?>
            <?php
        }
    }

    private function render_image($settings, $imgbox) {
        if (!empty($imgbox['imgbox_image']['url'])) :
            ?>
            <div class="imgbox-image">
                <?php
                $imgbox['imgbox_image_size']             = $settings['imgbox_image_size'];
                $imgbox['imgbox_image_custom_dimension'] = $settings['imgbox_image_custom_dimension'];
                echo Group_Control_Image_Size::get_attachment_image_html($imgbox, 'imgbox_image');
                ?>
            </div>
        <?php
        endif;
    }
}

$widgets_manager->register(new Lebagol_Elementor_ImgBoxs());

