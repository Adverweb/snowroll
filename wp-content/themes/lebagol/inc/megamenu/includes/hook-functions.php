<?php

defined('ABSPATH') || exit();

/**
 * Hook to delete post elementor related with this menu
 */
add_action("before_delete_post", "lebagol_megamenu_on_delete_menu_item", 9);
function lebagol_megamenu_on_delete_menu_item($post_id) {
    if (is_nav_menu_item($post_id)) {
        $related_id = lebagol_megamenu_get_post_related_menu($post_id);
        if ($related_id) {
            wp_delete_post($related_id, true);
        }
    }
}


add_filter('elementor/editor/footer', 'lebagol_megamenu_add_back_button_inspector');
function lebagol_megamenu_add_back_button_inspector() {
    if (!isset($_GET['lebagol-menu-editable']) || !$_GET['lebagol-menu-editable']) {
        return;
    }
    ?>
    <script type="text/template" id="tmpl-elementor-panel-footer-content">
        <div id="elementor-panel-footer-back-to-admin" class="elementor-panel-footer-tool elementor-leave-open tooltip-target" data-tooltip="<?php esc_attr_e('Back', 'lebagol'); ?>">
            <i class="eicon-close" aria-hidden="true"></i>
        </div>
        <div id="elementor-panel-footer-settings" class="elementor-panel-footer-tool elementor-leave-open tooltip-target" data-tooltip="<?php esc_attr_e('Settings', 'lebagol'); ?>">
            <i class="eicon-cog" aria-hidden="true"></i>
            <span class="elementor-screen-only"><?php echo esc_html__('Page Settings', 'lebagol'); ?></span>
        </div>
        <div id="elementor-panel-footer-navigator" class="elementor-panel-footer-tool tooltip-target" data-tooltip="<?php esc_attr_e('Navigator', 'lebagol'); ?>">
            <i class="eicon-navigator" aria-hidden="true"></i>
            <span class="elementor-screen-only">
                    <?php echo esc_html__('Navigator', 'lebagol'); ?>
                </span>
        </div>
        <div id="elementor-panel-footer-responsive" class="elementor-panel-footer-tool">
            <i class="eicon-device-desktop tooltip-target" aria-hidden="true" data-tooltip="<?php esc_attr_e('Responsive Mode', 'lebagol'); ?>"></i>
            <span class="elementor-screen-only">
					<?php echo esc_html__('Responsive Mode', 'lebagol'); ?>
				</span>
        </div>
        <div id="elementor-panel-footer-history" class="elementor-panel-footer-tool elementor-leave-open tooltip-target" data-tooltip="<?php esc_attr_e('History', 'lebagol'); ?>">
            <i class="eicon-history" aria-hidden="true"></i>
            <span class="elementor-screen-only"><?php echo esc_html__('History', 'lebagol'); ?></span>
        </div>
        <div id="elementor-panel-footer-saver-publish" class="elementor-panel-footer-tool">
            <button id="elementor-panel-saver-button-publish" class="elementor-button elementor-button-success elementor-saver-disabled">
					<span class="elementor-state-icon">
						<i class="eicon-loading eicon-animation-spin" aria-hidden="true"></i>
					</span>
                <span id="elementor-panel-saver-button-publish-label">
						<?php echo esc_html__('Publish', 'lebagol'); ?>
					</span>
            </button>
        </div>
        <div id="elementor-panel-saver-save-options" class="elementor-panel-footer-tool">
            <button id="elementor-panel-saver-button-save-options" class="elementor-button elementor-button-success tooltip-target elementor-disabled" data-tooltip="<?php esc_attr_e('Save Options', 'lebagol'); ?>">
                <i class="eicon-caret-up" aria-hidden="true"></i>
                <span class="elementor-screen-only"><?php echo esc_html__('Save Options', 'lebagol'); ?></span>
            </button>
        </div>
    </script>

    <?php
}

