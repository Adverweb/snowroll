<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Lebagol_Login' ) ) :
	class Lebagol_Login {
		public function __construct() {
			add_action( 'wp_ajax_lebagol_login', array( $this, 'ajax_login' ) );
			add_action( 'wp_ajax_nopriv_lebagol_login', array( $this, 'ajax_login' ) );

			add_action( 'wp_enqueue_scripts', array( $this, 'scripts' ), 10 );
		}

		public function scripts(){

			wp_enqueue_script( 'lebagol-ajax-login', get_template_directory_uri() . '/assets/js/frontend/login.js', array('jquery'), LEBAGOL_VERSION, true );
		}

		public function ajax_login() {
			do_action( 'lebagol_ajax_verify_captcha' );
			check_ajax_referer( 'ajax-lebagol-login-nonce', 'security-login' );
			$info                  = array();
			$info['user_login']    = $_REQUEST['username'];
			$info['user_password'] = $_REQUEST['password'];
			$info['remember']      = $_REQUEST['remember'];

			$user_signon = wp_signon( $info, is_ssl() );
			if ( is_wp_error( $user_signon ) ) {
				wp_send_json( array(
					'status' => false,
					'msg'    => esc_html__( 'Wrong username or password. Please try again!!!', 'lebagol' )
				) );
			} else {
				wp_set_current_user( $user_signon->ID );
				wp_send_json( array(
					'status' => true,
					'msg'    => esc_html__( 'Signin successful, redirecting...', 'lebagol' )
				) );
			}
		}
	}
new Lebagol_Login();
endif;
