<?php

if ( !defined( 'ABSPATH' ) ) {
	exit;
}

class OVAPO_get_data {
	public function __construct() {

		add_filter( 'OVAPO_project', array( $this, 'OVAPO_project' ), 10, 0 );
		add_filter( 'OVAPO_project_load_more', array( $this, 'OVAPO_project_load_more' ), 10, 0 );
		// add_filter( 'OVAPO_project_full', array( $this, 'OVAPO_project_full' ), 10, 0 );

	}

	private function OVAPO_query_base( $paged = '', $order = 'ASC', $orderby = 'date' ){

		$args_base = $args_paged = $args_cat = $args_orderby = array();

		$args_base = array(
			'post_type'   => 'project',
			'post_status' => 'publish',
			'order'       => $order,
		);

		if( is_tax( 'project_cat' ) ||  get_query_var( 'project_cat' ) != '' ){
			$args_cat = array( 
				'tax_query' => array(
					array(
						'taxonomy' => 'project_cat',
						'field'    => 'slug',
						'terms'    => get_query_var( 'project_cat' ),
					)
				)
			);
		}

		$args_paged = ( $paged != '' ) ? array( 'paged' => $paged ) : array();
		
		switch ($orderby) {
			case 'title':
			$args_orderby =  array( 'orderby' => 'title' );
			break;

			case 'ID':
			$args_orderby =  array( 'orderby' => 'ID');
			break;
			
			case 'date':
			$args_orderby =  array( 'orderby' => 'date');
			break;
			
			default:
			break;
		}
		return array_merge_recursive( $args_base, $args_paged, $args_cat, $args_orderby );
	}

	public function OVAPO_project(){

		$paged   = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
		$order   = OVAPO_Settings::archive_project_order();
		$orderby = OVAPO_Settings::archive_project_orderby();

		/* Query base */
		$args_basic = $this->OVAPO_query_base( $paged, $order, $orderby );

		$arg = new WP_Query( $args_basic );
		
		return $arg;
	}

	public function OVAPO_project_load_more(){

		$paged   = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
		$order   = OVAPO_Settings::archive_project_order();
		$orderby = OVAPO_Settings::archive_project_orderby();

		/* Query base */
		$args_basic = $this->OVAPO_query_base( $paged, $order, $orderby );
		$arg = new WP_Query( $args_basic );

		return $arg;
	}

}
new OVAPO_get_data();