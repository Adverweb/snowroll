<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!lebagol_is_woocommerce_activated()) {
    return;
}

use Elementor\Controls_Manager;
use Elementor\Plugin;

/**
 * Elementor tabs widget.
 *
 * Elementor widget that displays vertical or horizontal tabs with different
 * pieces of content.
 *
 * @since 1.0.0
 */
class Lebagol_Elementor_Widget_Product_Navigation extends Elementor\Widget_Base {


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
        return 'lebagol-product-navigation';
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
        return esc_html__('Single Product Navigation', 'lebagol');
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

    /**
     * Register tabs widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since  1.0.0
     * @access protected
     */
    protected function register_controls() {

        //Section Query
        $this->start_controls_section(
            'section_setting',
            [
                'label' => esc_html__('Settings', 'lebagol'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_responsive_control(
            'alignment',
            [
                'label'     => esc_html__('Alignment', 'lebagol'),
                'type'      => Controls_Manager::CHOOSE,
                'options'   => [
                    'flex-start' => [
                        'title' => esc_html__('Left', 'lebagol'),
                        'icon'  => 'eicon-text-align-left',
                    ],
                    'center'     => [
                        'title' => esc_html__('Center', 'lebagol'),
                        'icon'  => 'eicon-text-align-center',
                    ],
                    'flex-end'   => [
                        'title' => esc_html__('Right', 'lebagol'),
                        'icon'  => 'eicon-text-align-right',
                    ],
                ],
                'default'   => 'flex-end',
                'selectors' => [
                    '{{WRAPPER}} .lebagol-product-pagination-wrap' => 'justify-content: {{VALUE}}',
                ],
            ]
        );


        $this->end_controls_section();

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
        $settings       = $this->get_settings_for_display();
        $in_same_term   = apply_filters('lebagol_single_product_pagination_same_category', true);
        $excluded_terms = apply_filters('lebagol_single_product_pagination_excluded_terms', '');
        $taxonomy       = apply_filters('lebagol_single_product_pagination_taxonomy', 'product_cat');

        $previous_product = lebagol_get_previous_product($in_same_term, $excluded_terms, $taxonomy);
        $next_product     = lebagol_get_next_product($in_same_term, $excluded_terms, $taxonomy);

        if (Plugin::$instance->editor->is_edit_mode()) {
            ?>
            <div class="lebagol-product-pagination-wrap">
                <nav class="lebagol-product-pagination" aria-label="<?php esc_attr_e('More products', 'lebagol'); ?>">
                    <a href="#" rel="prev"><i class="lebagol-icon-angle-small-left"></i></a>
                    <a href="#" class="nav-back-shop"><i class="lebagol-icon-apps"></i></a>
                    <a href="#" rel="next"><i class="lebagol-icon-angle-small-right"></i></a>
                </nav>
            </div>
            <?php
        } else {

            if ((!$previous_product && !$next_product) || !is_product()) {
                return;
            }

            ?>
            <div class="lebagol-product-pagination-wrap">
                <nav class="lebagol-product-pagination" aria-label="<?php esc_attr_e('More products', 'lebagol'); ?>">
                    <?php if ($previous_product) : ?>
                        <a href="<?php echo esc_url($previous_product->get_permalink()); ?>" rel="prev">
                            <i class="lebagol-icon-angle-small-left"></i>
                            <div class="product-item">
                                <?php echo sprintf('%s', $previous_product->get_image()); ?>
                                <div class="lebagol-product-pagination-content">
                                    <h4 class="lebagol-product-pagination__title"><?php echo sprintf('%s', $previous_product->get_name()); ?></h4>
                                    <?php if ($price_html = $previous_product->get_price_html()) :
                                        printf('<span class="price">%s</span>', $price_html);
                                    endif; ?>
                                </div>
                            </div>
                        </a>
                    <?php endif; ?>
                    <a href="<?php echo esc_url(apply_filters('lebagol_single_product_back_btn_url', get_permalink(wc_get_page_id('shop')))); ?>" class="nav-back-shop"><i class="lebagol-icon-apps"></i></a>
                    <?php if ($next_product) : ?>
                        <a href="<?php echo esc_url($next_product->get_permalink()); ?>" rel="next">
                            <i class="lebagol-icon-angle-small-right"></i>
                            <div class="product-item">
                                <?php echo sprintf('%s', $next_product->get_image()); ?>
                                <div class="lebagol-product-pagination-content">
                                    <h4 class="lebagol-product-pagination__title"><?php echo sprintf('%s', $next_product->get_name()); ?></h4>
                                    <?php if ($price_html = $next_product->get_price_html()) :
                                        printf('<span class="price">%s</span>', $price_html);
                                    endif; ?>
                                </div>
                            </div>
                        </a>
                    <?php endif; ?>
                </nav>
            </div>
        <?php
        }
    }
}

$widgets_manager->register(new Lebagol_Elementor_Widget_Product_Navigation());
