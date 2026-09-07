<?php

if ( !defined( 'ABSPATH' ) ) {
	exit;
}

class OVAPO_Settings {
	
	public static function ovapo_project_post_type_rewrite_slug(){
		$ops = get_option('ovapo_options');
		return isset( $ops['ovapo_project_post_type_rewrite_slug'] ) ? $ops['ovapo_project_post_type_rewrite_slug'] : 'project';
	}
	
	public static function archive_project_orderby(){
		$ops = get_option('ovapo_options');
		return isset( $ops['archive_project_orderby'] ) ? $ops['archive_project_orderby'] : 'date';
	}
	
	public static function archive_project_order(){
		$ops = get_option('ovapo_options');
		return isset( $ops['archive_project_order'] ) ? $ops['archive_project_order'] : 'ASC';
	}
	
	public static function archive_project_posts_per_page(){
		$ops = get_option('ovapo_options');
		return isset( $ops['archive_project_posts_per_page'] ) ? $ops['archive_project_posts_per_page'] : '9';
	}
	
	public static function archive_project_full_posts_per_page(){
		$ops = get_option('ovapo_options');
		return isset( $ops['archive_project_full_posts_per_page'] ) ? $ops['archive_project_full_posts_per_page'] : '8';
	}

	public static function archive_project_type(){
		$ops = get_option('ovapo_options');
		return isset( $ops['archive_project_type'] ) ? $ops['archive_project_type'] : 'default';
	}

	public static function archive_project_header(){
		$ops = get_option('ovapo_options');
		return isset( $ops['archive_project_header'] ) ? $ops['archive_project_header'] : 'default';
	}

	public static function archive_project_footer(){
		$ops = get_option('ovapo_options');
		return isset( $ops['archive_project_footer'] ) ? $ops['archive_project_footer'] : 'default';
	}


	public static function single_project_header(){
		$ops = get_option('ovapo_options');
		return isset( $ops['single_project_header'] ) ? $ops['single_project_header'] : 'default';
	}

	public static function single_project_footer(){
		$ops = get_option('ovapo_options');
		return isset( $ops['single_project_footer'] ) ? $ops['single_project_footer'] : 'default';
	}

	

	public static function single_project_type(){
		$ops = get_option('ovapo_options');
		return isset( $ops['single_project_type'] ) ? $ops['single_project_type'] : 'type_1';
	}
	
}

new OVAPO_Settings();