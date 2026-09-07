<?php

class ovaframework_hooks {

	public function __construct() {

		/* Add Widget */
		add_action( 'widgets_init', array( $this,'ovaframework_widgets_services') );

		add_action( 'after_setup_theme', array( $this,'ova_add_image_size' ) );

		// Customize Menu Structures
		add_filter( 'wp_nav_menu_args', array( $this, 'ova_prefix_modify_nav_menu_args' ) );
		
		// Share Social in Single Post
		add_filter( 'ova_share_social', array( $this, 'constrau_content_social' ), 2, 10 );

		// Allow add font class to title of widget
		add_filter( 'widget_title', array( $this, 'ova_html_widget_title' ) );

		add_filter( 'widget_text', 'do_shortcode' );

		add_filter( 'upload_mimes', array( $this, 'ova_upload_mimes' ), 1, 10);

		/* Filter Animation Elementor */
		add_filter( 'elementor/controls/animations/additional_animations', array( $this, 'ova_add_animations'), 10 , 0 );

	}

	/* Filter Walker for all menu */
	public function ova_prefix_modify_nav_menu_args( $args ) {
		return array_merge( $args, array(
			'fallback_cb' => 'Ova_Walker_Menu::fallback',
			'walker' 	=> new Ova_Walker_Menu()
		) );
	}

	public function constrau_content_social( $link, $title ) {
		$html = '<ul class="share-social-icons clearfix">

		<li><a class="share-ico ico-facebook" target="_blank" href="http://www.facebook.com/sharer.php?u='.$link.'"><i class="fa fa-facebook"></i></a></li>

		<li><a class="share-ico ico-twitter" target="_blank" href="https://twitter.com/share?url='.$link.'"><i class="fab fa-twitter"></i></a></li>

		<li><a class="share-ico ico-pinterest" target="_blank" href="http://www.pinterest.com/pin/create/button/?url='.$link.'"><i class="fa fa-pinterest-p" ></i></a></li>

		<li><a class="share-ico ico-mail" href="mailto:?body='.$link.'"><i class="fa fa-envelope-o"></i></a></li>                                


		</ul>';
		return $html;
	}

	public function ova_upload_mimes($mimes){
		$mimes['zip'] = 'application/zip';
		$mimes['svg'] = 'image/svg+xml';
		return $mimes;
	}


	// Filter class in widget title
	public function ova_html_widget_title( $title ) {
		$title = str_replace( '{{', '<i class="', $title );
		$title = str_replace( '}}', '"></i>', $title );
		return $title;
	}

	public function ova_add_animations(){
		$animations = array(
			'OvaTheme' => array(
				'ova-move-up' 		=> esc_html__('Move Up', 'ova-framework'),
				'ova-move-down' 	=> esc_html__( 'Move Down', 'ova-framework' ),
				'ova-move-left'     => esc_html__('Move Left', 'ova-framework'),
				'ova-move-right'    => esc_html__('Move Right', 'ova-framework'),
				'ova-scale-up'      => esc_html__('Scale Up', 'ova-framework'),
				'ova-flip'          => esc_html__('Flip', 'ova-framework'),
				'ova-helix'         => esc_html__('Helix', 'ova-framework'),
				'ova-popup'			=> esc_html__( 'PopUp','ova-framework' )
			),
		);

		return $animations;
	}

	public function ovaframework_widgets_services() {
		$args_service = array(
			'name' => esc_html__( 'Services Sidebar', 'ova-framework'),
			'id' => "services-sidebar",
			'description' => esc_html__( 'Services Sidebar', 'ova-framework' ),
			'class' => '',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' => "</div>",
			'before_title' => '<h4 class="widget-title second_font">',
			'after_title' => "</h4>",
		);
		register_sidebar( $args_service );
	}

	public function ova_add_image_size() {
		add_image_size( 'services_detail', 640, 400, true );
	}

}

new ovaframework_hooks();