<?php

/**
 * Producta_Control control.
 *
 */
class Products_Review_Control extends \Elementor\Control_Select2 {

    public function get_type() {
        return 'products_review';
    }

    public function enqueue() {

        wp_register_script('elementor-products-review', get_theme_file_uri('/inc/elementor/product_review/select2.js'), ['jquery'], LEBAGOL_VERSION, true);
        wp_enqueue_script('elementor-products-review');
    }
}
