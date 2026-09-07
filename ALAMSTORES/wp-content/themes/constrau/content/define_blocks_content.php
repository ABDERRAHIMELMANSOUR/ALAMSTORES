<?php

/* This is functions define blocks to display post */

if ( ! function_exists( 'constrau_content_thumbnail' ) ) {
	function constrau_content_thumbnail( $size ) {
		if ( has_post_thumbnail()  && ! post_password_required() || has_post_format( 'image') )  :
			the_post_thumbnail( $size, array('class'=> 'img-responsive' ));
	endif;
}
}

if ( ! function_exists( 'constrau_postformat_video' ) ) {
	function constrau_postformat_video( ) { ?>
		<?php if(has_post_format('video') && wp_oembed_get(get_post_meta(get_the_id(), "ova_met_embed_media", true))){ ?>
			<div class="js-video postformat_video">
				<?php echo wp_oembed_get(get_post_meta(get_the_id(), "ova_met_embed_media", true)); ?>
			</div>
		<?php } ?>
	<?php }
}

if ( ! function_exists( 'constrau_postformat_audio ') ) {
	function constrau_postformat_audio( ) { ?>
		<?php if(has_post_format('audio') && wp_oembed_get(get_post_meta(get_the_id(), "ova_met_embed_media", true))){ ?>
			<div class="js-video postformat_audio">
				<?php echo wp_oembed_get(get_post_meta(get_the_id(), "ova_met_embed_media", true)); ?>
			</div>
		<?php } ?>
	<?php }
}

