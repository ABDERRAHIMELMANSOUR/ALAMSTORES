<?php
defined( 'ABSPATH' ) || exit();

if( !class_exists( 'OVAPO_admin_menu' ) ){

	class OVAPO_admin_menu{

		public function __construct(){
			$this->init();
		}

		public function init(){
			add_action( 'admin_menu', array( $this, 'OVAPO_register_menu' ) );
		}

		public function OVAPO_register_menu(){

			// Get Options
			
			add_menu_page( 
				__( 'Project', 'ova-project' ), 
				__( 'Project', 'ova-project' ), 
				'edit_posts',
				'ovapo-menu', 
				null,
				'dashicons-portfolio', 
				4
			);

			add_submenu_page( 
				'ovapo-menu', 
				__( 'Add New', 'ova-project' ), 
				__( 'Add New', 'ova-project' ), 
				'administrator',
				'post-new.php?post_type=project'
			);

			add_submenu_page( 
				'ovapo-menu', 
				__( 'Categories', 'ova-project' ), 
				__( 'Categories', 'ova-project' ), 
				'administrator',
				'edit-tags.php?taxonomy=project_cat'.'&post_type=project'
			);
			
			add_submenu_page( 
				'ovapo-menu', 
				__( 'Settings', 'ova-project' ),
				__( 'Settings', 'ova-project' ),
				'administrator',
				'ovapo_general_settings',
				array( 'OVAPO_Admin_Settings', 'create_admin_setting_page' )
			);

		}

	}
	new OVAPO_admin_menu();

}