<?php

if ( !defined( 'ABSPATH' ) ) {
	exit;
}

if( !function_exists( 'ovapo_locate_template' ) ){
	function ovapo_locate_template( $template_name, $template_path = '', $default_path = '' ) {
		
		// Set variable to search in ovacs-templates folder of theme.
		if ( ! $template_path ) :
			$template_path = 'ovapo-templates/';
		endif;

		// Set default plugin templates path.
		if ( ! $default_path ) :
			$default_path = OVAPO_PLUGIN_PATH . 'templates/'; // Path to the template folder
		endif;

		// Search template file in theme folder.
		$template = locate_template( array(
			$template_path . $template_name
			// $template_name
		) );

		// Get plugins template file.
		if ( ! $template ) :
			$template = $default_path . $template_name;
		endif;

		return apply_filters( 'ovapo_locate_template', $template, $template_name, $template_path, $default_path );
	}

}


function ovapo_get_template( $template_name, $args = array(), $tempate_path = '', $default_path = '' ) {
	if ( is_array( $args ) && isset( $args ) ) :
		extract( $args );
endif;
$template_file = ovapo_locate_template( $template_name, $tempate_path, $default_path );
if ( ! file_exists( $template_file ) ) :
	_doing_it_wrong( __FUNCTION__, sprintf( '<code>%s</code> does not exist.', $template_file ), '1.0.0' );
	return;
endif;


include $template_file;
}


function ovapo_pagination_plugin($ovacs_query = null) {

	/** Stop execution if there's only 1 page */
	if($ovacs_query != null){
		if( $ovacs_query->max_num_pages <= 1 )
			return;	
	}else if( $wp_query->max_num_pages <= 1 )
	return;



	$paged = get_query_var( 'paged' ) ? absint( get_query_var( 'paged' ) ) : 1;


	

	if($ovacs_query!=null){
		$max   = intval( $ovacs_query->max_num_pages );
	}else{
		$max   = intval( $wp_query->max_num_pages );	
	}
	

	/** Add current page to the array */
	if ( $paged >= 1 )
		$links[] = $paged;

	/** Add the pages around the current page to the array */
	if ( $paged >= 3 ) {
		$links[] = $paged - 1;
		$links[] = $paged - 2;
	}

	if ( ( $paged + 2 ) <= $max ) {
		$links[] = $paged + 2;
		$links[] = $paged + 1;
	}


	echo wp_kses( __( '<div class="project_pagination"><ul class="pagination">','ova-project' ), true ) . "\n";
	
	/** Previous Post Link */
	if ( get_previous_posts_link() )
		printf( '<li class="prev page-numbers">%s</li>' . "\n", get_previous_posts_link('<i class="arrow_carrot-left"></i>') );
	
	/** Link to first page, plus ellipses if necessary */
	if ( ! in_array( 1, $links ) ) {
		$class = 1 == $paged ? ' class="active"' : '';
		
		printf( '<li%s><a href="%s">%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( 1 ) ), '1' );
		
		if ( ! in_array( 2, $links ) )
			echo wp_kses( __('<li><span class="pagi_dots">...</span></li>', 'ova-project' ) , true);
	}
	
	/** Link to current page, plus 2 pages in either direction if necessary */
	sort( $links );
	foreach ( (array) $links as $link ) {
		$class = $paged == $link ? ' class="active"' : '';
		printf( '<li%s><a href="%s">%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( $link ) ), $link );
	}
	
	/** Link to last page, plus ellipses if necessary */
	if ( ! in_array( $max, $links ) ) {
		if ( ! in_array( $max - 1, $links ) )
			echo wp_kses( __('<li><span class="pagi_dots">...</span></li>', 'ova-project' ) , true) . "\n";
		
		$class = $paged == $max ? ' class="active"' : '';
		printf( '<li%s><a href="%s">%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( $max ) ), $max );
	}
	
	/** Next Post Link */
	if ( get_next_posts_link() )
		printf( '<li class="next page-numbers">%s</li>' . "\n", get_next_posts_link('<i class="arrow_carrot-right"></i>') );
	
	echo wp_kses( __( '</ul></div>', 'ova-project' ), true ) . "\n";
}

/***** Crop Image Thumbnail Archive *****/
function ovapo_custom_thumbs() {
	add_image_size( 'feature_image_vertical', 400, 500, true );
	add_image_size( 'feature_image_project', 640, 480, true );
	add_image_size( 'feature_image_project_2', 640, 328, true );
}
add_action( 'after_setup_theme', 'ovapo_custom_thumbs' );


/***** Posts Per Page Archive *****/
function project_archive_posts_per_page ( $query ) {
	$type = isset($_GET['type']) ? $_GET['type'] : '';
	if ( ! is_admin() ) {
		if ( is_post_type_archive( 'project' ) || is_tax( 'project_cat' ) ) {
			if ($type == 'full' || OVAPO_Settings::archive_project_type() == 'full' ) {
				$query->set('posts_per_page', OVAPO_Settings::archive_project_full_posts_per_page() );
			} else {
				$query->set('posts_per_page', OVAPO_Settings::archive_project_posts_per_page() );
			}
		}
	}
};
add_action( 'pre_get_posts', 'project_archive_posts_per_page' );





add_filter( 'constrau_header_customize', 'ova_project_header_customize', 10, 1 );
function ova_project_header_customize( $header ){


	if( is_tax( 'project_cat' ) ||  get_query_var( 'project_cat' ) != '' || is_post_type_archive( 'project' ) ){

		$header = OVAPO_Settings::archive_project_header();

	}else if( is_singular( 'project' ) ){

		$header = OVAPO_Settings::single_project_header();
		
	}

	return $header;

}




add_filter( 'constrau_footer_customize', 'ova_project_footer_customize', 10, 1 );
function ova_project_footer_customize( $footer ){


	if( is_tax( 'project_cat' ) ||  get_query_var( 'project_cat' ) != '' || is_post_type_archive( 'project' ) ){

		$footer = OVAPO_Settings::archive_project_footer();

	}else if( is_singular( 'project' ) ){

		$footer = OVAPO_Settings::single_project_footer();
		
	}

	return $footer;

}


