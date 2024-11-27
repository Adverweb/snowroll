<?php

if (!defined('ABSPATH')) {
    exit;
}

class Lebagol_Woocommerce_Product_Labels {
    private        $labels    = [];
    private static $_instance = null;

    public static function instance() {
        if (!isset(self::$_instance)) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    /**
     * Constructor
     */
    private function __construct() {
        add_action('init', [$this, 'product_lables_register']);
        add_action('init', [$this, 'init']);
        add_action('save_post_lebagol_prd_labels', [$this, 'label_save_fields']);
        add_action('admin_menu', [$this, 'register_admin_menu'], 50);
        add_action('add_meta_boxes', [$this, 'lables_meta_box']);

        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_script']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_script']);

        add_action('wp_ajax_lebagol_search_term', [$this, 'ajax_search_term']);
        add_action('wp_ajax_lebagol_add_conditional', [$this, 'ajax_add_conditional']);

        add_filter('woocommerce_product_data_tabs', [$this, 'product_data_tabs']);
        add_action('woocommerce_product_data_panels', [$this, 'product_data_panels']);
        add_action('woocommerce_process_product_meta', [$this, 'product_save_fields']);

        add_action('wp_ajax_lebagol_search_labels', [$this, 'ajax_search_labels']);

    }

    function init() {
        $args = [
            'fields'         => 'ids',
            'post_type'      => 'lebagol_prd_labels',
            'post_status'    => 'publish',
            'posts_per_page' => -1,
        ];

        $posts = get_posts($args);

        if (!empty($posts)) {
            foreach ($posts as $post_id) {
                $this->labels['b' . $post_id] = $this->label_data($post_id);
            }
        }
    }

    private function label_data($id) {
        $options = [
            'text',
            'background_color',
            'text_color',
            'apply',
            'terms',
            'conditionals',
            'order',
            'show_detail'
        ];


        $data = [
            'id'    => $id,
            'title' => get_the_title($id),
        ];

        foreach ($options as $option) {
            $data[$option] = get_post_meta($id, $option, true);
        }

        return $data;
    }

    public function enqueue_admin_script() {
        global $typenow;
        if ('lebagol_prd_labels' == $typenow || 'product' == $typenow) {
            wp_enqueue_style('lebagol-admin-product-labels', get_template_directory_uri() . '/inc/merlin/includes/product-labels/assets/admin.css', '', LEBAGOL_VERSION);
            wp_enqueue_script('lebagol-admin-product-labels', get_template_directory_uri() . '/inc/merlin/includes/product-labels/assets/admin.js', [
                'jquery',
                'jquery-ui-dialog',
                'wp-color-picker',
                'wp-color-picker-alpha',
                'wc-enhanced-select',
                'selectWoo',
            ], LEBAGOL_VERSION, true);
            wp_localize_script('lebagol-admin-product-labels', 'lebagol_vars', [
                    'nonce' => wp_create_nonce('lebagol-security')
                ]
            );

        }
    }

    public function enqueue_script() {
        wp_add_inline_style('lebagol-style', $this->inline_css());
    }

    public function product_lables_register() {
        $labels = [
            'name'               => esc_html__('Labels', 'lebagol'),
            'singular_name'      => esc_html__('Label', 'lebagol'),
            'menu_name'          => esc_html__('Labels', 'lebagol'),
            'name_admin_bar'     => esc_html__('Label', 'lebagol'),
            'add_new'            => esc_html__('Add New', 'lebagol'),
            'add_new_item'       => esc_html__('Add New Label', 'lebagol'),
            'new_item'           => esc_html__('New Label', 'lebagol'),
            'edit_item'          => esc_html__('Edit Label', 'lebagol'),
            'view_item'          => esc_html__('View Label', 'lebagol'),
            'all_items'          => esc_html__('All Label', 'lebagol'),
            'search_items'       => esc_html__('Search Labels', 'lebagol'),
            'parent_item_colon'  => esc_html__('Parent Labels:', 'lebagol'),
            'not_found'          => esc_html__('No Labels found.', 'lebagol'),
            'not_found_in_trash' => esc_html__('No Labels found in Trash.', 'lebagol'),
        ];

        $args = [
            'labels'              => $labels,
            'public'              => false,
            'show_ui'             => true,
            'show_in_menu'        => false,
            'show_in_nav_menus'   => false,
            'exclude_from_search' => true,
            'capability_type'     => 'post',
            'hierarchical'        => false,
            'has_archive'         => false,
            'supports'            => ['title'],
        ];

        register_post_type('lebagol_prd_labels', $args);
    }


    /**
     * Register the admin menu for Header Footer & Blocks builder.
     *
     * @since  1.0.0
     * @since  1.0.1
     *         Moved the menu under Appearance -> Header Footer & Blocks Builder
     */
    public function register_admin_menu() {
        add_submenu_page(
            'edit.php?post_type=product',
            esc_html__('Labels', 'lebagol'),
            esc_html__('Labels', 'lebagol'),
            'edit_pages',
            'edit.php?post_type=lebagol_prd_labels'
        );
    }

    public function lables_meta_box() {
        add_meta_box('lebagol_configuration', esc_html__('Configuration', 'lebagol'), [
            $this,
            'label_configuration'
        ], 'lebagol_prd_labels', 'advanced', 'low');
    }

    function label_configuration($post) {
        $post_id          = $post->ID;
        $text             = !empty(get_post_meta($post_id, 'text', true)) ? get_post_meta($post_id, 'text', true) : 'Hot';
        $background_color = !empty(get_post_meta($post_id, 'background_color', true)) ? get_post_meta($post_id, 'background_color', true) : '#81d742';
        $text_color       = !empty(get_post_meta($post_id, 'text_color', true)) ? get_post_meta($post_id, 'text_color', true) : '#ffffff';
        $order            = !empty(get_post_meta($post_id, 'order', true)) ? get_post_meta($post_id, 'order', true) : '1';
        $show_detail      = !empty(get_post_meta($post_id, 'show_detail', true)) ? 'checked' : '';
        $apply            = !empty(get_post_meta($post_id, 'apply', true)) ? get_post_meta($post_id, 'apply', true) : '';
        $terms            = !empty(get_post_meta($post_id, 'terms', true)) ? get_post_meta($post_id, 'terms', true) : [];
        $conditionals     = !empty(get_post_meta($post_id, 'conditionals', true)) ? (array)get_post_meta($post_id, 'conditionals', true) : [];

        ?>
        <table class="lebagol_configuration_table">
            <tr class="lebagol_configuration_tr">
                <td class="lebagol_configuration_th">
                    <?php esc_html_e('Apply', 'lebagol'); ?>
                </td>
                <td class="lebagol_configuration_td">
                    <select name="lebagol_apply" id="lebagol_apply">
                        <option value="" <?php selected($apply, ''); ?>><?php esc_html_e('None', 'lebagol'); ?></option>
                        <option value="combination" <?php selected($apply, 'combination'); ?>><?php esc_html_e('Combined', 'lebagol'); ?></option>
                        <option value="all" <?php selected($apply, 'all'); ?>><?php esc_html_e('All products', 'lebagol'); ?></option>
                        <option value="sale" <?php selected($apply, 'sale'); ?>><?php esc_html_e('On sale', 'lebagol'); ?></option>
                        <option value="featured" <?php selected($apply, 'featured'); ?>><?php esc_html_e('Featured', 'lebagol'); ?></option>
                        <option value="bestselling" <?php selected($apply, 'bestselling'); ?>><?php esc_html_e('Best selling', 'lebagol'); ?></option>
                        <option value="instock" <?php selected($apply, 'instock'); ?>><?php esc_html_e('In stock', 'lebagol'); ?></option>
                        <option value="outofstock" <?php selected($apply, 'outofstock'); ?>><?php esc_html_e('Out of stock', 'lebagol'); ?></option>
                        <option value="backorder" <?php selected($apply, 'backorder'); ?>><?php esc_html_e('On backorder', 'lebagol'); ?></option>
                        <?php
                        $taxonomies = get_object_taxonomies('product', 'objects');

                        foreach ($taxonomies as $taxonomy) {
                            if ($taxonomy->name == 'product_visibility' || $taxonomy->name == 'product_shipping_class') {
                                continue;
                            }
                            echo '<option value="' . esc_attr($taxonomy->name) . '" ' . ($apply === $taxonomy->name ? 'selected' : '') . '>' . esc_html($taxonomy->label) . '</option>';
                        }
                        ?>
                    </select>
                    <p class="description"><?php esc_html_e('Select which products you want to add this label automatically. If "None" is set, you can still manually choose to add this in the "Labels" tab of each individual product page.', 'lebagol'); ?></p>
                </td>
            </tr>
            <tr class="lebagol_configuration_tr" id="lebagol_configuration_combination" style="<?php echo esc_attr($apply === 'combination' ? '' : 'display:none;'); ?>">
                <td class="lebagol_configuration_th">
                    <?php esc_html_e('Combined', 'lebagol'); ?>
                </td>
                <td class="lebagol_configuration_td">
                    <div class="lebagol_conditionals">
                        <?php
                        foreach ($conditionals as $key => $value) {
                            self::conditional($key, $value);
                        }
                        ?>
                    </div>
                    <button class="lebagol_add_conditional button"><?php echo esc_html__('Add Conditions', 'lebagol'); ?></button>
                </td>
            </tr>
            <tr class="lebagol_configuration_tr" id="lebagol_configuration_terms" style="<?php echo esc_attr(!empty($apply)
                                                                                                         && !in_array($apply, [
                'all',
                'sale',
                'featured',
                'bestselling',
                'instock',
                'outofstock',
                'backorder',
                'combination'
            ]) ? '' : 'display:none;'); ?>">
                <td class="lebagol_configuration_th">
                    <span id="lebagol_configuration_terms_label"><?php esc_html_e('Terms', 'lebagol'); ?></span>
                </td>
                <td class="lebagol_configuration_td">
                    <?php
                    if (!is_array($terms)) {
                        $terms = array_map('trim', explode(',', $terms));
                    }
                    $terms = array_map('rawurldecode', $terms);
                    ?>
                    <select class="lebagol_terms" id="lebagol_terms" name="lebagol_terms[]" multiple="multiple" data-<?php echo esc_attr($apply); ?>="<?php echo esc_attr(implode(',', $terms)); ?>">
                        <?php
                        if (!empty($terms)) {
                            foreach ($terms as $t) {
                                if ($term = get_term_by('slug', $t, $apply)) {
                                    echo '<option value="' . esc_attr($t) . '" selected>' . esc_html($term->name) . '</option>';
                                }
                            }
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr class="lebagol_configuration_tr lebagol_configuration_tr_allow lebagol_configuration_tr_text">
                <td class="lebagol_configuration_th">
                    <?php esc_html_e('Text', 'lebagol'); ?>
                </td>
                <td class="lebagol_configuration_td">
                    <input type="text" value="<?php echo esc_attr(html_entity_decode($text)); ?>" name="lebagol_text" id="lebagol_text" class="regular-text"/>
                </td>
            </tr>
            <tr class="lebagol_configuration_tr lebagol_configuration_tr_allow lebagol_configuration_tr_text_color">
                <td class="lebagol_configuration_th">
                    <?php esc_html_e('Text color', 'lebagol'); ?>
                </td>
                <td class="lebagol_configuration_td">
                    <input class="color-picker" type="text" value="<?php echo esc_attr($text_color); ?>" name="lebagol_text_color" id="lebagol_text_color"/>
                </td>
            </tr>
            <tr class="lebagol_configuration_tr lebagol_configuration_tr_allow lebagol_configuration_tr_background_color">
                <td class="lebagol_configuration_th">
                    <?php esc_html_e('Background color', 'lebagol'); ?>
                </td>
                <td class="lebagol_configuration_td">
                    <input class="color-picker" type="text" value="<?php echo esc_attr($background_color); ?>" name="lebagol_background_color" id="lebagol_background_color"/>
                </td>
            </tr>
            <tr class="lebagol_configuration_tr">
                <td class="lebagol_configuration_th">
                    <?php esc_html_e('Order', 'lebagol'); ?>
                </td>
                <td class="lebagol_configuration_td">
                    <input type="number" min="1" max="500" value="<?php echo esc_attr($order); ?>" name="lebagol_order" id="lebagol_order"/>
                </td>
            </tr>
            <tr class="lebagol_configuration_tr">
                <td class="lebagol_configuration_th">
                    <?php esc_html_e('Show in Detail', 'lebagol'); ?>
                </td>
                <td class="lebagol_configuration_td">
                    <input type="checkbox" value="yes" name="lebagol_show_detail" id="lebagol_show_detail" <?php echo esc_attr($show_detail); ?> />
                    <label for="lebagol_show_detail"><?php echo esc_html__('Enable', 'lebagol'); ?></label>
                </td>
            </tr>
        </table>
        <?php
    }

    public function label_save_fields($post_id) {
        if (isset($_POST['lebagol_text'])) {
            update_post_meta($post_id, 'text', sanitize_text_field(htmlentities(wp_kses_post($_POST['lebagol_text']))));
        }

        if (isset($_POST['lebagol_background_color'])) {
            update_post_meta($post_id, 'background_color', sanitize_text_field($_POST['lebagol_background_color']));
        }

        if (isset($_POST['lebagol_text_color'])) {
            update_post_meta($post_id, 'text_color', sanitize_text_field($_POST['lebagol_text_color']));
        }

        if (isset($_POST['lebagol_apply'])) {
            update_post_meta($post_id, 'apply', sanitize_text_field($_POST['lebagol_apply']));
        }

        if (isset($_POST['lebagol_terms'])) {
            update_post_meta($post_id, 'terms', self::sanitize_array($_POST['lebagol_terms']));
        }

        if (isset($_POST['lebagol_conditionals'])) {
            update_post_meta($post_id, 'conditionals', self::sanitize_array($_POST['lebagol_conditionals']));
        }

        if (isset($_POST['lebagol_order'])) {
            update_post_meta($post_id, 'order', sanitize_text_field($_POST['lebagol_order']));
        }

        if (isset($_POST['lebagol_show_detail'])) {
            update_post_meta($post_id, 'show_detail', sanitize_text_field($_POST['lebagol_show_detail']));
        } else {
            update_post_meta($post_id, 'show_detail', '');
        }
    }

    function conditional($key = '', $conditional = []) {
        if (empty($key)) {
            $key = uniqid();
        }

        $apply   = isset($conditional['apply']) ? $conditional['apply'] : 'sale';
        $compare = isset($conditional['compare']) ? $conditional['compare'] : 'is';
        $value   = isset($conditional['value']) ? $conditional['value'] : '';
        $select  = isset($conditional['select']) ? (array)$conditional['select'] : [];
        ?>
        <div class="lebagol_conditional">
            <span class="lebagol_conditional_remove"> &times; </span>
            <select class="lebagol_conditional_apply" name="lebagol_conditionals[<?php echo esc_attr($key); ?>][apply]">
                <option value="sale" <?php selected($apply, 'sale'); ?>><?php esc_html_e('On sale', 'lebagol'); ?></option>
                <option value="featured" <?php selected($apply, 'featured'); ?>><?php esc_html_e('Featured', 'lebagol'); ?></option>
                <option value="bestselling" <?php selected($apply, 'bestselling'); ?>><?php esc_html_e('Best selling', 'lebagol'); ?></option>
                <option value="instock" <?php selected($apply, 'instock'); ?>><?php esc_html_e('In stock', 'lebagol'); ?></option>
                <option value="outofstock" <?php selected($apply, 'outofstock'); ?>><?php esc_html_e('Out of stock', 'lebagol'); ?></option>
                <option value="backorder" <?php selected($apply, 'backorder'); ?>><?php esc_html_e('On backorder', 'lebagol'); ?></option>
                <option value="price" <?php selected($apply, 'price'); ?>><?php esc_html_e('Price', 'lebagol'); ?></option>
                <option value="rating" <?php selected($apply, 'rating'); ?>><?php esc_html_e('Star rating', 'lebagol'); ?></option>
                <option value="release" <?php selected($apply, 'release'); ?>><?php esc_html_e('New release (days)', 'lebagol'); ?></option>
                <?php
                $taxonomies = get_object_taxonomies('product', 'objects');

                foreach ($taxonomies as $taxonomy) {
                    if ($taxonomy->name == 'product_visibility' || $taxonomy->name == 'product_shipping_class') {
                        continue;
                    }
                    echo '<option value="' . esc_attr($taxonomy->name) . '" ' . ($apply === $taxonomy->name ? 'selected' : '') . '>' . esc_html($taxonomy->label) . '</option>';
                }
                ?>
            </select>
            <select class="lebagol_conditional_compare" name="lebagol_conditionals[<?php echo esc_attr($key); ?>][compare]">
                <optgroup label="<?php esc_attr_e('Text', 'lebagol'); ?>" class="lebagol_conditional_compare_terms">
                    <option value="is" <?php selected($compare, 'is'); ?>><?php esc_html_e('including', 'lebagol'); ?></option>
                    <option value="is_not" <?php selected($compare, 'is_not'); ?>><?php esc_html_e('excluding', 'lebagol'); ?></option>
                </optgroup>
                <optgroup label="<?php esc_attr_e('Number', 'lebagol'); ?>" class="lebagol_conditional_compare_price">
                    <option value="equal" <?php selected($compare, 'equal'); ?>><?php esc_html_e('equal to', 'lebagol'); ?></option>
                    <option value="not_equal" <?php selected($compare, 'not_equal'); ?>><?php esc_html_e('not equal to', 'lebagol'); ?></option>
                    <option value="greater" <?php selected($compare, 'greater'); ?>><?php esc_html_e('greater than', 'lebagol'); ?></option>
                    <option value="less" <?php selected($compare, 'less'); ?>><?php esc_html_e('less than', 'lebagol'); ?></option>
                    <option value="greater_equal" <?php selected($compare, 'greater_equal'); ?>><?php esc_html_e('greater or equal to', 'lebagol'); ?></option>
                    <option value="less_equal" <?php selected($compare, 'less_equal'); ?>><?php esc_html_e('less or equal to', 'lebagol'); ?></option>
                </optgroup>
            </select>
            <input type="number" class="lebagol_conditional_value" min="0" step="0.0001" data-<?php echo esc_attr($apply); ?>="<?php echo esc_attr($value); ?>" name="lebagol_conditionals[<?php echo esc_attr($key); ?>][value]" value="<?php echo esc_attr($value); ?>"/>
            <span class="lebagol_conditional_select_wrap">
                <select class="lebagol_conditional_select" data-<?php echo esc_attr($apply); ?>="<?php echo esc_attr(implode(',', $select)); ?>" name="lebagol_conditionals[<?php echo esc_attr($key); ?>][select][]" multiple="multiple">
                    <?php
                    if (count($select)) {
                        foreach ($select as $t) {
                            if ($term = get_term_by('slug', $t, $apply)) {
                                echo '<option value="' . esc_attr($t) . '" selected>' . esc_html($term->name) . '</option>';
                            }
                        }
                    }
                    ?>
                </select>
            </span>
        </div>
        <?php
    }

    function ajax_add_conditional() {
        check_ajax_referer('lebagol-security', 'nonce');
        self::conditional();
        wp_die();
    }

    public function sanitize_array($arr) {
        foreach ((array)$arr as $k => $v) {
            if (is_array($v)) {
                $arr[$k] = self::sanitize_array($v);
            } else {
                $arr[$k] = sanitize_post_field('post_content', $v, 0, 'db');
            }
        }

        return $arr;
    }

    private function global_labels($product) {
        if (!is_a($product, 'WC_Product')) {
            $product = wc_get_product(absint($product));
        }

        if (!$product) {
            return false;
        }

        $product_id = $product->get_id();
        $labels     = [];

        foreach ($this->labels as $key => $label) {
            if (isset($label['apply']) && !empty($label['apply'])) {
                if (($label['apply'] === 'all') || ($label['apply'] === 'featured' && $product->is_featured()) || ($label['apply'] === 'sale' && $product->is_on_sale()) || ($label['apply'] === 'instock' && $product->is_in_stock()) || ($label['apply'] === 'outofstock' && !$product->is_in_stock()) || ($label['apply'] === 'backorder' && $product->is_on_backorder())) {
                    $labels[$key] = $label;
                    continue;
                }

                if ($label['apply'] === 'bestselling') {
                    $bestselling = self::best_selling_products();

                    if (!empty($bestselling) && in_array($product_id, $bestselling)) {
                        $labels[$key] = $label;
                        continue;
                    }
                }

                if (!in_array($label['apply'], [
                        'all',
                        'sale',
                        'featured',
                        'bestselling',
                        'instock',
                        'outofstock',
                        'backorder',
                        'combination'
                    ])
                    && !empty($label['terms'])) {
                    // taxonomy
                    if (!is_array($label['terms'])) {
                        $terms = array_map('trim', explode(',', $label['terms']));
                    } else {
                        $terms = $label['terms'];
                    }

                    // for special characters
                    $term_ids = [];
                    $terms    = array_map('rawurldecode', $terms);

                    foreach ($terms as $term_slug) {
                        if ($term_obj = get_term_by('slug', $term_slug, $label['apply'])) {
                            $term_ids[] = $term_obj->term_id;
                        }
                    }

                    if (!empty($term_ids) && has_term($term_ids, $label['apply'], $product_id)) {
                        $labels[$key] = $label;
                    }
                }

                if ($label['apply'] === 'combination' && !empty($label['conditionals'])) {

                    $check_label = false;
                    foreach ($label['conditionals'] as $condition) {
                        if (in_array($condition['apply'], [
                            'featured',
                            'sale',
                            'instock',
                            'outofstock',
                            'backorder',
                        ])) {
                            if (($condition['apply'] === 'featured' && $product->is_featured()) || ($condition['apply'] === 'sale' && $product->is_on_sale()) || ($condition['apply'] === 'instock' && $product->is_in_stock()) || ($condition['apply'] === 'outofstock' && !$product->is_in_stock()) || ($condition['apply'] === 'backorder' && $product->is_on_backorder())) {
                                $check_label = true;
                            } else {
                                $check_label = false;
                                break;
                            }
                        }

                        if ($condition['apply'] == 'price') {
                            if ($this->supcondition_check(floatval($product->get_price()), floatval($condition['value']), $condition['compare'])) {
                                $check_label = true;
                            } else {
                                $check_label = false;
                                break;
                            }
                        }

                        if ($condition['apply'] == 'rating') {
                            if ($this->supcondition_check(floatval($product->get_average_rating()), floatval($condition['value']), $condition['compare'])) {
                                $check_label = true;
                            } else {
                                $check_label = false;
                                break;
                            }
                        }

                        if ($condition['apply'] == 'release') {
                            $post_date = $product->get_date_created();
                            $post_date = date('Y-m-d', strtotime($post_date));
                            $value     = $condition['value'];
                            $test_date = date('Y-m-d', strtotime("-$value days", time()));
                            if ($this->supcondition_check(strtotime($test_date), strtotime($post_date), $condition['compare'])) {
                                $check_label = true;
                            } else {
                                $check_label = false;
                                break;
                            }
                        }

                        if ($condition['apply'] == 'product_type' && (is_array($condition['select']) && count($condition['select']) > 0)) {
                            if ($this->condition_check_array($product->get_type(), $condition['select'], $condition['compare'])) {
                                $check_label = true;
                            } else {
                                $check_label = false;
                                break;
                            }
                        }

                        if (($condition['apply'] == 'product_cat' || $condition['apply'] == 'product_tag') && (is_array($condition['select']) && count($condition['select']) > 0)) {
                            if ($condition['compare'] == 'is') {

                                if (has_term($condition['select'], $condition['apply'], $product_id)) {
                                    $check_label = true;
                                } else {
                                    $check_label = false;
                                    break;
                                }
                            }
                            if ($condition['compare'] == 'is_not') {
                                if (!has_term($condition['select'], $condition['apply'], $product_id)) {
                                    $check_label = true;
                                } else {
                                    $check_label = false;
                                    break;
                                }
                            }

                        }
                    }

                    if ($check_label) {
                        $labels[$key] = $label;
                    }
                }
            }
        }

        return $labels;
    }

    public function render_labels($product, $is_single = false, $flat = true) {
        $overwrite = false;

        if (!is_a($product, 'WC_Product')) {
            $product = wc_get_product(absint($product));
        }

        if (!$product) {
            return;
        }

        $labels = [];
        $type   = $product->get_meta('lebagol_label_type');

        switch ($type) {
            case 'disable':

                return;
            case 'overwrite':
                $overwrite = true;

                if ($ids = $product->get_meta('lebagol_labels')) {
                    foreach ($ids as $id) {
                        if (isset($this->labels['b' . $id])) {
                            $labels['b' . $id] = $this->labels['b' . $id];
                        }
                    }
                }

                break;
            case 'prepend':
                $prepend_labels = [];

                if ($ids = $product->get_meta('lebagol_labels')) {
                    foreach ($ids as $id) {
                        if (isset($this->labels['b' . $id])) {
                            $prepend_labels['b' . $id] = $this->labels['b' . $id];
                        }
                    }
                }

                $labels = array_merge($prepend_labels, $this->global_labels($product));

                break;
            case 'append':
                $labels = $this->global_labels($product);

                if ($ids = $product->get_meta('lebagol_labels')) {
                    foreach ($ids as $id) {
                        if (isset($this->labels['b' . $id])) {
                            $labels['b' . $id] = $this->labels['b' . $id];
                        }
                    }
                }

                break;
            default:
                $labels = $this->global_labels($product);
        }

        if (empty($labels) || !is_array($labels)) {
            return;
        }

        if ($is_single) {
            foreach ($labels as $key => $label) {
                if (empty($label['show_detail'])) {
                    unset($labels[$key]);
                }
            }
        }

        if (empty($labels)) {
            return;
        }

        if (!$overwrite) {
            array_multisort(array_column($labels, 'order'), SORT_ASC, $labels);
        }

        if (is_array($labels) && count($labels) > 0) {
            if (!$flat) {
                echo '<div class="product-labels>';
            }
            foreach ($labels as $label) {
                $this->render_label($label);
            }
            if (!$flat) {
                echo '</div>';
            }
        }

    }

    private function render_label($label) {

        $label_output  = '';
        $label_class   = 'product-label product-label-' . $label['id'];
        $label_class   = apply_filters('lebagol_label_class', $label_class, $label);
        $label_content = do_shortcode(html_entity_decode($label['text']));
        $label_content = wp_kses_post(apply_filters('lebagol_label_content', $label_content, $label));
        if (empty($label_content)) {
            return;
        }

        $label_output .= '<span class="' . esc_attr($label_class) . '">' . $label_content . '</span>';

        echo apply_filters('lebagol_render_label', $label_output, $label);
    }

    private function inline_css() {
        $css = '';
        foreach ($this->labels as $label) {
            $color            = 'color: ' . (empty($label['text_color']) ? '#ffffff' : $label['text_color']) . ';';
            $background_color = 'background-color: ' . (empty($label['background_color']) ? '#81d742' : $label['background_color']) . ';';
            $css              .= '.product-label.product-label-' . $label['id'] . '{' . $color . ' ' . $background_color . '}';
        }

        return apply_filters('lebagol_inline_css', $css);
    }

    public static function best_selling_products() {
        if (!($products = get_site_transient('lebagol_best_selling_products'))) {
            $args = [
                'limit'    => '12',
                'status'   => 'publish',
                'orderby'  => 'meta_value_num',
                'meta_key' => 'total_sales',
                'order'    => 'DESC',
                'return'   => 'ids',
            ];

            $products = wc_get_products(apply_filters('lebagol_best_selling_products_args', $args));
            set_site_transient('lebagol_best_selling_products', $products, 24 * HOUR_IN_SECONDS);
        }

        return apply_filters('lebagol_best_selling_products', $products);
    }

    function ajax_search_term() {
        check_ajax_referer('lebagol-security', 'nonce');

        $return = [];
        $args   = [
            'taxonomy'   => sanitize_text_field(isset($_REQUEST['taxonomy']) ? $_REQUEST['taxonomy'] : ''),
            'orderby'    => 'id',
            'order'      => 'ASC',
            'hide_empty' => false,
            'fields'     => 'all',
            'name__like' => sanitize_text_field(isset($_REQUEST['q']) ? $_REQUEST['q'] : ''),
        ];

        $terms = get_terms($args);

        if (count($terms)) {
            foreach ($terms as $term) {
                $return[] = [$term->slug, $term->name];
            }
        }

        wp_send_json($return);
    }

    function product_data_tabs($tabs) {
        $tabs['lebagol_label'] = [
            'label'  => esc_html__('Lebagol Labels', 'lebagol'),
            'target' => 'lebagol_settings'
        ];

        return $tabs;
    }

    function product_data_panels() {
        global $post, $thepostid, $product_object;

        if ($product_object instanceof WC_Product) {
            $product_id = $product_object->get_id();
        } elseif (is_numeric($thepostid)) {
            $product_id = $thepostid;
        } elseif ($post instanceof WP_Post) {
            $product_id = $post->ID;
        } else {
            $product_id = 0;
        }

        if (!$product_id) {
            ?>
            <div id='lebagol_settings' class='panel woocommerce_options_panel lebagol_settings lebagol_table'>
                <p style="padding: 0 12px; color: #c9356e"><?php esc_html_e('Product wasn\'t returned.', 'lebagol'); ?></p>
            </div>
            <?php
            return;
        }

        $type       = get_post_meta($product_id, 'lebagol_label_type', true) ?: 'default';
        $labels_ids = get_post_meta($product_id, 'lebagol_labels', true) ? get_post_meta($product_id, 'lebagol_labels', true) : [];
        ?>
        <div id='lebagol_settings' class='panel woocommerce_options_panel lebagol_settings lebagol_table'>
            <div class="options_group">

                <div class="lebagol_tr">
                    <div class="lebagol_td"><?php esc_html_e('Type', 'lebagol'); ?></div>
                    <div class="lebagol_active">
                        <input name="lebagol_label_type" type="radio" value="default" <?php echo esc_attr($type === 'default' ? 'checked' : ''); ?>/> <?php esc_html_e('Default', 'lebagol'); ?>
                    </div>
                    <div class="lebagol_active">
                        <input name="lebagol_label_type" type="radio" value="disable" <?php echo esc_attr($type === 'disable' ? 'checked' : ''); ?>/> <?php esc_html_e('Disable', 'lebagol'); ?>
                    </div>
                    <div class="lebagol_active">
                        <input name="lebagol_label_type" type="radio" value="overwrite" <?php echo esc_attr($type === 'overwrite' ? 'checked' : ''); ?> /> <?php esc_html_e('Overwrite', 'lebagol'); ?>
                    </div>
                    <div class="lebagol_active">
                        <input name="lebagol_label_type" type="radio" value="prepend" <?php echo esc_attr($type === 'prepend' ? 'checked' : ''); ?> /> <?php esc_html_e('Prepend', 'lebagol'); ?>
                    </div>
                    <div class="lebagol_active">
                        <input name="lebagol_label_type" type="radio" value="append" <?php echo esc_attr($type === 'append' ? 'checked' : ''); ?> /> <?php esc_html_e('Append', 'lebagol'); ?>
                    </div>
                </div>
                <div class="lebagol_tr">
                    <div class="lebagol_td"><?php esc_html_e('Labels', 'lebagol'); ?></div>
                    <select name="lebagol_labels[]" id="lebagol_labels" multiple="multiple">
                        <?php foreach ($labels_ids as $label) {
                            echo '<option value="' . esc_attr($label) . '" selected>' . esc_html(get_the_title($label)) . '</option>';
                        } ?>
                    </select>
                </div>
            </div>
        </div>
        <?php
    }

    function ajax_search_labels() {
        check_ajax_referer('lebagol-security', 'nonce');

        $return         = [];
        $search_results = new WP_Query([
            'post_type'           => 'lebagol_prd_labels',
            's'                   => sanitize_text_field(isset($_REQUEST['q']) ? $_REQUEST['q'] : ''),
            'post_status'         => 'publish',
            'ignore_sticky_posts' => 1,
            'posts_per_page'      => 500
        ]);

        if ($search_results->have_posts()) {
            while ($search_results->have_posts()) {
                $search_results->the_post();
                $return[] = [$search_results->post->ID, $search_results->post->post_title];
            }
        }

        wp_send_json($return);
    }

    function product_save_fields($post_id) {
        if (isset($_POST['lebagol_label_type'])) {
            update_post_meta($post_id, 'lebagol_label_type', sanitize_text_field($_POST['lebagol_label_type']));
        } else {
            delete_post_meta($post_id, 'lebagol_label_type');
        }

        if (isset($_POST['lebagol_labels'])) {
            update_post_meta($post_id, 'lebagol_labels', self::sanitize_array($_POST['lebagol_labels']));
        }

    }

    public function condition_check_array($value1, $value2, $condition) {
        $check = in_array($value1, $value2);
        return $condition == 'is' ? $check : !$check;

    }

    public function supcondition_check($value1, $value2, $condition) {
        $check = false;
        switch ($condition) {
            case 'equal':
                $check = $value1 == $value2;
                break;
            case 'not_equal':
                $check = $value1 != $value2;
                break;
            case 'less':
                $check = $value1 < $value2;
                break;
            case 'less_equal':
                $check = $value1 <= $value2;
                break;
            case 'greater':
                $check = $value1 > $value2;
                break;
            case 'greater_equal':
                $check = $value1 >= $value2;
                break;
        }
        return $check;
    }
}

Lebagol_Woocommerce_Product_Labels::instance();