if ( ! function_exists( 'constrau_content_title' ) ) {
	function constrau_content_title() { ?>

		<?php if ( is_single() ) : ?>
			<h1 class="post-title second_font">
				<?php the_title(); ?>
			</h1>
			<?php else : ?>
				<h2 class="post-title">
					<a class="second_font" href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title_attribute(); ?>">
						<?php the_title(); ?>
					</a>
				</h2>
			<?php endif; ?>

		<?php }
	}

	if ( ! function_exists( 'constrau_content_meta_grid' ) ) {
		function constrau_content_meta_grid( ) { ?>
			<span class="post-meta-content">
				<span class="general-meta categories second_font">
					<i class="zmdi zmdi-tag"></i>
					<?php the_category('&sbquo;&nbsp;'); ?>
				</span>
				<span class="meta-slash"><?php echo esc_html_e('|', 'constrau') ?></span>
				<span class="general-meta post-author second_font">
					<span class="left"><i class="zmdi zmdi-edit"></i></span>
					<span class="right"><a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"><?php the_author_meta( 'display_name' ); ?></a></span>
				</span>
				
			</span>
		<?php }
	}



	if ( ! function_exists( 'constrau_content_meta' ) ) {
		function constrau_content_meta( ) { ?>
			<span class="post-meta-content">
				<span class="general-meta categories second_font">
					<i class="zmdi zmdi-tag"></i>
					<?php the_category('&sbquo;&nbsp;'); ?>
					<span class="meta-slash"><?php echo esc_html_e('|', 'constrau') ?></span>
				</span>
				
				<span class="general-meta post-author second_font">
					<span class="left"><i class="zmdi zmdi-edit"></i></span>
					<span class="right"><a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"><?php the_author_meta( 'display_name' ); ?></a></span>
				</span>
				<span class="meta-slash"><?php echo esc_html_e('|', 'constrau') ?></span>
				<span class="general-meta post-date">
					<span class="left"><i class="zmdi zmdi-calendar"></i></span>
					<span class="right second_font"><?php the_time( get_option( 'date_format' ) );?></span>
				</span>
				<span class="meta-slash"><?php echo esc_html_e('|', 'constrau') ?></span>
				<span class="general-meta comment second_font">
					<span class="left"><i class="zmdi zmdi-comment-text"></i></span>
					<span class="right">                   
						<?php comments_popup_link(
							esc_html__(' 0 commentaire', 'constrau'), 
							esc_html__(' 1 commentaire', 'constrau'), 
							' % '.esc_html__('commentaires', 'constrau'),
							'',
							esc_html__( 'Comment off', 'constrau' )
						); ?>
					</span>             
				</span>
			</span>
		<?php }
	}

	if ( ! function_exists( 'constrau_content_body' ) ) {
		function constrau_content_body( ) { ?>
			<div class="post-excerpt">
				<?php if(is_single()){
					the_content();
					wp_link_pages( array(
						'before'      => '<div class="page-links"><span class="page-links-title">' . esc_html__( 'Pages:', 'constrau' ) . '</span>',
						'after'       => '</div>',
						'link_before' => '<span>',
						'link_after'  => '</span>',
						'pagelink'    => '%',
						'separator'   => '',
					) );             
				}else{
					the_excerpt();
				}?>
			</div>

			<?php 
		}
	}

	if ( ! function_exists( 'constrau_content_readmore' ) ) {
		function constrau_content_readmore( ) { ?>
			<div class="post-footer">
				<div class="post-readmore-constrau">
					<a href="<?php the_permalink(); ?>"><?php  esc_html_e('LIRE LA SUITE', 'constrau'); ?></a>
				</div>
			</div>
		<?php }
	}


	if ( ! function_exists( 'constrau_content_tag' ) ) {
		function constrau_content_tag( ) { ?>

			<footer class="post-tag-constrau">
				<?php if(has_tag()){ ?>
					<div class="post-tags-constrau">
						<span class="ovatags second_font"><?php esc_html_e('Tags: ', 'constrau'); ?></span>
						<?php the_tags('','',''); ?>
					</div>
				<?php } ?>

				<?php if( has_filter( 'ova_share_social' ) ){ ?>
					<div class="share_social-constrau">
						<span class="text-social second_font"><?php echo esc_html_e("PARTAGER : ", 'constrau') ?></span>
						<?php echo apply_filters('ova_share_social', get_the_permalink(), get_the_title() ); ?>
					</div>
				<?php } ?>
			</footer>

		<?php }
	}


	if ( ! function_exists( 'constrau_content_gallery' ) ) {
		function constrau_content_gallery( ) {

			$gallery = get_post_meta(get_the_ID(), 'ova_met_file_list', true) ? get_post_meta(get_the_ID(), 'ova_met_file_list', true) : '';

			$k = 0;
			if($gallery){ $i=0; ?>

				<div id="carousel-example-generic-<?php echo esc_attr(get_the_ID()) ?>" class="carousel slide" data-ride="carousel">
					<!-- Indicators -->
					<ol class="carousel-indicators">
						<?php foreach ($gallery as $key => $value) {  $active = ( $i == 0 ) ? 'active' : ''; ?>
						<li data-target="#carousel-example-generic-<?php echo esc_attr(get_the_ID()) ?>" data-slide-to="<?php echo esc_attr($i); ?>" class="<?php echo esc_attr($active); ?>"></li>
						<?php $i++; } ?>
					</ol>

					<!-- Wrapper for slides -->
					<div class="carousel-inner" role="listbox">
						<?php foreach ($gallery as $key => $value) { $active_dot = ( $k == 0 ) ? 'active' : ''; ?>
						<div class="carousel-item <?php echo esc_attr($active_dot); $k++; ?>">
							<img class="img-responsive" src="<?php  echo esc_attr($value); ?>" alt="<?php the_title_attribute(); ?>">
						</div>
					<?php } ?>
				</div>

			</div>


			<?php
		}
	}
}






//Custom comment List:
if ( ! function_exists( 'constrau_theme_comment' ) ) {
	function constrau_theme_comment($comment, $args, $depth) {

		$GLOBALS['comment'] = $comment; ?>   
		<li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">
			<article class="comment_item" id="comment-<?php comment_ID(); ?>">

				<header class="comment-author">
					<?php echo get_avatar($comment,$size='70', $default = 'mysteryman' ); ?>
				</header>

				<section class="comment-details">

					<div class="author-name">

						<div class="name">
							<?php printf('%s', get_comment_author_link()) ?>	
						</div>

						<div class="date">
							<?php printf(get_comment_date()) ?>	
						</div>

						<div class="ova_reply">
							<i class="zmdi zmdi-mail-reply"></i>
							<?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
							<?php edit_comment_link( esc_html__( '(Edit)', 'constrau' ), '  ', '' );?>
						</div>
					</div> 

					<div class="comment-body clearfix comment-content">
						<?php comment_text() ?>
					</div>

				</section>

				<?php if ($comment->comment_approved == '0') : ?>
					<em><?php esc_html_e('Votre commentaire est en attente de modération.', 'constrau') ?></em>
					<br />
				<?php endif; ?>

			</article>
			<?php
		}
	}








