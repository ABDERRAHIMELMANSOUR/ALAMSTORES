<?php
add_action('wp_enqueue_scripts', 'constrau_theme_scripts_styles');
add_action('wp_enqueue_scripts', 'constrau_theme_script_default');


function constrau_theme_scripts_styles() {

    // enqueue the javascript that performs in-link comment reply fanciness
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' ); 
	}
	
	/* Add Javascript  */
	wp_enqueue_script( 'bootstrap', CONSTRAU_URI.'/assets/libs/bootstrap/js/bootstrap.bundle.min.js' , array( 'jquery' ), null, true );
	wp_enqueue_script( 'select2', CONSTRAU_URI.'/assets/libs/select2/select2.min.js' , array( 'jquery' ), null, true );
	wp_enqueue_script( 'video-popup', CONSTRAU_URI.'/assets/libs/video.popup.js', array('jquery'), false, true );

    // Load js when product detail
	if( is_singular( 'product' ) ){
		if( is_ssl() ){
			wp_enqueue_script('prettyphoto', CONSTRAU_URI.'/assets/libs/prettyphoto/jquery.prettyPhoto_https.js', array('jquery'),null,true);  
		}else{
			wp_enqueue_script('prettyphoto', CONSTRAU_URI.'/assets/libs/prettyphoto/jquery.prettyPhoto.js', array('jquery'),null,true);
		}
		wp_enqueue_style('prettyphoto', CONSTRAU_URI.'/assets/libs/prettyphoto/css/prettyPhoto.css', array(), null);
	}

	if( is_singular( 'product' ) )  {
		wp_enqueue_script('owl-carousel', CONSTRAU_URI.'/assets/libs/owl-carousel/owl.carousel.min.js', array('jquery'),null,true);
		wp_enqueue_style('owl-carousel', CONSTRAU_URI.'/assets/libs/owl-carousel/assets/owl.carousel.min.css', array(), null);
	}


	wp_enqueue_script('constrau-script', CONSTRAU_URI.'/assets/js/script.js', array('jquery'),null,true);

	/* Add Css  */
	wp_enqueue_style('bootstrap', CONSTRAU_URI.'/assets/libs/bootstrap/css/bootstrap.min.css', array(), null);

	wp_enqueue_style('flaticon-arrows', CONSTRAU_URI.'/assets/libs/flaticon/arrows/flaticon.css', array(), null);
	wp_enqueue_style('flaticon-construction', CONSTRAU_URI.'/assets/libs/flaticon/construction/flaticon.css', array(), null);
	wp_enqueue_style('flaticon-construction-icons', CONSTRAU_URI.'/assets/libs/flaticon/construction-icons/flaticon.css', array(), null);
	wp_enqueue_style('flaticon-construction-industry', CONSTRAU_URI.'/assets/libs/flaticon/construction-industry/flaticon.css', array(), null);
	wp_enqueue_style('flaticon-construction-line-craft', CONSTRAU_URI.'/assets/libs/flaticon/construction-line-craft/flaticon.css', array(), null);
	wp_enqueue_style('flaticon-contact', CONSTRAU_URI.'/assets/libs/flaticon/contact/flaticon.css', array(), null);
	wp_enqueue_style('flaticon-labor-day', CONSTRAU_URI.'/assets/libs/flaticon/labor-day/flaticon.css', array(), null);
	wp_enqueue_style('flaticon-motivation', CONSTRAU_URI.'/assets/libs/flaticon/motivation/flaticon.css', array(), null);
	wp_enqueue_style('flaticon-pointers', CONSTRAU_URI.'/assets/libs/flaticon/pointers/flaticon.css', array(), null);
	wp_enqueue_style('flaticon-productivity-icons', CONSTRAU_URI.'/assets/libs/flaticon/productivity-icons/flaticon.css', array(), null);
	

	wp_enqueue_style( 'select2', CONSTRAU_URI. '/assets/libs/select2/select2.min.css', array(), null );

	wp_enqueue_style('v4-shims', CONSTRAU_URI.'/assets/libs/fontawesome/css/v4-shims.min.css', array(), null);
	wp_enqueue_style('fontawesome', CONSTRAU_URI.'/assets/libs/fontawesome/css/all.min.css', array(), null);
	wp_enqueue_style('elegant-font', CONSTRAU_URI.'/assets/libs/elegant_font/ele_style.css', array(), null);
	wp_enqueue_style( 'material-icon', CONSTRAU_URI.'/assets/libs/material-design-iconic-font/css/material-design-iconic-font.min.css', array(), null);    
	
	
	wp_enqueue_style('constrau-theme', CONSTRAU_URI.'/assets/css/theme.css', array(), null);

	

}

function constrau_theme_script_default(){

	if ( is_child_theme() ) {
		wp_enqueue_style( 'constrau-parent-style', trailingslashit( get_template_directory_uri() ) . 'style.css', array(), null );
	}

	wp_enqueue_style( 'constrau-style', get_stylesheet_uri(), array(), null );
}