add_action('wp_ajax_lebagol_load_menu_data', 'lebagol_megamenu_load_menu_data');
function lebagol_megamenu_load_menu_data() {
    $nonce   = !empty($_POST['nonce']) ? sanitize_text_field($_POST['nonce']) : '';
    $menu_id = !empty($_POST['menu_id']) ? absint($_POST['menu_id']) : false;
    if (!wp_verify_nonce($nonce, 'lebagol-menu-data-nonce') || !$menu_id) {
        wp_send_json(array(
            'message' => esc_html__('Access denied', 'lebagol')
        ));
    }

    $data = lebagol_megamenu_get_item_data($menu_id);

    $data = $data ? $data : array();
    if (isset($_POST['istop']) && absint($_POST['istop']) == 1) {
        if (class_exists('Elementor\Plugin')) {
            if (isset($data['enabled']) && $data['enabled']) {
                $related_id = lebagol_megamenu_get_post_related_menu($menu_id);
                if (!$related_id) {
                    lebagol_megamenu_create_related_post($menu_id);
                    $related_id = lebagol_megamenu_get_post_related_menu($menu_id);
                }

                if ($related_id && isset($_REQUEST['menu_id']) && is_admin()) {
                    $url                      = Elementor\Plugin::instance()->documents->get($related_id)->get_edit_url();
                    $data['edit_submenu_url'] = add_query_arg(array('lebagol-menu-editable' => 1), $url);
                }
            } else {
                $url                      = admin_url();
                $data['edit_submenu_url'] = add_query_arg(array('lebagol-menu-createable' => 1, 'menu_id' => $menu_id), $url);
            }
        }
    }

    $results = apply_filters('lebagol_menu_settings_data', array(
        'status' => true,
        'data'   => $data
    ));

    wp_send_json($results);

}

add_action('wp_ajax_lebagol_update_menu_item_data', 'lebagol_megamenu_update_menu_item_data');
function lebagol_megamenu_update_menu_item_data() {
    $nonce = !empty($_POST['nonce']) ? sanitize_text_field($_POST['nonce']) : '';
    if (!wp_verify_nonce($nonce, 'lebagol-update-menu-item')) {
        wp_send_json(array(
            'message' => esc_html__('Access denied', 'lebagol')
        ));
    }

    $settings = !empty($_POST['lebagol-menu-item']) ? ($_POST['lebagol-menu-item']) : array();
    $menu_id  = !empty($_POST['menu_id']) ? absint($_POST['menu_id']) : false;

    do_action('lebagol_before_update_menu_settings', $settings);


    lebagol_megamenu_update_item_data($menu_id, $settings);

    do_action('lebagol_menu_settings_updated', $settings);
    wp_send_json(array('status' => true));
}

