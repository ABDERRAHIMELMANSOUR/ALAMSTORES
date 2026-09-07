<?php 
defined( 'ABSPATH' ) || exit();
global $post;
if( !class_exists( 'OVAPO_Admin_Assets' ) ){
	class OVAPO_Admin_Assets{

		public function __construct(){
			add_action( 'admin_footer', array( $this, 'enqueue_scripts' ), 10, 2 );
		}

		public function enqueue_scripts(){

			/* Init Css Admin */
			wp_enqueue_style( 'ovapo-admin-style', OVAPO_PLUGIN_URI.'assets/css/admin/ovapo-admin-style.css' );

			/* Init JS Admin */
			wp_enqueue_script( 'ovapo-admin-script', OVAPO_PLUGIN_URI.'assets/js/ovapo-admin-script.js', array('jquery'), false, true );

			/* Jquery UI */
			wp_enqueue_style( 'jquery-ui', OVAPO_PLUGIN_URI.'assets/libs/jquery-ui/jquery-ui.min.css' );
			wp_enqueue_script( 'jquery-ui-tabs' );

		}
	}
	new OVAPO_Admin_Assets();
}