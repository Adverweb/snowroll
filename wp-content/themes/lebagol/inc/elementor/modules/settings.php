<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Lebagol_Elementor_Theme_Settings' ) ) :
	/**
	 * The main Lebagol_Elementor_Theme_Settings class
	 */
	class Lebagol_Elementor_Theme_Settings {
		private $tabs = [];
		public function __construct() {
			$files = glob( get_template_directory() . '/inc/elementor/modules/settings/*.php' );
			foreach ( $files as $file ) {
				$name = str_replace('.php', '', wp_basename( $file ));
				$dirname = wp_basename( dirname($file) );
				$this->tabs[$dirname . '-' . $name] = 'Lebagol_Elementor_' . ucwords($dirname) . '_' . ucwords($name);
				$file = get_theme_file_path( 'inc/elementor/modules/settings/' . wp_basename( $file ) );

				if ( file_exists( $file ) ) {
					require_once $file;
				}
			}
			add_action( 'elementor/kit/register_tabs', [ $this, 'register_tabs' ], 10, 1 );
		}

		public function register_tabs( $tab ) {
			foreach ( $this->tabs as $id => $class ) {
				$tab->register_tab( $id, $class );
			}
		}
	}
endif;

new Lebagol_Elementor_Theme_Settings();
