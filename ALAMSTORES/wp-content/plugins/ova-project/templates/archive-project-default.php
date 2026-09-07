<?php if ( !defined( 'ABSPATH' ) ) exit();

get_header();

$project = apply_filters( 'OVAPO_project_load_more', 10 );
?>

<div class="archive_project_default">
	<div class="container">

		<div class="button-group filter-button-group">
			<button data-filter="all" class="active second_font"><?php esc_html_e( 'All', 'ova-project' ); ?></button>

			<?php
			$terms = get_terms('project_cat');
			$first_term = $terms[0]->term_id;
			// var_dump($first_term);
			$count = count($terms);
			if ( $count > 0 ){
				foreach ( $terms as $term ) { ?>
					<button data-filter="<?php echo esc_attr($term->slug); ?>" class="second_font"><?php esc_html_e( $term->name, 'ova-project' ); ?></button>
				<?php }
			} ?>
		</div>

		<div class="content">
			<div class="grid">
				<?php
				if( $project->have_posts() ) : while( $project->have_posts() ) : $project->the_post();
					$id = get_the_ID();

					$ovapo_year = get_post_meta( $id, 'ovapo_year', true ) ? get_post_meta( $id, 'ovapo_year', true ) : '';
					$ovapo_area = get_post_meta( $id, 'ovapo_area', true ) ? get_post_meta( $id, 'ovapo_area', true ) : '';
					$ovapo_area_replace = str_replace(array('{', '}'), array('<sup>', '</sup>'), $ovapo_area);

					$ovapo_cat = get_the_terms( $id, 'project_cat' );

					$cat_name = array();
					if ($ovapo_cat != '') {
						foreach ($ovapo_cat as $key => $value) {
							$cat_name[] = $value->name;
						}
					}
					$category_name = join(', ', $cat_name);

					?>
					<div class="grid-item">
						<div class="wrap_item">
							<div class="img_feature">
								<?php the_post_thumbnail('feature_image_project'); ?>
							</div>
							<div class="info">
								<a class="title second_font" href="<?php echo esc_attr(get_the_permalink()); ?>"><?php the_title();?></a>
								<p class="cat"><?php echo esc_html($category_name) ?></p>
								<div class="bottom">
									<div class="year_area">
										<p class="year"><?php echo $ovapo_year; ?></p>
										<?php if ($ovapo_year != '' && $ovapo_area_replace != '') {
											echo esc_html('-');
										} ?>
										<p class="area"><?php echo $ovapo_area_replace; ?></p>
									</div>
									<a href="<?php the_permalink(); ?>" class="read_more"><i class="pointer_flaticon-019-right-arrow"></i></a>
								</div>
							</div>
						</div>
					</div>
				<?php endwhile; ?>
			</div>

			<div class="wrap_load_more">
				<span class="load_more" data-max_num_pages="1" data-paged="2" data-category="all" ><?php esc_html_e( 'Load More Project', 'ova-project' ); ?></span>
				<span class="no_load_more"><?php esc_html_e( 'No Data', 'ova-project' ); ?></span>
				<svg class="loader" width="50" height="50">
					<circle cx="25" cy="25" r="10" stroke="#a1a1a1"/>
					<circle cx="25" cy="25" r="20" stroke="#a1a1a1"/>
				</svg>
			</div>
			<div class="wrap_loader_filter">
				<svg class="loader_filter" width="50" height="50">
					<circle cx="25" cy="25" r="10" stroke="#a1a1a1"/>
					<circle cx="25" cy="25" r="20" stroke="#a1a1a1"/>
				</svg>
			</div>
			
			<?php else: ?>
				<div class="search_not_found">
					<?php esc_html_e( 'Not Found Project', 'ova-project' ); ?>
				</div>
			<?php endif; wp_reset_postdata(); ?>

		</div>
	</div>
</div>

<?php get_footer(); ?>