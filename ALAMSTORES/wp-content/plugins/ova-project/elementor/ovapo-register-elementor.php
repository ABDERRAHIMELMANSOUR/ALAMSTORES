<?php

namespace ovapo_elementor;

use ovapo_elementor\widgets\ovapo_project_grid;
use ovapo_elementor\widgets\ovapo_project_slide;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class OVAPO_Register_Elementor {

	public function __construct() {
		$this->add_actions();
	}

	private function add_actions() {

     	// Register Ovatheme Category in Pane
		add_action( 'elementor/elements/categories_registered', array( $this, 'add_ovatheme_category' ) );
		
		add_action( 'elementor/widgets/widgets_registered', [ $this, 'on_widgets_registered' ] );
		
	}
	
	public function add_ovatheme_category(  ) {

		\Elementor\Plugin::instance()->elements_manager->add_category(
			'ovatheme',
			[
				'title' => __( 'Ovatheme', 'ova-project' ),
				'icon' => 'fa fa-plug',
			]
		);
	}

	public function on_widgets_registered() {
		$this->includes();
		$this->register_widget();
	}

	private function includes() {
		
		require OVAPO_PLUGIN_PATH . 'elementor/widgets/ovapo_project_grid.php';
		require OVAPO_PLUGIN_PATH . 'elementor/widgets/ovapo_project_slide.php';
		
	}

	private function register_widget() {

		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new ovapo_project_grid() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new ovapo_project_slide() );

	}

}

new OVAPO_Register_Elementor();