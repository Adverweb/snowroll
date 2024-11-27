<?php

defined('ABSPATH') || exit;

global $product;

// Ensure visibility.
if (empty($product) || !$product->is_visible()) {
    return;
}

?>
<li <?php wc_product_class('product-list', $product); ?>>
    <?php
    /**
     * Functions hooked in to lebagol_woocommerce_before_shop_loop_item action
     *
     */
    do_action('lebagol_woocommerce_before_shop_loop_item');


    ?>
    <div class="product-transition">
        <div class="product-labels">
            <?php woocommerce_show_product_loop_sale_flash();
            Lebagol_Woocommerce_Product_Labels::instance()->render_labels($product);
            ?>
        </div>
        <div class="product-image image-main">
            <?php
            /**
             * Functions hooked in to lebagol_woocommerce_before_shop_loop_item_title action
             *
             * @see woocommerce_template_loop_product_thumbnail - 15 - woo
             *
             *
             */
            do_action('lebagol_woocommerce_before_shop_loop_item_title');
            ?>
        </div>
        <?php do_action('lebagol_woocommerce_gallery_image'); ?>
    </div>
    <div class="product-caption">
        <?php woocommerce_template_loop_rating(); ?>
        <?php woocommerce_template_loop_product_title(); ?>
        <?php woocommerce_template_loop_price(); ?>
        <?php lebagol_woocommerce_get_product_short_description(); ?>
        <div class="caption-bottom">
            <?php woocommerce_template_loop_add_to_cart(); ?>
            <?php lebagol_woocommerce_group_action('top'); ?>
        </div>
    </div>
    <?php
    /**
     * Functions hooked in to lebagol_woocommerce_after_shop_loop_item action
     *
     */
    do_action('lebagol_woocommerce_after_shop_loop_item');
    ?>
</li>
