<?php 

if( !defined( 'ABSPATH' ) ) exit();


if( !class_exists( 'OVAPO_admin' ) ){

	/**
	 * Make Admin Class
	 */
	class OVAPO_admin{
		public static $custom_meta_fields = array();

		/**
		 * Construct Admin
		 */
		public function __construct(){
			$this->init();

		}

		public function init(){
			require_once( OVAPO_PLUGIN_PATH. '/admin/ovapo-admin-menu.php' );
			require_once( OVAPO_PLUGIN_PATH. '/admin/ovapo-admin-assets.php' );
			require_once( OVAPO_PLUGIN_PATH. '/admin/ovapo-admin-settings.php' );
		}
	}
	new OVAPO_admin();

}