<?php $sticky_class = is_sticky()?'sticky':''; ?>

<?php if( has_post_format('link') ){ ?>

	<article id="post-<?php the_ID(); ?>" <?php post_class('post-wrap '. $sticky_class); ?>  >
		
		<?php
		$link = get_post_meta( $post->ID, 'format_link_url', true );
		$link_description = get_post_meta( $post->ID, 'format_link_description', true );
		
		if ( is_single() ) {
			printf( '<h1 class="entry-title"><a href="%1$s" target="blank">%2$s</a></h1>',
				$link,
				get_the_title()
			);
		} else {
			printf( '<h2 class="entry-title"><a href="%1$s" target="blank">%2$s</a></h2>',
				$link,
				get_the_title()
			);
		}
		?>
		<?php
		printf( '<a href="%1$s" target="blank">%2$s</a>',
			$link,
			$link_description
		);
		?>
	</article>

<?php }elseif ( has_post_format('aside') ){ ?>

	<article id="post-<?php the_ID(); ?>" <?php post_class('post-wrap '. $sticky_class); ?>  >
		<div class="post-body">
			<?php the_content(''); /* Display content  */ ?>
		</div>
	</article>

<?php }else{ ?>

	<article id="post-<?php the_ID(); ?>" <?php post_class('post-wrap '. $sticky_class); ?>  >
		
		<?php if( has_post_format('audio') ){ ?>

			<div class="post-media">
				<?php constrau_postformat_audio(); /* Display video of post */ ?>
			</div>

		<?php }elseif(has_post_format('gallery')){ ?>

			<?php constrau_content_gallery(); /* Display gallery of post */ ?>

		<?php }elseif(has_post_format('video')){ ?>

			<div class="post-media">
				<?php constrau_postformat_video(); /* Display video of post */ ?>
			</div>

		<?php }elseif(has_post_thumbnail()){ ?>

			<div class="post-media">
				<?php constrau_content_thumbnail('medium_large'); /* Display thumbnail of post */ ?>
				<?php if (get_theme_mod('show_date_blog') === 'yes') : ?>
					<span class="date"><span class="unit second_font day"><?php the_time( 'd' );?></span><span class="unit second_font month"><?php the_time( 'M, Y' );?></span></span>
				<?php endif ?>
			</div>

		<?php } ?>


		<div class="post-title">
			<?php constrau_content_title(); /* Display title of post */ ?>
		</div>

		<div class="post-meta">
			<?php constrau_content_meta_grid(); /* Display Date, Author, Comment */ ?>
		</div>
		<?php
		$exceprt = "";
		if (get_the_excerpt() !== '') {
			$exceprt = get_the_excerpt();
		} else {
			$exceprt = get_the_content();
		}
		?>
		<div class="post-body">
			<div class="post-excerpt">
				<p>
					<?php echo esc_html(constrau_custom_text($exceprt, get_theme_mod('num_text_blog_grid',20))); /* Display content of post or intro in category page */ ?>
				</p>
				
			</div>
		</div>

		<?php if(is_single()){ ?>
			<?php constrau_content_tag(); /* Display tags, category */ ?>
		<?php } ?>

	</article>


	<?php } ?>