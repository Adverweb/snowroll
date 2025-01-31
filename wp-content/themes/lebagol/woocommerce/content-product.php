<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 */

defined('ABSPATH') || exit;

global $product;

// Ensure visibility.
if (empty($product) || !$product->is_visible()) {
    return;
}
$class = wc_get_loop_prop('product-carousel') == 'swiper-wrapper' ? 'swiper-slide product-style-default' : 'product-style-default';
?>
<li <?php wc_product_class($class, $product); ?>>
    <div class="product-block">
        <div class="product-transition">
            <div class="product-labels">
                <?php woocommerce_show_product_loop_sale_flash();
                Lebagol_Woocommerce_Product_Labels::instance()->render_labels($product);
                ?>
            </div>
            <?php lebagol_template_loop_product_thumbnail(); ?>
            <?php lebagol_woocommerce_group_action(); ?>
            <?php do_action('lebagol_woocommerce_gallery_image');?>
            <?php woocommerce_template_loop_product_link_open(); ?>
            <?php woocommerce_template_loop_product_link_close(); ?>
            <?php woocommerce_template_loop_add_to_cart(); ?>
        </div>
        <div class="product-caption">
            <?php woocommerce_template_loop_rating(); ?>
            <?php woocommerce_template_loop_product_title(); ?>
            <?php woocommerce_template_loop_price(); ?>
        </div>
    </div>
</li>
