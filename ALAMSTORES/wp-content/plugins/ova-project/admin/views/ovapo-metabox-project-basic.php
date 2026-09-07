<?php

if( !defined( 'ABSPATH' ) ) exit();

$id = get_the_ID();

$ovapo_client = get_post_meta( $id, 'ovapo_client', true ) ? get_post_meta( $id, 'ovapo_client', true ) : '';
$ovapo_address = get_post_meta( $id, 'ovapo_address', true ) ? get_post_meta( $id, 'ovapo_address', true ) : '';
$ovapo_area = get_post_meta( $id, 'ovapo_area', true ) ? get_post_meta( $id, 'ovapo_area', true ) : '';
$ovapo_year = get_post_meta( $id, 'ovapo_year', true ) ? get_post_meta( $id, 'ovapo_year', true ) : '';
$ovapo_value = get_post_meta( $id, 'ovapo_value', true ) ? get_post_meta( $id, 'ovapo_value', true ) : '';
$ovapo_value = get_post_meta( $id, 'ovapo_value', true ) ? get_post_meta( $id, 'ovapo_value', true ) : '';
$ovapo_featured = get_post_meta( $id, 'ovapo_featured', true ) ? get_post_meta( $id, 'ovapo_featured', true ) : '' ;

?>

<div class="ovapo_metabox">
	<br>
	<div class="ovapo_row">
		<label class="label"><strong><?php esc_html_e( 'Client:', 'ova-project' ); ?></strong></label>
		<input type="text" id="ovapo_client" class="ovapo_client" value="<?php echo esc_attr($ovapo_client); ?>" placeholder="Insert Client"  name="ovapo_client" autocomplete="off" />
	</div>
	<br>

	<div class="ovapo_row">
		<label class="label"><strong><?php esc_html_e( 'Location:', 'ova-project' ); ?></strong></label>
		<input type="text" id="ovapo_address" class="ovapo_address" value="<?php echo esc_attr($ovapo_address); ?>" placeholder="123 New York"  name="ovapo_address" autocomplete="off" />
	</div>
	<br>

	<div class="ovapo_row">
		<label class="label"><strong><?php esc_html_e( 'Surface Area:', 'ova-project' ); ?></strong></label>
		<input type="text" id="ovapo_area" class="ovapo_area" value="<?php echo esc_attr($ovapo_area); ?>" placeholder="Insert Area"  name="ovapo_area" autocomplete="off" />
	</div>
	<br>

	<div class="ovapo_row">
		<label class="label"><strong><?php esc_html_e( 'Year:', 'ova-project' ); ?></strong></label>
		<input type="text" id="ovapo_year" class="ovapo_year" value="<?php echo esc_attr($ovapo_year); ?>" placeholder="Insert Year"  name="ovapo_year" autocomplete="off" />
	</div>
	<br>

	<div class="ovapo_row">
		<label class="label"><strong><?php esc_html_e( 'Value:', 'ova-project' ); ?></strong></label>
		<input type="text" id="ovapo_value" class="ovapo_value" value="<?php echo esc_attr($ovapo_value); ?>" placeholder="Insert Value"  name="ovapo_value" autocomplete="off" />
	</div>
	<br>

	<div class="ovapo_row">
		<label class="label"><strong><?php esc_html_e( 'Featured:', 'ova-project' ); ?></strong></label>
		<input type="checkbox" value="<?php echo esc_attr($ovapo_featured); ?>" name="ovapo_featured" <?php echo esc_attr($ovapo_featured); ?> />
	</div>
	<br>

</div>

<?php wp_nonce_field( 'ovapo_nonce', 'ovapo_nonce' ); ?>