add_action('admin_footer', 'lebagol_megamenu_underscore_template');
function lebagol_megamenu_underscore_template() {
    global $pagenow;
    if ($pagenow === 'nav-menus.php') { ?>
        <script type="text/html" id="tpl-lebagol-menu-item-modal">
            <div id="lebagol-modal" class="lebagol-modal">
                <div id="lebagol-modal-body" class="<%= data.edit_submenu === true ? 'edit-menu-active' : ( data.is_loading ? 'loading' : '' ) %>">
                    <% if ( data.edit_submenu !== true && data.is_loading !== true ) { %>
                    <form id="menu-edit-form">
                        <% } %>
                        <div class="lebagol-modal-content">
                            <% if ( data.edit_submenu === true ) { %>
                            <iframe src="<%= data.edit_submenu_url %>"/>
                            <% } else if ( data.is_loading === true ) { %>
                            <i class="fa fa-spin fa-spinner"></i>
                            <% } else { %>
                            <div class="form-group toggle-select-setting">
                                <label for="icon"><?php esc_html_e('Icon', 'lebagol') ?></label>
                                <select id="icon" name="lebagol-menu-item[icon]" class="form-control icon-picker lebagol-input-switcher lebagol-input-switcher-true" data-target=".icon-custom" data-target2=".icon-custom-image">
                                    <option value="" <%= data.icon == '' ? ' selected' : '' %>><?php echo esc_html__('No Use', 'lebagol') ?></option>
                                    <option value="1" <%= data.icon == 1 ? ' selected' : '' %>><?php echo esc_html__('Custom Class', 'lebagol') ?></option>
                                    <option value="2" <%= data.icon == 2 ? ' selected' : '' %>><?php echo esc_html__('Custom Image', 'lebagol') ?></option>
                                    <?php foreach (lebagol_megamenu_get_fontawesome_icons() as $value) : ?>
                                        <option value="<?php echo 'lebagol-icon-' . esc_attr($value) ?>"<%= data.icon == '<?php echo 'lebagol-icon-' . esc_attr($value) ?>' ? ' selected' : '' %>><?php echo esc_attr($value) ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                            <div class="form-group icon-custom-image toggle-select-setting elementor-hidden" style="display: flex;">
                                <label for="icon-custom-image"><?php esc_html_e('Icon Image', 'lebagol') ?></label>
                                <input type="hidden" name="lebagol-menu-item[icon_custom_image]" class="input" id="icon-custom-image"/>
                                <input type="hidden" name="lebagol-menu-item[icon_custom_image_url]" class="input" id="icon-custom-image-url"/>
                                <div class="megamenu-icon-image-wrapper">
                                    <% if (data.icon_custom_image_url !== ''){%>
                                    <img src="<%= data.icon_custom_image_url %>" style="margin-right:10px;padding:0;max-height:32px;float:none;" alt="">
                                    <% } %>
                                </div>
                                <button id="edit-megamenu-image" class="edit-megamenu-image button button-primary button-large">
                                    <?php esc_html_e('Choose image', 'lebagol') ?>
                                </button>
                                <button style="margin-left:10px" id="edit-megamenu-image-remove" class="edit-megamenu-image-remove button button-primary button-large">
                                    <?php esc_html_e('Remove image', 'lebagol') ?>
                                </button>
                            </div>
                            <div class="form-group icon-custom toggle-select-setting elementor-hidden">
                                <label for="icon-custom"><?php esc_html_e('Icon Class Name', 'lebagol') ?></label>
                                <input type="text" name="lebagol-menu-item[icon-custom]" class="input" id="icon-custom"/>
                            </div>
                            <div class="form-group">
                                <label for="icon_color"><?php esc_html_e('Icon Color', 'lebagol') ?></label>
                                <input class="color-picker" name="lebagol-menu-item[icon_color]" value="<%= data.icon_color %>" id="icon_color"/>
                            </div>
                            <div class="form-group">
                                <label for="icon_size"><?php esc_html_e('Icon Size', 'lebagol') ?></label>
                                <input type="text" name="lebagol-menu-item[icon_size]" value="<%= data.icon_size %>" id="icon_size"/>
                                <span class="unit">px</span>
                            </div>
                            <div class="form-group">
                                <label for="color"><?php esc_html_e('Color', 'lebagol') ?></label>
                                <input class="color-picker" name="lebagol-menu-item[color]" value="<%= data.color %>" id="color"/>
                            </div>
                            <div class="form-group">
                                <label for="badge_title"><?php esc_html_e('Badges Title', 'lebagol') ?></label>
                                <input class="form-control" name="lebagol-menu-item[badge_title]" value="<%= data.badge_title %>" id="badge_title"/>
                            </div>
                            <div class="form-group">
                                <label for="badge_color"><?php esc_html_e('Badges Color', 'lebagol') ?></label>
                                <input class="color-picker" name="lebagol-menu-item[badge_color]" value="<%= data.badge_color %>" id="badge_color"/>
                            </div>
                            <div class="form-group">
                                <label for="badges_bg_color"><?php esc_html_e('Badges Bg Color', 'lebagol') ?></label>
                                <input class="color-picker" name="lebagol-menu-item[badges_bg_color]" value="<%= data.badges_bg_color %>" id="badges_bg_color"/>
                            </div>

                            <div class="form-group submenu-setting toggle-select-setting">
                                <label><?php esc_html_e('Mega Submenu Enabled', 'lebagol') ?></label>
                                <select name="lebagol-menu-item[enabled]" class="lebagol-input-switcher lebagol-input-switcher-true" data-target=".submenu-width-setting">
                                    <option value="1"
                                    <%= data.enabled == 1? 'selected':''
                                    %>> <?php esc_html_e('Yes', 'lebagol') ?></opttion>
                                    <option value="0"
                                    <%= data.enabled == 0? 'selected':''
                                    %>><?php esc_html_e('No', 'lebagol') ?></opttion>
                                </select>
                                <button id="edit-megamenu" class="button button-primary button-large">
                                    <?php esc_html_e('Edit Megamenu Submenu', 'lebagol') ?>
                                </button>
                            </div>

                            <div class="form-group submenu-width-setting toggle-select-setting elementor-hidden">
                                <label><?php esc_html_e('Sub Megamenu Width', 'lebagol') ?></label>
                                <select name="lebagol-menu-item[customwidth]" class="lebagol-input-switcher lebagol-input-switcher-true" data-target=".submenu-subwidth-setting">
                                    <option value="1"
                                    <%= data.customwidth == 1? 'selected':''
                                    %>> <?php esc_html_e('Yes', 'lebagol') ?></opttion>
                                    <option value="0"
                                    <%= data.customwidth == 0? 'selected':''
                                    %>><?php esc_html_e('Full Width', 'lebagol') ?></opttion>
                                    <option value="2"
                                    <%= data.customwidth == 2? 'selected':''
                                    %>><?php esc_html_e('Stretch Width', 'lebagol') ?></opttion>
                                    <option value="3"
                                    <%= data.customwidth == 3? 'selected':''
                                    %>><?php esc_html_e('Container Width', 'lebagol') ?></opttion>
                                </select>
                            </div>

                            <div class="form-group submenu-width-setting submenu-subwidth-setting toggle-select-setting elementor-hidden">
                                <label for="menu_subwidth"><?php esc_html_e('Sub Mega Menu Max Width', 'lebagol') ?></label>
                                <input type="text" name="lebagol-menu-item[subwidth]" value="<%= data.subwidth?data.subwidth:'600' %>" class="input" id="menu_subwidth"/>
                                <span class="unit">px</span>
                            </div>

                            <div class="form-group submenu-width-setting submenu-subwidth-setting toggle-select-setting elementor-hidden">
                                <label><?php esc_html_e('Sub Mega Menu Position Left', 'lebagol') ?></label>
                                <select name="lebagol-menu-item[menuposition]">
                                    <option value="0"
                                    <%= data.menuposition == 0? 'selected':''
                                    %>><?php esc_html_e('No', 'lebagol') ?></opttion>
                                    <option value="1"
                                    <%= data.menuposition == 1? 'selected':''
                                    %>> <?php esc_html_e('Yes', 'lebagol') ?></opttion>
                                </select>
                            </div>

                            <% } %>
                        </div>
                        <% if ( data.is_loading !== true && data.edit_submenu !== true ) { %>
                        <div class="lebagol-modal-footer">
                            <a href="#" class="close button"><%= lebagol_memgamnu_params.i18n.close %></a>
                            <?php wp_nonce_field('lebagol-update-menu-item', 'nonce') ?>
                            <input name="menu_id" value="<%= data.menu_id %>" type="hidden"/>
                            <button type="submit" class="button button-primary button-large menu-save pull-right"><%=
                                lebagol_memgamnu_params.i18n.submit %>
                            </button>
                        </div>
                        <% } %>
                        <% if ( data.edit_submenu !== true && data.is_loading !== true ) { %>
                    </form>
                    <% } %>
                </div>
                <div class="lebagol-modal-overlay"></div>
            </div>
        </script>
    <?php }
}







