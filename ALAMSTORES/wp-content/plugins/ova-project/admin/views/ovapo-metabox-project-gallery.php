<?php

if( !defined( 'ABSPATH' ) ) exit();

$id = get_the_ID();

$ovapo_gallery_id = get_post_meta( $id, 'ovapo_gallery_id', true ) ? get_post_meta( $id, 'ovapo_gallery_id', true ) : '';

?>
<div class="ovapo_metabox">
	<br>
	<a class="gallery-add button button-primary button-large text-right" href="#" data-uploader-title="<?php esc_html_e( 'Add image(s) to gallery', 'ova-project' ); ?>" data-uploader-button-text="<?php esc_html_e( "Add image(s)", 'ova-project' ); ?>"><?php esc_html_e( "Add image(s)", 'ova-project' ); ?></a>

	<ul id="gallery-metabox-list">
		<?php if ($ovapo_gallery_id) : foreach ($ovapo_gallery_id as $key => $value) : $image = wp_get_attachment_image_src($value); ?>
			<li>
				<input type="hidden" name="ovapo_gallery_id[<?php echo $key; ?>]" value="<?php echo esc_attr($value); ?>">
				<img class="image-preview" src="<?php echo $image[0]; ?>">
				<a class="change-image button button-small" href="#" data-uploader-title="<?php esc_html_e( 'Change image', 'ova-project' ); ?>" data-uploader-button-text="<?php esc_html_e( "Change image", 'ova-project' ); ?>"><?php esc_html_e( "Change image", 'ova-project' ); ?></a>
				<small><a class="remove-image" href="#"><?php esc_html_e( "Remove image", 'ova-project' ); ?></a></small>
			</li>
		<?php endforeach; endif; ?>
	</ul>

</div>

<?php wp_nonce_field( 'ovapo_nonce', 'ovapo_nonce' ); ?>