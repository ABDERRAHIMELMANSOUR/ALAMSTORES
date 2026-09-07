<?php

namespace ova_framework;

use ova_framework\widgets\ova_menu;
use ova_framework\widgets\ova_logo;
use ova_framework\widgets\ova_header;


use ova_framework\widgets\ova_contact_info;
use ova_framework\widgets\ova_social;
use ova_framework\widgets\ova_search_popup;
use ova_framework\widgets\Ova_Menu_Cart;
use ova_framework\widgets\ova_language;

use ova_framework\widgets\ova_blog;
use ova_framework\widgets\ova_blog_slider;
use ova_framework\widgets\ova_skill_bar;
use ova_framework\widgets\ova_heading;
use ova_framework\widgets\ova_testimonial;
use ova_framework\widgets\ova_contact;
use ova_framework\widgets\ova_contact_v2;
use ova_framework\widgets\ova_history;
use ova_framework\widgets\ova_about_number;
use ova_framework\widgets\ova_video_popup;
use ova_framework\widgets\ova_icon_counter;
use ova_framework\widgets\ova_feature;
use ova_framework\widgets\ova_feature_v2;
use ova_framework\widgets\ova_position;
use ova_framework\widgets\ova_partner;
use ova_framework\widgets\ova_teamwork;
use ova_framework\widgets\ova_services_grid;
use ova_framework\widgets\ova_services_slide;
use ova_framework\widgets\ova_services_image;
use ova_framework\widgets\ova_process;
use ova_framework\widgets\ova_our_skill;
use ova_framework\widgets\ova_industry_solution;
use ova_framework\widgets\ova_slideshow;
use ova_framework\widgets\ova_icon_landing_page;
use ova_framework\widgets\ova_slide_item_landing;

use ova_framework\widgets\ova_about_team;



if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly




/**
 * Main Plugin Class
 *
 * Register new elementor widget.
 *
 * @since 1.0.0
 */
class Ova_Register_Elementor {

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function __construct() {
		$this->add_actions();
	}

	/**
	 * Add Actions
	 *
	 * @since 1.0.0
	 *
	 * @access private
	 */
	private function add_actions() {

		// Register Header Footer Category in Pane
	    add_action( 'elementor/elements/categories_registered', array( $this, 'add_hf_category' ) );

	     // Register Ovatheme Category in Pane
	    add_action( 'elementor/elements/categories_registered', array( $this, 'add_ovatheme_category' ) );
	    
		
		add_action( 'elementor/widgets/widgets_registered', [ $this, 'on_widgets_registered' ] );
		

	}

	
	public  function add_hf_category(  ) {
	    \Elementor\Plugin::instance()->elements_manager->add_category(
	        'hf',
	        [
	            'title' => __( 'Header Footer', 'ova-framework' ),
	            'icon' => 'fa fa-plug',
	        ]
	    );

	}

	
	public function add_ovatheme_category(  ) {

	    \Elementor\Plugin::instance()->elements_manager->add_category(
	        'ovatheme',
	        [
	            'title' => __( 'Ovatheme', 'ova-framework' ),
	            'icon' => 'fa fa-plug',
	        ]
	    );

	}


