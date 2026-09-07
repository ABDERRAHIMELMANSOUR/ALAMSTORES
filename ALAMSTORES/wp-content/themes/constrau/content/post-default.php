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
			<?php the_content(); /* Display content  */ ?>
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
				<?php constrau_content_thumbnail('full'); /* Display thumbnail of post */ ?>
			</div>

		<?php } ?>

		<?php if( !class_exists('OvaFramework') ){ ?>
			<div class="post-title">
				<?php constrau_content_title(); /* Display title of post */ ?>
			</div>
		<?php } ?>

		<div class="post-meta">
			<?php constrau_content_meta(); /* Display Date, Author, Comment */ ?>
		</div>

		<div class="post-body">
			<div class="post-excerpt">
				<?php constrau_content_body(); /* Display content of post or intro in category page */ ?>
			</div>
		</div>

		<?php if(is_single()){ ?>
			<?php constrau_content_tag(); /* Display tags, category */ ?>
		<?php } ?>

		<div class="pagination-detail">
			<?php
			$prev_post = get_previous_post();
			?>
			<div class="pre">
				<?php
				if($prev_post) {
					?>
					<a rel="prev" href="<?php echo esc_attr(get_permalink($prev_post->ID)) ?>" ><i class="pointer_flaticon-025-left-arrow-2"></i>PRÉCÉDENT</a>
					<?php
				}
				?>
			</div>
			<div>
				<a rel="prev" href="<?php echo esc_attr(get_post_type_archive_link( 'post' )) ?>" ><i class="fas fa-th"></i></a>
			</div>
			<div class="next">
				<?php
				$next_post = get_next_post();
				if($next_post) {
					?>
					<a rel="next" href="<?php echo esc_attr(get_permalink($next_post->ID)) ?> ">SUIVANT<i class="pointer_flaticon-017-right-arrow-2"></i></a>
					<?php
				}
				?>
			</div>
		</div>

	</article>


<?php } ?>

