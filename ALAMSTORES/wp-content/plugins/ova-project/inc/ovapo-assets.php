<?php 
defined( 'ABSPATH' ) || exit();

if( !class_exists( 'OVAPO_Assets' ) ){
	class OVAPO_Assets{

		public function __construct(){

			add_action( 'wp_enqueue_scripts', array( $this, 'ovapo_enqueue_style_script' ) );

			/* Add JS for Elementor */
			add_action( 'elementor/frontend/after_register_scripts', array( $this, 'ova_enqueue_scripts_elementor_project' ) );

		}

		public function ovapo_enqueue_style_script(){

			/* Add CSS */
			if (is_singular( 'project' )) {
				wp_enqueue_style( 'slick-style', OVAPO_PLUGIN_URI.'assets/libs/slick/slick/slick.css', array(), null );
				wp_enqueue_style( 'slick-theme-style', OVAPO_PLUGIN_URI.'assets/libs/slick/slick/slick-theme.css', array(), null );
			}
			
			
			wp_enqueue_style( 'ovapo-style', OVAPO_PLUGIN_URI.'assets/css/frontend/ovapo-style.css', array(), null );

			/* Add JS */
			if (is_singular( 'project' )) {
				wp_enqueue_script( 'slick-script', OVAPO_PLUGIN_URI.'assets/libs/slick/slick/slick.min.js', array('jquery'), false, true );
			}

			wp_enqueue_script( 'ovapo-script', OVAPO_PLUGIN_URI.'assets/js/ovapo-script.js', array('jquery'), false, true );
			wp_localize_script( 'ovapo-script', 'ajax_object', array('ajax_url' => admin_url('admin-ajax.php')) );

		}

		/* Add JS for elementor */
		public function ova_enqueue_scripts_elementor_project(){
			wp_enqueue_script( 'elementor-project-script', OVAPO_PLUGIN_URI. 'assets/js/script-elementor.js', [ 'jquery' ], false, true );
		}
		
	}
	new OVAPO_Assets();
}