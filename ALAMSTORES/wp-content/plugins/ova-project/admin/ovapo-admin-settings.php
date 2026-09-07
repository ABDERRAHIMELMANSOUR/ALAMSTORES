<?php 

if( !defined( 'ABSPATH' ) ) exit();


if( !class_exists( 'OVAPO_Admin_Settings' ) ){

	/**
	 * Make Admin Class
	 */
	class OVAPO_Admin_Settings{

		/**
		 * Construct Admin
		 */
		public function __construct(){

			add_action( 'admin_enqueue_scripts', array( $this, 'ovapo_load_media' ) );
			add_action( 'admin_init', array( $this, 'register_options' ) );

		}


		public function ovapo_load_media() {
			wp_enqueue_media();
		}

		public function print_options_section(){
			return true;
		}

		public function register_options(){

			register_setting(
				'ovapo_options_group', // Option group
				'ovapo_options', // Name Option
				array( $this, 'settings_callback' ) // Call Back
			);

			/**
			 * General Settings
			 */
			add_settings_section(
				'ovapo_general_section_id', // ID
				esc_html__('General Setting', 'ova-project'), // Title
				array( $this, 'print_options_section' ),
				'ovapo_general_settings' // Page
			);

			add_settings_field(
				'ovapo_project_post_type_rewrite_slug', // ID
				esc_html__('Rewrite Slug','ova-project'),
				array( $this, 'ovapo_project_post_type_rewrite_slug' ),
				'ovapo_general_settings', // Page
				'ovapo_general_section_id' // Section ID
			);

			add_settings_field(
				'archive_project_orderby', // ID
				esc_html__('Orderby','ova-project'),
				array( $this, 'archive_project_orderby' ),
				'ovapo_general_settings', // Page
				'ovapo_general_section_id' // Section ID
			);

			add_settings_field(
				'archive_project_order', // ID
				esc_html__('Order','ova-project'),
				array( $this, 'archive_project_order' ),
				'ovapo_general_settings', // Page
				'ovapo_general_section_id' // Section ID
			);

			add_settings_field(
				'archive_project_posts_per_page', // ID
				esc_html__('Posts Per Page Archive','ova-project'),
				array( $this, 'archive_project_posts_per_page' ),
				'ovapo_general_settings', // Page
				'ovapo_general_section_id' // Section ID
			);

			add_settings_field(
				'archive_project_full_posts_per_page', // ID
				esc_html__('Posts Per Page Archive Full','ova-project'),
				array( $this, 'archive_project_full_posts_per_page' ),
				'ovapo_general_settings', // Page
				'ovapo_general_section_id' // Section ID
			);

			add_settings_field(
				'archive_project_type', // ID
				esc_html__('Archive Type','ova-project'),
				array( $this, 'archive_project_type' ),
				'ovapo_general_settings', // Page
				'ovapo_general_section_id' // Section ID
			);

			add_settings_field(
				'archive_project_header', // ID
				esc_html__('Archive header','ova-project'),
				array( $this, 'archive_project_header' ),
				'ovapo_general_settings', // Page
				'ovapo_general_section_id' // Section ID
			);

			add_settings_field(
				'archive_project_footer', // ID
				esc_html__('Archive footer','ova-project'),
				array( $this, 'archive_project_footer' ),
				'ovapo_general_settings', // Page
				'ovapo_general_section_id' // Section ID
			);

			add_settings_field(
				'single_project_type', // ID
				esc_html__('Single Type','ova-project'),
				array( $this, 'single_project_type' ),
				'ovapo_general_settings', // Page
				'ovapo_general_section_id' // Section ID
			);

			add_settings_field(
				'single_project_header', // ID
				esc_html__('Single header','ova-project'),
				array( $this, 'single_project_header' ),
				'ovapo_general_settings', // Page
				'ovapo_general_section_id' // Section ID
			);

			add_settings_field(
				'single_project_footer', // ID
				esc_html__('Single footer','ova-project'),
				array( $this, 'single_project_footer' ),
				'ovapo_general_settings', // Page
				'ovapo_general_section_id' // Section ID
			);

		}

		public function settings_callback( $input ){

			$new_input = array();

			if( isset( $input['ovapo_project_post_type_rewrite_slug'] ) )
				$new_input['ovapo_project_post_type_rewrite_slug'] = sanitize_text_field( $input['ovapo_project_post_type_rewrite_slug'] ) ? sanitize_text_field( $input['ovapo_project_post_type_rewrite_slug'] ) : 'project';

			if( isset( $input['archive_project_orderby'] ) )
				$new_input['archive_project_orderby'] = sanitize_text_field( $input['archive_project_orderby'] ) ? sanitize_text_field( $input['archive_project_orderby'] ) : 'date';

			if( isset( $input['archive_project_order'] ) )
				$new_input['archive_project_order'] = sanitize_text_field( $input['archive_project_order'] ) ? sanitize_text_field( $input['archive_project_order'] ) : 'ASC';

			if( isset( $input['archive_project_order'] ) )
				$new_input['archive_project_order'] = sanitize_text_field( $input['archive_project_order'] ) ? sanitize_text_field( $input['archive_project_order'] ) : 'ASC';

			if( isset( $input['archive_project_posts_per_page'] ) )
				$new_input['archive_project_posts_per_page'] = sanitize_text_field( $input['archive_project_posts_per_page'] ) ? sanitize_text_field( $input['archive_project_posts_per_page'] ) : '9';

			if( isset( $input['archive_project_full_posts_per_page'] ) )
				$new_input['archive_project_full_posts_per_page'] = sanitize_text_field( $input['archive_project_full_posts_per_page'] ) ? sanitize_text_field( $input['archive_project_full_posts_per_page'] ) : '8';

			if( isset( $input['archive_project_type'] ) )
				$new_input['archive_project_type'] = sanitize_text_field( $input['archive_project_type'] ) ? sanitize_text_field( $input['archive_project_type'] ) : 'default';



			if( isset( $input['archive_project_header'] ) )
				$new_input['archive_project_header'] = sanitize_text_field( $input['archive_project_header'] ) ? sanitize_text_field( $input['archive_project_header'] ) : 'default';

			if( isset( $input['archive_project_footer'] ) )
				$new_input['archive_project_footer'] = sanitize_text_field( $input['archive_project_footer'] ) ? sanitize_text_field( $input['archive_project_footer'] ) : 'default';

			

			if( isset( $input['single_project_type'] ) )
				$new_input['single_project_type'] = sanitize_text_field( $input['single_project_type'] ) ? sanitize_text_field( $input['single_project_type'] ) : 'type_1';

			if( isset( $input['single_project_header'] ) )
				$new_input['single_project_header'] = sanitize_text_field( $input['single_project_header'] ) ? sanitize_text_field( $input['single_project_header'] ) : 'default';

			if( isset( $input['single_project_footer'] ) )
				$new_input['single_project_footer'] = sanitize_text_field( $input['single_project_footer'] ) ? sanitize_text_field( $input['single_project_footer'] ) : 'default';

			return $new_input;
		}


		public static function create_admin_setting_page() { ?>
			<div class="wrap">
				<h1><?php esc_html_e( "Project Settings", 'ova-project' ); ?></h1>

				<form method="post" action="options.php">
					<div id="tabs">
						<?php settings_fields( 'ovapo_options_group' ); // Options group ?>

						<!-- Menu Tab -->
						<ul>
							<li><a href="#ovapo_general_settings"><?php esc_html_e( 'General Settings', 'ova-project' ); ?></a></li>
						</ul>

						<!-- General Tab Content -->  
						<div id="ovapo_general_settings" class="ovapo_admin_settings">
							<?php do_settings_sections( 'ovapo_general_settings' ); // Page ?>
						</div>

					</div>
					<?php submit_button(); ?>
				</form>
			</div>
		<?php }

		/***** General Settings *****/
		public function ovapo_project_post_type_rewrite_slug(){
			$ovapo_project_post_type_rewrite_slug = esc_attr( OVAPO_Settings::ovapo_project_post_type_rewrite_slug() );
			printf(
				'<input type="text"  id="ovapo_project_post_type_rewrite_slug" name="ovapo_options[ovapo_project_post_type_rewrite_slug]" value="%s" />',
				isset( $ovapo_project_post_type_rewrite_slug ) ? $ovapo_project_post_type_rewrite_slug : 'project'
			);
			echo '<span >'.esc_html__(' Name should only contain lowercase letters and the underscore character, and not be more than 32 characters long and  without any spaces', 'ova-project').'<span>';
		}

		public function archive_project_orderby(){
			$archive_project_orderby = OVAPO_Settings::archive_project_orderby();
			$archive_project_orderby = isset( $archive_project_orderby ) ? $archive_project_orderby : 'date';
			
			$title       = ( 'title' == $archive_project_orderby) ? 'selected' : '';
			$date        = ( 'date' == $archive_project_orderby) ? 'selected' : '';
			$id          = ( 'ID' == $archive_project_orderby) ? 'selected' : '';

			?>
			<select name="ovapo_options[archive_project_orderby]" id="archive_project_orderby">
				<option <?php echo esc_attr($title) ?> value="title"><?php echo esc_html__('Title', 'ova-project') ?></option>
				<option <?php echo esc_attr($date) ?> value="date"><?php echo esc_html__('Date', 'ova-project') ?></option>
				<option <?php echo esc_attr($id) ?> value="ID"><?php echo esc_html__('ID', 'ova-project') ?></option>
			</select>
			<?php
		}

		public function archive_project_order(){
			$archive_project_order = OVAPO_Settings::archive_project_order(); 	
			$archive_project_order = isset( $archive_project_order ) ? $archive_project_order : 'ASC';

			$asc_selected  = ('ASC' == $archive_project_order) ? 'selected' : '';
			$desc_selected = ('DESC' == $archive_project_order) ? 'selected' : '';

			?>
			<select name="ovapo_options[archive_project_order]" id="archive_project_order">
				<option <?php echo esc_attr($asc_selected) ?> value  ="ASC"><?php echo esc_html__('Increase', 'ova-project') ?></option>
				<option <?php echo esc_attr($desc_selected) ?> value ="DESC"><?php echo esc_html__('Decrease', 'ova-project') ?></option>
			</select>
			<?php
		}

		public function archive_project_posts_per_page(){
			$archive_project_posts_per_page = esc_attr( OVAPO_Settings::archive_project_posts_per_page() );
			printf(
				'<input type="number"  id="archive_project_posts_per_page" name="ovapo_options[archive_project_posts_per_page]" value="%s" />',
				isset( $archive_project_posts_per_page ) ? $archive_project_posts_per_page : '9'
			);
		}

		public function archive_project_full_posts_per_page(){
			$archive_project_full_posts_per_page = esc_attr( OVAPO_Settings::archive_project_full_posts_per_page() );
			printf(
				'<input type="number"  id="archive_project_full_posts_per_page" name="ovapo_options[archive_project_full_posts_per_page]" value="%s" />',
				isset( $archive_project_full_posts_per_page ) ? $archive_project_full_posts_per_page : '8'
			);
		}

		public function archive_project_type(){
			$archive_project_type = OVAPO_Settings::archive_project_type(); 	
			$archive_project_type = isset( $archive_project_type ) ? $archive_project_type : 'ASC';

			$default  = ('default' == $archive_project_type) ? 'selected' : '';
			$compact = ('compact' == $archive_project_type) ? 'selected' : '';
			$full = ('full' == $archive_project_type) ? 'selected' : '';

			?>
			<select name="ovapo_options[archive_project_type]" id="archive_project_type">
				<option <?php echo esc_attr($default) ?> value  ="default"><?php echo esc_html__('Default', 'ova-project') ?></option>
				<option <?php echo esc_attr($compact) ?> value ="compact"><?php echo esc_html__('Compact', 'ova-project') ?></option>
				<option <?php echo esc_attr($full) ?> value ="full"><?php echo esc_html__('Full', 'ova-project') ?></option>
			</select>
			<?php
		}


		



		public function archive_project_header(){
			$archive_project_header = OVAPO_Settings::archive_project_header(); 	
			
			
			$lits_headers = apply_filters('constrau_list_header', '');

			?>
			<select name="ovapo_options[archive_project_header]" id="archive_project_header">
				<?php if( ! empty( $lits_headers ) ){
					foreach( $lits_headers as $key => $val  ){
						$selected = ($archive_project_header == $key) ? 'selected' : '';
					?>
					<option <?php echo esc_attr( $selected ) ?> value="<?php echo esc_attr( $key ) ?>">
						<?php echo esc_html( $val ) ?>
					</option>
					<?php
					}
				} ?>
			</select>

			<?php
		}

		public function archive_project_footer(){
			$archive_project_footer = OVAPO_Settings::archive_project_footer(); 	
			
			
			$lits_footer = apply_filters('constrau_list_footer', '');

			?>
			<select name="ovapo_options[archive_project_footer]" id="archive_project_footer">
				<?php if( ! empty( $lits_footer ) ){
					foreach( $lits_footer as $key => $val  ){
						$selected = ($archive_project_footer == $key) ? 'selected' : '';
					?>
					<option <?php echo esc_attr( $selected ) ?> value="<?php echo esc_attr( $key ) ?>">
						<?php echo esc_html( $val ) ?>
					</option>
					<?php
					}
				} ?>
			</select>

			<?php
		}

		public function single_project_type(){
			$single_project_type = OVAPO_Settings::single_project_type(); 	
			$single_project_type = isset( $single_project_type ) ? $single_project_type : 'ASC';

			$type_1 = ('type_1' == $single_project_type) ? 'selected' : '';
			$type_2 = ('type_2' == $single_project_type) ? 'selected' : '';
			$type_3 = ('type_3' == $single_project_type) ? 'selected' : '';

			?>
			<select name="ovapo_options[single_project_type]" id="single_project_type">
				<option <?php echo esc_attr($type_1) ?> value ="type_1"><?php echo esc_html__('Type 1', 'ova-project') ?></option>
				<option <?php echo esc_attr($type_2) ?> value ="type_2"><?php echo esc_html__('Type 2', 'ova-project') ?></option>
				<option <?php echo esc_attr($type_3) ?> value ="type_3"><?php echo esc_html__('Type 3', 'ova-project') ?></option>
			</select>
			<?php
		}

		public function single_project_header(){
			$single_project_header = OVAPO_Settings::single_project_header(); 	
			
			
			$lits_header = apply_filters('constrau_list_header', '');

			?>
			<select name="ovapo_options[single_project_header]" id="single_project_header">
				<?php if( ! empty( $lits_header ) ){
					foreach( $lits_header as $key => $val  ){
						$selected = ($single_project_header == $key) ? 'selected' : '';
					?>
					<option <?php echo esc_attr( $selected ) ?> value="<?php echo esc_attr( $key ) ?>">
						<?php echo esc_html( $val ) ?>
					</option>
					<?php
					}
				} ?>
			</select>

			<?php
		}

		public function single_project_footer(){
			$single_project_footer = OVAPO_Settings::single_project_footer(); 	
			
			
			$lits_footer = apply_filters('constrau_list_footer', '');

			?>
			<select name="ovapo_options[single_project_footer]" id="single_project_footer">
				<?php if( ! empty( $lits_footer ) ){
					foreach( $lits_footer as $key => $val  ){
						$selected = ($single_project_footer == $key) ? 'selected' : '';
					?>
					<option <?php echo esc_attr( $selected ) ?> value="<?php echo esc_attr( $key ) ?>">
						<?php echo esc_html( $val ) ?>
					</option>
					<?php
					}
				} ?>
			</select>

			<?php
		}



	}
	new OVAPO_Admin_Settings();
}
