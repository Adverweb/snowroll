<?php
/**
 * Lebagol WooCommerce Settings Class
 *
 * @package  lebagol
 * @since    2.4.3
 */

use Elementor\Plugin;

if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('Lebagol_WooCommerce_Size_Chart')) :

    /**
     * The Lebagol WooCommerce Settings Class
     */
    class Lebagol_WooCommerce_Size_Chart {

        public function __construct() {
            if (lebagol_is_elementor_activated()) {
                add_filter('woocommerce_product_data_tabs', array($this, 'settings_product_tabs'));
                add_filter('woocommerce_product_data_panels', array(
                    $this,
                    'settings_options_product_tab_content'
                ),999);
                add_action('woocommerce_process_product_meta', array($this, 'save_settings_option_fields'));

                add_action('lebagol_woocommerce_single_product_loop_action', array($this, 'render_sizechart_button'), 15);
                add_action('wp_enqueue_scripts', [$this, 'add_css']);
            }
        }

        public function settings_product_tabs($tabs) {

            $tabs['sizechart'] = array(
                'label'    => esc_html__('Lebagol settings', 'lebagol'),
                'target'   => 'lebagol_options',
                'class'    => array(),
                'priority' => 80,
            );

            return $tabs;

        }

        private function check_chart($slug = '') {

            if ($slug) {

                $queried_post = get_page_by_path($slug, OBJECT, 'elementor_library');

                if (isset($queried_post->ID)) {
                    return $queried_post->ID;
                }
            }

            return false;
        }

        public function settings_options_product_tab_content() {

            global $post;

            ?>
            <div id='lebagol_options' class='panel woocommerce_options_panel'>
                <div class='options_group'><?php

                    $value = get_post_meta($post->ID, '_sizechart_select', true);
                    if (empty($value)) {
                        $value = '';
                    }
                    $options[''] = esc_html__('Select size chart', 'lebagol');

                    $args = array(
                        'post_type'      => 'elementor_library',
                        'posts_per_page' => -1,
                        'orderby'        => 'title',
                        's'              => 'SizeChart ',
                        'order'          => 'ASC',
                    );

                    $query1 = new WP_Query($args);
                    while ($query1->have_posts()) {
                        $query1->the_post();
                        $options[$post->post_name] = $post->post_title;
                    }

                    wp_reset_postdata();

                    woocommerce_wp_select(array(
                        'id'      => '_sizechart_select',
                        'label'   => esc_html__('Size chart', 'lebagol'),
                        'options' => $options,
                        'value'   => $value,
                    ));

                    ?>
                    <p class="form-field form-field-wide wc-customer-custom"><?php echo esc_html__('Size chart will take templates name prefix is "SizeChart"','lebagol');?></p>
                </div>

            </div>
            <?php

        }

        public function save_settings_option_fields($post_id) {
            if (isset($_POST['_sizechart_select'])) {
                update_post_meta($post_id, '_sizechart_select', esc_attr($_POST['_sizechart_select']));
            }
        }

        public function add_css() {
            global $post;
            if (!is_product()) {
                return;
            }
            $slug     = get_post_meta($post->ID, '_sizechart_select', true);
            $chart_id = $this->check_chart($slug);
            if (!empty($slug) && $chart_id) {
                Elementor\Core\Files\CSS\Post::create($chart_id)->enqueue();
            }
        }

        public function render_sizechart_button() {
            global $post;
            if (!is_product()) {
                return;
            }
            $slug     = get_post_meta($post->ID, '_sizechart_select', true);
            $chart_id = $this->check_chart($slug);
            if (!empty($slug) && $chart_id) {
                echo '<div class="sizechart-wrap">';
                echo '<a href="#product-sizechart-popup" class="product-sizechart-button" data-effect="mfp-move-horizontal"><i class="lebagol-icon-ruler-triangle"></i><span>' . esc_html__('Size Guide', 'lebagol') . '</span></a>';
                echo '<div id="product-sizechart-popup" class="product-sizechart-popup mfp-hide mfp-with-anim"><div class="popup-content">' . Elementor\Plugin::instance()->frontend->get_builder_content($chart_id) . '</div></div>';
                echo '</div>';
            }
        }
    }

    return new Lebagol_WooCommerce_Size_Chart();

endif;
