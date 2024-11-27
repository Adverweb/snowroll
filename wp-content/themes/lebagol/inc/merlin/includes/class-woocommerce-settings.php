<?php
/**
 * Lebagol WooCommerce Settings Class
 *
 * @package  lebagol
 * @since    2.4.3
 */

if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('Lebagol_WooCommerce_Settings')) :

    /**
     * The Lebagol WooCommerce Settings Class
     */
    class Lebagol_WooCommerce_Settings {

        public function __construct() {
            add_action('add_meta_boxes', [$this, 'lebagol_create_product_meta_box']);
            add_action('woocommerce_process_product_meta', [$this, 'lebagol_proccess_product_meta_box'], 50, 2);
        }

        public function lebagol_content_meta_box_technical_specs($post) {
            $product = wc_get_product($post->ID);
            $content = $product->get_meta('_technical_specs');
            echo '<div class="product_technical_specs">';
            wp_editor(wp_specialchars_decode($content, ENT_QUOTES), '_technical_specs', ['textarea_rows' => 10]);
            echo '</div>';
        }

        public function lebagol_content_meta_box_extra_info($post) {
            $product = wc_get_product($post->ID);
            $content = $product->get_meta('_extra_info');
            echo '<div class="product_extra_info">';
            wp_editor(wp_specialchars_decode($content, ENT_QUOTES), '_extra_info', ['textarea_rows' => 10]);
            echo '</div>';
        }


        public function lebagol_create_product_meta_box() {
            add_meta_box(
                'custom_product_meta_box',
                esc_html__('Technical specs', 'lebagol'),
                [$this,'lebagol_content_meta_box_technical_specs'],
                'product',
                'normal',
                'default'
            );

            add_meta_box(
                'custom_product_meta_box_2',
                esc_html__('Extra info', 'lebagol'),
                [$this,'lebagol_content_meta_box_extra_info'],
                'product',
                'normal',
                'default'
            );
        }

        public function lebagol_proccess_product_meta_box($post_id, $post) {
            if (isset($_POST['_technical_specs'])) {
                update_post_meta($post_id, '_technical_specs', $_POST['_technical_specs']);
            }
            if (isset($_POST['_extra_info'])) {
                update_post_meta($post_id, '_extra_info', esc_attr($_POST['_extra_info']));
            }
        }

    }

    return new Lebagol_WooCommerce_Settings();

endif;
