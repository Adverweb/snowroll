<?php
if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('Lebagol_WooCommerce_Product_Cat')) :

    /**
     * The Lebagol WooCommerce Integration class
     */
    class Lebagol_WooCommerce_Product_Cat {

        /**
         * Setup class.
         *
         * @since 1.0
         */
        public function __construct() {
            add_action('product_cat_add_form_fields', array($this, 'custom_taxonomy_add_meta_field'), 10, 2);
            add_action('product_cat_edit_form_fields', array($this, 'custom_taxonomy_edit_meta_field'), 10, 2);
            add_action('edited_product_cat', array($this, 'save_taxonomy_custom_meta'), 10, 2);
            add_action('create_product_cat', array($this, 'save_taxonomy_custom_meta'), 10, 2);
            add_filter('list_product_cats', array($this, 'filter_list_product_cats'), 10, 2);
        }

        public function filter_list_product_cats($name, $cat) {

            $term_meta = get_option("taxonomy_" . $cat->term_id);
            if (isset($term_meta['meta_icon']) && $term_meta['meta_icon']) {
                $name = '<i class="' . $term_meta['meta_icon'] . '"></i><span>' . $name . '</span>';
            }
            return $name;
        }

        public function custom_taxonomy_add_meta_field() {
            ?>
            <div class="form-field">
                <label for="term_meta[meta_icon]"><?php echo esc_html__('Icon Class', 'lebagol'); ?></label>
                <input type="text" name="term_meta[meta_icon]" id="term_meta[meta_icon]">
            </div>
            <?php
        }

        public function custom_taxonomy_edit_meta_field($term) {

            //getting term ID
            $term_id = $term->term_id;

            // retrieve the existing value(s) for this meta field. This returns an array
            $term_meta = get_option("taxonomy_" . $term_id);
            ?>
            <tr class="form-field">
                <th scope="row" valign="top">
                    <label for="term_meta[meta_icon]"><?php echo esc_html__('Icon Class', 'lebagol'); ?></label></th>
                <td>
                    <input type="text" name="term_meta[meta_icon]" id="term_meta[meta_icon]" value="<?php echo esc_attr($term_meta['meta_icon']) ? esc_attr($term_meta['meta_icon']) : ''; ?>">
                </td>
            </tr>

            <?php
        }

        public function save_taxonomy_custom_meta($term_id) {
            if (isset($_POST['term_meta'])) {
                $term_meta = get_option("taxonomy_" . $term_id);
                $cat_keys  = array_keys($_POST['term_meta']);
                foreach ($cat_keys as $key) {
                    if (isset($_POST['term_meta'][$key])) {
                        $term_meta[$key] = $_POST['term_meta'][$key];
                    }
                }
                // Save the option array.
                update_option("taxonomy_" . $term_id, $term_meta);
            }
        }

    }

endif;

return new Lebagol_WooCommerce_Product_Cat();
