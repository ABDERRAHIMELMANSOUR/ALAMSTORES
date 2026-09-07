<?php

if( !defined( 'ABSPATH' ) ) exit();

global $post;

?>

<div class="row">

	<div id="tabs">

		<ul>
			<li><a href="#metabox-project-basic"><?php esc_html_e( 'Basic', 'ova-project' ); ?> </a></li>
			<li><a href="#metabox-project-gallery" class=""><?php esc_html_e( 'Gallery', 'ova-project' ); ?></a></li>
		</ul>

		<!-- Basic Tab Content -->  
		<div id="metabox-project-basic">
			<?php require_once( OVAPO_PLUGIN_PATH.'/admin/views/ovapo-metabox-project-basic.php' ); ?>
		</div>

		<!-- Gallery -->  
		<div id="metabox-project-gallery">
			<?php require_once( OVAPO_PLUGIN_PATH.'/admin/views/ovapo-metabox-project-gallery.php' ); ?>
		</div>

	</div>

	<br/> 
</div>

<?php wp_nonce_field( 'ovapo_nonce', 'ovapo_nonce' ); ?>