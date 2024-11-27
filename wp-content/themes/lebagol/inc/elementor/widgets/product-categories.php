<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
if (!lebagol_is_woocommerce_activated()) {
    return;
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;

/**
 * Elementor Lebagol_Elementor_Products_Categories
 * @since 1.0.0
 */
class Lebagol_Elementor_Products_Categories extends Lebagol_Base_Widgets_Swiper {

    public function get_categories() {
        return array('lebagol-addons');
    }

    /**
     * Get widget name.
     *
     * Retrieve tabs widget name.
     *
     * @return string Widget name.
     * @since  1.0.0
     * @access public
     *
     */
    public function get_name() {
        return 'lebagol-product-categories';
    }

    /**
     * Get widget title.
     *
     * Retrieve tabs widget title.
     *
     * @return string Widget title.
     * @since  1.0.0
     * @access public
     *
     */
    public function get_title() {
        return esc_html__('Product Categories', 'lebagol');
    }

    /**
     * Get widget icon.
     *
     * Retrieve tabs widget icon.
     *
     * @return string Widget icon.
     * @since  1.0.0
     * @access public
     *
     */
    public function get_icon() {
        return 'eicon-tabs';
    }

    public function get_script_depends() {
        return ['lebagol-elementor-product-categories', 'lebagol-elementor-swiper'];
    }

    public function on_export($element) {
        unset($element['settings']['category_image']['id']);

        return $element;
    }

    protected function register_controls() {

        //Section Query
        $this->start_controls_section(
            'section_setting',
            [
                'label' => esc_html__('Categories', 'lebagol'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'categories',
            [
                'label'       => esc_html__('Categories', 'lebagol'),
                'type'        => Controls_Manager::SELECT2,
                'label_block' => true,
                'options'     => $this->get_product_categories(),
                'multiple'    => false,
            ]
        );

        $repeater->add_control(
            'categories_name',
            [
                'label' => esc_html__('Alternate Name', 'lebagol'),
                'type'  => Controls_Manager::TEXT,
            ]
        );
        $repeater->add_control(
            'categories_subtitle',
            [
                'label' => esc_html__('Subtitle', 'lebagol'),
                'type'  => Controls_Manager::TEXT,
            ]
        );

        $repeater->add_control(
            'categories_description',
            [
                'label' => esc_html__('Description', 'lebagol'),
                'type'  => Controls_Manager::TEXTAREA,
                'label_block' => true,
                'rows'        => '5',
            ]
        );
        $repeater->add_control(
            'category_image',
            [
                'label'      => esc_html__('Choose Image', 'lebagol'),
                'default'    => [
                    'url' => Elementor\Utils::get_placeholder_image_src(),
                ],
                'type'       => Controls_Manager::MEDIA,
                'show_label' => false,
            ]

        );
        $repeater->add_control(
            'category_color_item',
            [
                'label' => __('Background Color', 'lebagol'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .layout-1 {{CURRENT_ITEM}}.product-cat' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .layout-3 {{CURRENT_ITEM}} .category-product-img a:before' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .layout-4 {{CURRENT_ITEM}}.product-cat:before' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'categories_list',
            [
                'label'       => esc_html__('Items', 'lebagol'),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'title_field' => '{{{ categories }}}',
            ]
        );

        $this->add_group_control(
            Elementor\Group_Control_Image_Size::get_type(),
            [
                'name'      => 'image',
                'default'   => 'full',
                'separator' => 'none',
            ]
        );
        $this->add_responsive_control(
            'product_cate_alignment',
            [
                'label'     => esc_html__('Alignment', 'lebagol'),
                'type'      => Controls_Manager::CHOOSE,
                'options'   => [
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
                'default'   => 'center',
                'selectors' => [
                    '{{WRAPPER}} .elementor-categories-item' => 'text-align: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'product_cate_layout',
            [
                'label'   => esc_html__('Layout', 'lebagol'),
                'type'    => Controls_Manager::SELECT,
                'default' => '1',
                'options' => [
                    '1' => esc_html__('Layout 1', 'lebagol'),
                    '2' => esc_html__('Layout 2', 'lebagol'),
                    '3' => esc_html__('Layout 3', 'lebagol'),
                    '4' => esc_html__('Layout 4', 'lebagol'),
                    '5' => esc_html__('Layout 5', 'lebagol'),
                ]
            ]
        );

        $this->add_responsive_control(
            'column',
            [
                'label'     => esc_html__('Columns', 'lebagol'),
                'type'      => \Elementor\Controls_Manager::SELECT,
                'default'   => 4,
                'options'   => [1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5, 6 => 6, 7 => 7, 8 => 8, 9 => 9],
                'selectors' => [
                    '{{WRAPPER}} .d-grid' => 'grid-template-columns: repeat({{VALUE}}, 1fr)',
                ],
                'condition' => ['enable_carousel!' => 'yes']
            ]
        );
        $this->add_responsive_control(
            'product_gutter',
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

        $this->add_control(
            'enable_carousel',
            [
                'label' => esc_html__('Enable Carousel', 'lebagol'),
                'type'  => Controls_Manager::SWITCHER,
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'style_wrapper',
            [
                'label' => esc_html__('Style', 'lebagol'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label'    => esc_html__('Title Typography', 'lebagol'),
                'name'     => 'title_typography',
                'selector' => '{{WRAPPER}} .cat-title',
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
            'title_color',
            [
                'label'     => esc_html__('Color', 'lebagol'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .product-cat .cat-title a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'wrap_background',
            [
                'label'     => esc_html__('Wrap Background Color', 'lebagol'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .product-cat' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'box_shadow',
                'selector' => '{{WRAPPER}} .product-cat',
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
            'wrap_background_hover',
            [
                'label'     => esc_html__('Wrap Background Color', 'lebagol'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .product-cat:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'title_color_hover',
            [
                'label'     => esc_html__('Color', 'lebagol'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .product-cat .cat-title a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'box_shadow_hover',
                'selector' => '{{WRAPPER}} .product-cat:hover',
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_responsive_control(
            'wrapper_radius',
            [
                'label'      => esc_html__('Border Radius', 'lebagol'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .product-cat' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'wrapper_padding',
            [
                'label'      => esc_html__('Padding', 'lebagol'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .product-cat' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'wrapper_margin',
            [
                'label'      => esc_html__('Margin', 'lebagol'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .product-cat' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Image style
        $this->start_controls_section(
            'section_style_image',
            [
                'label' => esc_html__('Image', 'lebagol'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'image_border_radius',
            [
                'label'      => esc_html__('Border Radius', 'lebagol'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .category-product-img img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );


        $this->add_responsive_control(
            'image_padding',
            [
                'label'      => esc_html__('Padding', 'lebagol'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .category-product-img' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .category-product-img' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Subtitle style
        $this->start_controls_section(
            'subtitle_style',
            [
                'label' => esc_html__('Sub Title', 'lebagol'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label'    => esc_html__('Sub Title Typography', 'lebagol'),
                'name'     => 'subtitle_typography',
                'selector' => '{{WRAPPER}} .cat-subtitle',
            ]
        );
        $this->add_control(
            'subtitle_color',
            [
                'label'     => esc_html__('Color', 'lebagol'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cat-subtitle' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'subtitle_margin',
            [
                'label'      => esc_html__('Margin', 'lebagol'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .cat-subtitle' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Carousel options
        $this->add_control_carousel(['enable_carousel' => 'yes']);
    }

    protected function get_product_categories() {
        $categories = get_terms(array(
                'taxonomy'   => 'product_cat',
                'hide_empty' => false,
            )
        );
        $results    = array();
        if (!is_wp_error($categories)) {
            foreach ($categories as $category) {
                $results[$category->slug] = $category->name;
            }
        }
        return $results;
    }

    /**
     * Render tabs widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since  1.0.0
     * @access protected
     */
    protected function render() {
        $settings = $this->get_settings_for_display();
        if (!empty($settings['categories_list']) && is_array($settings['categories_list'])) {
            $this->add_render_attribute('wrapper', 'class', 'elementor-categories-item-wrapper');
            $this->add_render_attribute('row', 'class', 'layout-' . esc_attr($settings['product_cate_layout']));
            $this->add_render_attribute('wrapper', 'class', 'alignment-' . esc_attr($settings['product_cate_alignment']));
            $this->get_data_elementor_columns();
            $this->add_render_attribute('item', 'class', 'elementor-categories-item');

            ?>
            <div <?php $this->print_render_attribute_string('wrapper'); // WPCS: XSS ok. ?>>
                <div <?php $this->print_render_attribute_string('row'); // WPCS: XSS ok. ?>>
                    <?php foreach ($settings['categories_list'] as $index => $item) { ?>
                        <?php
                        $class_item = 'elementor-repeater-item-' . $item['_id'];
                        $tab_title_setting_key = $this->get_repeater_setting_key('item', 'items', $index);
                        $this->add_render_attribute($tab_title_setting_key, ['class' => ['product-cat', $class_item],]); ?>

                        <div <?php $this->print_render_attribute_string('item'); // WPCS: XSS ok. ?>>
                            <?php if (empty($item['categories'])) {
                                echo esc_html__('Choose Category', 'lebagol');
                                return;
                            }
                            $category = get_term_by('slug', $item['categories'], 'product_cat');
                            if ($category && !is_wp_error($category)) {
                                $query = new WP_Query(array(
                                    'tax_query' => array(
                                        array(
                                            'taxonomy'         => 'product_cat',
                                            'field'            => 'id',
                                            'terms'            => $category->term_id,
                                            'include_children' => true,
                                        ),
                                    ),
                                    'nopaging'  => true,
                                    'fields'    => 'ids',
                                ));

                                if (!empty($item['category_image']['id'])) {
                                    $image = Group_Control_Image_Size::get_attachment_image_src($item['category_image']['id'], 'image', $settings);
                                } else {
                                    $thumbnail_id = get_term_meta($category->term_id, 'thumbnail_id', true);
                                    if (!empty($thumbnail_id)) {
                                        $image = wp_get_attachment_url($thumbnail_id);
                                    } else {
                                        $image = wc_placeholder_img_src();
                                    }
                                } ?>
                                <div <?php $this->print_render_attribute_string($tab_title_setting_key); ?>>
                                       <div class="category-product-img">
                                        <a class="product-img" href="<?php echo esc_url(get_term_link($category)); ?>" title="<?php echo esc_attr($category->name); ?>"><img src="<?php echo esc_url_raw($image); ?>" alt="<?php echo esc_attr($category->name); ?>"></a>
                                        <?php if ($settings['product_cate_layout'] == '2') { ?>
                                            <div class="cat-button">
                                                <a href="<?php echo esc_url(get_term_link($category)); ?>" title="<?php echo esc_attr($category->name); ?>">
                                                    <i aria-hidden="true" class="lebagol-icon-arrow-small-right"></i>
                                                </a>
                                            </div>
                                        <?php } ?>

                                    </div>
                                    <div class="product-cat-content">
                                        <div class="cat-subtitle">
                                            <?php echo esc_html($item['categories_subtitle']); ?>
                                        </div>
                                        <div class="cat-title">
                                            <a href="<?php echo esc_url(get_term_link($category)); ?>" title="<?php echo esc_attr($category->name); ?>">
                                                <span><?php echo empty($item['categories_name']) ? esc_html($category->name) : sprintf('%s', $item['categories_name']); ?></span>
                                            </a>
                                        </div>
                                        <?php if ($settings['product_cate_layout'] == '3') { ?>
                                            <div class="cat-discription">
                                                <?php echo esc_html($item['categories_description']); ?>
                                            </div>
                                        <?php } ?>
                                        <div class="cat-total">
                                            <span class="count"><?php echo esc_html($query->post_count); ?></span>
                                            <span class="text"><?php echo esc_html__('products', 'lebagol'); ?></span>
                                        </div>
                                        <?php if ($settings['product_cate_layout'] != '2') { ?>
                                        <div class="cat-button">
                                            <a class="elementor-button" href="<?php echo esc_url(get_term_link($category)); ?>" title="<?php echo esc_attr($category->name); ?>">
                                                <span class="elementor-button-content-wrapper">
                                                    <i aria-hidden="true" class="elementor-button-icon left lebagol-icon-arrow-small-right"></i>
                                                    <span class="elementor-button-text"><?php echo esc_html__('Shop Now', 'lebagol'); ?></span>
                                                    <i aria-hidden="true" class="elementor-button-icon right lebagol-icon-arrow-small-right"></i>
                                                </span>
                                            </a>
                                        </div>
                                        <?php } ?>
                                    </div>

                                </div>
                                <?php
                                wp_reset_postdata();
                            } ?>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <?php
            $this->render_swiper_pagination_navigation(); ?>
            <?php
        }
    }

}

$widgets_manager->register(new Lebagol_Elementor_Products_Categories());

