<?php

if ( !defined( 'ABSPATH' ) ) {
	exit;
}

class OVAPO_templates_loader {
	
	/**
	 * The Constructor
	 */
	public function __construct() {

		add_filter( 'template_include', array( $this, 'template_loader' ) );
		
	}

	public function template_loader( $template ) {

		$post_type = isset($_REQUEST['post_type'] ) ? esc_html( $_REQUEST['post_type'] ) : get_post_type();
		$type = isset($_REQUEST['type'] ) ? esc_html( $_REQUEST['type'] ) : '';
		$archive_project_type = OVAPO_Settings::archive_project_type();
		$single_project_type = OVAPO_Settings::single_project_type();

		if( is_tax( 'project_cat' ) || get_query_var( 'project_cat' ) != ''  ){

			ovapo_get_template( 'archive-project-cat.php' );
			return false;
		}

		if(  $post_type == 'project' ){

			if ( is_post_type_archive( 'project' ) ) {

				if ( $archive_project_type == 'default' ) {
					if ( $type == 'compact' ) {
						ovapo_get_template( 'archive-project-compact.php' );
						return false;

					} elseif ( $type == 'full' ) {
						ovapo_get_template( 'archive-project-full.php' );
						return false;

					} else {
						ovapo_get_template( 'archive-project-default.php' );
						return false;
					}

				} elseif ( $archive_project_type == 'compact' ) {
					if ($type == 'default') {
						ovapo_get_template( 'archive-project-default.php' );
						return false;

					} elseif ( $type == 'full' ) {
						ovapo_get_template( 'archive-project-full.php' );
						return false;
					} else {
						ovapo_get_template( 'archive-project-compact.php' );
						return false;
					}
					
				} elseif ( $archive_project_type == 'full' ) {
					if ($type == 'default') {
						ovapo_get_template( 'archive-project-default.php' );
						return false;

					} elseif ( $type == 'compact' ) {
						ovapo_get_template( 'archive-project-compact.php' );
						return false;

					} else {
						ovapo_get_template( 'archive-project-full.php' );
						return false;
					}

				}
			}

			elseif ( is_singular( 'project' ) ) {

				if ( $single_project_type == 'type_1' ) {
					if ( $type == 'type_2' ) {
						ovapo_get_template( 'single-project-v2.php' );
						return false;

					} elseif ( $type == 'type_3' ) {
						ovapo_get_template( 'single-project-v3.php' );
						return false;
					} else {
						ovapo_get_template( 'single-project.php' );
						return false;
					}

				} elseif ( $single_project_type == 'type_2' ) {
					if ($type == 'type_1') {
						ovapo_get_template( 'single-project.php' );
						return false;

					} elseif ( $type == 'type_3' ) {
						ovapo_get_template( 'single-project-v3.php' );
						return false;
					} else {
						ovapo_get_template( 'single-project-v2.php' );
						return false;
					}

				} elseif ( $single_project_type == 'type_3' ) {
					if ($type == 'type_1') {
						ovapo_get_template( 'single-project.php' );
						return false;

					} elseif ( $type == 'type_2' ) {
						ovapo_get_template( 'single-project-v2.php' );
						return false;

					} else {
						ovapo_get_template( 'single-project-v3.php' );
						return false;
					}

				}
			}
		}

		if ( $post_type !== 'project'){
			return $template;
		}
	}
}

new OVAPO_templates_loader();