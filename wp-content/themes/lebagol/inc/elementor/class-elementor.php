<?php

use Elementor\Plugin;

if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('Lebagol_Elementor')) :

    /**
     * The Lebagol Elementor Integration class
     */
    class Lebagol_Elementor {
        private $suffix = '';

        public function __construct() {
            $this->suffix = (defined('SCRIPT_DEBUG') && SCRIPT_DEBUG) ? '' : '.min';

            add_action('wp', [$this, 'register_auto_scripts_frontend']);
            add_action('elementor/elements/categories_registered', [$this, 'register_widget_category']);
            add_action('wp_enqueue_scripts', [$this, 'add_scripts'], 15);
            add_action('elementor/widgets/register', array($this, 'customs_widgets'));
            add_action('elementor/widgets/register', array($this, 'include_widgets'));
            add_action('elementor/frontend/after_enqueue_scripts', [$this, 'add_js']);

            // Custom Animation Scroll
            add_filter('elementor/controls/animations/additional_animations', [$this, 'add_animations_scroll']);

            // Elementor Fix Noitice WooCommerce
            add_action('elementor/editor/before_enqueue_scripts', array($this, 'woocommerce_fix_notice'));

            // Backend
            add_action('elementor/editor/after_enqueue_styles', [$this, 'add_style_editor'], 99);

            // Add Icon Custom
            add_action('elementor/icons_manager/native', [$this, 'add_icons_native']);
            add_action('elementor/controls/register', [$this, 'add_icons']);

            if (!lebagol_is_elementor_pro_activated()) {
                require trailingslashit(get_template_directory()) . 'inc/elementor/custom-css.php';
                require trailingslashit(get_template_directory()) . 'inc/elementor/sticky-section.php';
                if (is_admin()) {
                    add_action('manage_elementor_library_posts_columns', [$this, 'admin_columns_headers']);
                    add_action('manage_elementor_library_posts_custom_column', [$this, 'admin_columns_content'], 10, 2);
                }

                require get_theme_file_path('inc/elementor/motion-fx/controls-group.php');
                require get_theme_file_path('inc/elementor/motion-fx/module.php');
            }

            require get_theme_file_path('inc/elementor/modules/page-settings.php');
            if (function_exists('hfe_init')) {
                require get_theme_file_path('inc/elementor/modules/header-settings.php');
            }
            add_filter('elementor/fonts/additional_fonts', [$this, 'additional_fonts']);
            add_action('wp_enqueue_scripts', [$this, 'elementor_kit']);

            require get_theme_file_path('inc/elementor/modules/settings.php');
        }

        public function elementor_kit() {
            $active_kit_id = Elementor\Plugin::$instance->kits_manager->get_active_id();
            Elementor\Plugin::$instance->kits_manager->frontend_before_enqueue_styles();
            $myvals = get_post_meta($active_kit_id, '_elementor_page_settings', true);
            if (!empty($myvals)) {
                $css = '';
                foreach ($myvals['system_colors'] as $key => $value) {
                    $css .= $value['color'] !== '' ? '--' . $value['_id'] . ':' . $value['color'] . ';' : '';
                }

                $var = "body{{$css}}";
                wp_add_inline_style('lebagol-style', $var);
            }
        }

        public function additional_fonts($fonts) {
            $fonts['Tirelessly Love You'] = 'system';
            $fonts['Kalnia'] = 'googlefonts';
            return $fonts;
        }

        public function admin_columns_headers($defaults) {
            $defaults['shortcode'] = esc_html__('Shortcode', 'lebagol');

            return $defaults;
        }

        public function admin_columns_content($column_name, $post_id) {
            if ('shortcode' === $column_name) {
                ob_start();
                ?>
                <input class="elementor-shortcode-input" type="text" readonly onfocus="this.select()" value="[hfe_template id='<?php echo esc_attr($post_id); ?>']"/>
                <?php
                ob_get_contents();
            }
        }

        public function add_js() {

            wp_enqueue_script('lebagol-elementor-frontend', get_theme_file_uri('/assets/js/elementor-frontend.js'), ['jquery', 'elementor-frontend'], LEBAGOL_VERSION);
        }

        public function add_style_editor() {

            wp_enqueue_style('lebagol-elementor-editor-icon', get_theme_file_uri('/assets/css/admin/elementor/icons.css'), [], LEBAGOL_VERSION);
        }

        public function add_scripts() {

            $suffix = (defined('SCRIPT_DEBUG') && SCRIPT_DEBUG) ? '' : '.min';
            wp_enqueue_style('lebagol-elementor', get_template_directory_uri() . '/assets/css/base/elementor.css', '', LEBAGOL_VERSION);
            wp_style_add_data('lebagol-elementor', 'rtl', 'replace');

            // Add Scripts

            $e_swiper_latest     = Plugin::$instance->experiments->is_feature_active('e_swiper_latest');
            $e_swiper_asset_path = $e_swiper_latest ? 'assets/lib/swiper/v8/' : 'assets/lib/swiper/';
            $e_swiper_version    = $e_swiper_latest ? '8.4.5' : '5.3.6';
            wp_register_script(
                'swiper',
                plugins_url('elementor/' . $e_swiper_asset_path . 'swiper.js', 'elementor'),
                [],
                $e_swiper_version,
                true
            );
        }

        public function register_auto_scripts_frontend() {
            $suffix = (defined('SCRIPT_DEBUG') && SCRIPT_DEBUG) ? '' : '.min';
            wp_register_script('lebagol-elementor-swiper', get_theme_file_uri('/assets/js/elementor-swiper' . $suffix . '.js'), array('jquery', 'elementor-frontend'), LEBAGOL_VERSION, true);
            // Register auto scripts frontend

            $files = glob(get_theme_file_path('/assets/js/elementor/*' . $suffix . '.js'));
            foreach ($files as $file) {
                $file_name = wp_basename($file);
                $handle    = str_replace($suffix . ".js", '', $file_name);
                $scr       = get_theme_file_uri('/assets/js/elementor/' . $file_name);
                if (file_exists($file)) {
                    wp_register_script('lebagol-elementor-' . $handle, $scr, ['jquery', 'elementor-frontend'], LEBAGOL_VERSION, true);
                }
            }
        }

        public function register_widget_category($this_cat) {
            $this_cat->add_category(
                'lebagol-addons',
                [
                    'title' => esc_html__('Lebagol Addons', 'lebagol'),
                    'icon'  => 'fa fa-plug',
                ]
            );
            return $this_cat;
        }

        public function add_animations_scroll($animations) {
            $animations['Lebagol Animation'] = [
                'opal-move-up'    => 'Move Up',
                'opal-move-down'  => 'Move Down',
                'opal-move-left'  => 'Move Left',
                'opal-move-right' => 'Move Right',
                'opal-flip'       => 'Flip',
                'opal-helix'      => 'Helix',
                'opal-scale-up'   => 'Scale',
                'opal-am-popup'   => 'Popup',
            ];

            return $animations;
        }

        public function customs_widgets() {
            $files = glob(get_theme_file_path('/inc/elementor/custom-widgets/*.php'));
            foreach ($files as $file) {
                if (file_exists($file)) {
                    require_once $file;
                }
            }
        }

        /**
         * @param $widgets_manager Elementor\Widgets_Manager
         */
        public function include_widgets($widgets_manager) {
            require 'base-swiper-widget.php';
            $files = glob(get_theme_file_path('/inc/elementor/widgets/*.php'));
            foreach ($files as $file) {
                if (file_exists($file)) {
                    require_once $file;
                }
            }
        }

        public function woocommerce_fix_notice() {
            if (lebagol_is_woocommerce_activated()) {
                remove_action('woocommerce_cart_is_empty', 'woocommerce_output_all_notices', 5);
                remove_action('woocommerce_shortcode_before_product_cat_loop', 'woocommerce_output_all_notices', 10);
                remove_action('woocommerce_before_shop_loop', 'woocommerce_output_all_notices', 10);
                remove_action('woocommerce_before_single_product', 'woocommerce_output_all_notices', 10);
                remove_action('woocommerce_before_cart', 'woocommerce_output_all_notices', 10);
                remove_action('woocommerce_before_checkout_form', 'woocommerce_output_all_notices', 10);
                remove_action('woocommerce_account_content', 'woocommerce_output_all_notices', 10);
                remove_action('woocommerce_before_customer_login_form', 'woocommerce_output_all_notices', 10);
            }
        }


        public function add_icons( $manager ) {
            $new_icons = json_decode( '{"lebagol-icon-arrow-small-left":"arrow-small-left","lebagol-icon-arrow-small-right":"arrow-small-right","lebagol-icon-avocado":"avocado","lebagol-icon-cacao":"cacao","lebagol-icon-call-outgoing":"call-outgoing","lebagol-icon-cone-ice-cream":"cone-ice-cream","lebagol-icon-earth":"earth","lebagol-icon-equalizer-line":"equalizer-line","lebagol-icon-eye1":"eye1","lebagol-icon-fast-delivery":"fast-delivery","lebagol-icon-gluten-free":"gluten-free","lebagol-icon-grid1":"grid1","lebagol-icon-ice-cream":"ice-cream","lebagol-icon-icecream-cone":"icecream-cone","lebagol-icon-leaves":"leaves","lebagol-icon-list1":"list1","lebagol-icon-local-delivery":"local-delivery","lebagol-icon-location":"location","lebagol-icon-mail-send-line":"mail-send-line","lebagol-icon-map-pin-2-line":"map-pin-2-line","lebagol-icon-no-gmo":"no-gmo","lebagol-icon-no-sugar":"no-sugar","lebagol-icon-pinterest-line":"pinterest-line","lebagol-icon-play-1":"play-1","lebagol-icon-plus-1":"plus-1","lebagol-icon-quote-1":"quote-1","lebagol-icon-random1":"random1","lebagol-icon-shaved-ice":"shaved-ice","lebagol-icon-shopping-bag-add":"shopping-bag-add","lebagol-icon-strawberry":"strawberry","lebagol-icon-time-line":"time-line","lebagol-icon-trade-1":"trade-1","lebagol-icon-twitter-x":"twitter-x","lebagol-icon-wishlist":"wishlist","lebagol-icon-youtube-line":"youtube-line","lebagol-icon-360":"360","lebagol-icon-angle-down":"angle-down","lebagol-icon-angle-left":"angle-left","lebagol-icon-angle-right":"angle-right","lebagol-icon-angle-up":"angle-up","lebagol-icon-arrow-left":"arrow-left","lebagol-icon-arrow-right":"arrow-right","lebagol-icon-bars":"bars","lebagol-icon-cart-empty":"cart-empty","lebagol-icon-cart":"cart","lebagol-icon-check-square":"check-square","lebagol-icon-check":"check","lebagol-icon-circle":"circle","lebagol-icon-cloud-download-alt":"cloud-download-alt","lebagol-icon-comment":"comment","lebagol-icon-comments":"comments","lebagol-icon-contact":"contact","lebagol-icon-copy":"copy","lebagol-icon-credit-card":"credit-card","lebagol-icon-dot-circle":"dot-circle","lebagol-icon-edit":"edit","lebagol-icon-envelope":"envelope","lebagol-icon-expand-alt":"expand-alt","lebagol-icon-expand":"expand","lebagol-icon-external-link-alt":"external-link-alt","lebagol-icon-file-alt":"file-alt","lebagol-icon-file-archive":"file-archive","lebagol-icon-folder-open":"folder-open","lebagol-icon-folder":"folder","lebagol-icon-frown":"frown","lebagol-icon-gift":"gift","lebagol-icon-grid":"grid","lebagol-icon-grip-horizontal":"grip-horizontal","lebagol-icon-heart-fill":"heart-fill","lebagol-icon-heart":"heart","lebagol-icon-history":"history","lebagol-icon-home":"home","lebagol-icon-info-circle":"info-circle","lebagol-icon-instagram":"instagram","lebagol-icon-level-up-alt":"level-up-alt","lebagol-icon-list":"list","lebagol-icon-map-marker-check":"map-marker-check","lebagol-icon-meh":"meh","lebagol-icon-minus-circle":"minus-circle","lebagol-icon-minus":"minus","lebagol-icon-mobile-android-alt":"mobile-android-alt","lebagol-icon-money-bill":"money-bill","lebagol-icon-pencil-alt":"pencil-alt","lebagol-icon-plus":"plus","lebagol-icon-quotes":"quotes","lebagol-icon-random":"random","lebagol-icon-reply-all":"reply-all","lebagol-icon-reply":"reply","lebagol-icon-search":"search","lebagol-icon-shield-check":"shield-check","lebagol-icon-shopping-bag":"shopping-bag","lebagol-icon-shopping-basket":"shopping-basket","lebagol-icon-sign-out-alt":"sign-out-alt","lebagol-icon-smile":"smile","lebagol-icon-spinner":"spinner","lebagol-icon-square":"square","lebagol-icon-star":"star","lebagol-icon-store":"store","lebagol-icon-sync":"sync","lebagol-icon-tachometer-alt":"tachometer-alt","lebagol-icon-thumbtack":"thumbtack","lebagol-icon-ticket":"ticket","lebagol-icon-times-circle":"times-circle","lebagol-icon-times-square":"times-square","lebagol-icon-times":"times","lebagol-icon-trophy-alt":"trophy-alt","lebagol-icon-truck":"truck","lebagol-icon-user":"user","lebagol-icon-video":"video","lebagol-icon-wishlist-empty":"wishlist-empty","lebagol-icon-adobe":"adobe","lebagol-icon-amazon":"amazon","lebagol-icon-android":"android","lebagol-icon-angular":"angular","lebagol-icon-apper":"apper","lebagol-icon-apple":"apple","lebagol-icon-atlassian":"atlassian","lebagol-icon-behance":"behance","lebagol-icon-bitbucket":"bitbucket","lebagol-icon-bitcoin":"bitcoin","lebagol-icon-bity":"bity","lebagol-icon-bluetooth":"bluetooth","lebagol-icon-btc":"btc","lebagol-icon-centos":"centos","lebagol-icon-chrome":"chrome","lebagol-icon-codepen":"codepen","lebagol-icon-cpanel":"cpanel","lebagol-icon-discord":"discord","lebagol-icon-dochub":"dochub","lebagol-icon-docker":"docker","lebagol-icon-dribbble":"dribbble","lebagol-icon-dropbox":"dropbox","lebagol-icon-drupal":"drupal","lebagol-icon-ebay":"ebay","lebagol-icon-facebook-f":"facebook-f","lebagol-icon-facebook":"facebook","lebagol-icon-figma":"figma","lebagol-icon-firefox":"firefox","lebagol-icon-google-plus":"google-plus","lebagol-icon-google":"google","lebagol-icon-grunt":"grunt","lebagol-icon-gulp":"gulp","lebagol-icon-html5":"html5","lebagol-icon-joomla":"joomla","lebagol-icon-link-brand":"link-brand","lebagol-icon-linkedin":"linkedin","lebagol-icon-mailchimp":"mailchimp","lebagol-icon-opencart":"opencart","lebagol-icon-paypal":"paypal","lebagol-icon-pinterest-p":"pinterest-p","lebagol-icon-reddit":"reddit","lebagol-icon-skype":"skype","lebagol-icon-slack":"slack","lebagol-icon-snapchat":"snapchat","lebagol-icon-spotify":"spotify","lebagol-icon-trello":"trello","lebagol-icon-twitter":"twitter","lebagol-icon-vimeo":"vimeo","lebagol-icon-whatsapp":"whatsapp","lebagol-icon-wordpress":"wordpress","lebagol-icon-yoast":"yoast","lebagol-icon-youtube":"youtube"}', true );
			$icons     = $manager->get_control( 'icon' )->get_settings( 'options' );
			$new_icons = array_merge(
				$new_icons,
				$icons
			);
			// Then we set a new list of icons as the options of the icon control
			$manager->get_control( 'icon' )->set_settings( 'options', $new_icons ); 
        }

        public function add_icons_native($tabs) {

            $tabs['opal-custom'] = [
                'name'          => 'lebagol-icon',
                'label'         => esc_html__('Lebagol Icon', 'lebagol'),
                'prefix'        => 'lebagol-icon-',
                'displayPrefix' => 'lebagol-icon-',
                'labelIcon'     => 'fab fa-font-awesome-alt',
                'ver'           => LEBAGOL_VERSION,
                'fetchJson'     => get_theme_file_uri('/inc/elementor/icons.json'),
                'native'        => true,
            ];

            return $tabs;
        }
    }

endif;

return new Lebagol_Elementor();
