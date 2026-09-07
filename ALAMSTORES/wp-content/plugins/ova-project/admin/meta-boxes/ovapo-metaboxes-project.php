<?php 

if( !defined( 'ABSPATH' ) ) exit();

if( !class_exists( 'OVAPO_metaboxes_render_project' ) ){

	class OVAPO_metaboxes_render_project{

		public static function render(){

			require_once( OVAPO_PLUGIN_PATH.'/admin/views/ovapo-metabox-project.php' );

		}

		public static function save($post_id, $post_data){
			
			if( empty($post_data) ) exit();

			// Checked Special
			if( array_key_exists('ovapo_featured', $post_data) == false ){
				$post_data['ovapo_featured'] = '';
			}else{
				$post_data['ovapo_featured'] = 'checked';
			}
			
			/* Check Gallery exits */
			if( !$post_data['ovapo_gallery_id'] ){
				$post_data['ovapo_gallery_id'] = '';
			}
			foreach ($post_data as $key => $value) {
				
				update_post_meta( $post_id, $key, $value );
			}
		}
	}
}
?>