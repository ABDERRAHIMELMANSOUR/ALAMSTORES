<header class="page-header">
	<h1 class="page-title"><?php esc_html_e( 'Erreur 404: Page non trouvée', 'constrau'); ?></h1>
</header>

<div class="page-content">
	<?php if ( is_home() && current_user_can( 'publish_posts' ) ) : ?>

	<p><?php printf( __( 'Prêt à publier votre premier poste? <a href="%1$s">Commencez ici</a>.', 'constrau' ), admin_url( 'post-new.php' ) ); ?></p>

	<?php elseif ( is_search() ) : ?>

	<p><?php esc_html_e( 'Désolé, mais rien ne correspond à vos termes de recherche. Veuillez réessayer avec d autres mots-clés.', 'constrau' ); ?></p>
	<?php get_search_form(); ?>

	<?php else : ?>

	<p><?php esc_html_e( 'Cette page que vous avez lancé n existe pas sur notre serveur.', 'constrau' ); ?></p>
	<?php get_search_form(); ?>

	<?php endif; ?>
</div>