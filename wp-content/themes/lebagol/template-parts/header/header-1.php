<header id="masthead" class="site-header header-1" role="banner">
    <div class="header-container">
        <div class="header-main">
            <div class="header-left">
                <?php
                lebagol_site_branding();
                if (lebagol_is_woocommerce_activated()) {
                    ?>
                    <div class="site-header-cart header-cart-mobile">
                        <?php lebagol_cart_link(); ?>
                    </div>
                    <?php
                }
                ?>
                <?php lebagol_mobile_nav_button(); ?>
            </div>
            <div class="header-center">
                <?php lebagol_primary_navigation(); ?>
            </div>
            <div class="header-right desktop-hide-down">
                <div class="header-group-action">
                    <?php
                    lebagol_header_account();
                    if (lebagol_is_woocommerce_activated()) {
                        lebagol_header_cart();
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</header><!-- #masthead -->
