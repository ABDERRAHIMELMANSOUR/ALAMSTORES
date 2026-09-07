<?php
	if(defined('CONSTRAU_URL') 	== false) 	define('CONSTRAU_URL', get_template_directory());
	if(defined('CONSTRAU_URI') 	== false) 	define('CONSTRAU_URI', get_template_directory_uri());

	load_theme_textdomain( 'constrau', CONSTRAU_URL . '/languages' );
	
	// require libraries, function
	require( CONSTRAU_URL.'/inc/init.php' );

	// Add js, css
	require( CONSTRAU_URL.'/extend/add_js_css.php' );
	
	// require walker menu
	require_once (CONSTRAU_URL.'/inc/ova_walker_nav_menu.php');
	

	// register menu, widget
	require( CONSTRAU_URL.'/extend/register_menu_widget.php' );

	// require content
	require_once (CONSTRAU_URL.'/content/define_blocks_content.php');
	
	// require breadcrumbs
	require( CONSTRAU_URL.'/extend/breadcrumbs.php' );

	// Hooks
	require( CONSTRAU_URL.'/inc/class_hook.php' );

	
	/* Customize */
	//  include plugin.php to use is_plugin_active()
	if( current_user_can('customize') ){
	    require_once CONSTRAU_URL.'/customize/custom-control/google-font.php';
	    require_once CONSTRAU_URL.'/customize/custom-control/heading.php';
	    require_once CONSTRAU_URL.'/customize/class-customize.php';
	}
	
    require_once CONSTRAU_URL.'/customize/render-style.php';
	
	// Require metabox
	if( is_admin() ){
		// Require TGM
		require_once ( CONSTRAU_URL.'/install_resource/active_plugins.php' );		
	}