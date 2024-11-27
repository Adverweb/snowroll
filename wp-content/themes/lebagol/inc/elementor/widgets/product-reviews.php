<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!lebagol_is_woocommerce_activated()) {
    return;
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

class Lebagol_Elementor_Product_Review extends Lebagol_Base_Widgets_Swiper {

    public function get_name() {
        return 'lebagol-product-review';
    }

    public function get_title() {
        return esc_html__('Lebagol Product Review', 'lebagol');
    }

    public function get_icon() {
        return 'eicon-review';
    }

    public function get_script_depends() {
        return ['lebagol-elementor-product-reviews'];
    }

    public function get_categories() {
        return array('lebagol-addons');
    }

    protected function register_controls() {


        $this->start_controls_section('reviews_content',
            [
                'label' => esc_html__('Reviews', 'lebagol'),
            ]
        );


        $repeater = new \Elementor\Repeater();

        $repeater->add_control('review_id', [
            'label'       => esc_html__('Products Review', 'lebagol'),
            'type'        => 'products_review',
            'label_block' => true,
            'multiple'    => false,
        ]);

        $this->add_control('reviews',
            [
                'label'  => esc_html__('Reviews', 'lebagol'),
                'type'   => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
            ]
        );

        $this->add_responsive_control(
            'column',
            [
                'label'     => esc_html__('Columns', 'lebagol'),
                'type'      => \Elementor\Controls_Manager::SELECT,
                'default'   => 3,
                'options'   => [1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5, 6 => 6],
                'selectors' => [
                    '{{WRAPPER}} .d-grid' => 'grid-template-columns: repeat({{VALUE}}, 1fr)',
                ],
                'condition' => ['enable_carousel!' => 'yes']
            ]
        );

        $this->add_responsive_control(
            'item_spacing',
            [
                'label'      => esc_html__('Spacing', 'lebagol'),
                'type'       => Controls_Manager::SLIDER,
                'range'      => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default'    => [
                    'size' => 30
                ],
                'size_units' => ['px', 'em'],
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
            'section_style',
            [
                'label' => esc_html__('Wrapper', 'lebagol'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label'    => esc_html__('Title Typography', 'lebagol'),
                'name'     => 'title_typography',
                'selector' => '{{WRAPPER}} .review-content',
            ]
        );

        $this->add_responsive_control(
            'wrapper_margin',
            [
                'label'      => esc_html__('Margin', 'lebagol'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .review-item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Carousel options
        $this->add_control_carousel(['enable_carousel' => 'yes']);
    }


    protected function render() {
        $settings = $this->get_settings_for_display();

        $this->add_render_attribute('wrapper', 'class', 'elementor-product-review-wrapper');
        $this->get_data_elementor_columns();

        ?>
        <div <?php $this->print_render_attribute_string('wrapper'); ?>>
            <div <?php $this->print_render_attribute_string('row'); ?>>
                <?php
                foreach ($settings['reviews'] as $index => $item) {
                    $review_id = $item['review_id'];
                    $review    = get_comment($review_id);
                    if ($review && $review->comment_type === 'review') {
                        ?>
                        <div <?php $this->print_render_attribute_string('item'); ?>>
                            <div class="review-item">
                                <?php
                                $product_id = $review->comment_post_ID;
                                $product    = wc_get_product($product_id);
                                if ($product) {
                                    ?>
                                    <div class="product-item">
                                        <?php
                                        echo '<div class="product-thumbnail">' . $product->get_image('thumbnail') . '</div>';
                                        echo '<div class="product-caption">';
                                        $rating = intval(get_comment_meta($review_id, 'rating', true));
                                        if ($rating && wc_review_ratings_enabled()) {
                                            echo wc_get_rating_html($rating);
                                        }
                                        echo '<h4 class="woocommerce-loop-product__title"><a href="' . $product->get_permalink() . '">' . esc_html($product->get_name()) . '</a></h3>';

                                        echo '</div>';
                                        ?>
                                    </div>
                                    <?php
                                }

                                echo '<div class="review-content"><p>' . esc_html($review->comment_content) . '</p></div>';
                                ?>

                                <div class="review-footer">
                                    <div class="review-meta">
                                        <?php
                                        echo '<span class="woocommerce-review__author">' . esc_html($review->comment_author) . '</span>';
                                        $review_date = date_i18n(get_option('date_format'), strtotime($review->comment_date));
                                        echo '<span class="woocommerce-review__published-date">' . esc_html($review_date) . '</span>';
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                }
                ?>
            </div>
            <?php $this->render_swiper_pagination_navigation(); ?>
        </div>
        <?php
    }


}

$widgets_manager->register(new Lebagol_Elementor_Product_Review());