	/**
	 * On Widgets Registered
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function on_widgets_registered() {
		$this->includes();
		$this->register_widget();
	}

	/**
	 * Includes
	 *
	 * @since 1.0.0
	 *
	 * @access private
	 */
	private function includes() {
		
		require OVA_PLUGIN_PATH . 'ova-elementor/widgets/ova-menu.php';
		require OVA_PLUGIN_PATH . 'ova-elementor/widgets/ova-logo.php';
		require OVA_PLUGIN_PATH . 'ova-elementor/widgets/ova-header.php';


		require OVA_PLUGIN_PATH . 'ova-elementor/widgets/ova-contact-info.php';
		require OVA_PLUGIN_PATH . 'ova-elementor/widgets/ova-social.php';
		require OVA_PLUGIN_PATH . 'ova-elementor/widgets/ova-search-popup.php';
		require OVA_PLUGIN_PATH . 'ova-elementor/widgets/ova-menu-cart.php';
		require OVA_PLUGIN_PATH . 'ova-elementor/widgets/ova-language.php';

		require OVA_PLUGIN_PATH . 'ova-elementor/widgets/ova_blog.php';
		require OVA_PLUGIN_PATH . 'ova-elementor/widgets/ova_blog_slider.php';
		require OVA_PLUGIN_PATH . 'ova-elementor/widgets/ova_skill_bar.php';
		require OVA_PLUGIN_PATH . 'ova-elementor/widgets/ova_heading.php';
		require OVA_PLUGIN_PATH . 'ova-elementor/widgets/ova_testimonial.php';
		require OVA_PLUGIN_PATH . 'ova-elementor/widgets/ova_contact.php';
		require OVA_PLUGIN_PATH . 'ova-elementor/widgets/ova_contact_v2.php';
		require OVA_PLUGIN_PATH . 'ova-elementor/widgets/ova_history.php';
		require OVA_PLUGIN_PATH . 'ova-elementor/widgets/ova_about_number.php';
		require OVA_PLUGIN_PATH . 'ova-elementor/widgets/ova_video_popup.php';
		require OVA_PLUGIN_PATH . 'ova-elementor/widgets/ova_icon_counter.php';
		require OVA_PLUGIN_PATH . 'ova-elementor/widgets/ova_feature.php';
		require OVA_PLUGIN_PATH . 'ova-elementor/widgets/ova_feature_v2.php';
		require OVA_PLUGIN_PATH . 'ova-elementor/widgets/ova_position.php';
		require OVA_PLUGIN_PATH . 'ova-elementor/widgets/ova_partner.php';
		require OVA_PLUGIN_PATH . 'ova-elementor/widgets/ova_teamwork.php';
		require OVA_PLUGIN_PATH . 'ova-elementor/widgets/ova_services_grid.php';
		require OVA_PLUGIN_PATH . 'ova-elementor/widgets/ova_services_slide.php';
		require OVA_PLUGIN_PATH . 'ova-elementor/widgets/ova_services_image.php';
		require OVA_PLUGIN_PATH . 'ova-elementor/widgets/ova_process.php';
		require OVA_PLUGIN_PATH . 'ova-elementor/widgets/ova_our_skill.php';
		require OVA_PLUGIN_PATH . 'ova-elementor/widgets/ova_industry_solution.php';
		require OVA_PLUGIN_PATH . 'ova-elementor/widgets/ova_slideshow.php';
		require OVA_PLUGIN_PATH . 'ova-elementor/widgets/ova_icon_landing_page.php';
		require OVA_PLUGIN_PATH . 'ova-elementor/widgets/ova_slide_item_landing.php';

		require OVA_PLUGIN_PATH . 'ova-elementor/widgets/ova-about-team.php';
		
		
	}

	/**
	 * Register Widget
	 *
	 * @since 1.0.0
	 *
	 * @access private
	 */
	private function register_widget() {

		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new ova_menu() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new ova_logo() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new ova_header() );


		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new ova_contact_info() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new ova_social() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new ova_search_popup() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Ova_Menu_Cart() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new ova_language() );

		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new ova_blog() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new ova_blog_slider() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new ova_skill_bar() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new ova_heading() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new ova_testimonial() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new ova_contact() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new ova_contact_v2() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new ova_history() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new ova_about_number() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new ova_video_popup() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new ova_icon_counter() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new ova_feature() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new ova_feature_v2() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new ova_position() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new ova_partner() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new ova_teamwork() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new ova_services_grid() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new ova_services_slide() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new ova_services_image() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new ova_process() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new ova_our_skill() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new ova_industry_solution() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new ova_slideshow() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new ova_icon_landing_page() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new ova_slide_item_landing() );

		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new ova_about_team() );

		
	}
	    
	

}

new Ova_Register_Elementor();





