<?php if ( !defined( 'ABSPATH' ) ) exit();

if( !class_exists( 'OVAPO_loadmore' ) ){
	class OVAPO_loadmore{

		public function __construct(){
			add_action( 'wp_ajax_load_more_posts_default', array( $this, 'load_more_posts_default') );
			add_action( 'wp_ajax_nopriv_load_more_posts_default', array( $this, 'load_more_posts_default') );

			add_action( 'wp_ajax_load_more_posts_compact', array( $this, 'load_more_posts_compact') );
			add_action( 'wp_ajax_nopriv_load_more_posts_compact', array( $this, 'load_more_posts_compact') );

			add_action( 'wp_ajax_load_more_posts_full', array( $this, 'load_more_posts_full') );
			add_action( 'wp_ajax_nopriv_load_more_posts_full', array( $this, 'load_more_posts_full') );

			add_action( 'wp_ajax_filter_archive_default', array( $this, 'filter_archive_default') );
			add_action( 'wp_ajax_nopriv_filter_archive_default', array( $this, 'filter_archive_default') );

			add_action( 'wp_ajax_filter_archive_compact', array( $this, 'filter_archive_compact') );
			add_action( 'wp_ajax_nopriv_filter_archive_compact', array( $this, 'filter_archive_compact') );

			add_action( 'wp_ajax_filter_archive_full', array( $this, 'filter_archive_full') );
			add_action( 'wp_ajax_nopriv_filter_archive_full', array( $this, 'filter_archive_full') );

			add_action( 'wp_ajax_filter_elementor_grid', array( $this, 'filter_elementor_grid') );
			add_action( 'wp_ajax_nopriv_filter_elementor_grid', array( $this, 'filter_elementor_grid') );

			add_action( 'wp_ajax_load_more_posts_category', array( $this, 'load_more_posts_category') );
			add_action( 'wp_ajax_nopriv_load_more_posts_category', array( $this, 'load_more_posts_category') );
		}

		/* Ajax Load More Archive Default */
		public static function load_more_posts_default() {
			$filter = $_POST['category'];
			$paged = $_POST['paged'];

			$order = OVAPO_Settings::archive_project_order();
			$orderby = OVAPO_Settings::archive_project_orderby();
			$posts_per_page = OVAPO_Settings::archive_project_posts_per_page();

			$args_base = array(
				'post_type' => 'project',
				'post_status' => 'publish',
				'paged' => $paged,
				'order' => $order,
				'orderby' => $orderby,
				'posts_per_page' => $posts_per_page,
			);

			if ($filter != 'all') {
				$args_cat = array(
					'tax_query' => array(
						array(
							'taxonomy' => 'project_cat',
							'field'    => 'slug',
							'terms'    => $filter,
						)
					)
				);
				$args = array_merge_recursive($args_cat, $args_base);
				$my_posts = new WP_Query( $args );

			} else {
				$my_posts = new WP_Query( $args_base );
			}
			
			?>

			<?php
			if( $my_posts->have_posts() ) : while( $my_posts->have_posts() ) : $my_posts->the_post();
				$id = get_the_ID();

				$ovapo_year = get_post_meta( $id, 'ovapo_year', true ) ? get_post_meta( $id, 'ovapo_year', true ) : '';
				$ovapo_area = get_post_meta( $id, 'ovapo_area', true ) ? get_post_meta( $id, 'ovapo_area', true ) : '';
				$ovapo_area_replace = str_replace(array('{', '}'), array('<sup>', '</sup>'), $ovapo_area);
				$ovapo_address = get_post_meta( $id, 'ovapo_address', true ) ? get_post_meta( $id, 'ovapo_address', true ) : '';

				$ovapo_cat = get_the_terms( $id, 'project_cat' );

				$cat_name = array();
				if ($ovapo_cat != '') {
					foreach ($ovapo_cat as $key => $value) {
						$cat_name[] = $value->name;
					}
				}
				$category_name = join(', ', $cat_name);

				?>

				<?php if ($filter == 'all') { ?>
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
				<?php } else { ?>
					<div class="grid-item">
						<div class="wrap_item">
							<div class="img_feature">
								<?php the_post_thumbnail('feature_image_project'); ?>
							</div>
							<div class="info">
								<a class="title second_font" href="<?php echo esc_attr(get_the_permalink()); ?>"><?php the_title();?></a>
								<p class="address"><?php echo esc_html($ovapo_address) ?></p>
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
				<?php } ?>

			<?php endwhile; endif; wp_reset_postdata(); ?>

			<?php
			wp_die();
		}


		/* Ajax Load More Archive Compact */
		public static function load_more_posts_compact() {
			$filter = $_POST['category'];
			$paged = $_POST['paged'];

			$order = OVAPO_Settings::archive_project_order();
			$orderby = OVAPO_Settings::archive_project_orderby();
			$posts_per_page = OVAPO_Settings::archive_project_posts_per_page();

			$args_base = array(
				'post_type' => 'project',
				'post_status' => 'publish',
				'paged' => $paged,
				'order' => $order,
				'orderby' => $orderby,
				'posts_per_page' => $posts_per_page,
			);

			if ($filter != 'all') {
				$args_cat = array(
					'tax_query' => array(
						array(
							'taxonomy' => 'project_cat',
							'field'    => 'slug',
							'terms'    => $filter,
						)
					)
				);
				$args = array_merge_recursive($args_cat, $args_base);
				$my_posts = new WP_Query( $args );

			} else {
				$my_posts = new WP_Query( $args_base );
			}
			
			?>

			<?php
			if( $my_posts->have_posts() ) : while( $my_posts->have_posts() ) : $my_posts->the_post();
				$id = get_the_ID();

				$ovapo_year = get_post_meta( $id, 'ovapo_year', true ) ? get_post_meta( $id, 'ovapo_year', true ) : '';
				$ovapo_area = get_post_meta( $id, 'ovapo_area', true ) ? get_post_meta( $id, 'ovapo_area', true ) : '';
				$ovapo_area_replace = str_replace(array('{', '}'), array('<sup>', '</sup>'), $ovapo_area);
				$ovapo_address = get_post_meta( $id, 'ovapo_address', true ) ? get_post_meta( $id, 'ovapo_address', true ) : '';

				$ovapo_cat = get_the_terms( $id, 'project_cat' );

				$cat_name = array();
				if ($ovapo_cat != '') {
					foreach ($ovapo_cat as $key => $value) {
						$cat_name[] = $value->name;
					}
				}
				$category_name = join(', ', $cat_name);

				?>

				<?php if ($filter == 'all') { ?>
					<div class="grid-item">
						<div class="wrap_item">
							<div class="img_feature">
								<?php echo get_the_post_thumbnail($id, 'feature_image_vertical'); ?>
								<?php echo get_the_post_thumbnail($id);?>
							</div>
							<div class="info">
								<a class="title second_font" href="<?php echo esc_attr(get_the_permalink()); ?>"><?php the_title();?></a>
								<p class="cat"><?php echo esc_html($category_name) ?></p>
								<?php if (has_excerpt( $id )) { ?>
									<p class="desc"><?php echo get_the_excerpt(); ?></p>
								<?php } ?>
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
				<?php } else { ?>
					<div class="grid-item">
						<div class="wrap_item">
							<div class="img_feature">
								<?php echo get_the_post_thumbnail($id, 'feature_image_vertical'); ?>
								<?php echo get_the_post_thumbnail($id);?>
							</div>
							<div class="info">
								<a class="title second_font" href="<?php echo esc_attr(get_the_permalink()); ?>"><?php the_title();?></a>
								<p class="address"><?php echo esc_html($ovapo_address) ?></p>
								<?php if (has_excerpt( $id )) { ?>
									<p class="desc"><?php echo get_the_excerpt(); ?></p>
								<?php } ?>
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
				<?php } ?>

			<?php endwhile; endif; wp_reset_postdata(); ?>

			<?php
			wp_die();
		}


		/* Ajax Load More Archive Full */
		public static function load_more_posts_full() {
			$filter = $_POST['category'];
			$paged = $_POST['paged'];

			$order = OVAPO_Settings::archive_project_order();
			$orderby = OVAPO_Settings::archive_project_orderby();
			$posts_per_page_full = OVAPO_Settings::archive_project_full_posts_per_page();

			$args_base = array(
				'post_type' => 'project',
				'post_status' => 'publish',
				'paged' => $paged,
				'order' => $order,
				'orderby' => $orderby,
				'posts_per_page' => $posts_per_page_full,
			);

			if ($filter != 'all') {
				$args_cat = array(
					'tax_query' => array(
						array(
							'taxonomy' => 'project_cat',
							'field'    => 'slug',
							'terms'    => $filter,
						)
					)
				);
				$args = array_merge_recursive($args_cat, $args_base);
				$my_posts = new WP_Query( $args );

			} else {
				$my_posts = new WP_Query( $args_base );
			}
			
			?>

			<?php
			if( $my_posts->have_posts() ) : while( $my_posts->have_posts() ) : $my_posts->the_post();
				$id = get_the_ID();

				$ovapo_year = get_post_meta( $id, 'ovapo_year', true ) ? get_post_meta( $id, 'ovapo_year', true ) : '';
				$ovapo_area = get_post_meta( $id, 'ovapo_area', true ) ? get_post_meta( $id, 'ovapo_area', true ) : '';
				$ovapo_area_replace = str_replace(array('{', '}'), array('<sup>', '</sup>'), $ovapo_area);
				$ovapo_address = get_post_meta( $id, 'ovapo_address', true ) ? get_post_meta( $id, 'ovapo_address', true ) : '';

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
						<?php the_post_thumbnail('feature_image_project'); ?>
						<div class="info">
							<a href="<?php the_permalink(); ?>" class="title second_font"><?php the_title();?></a>
							<p class="cat"><?php echo esc_html($category_name); ?></p>
						</div>
					</div>
				</div>

			<?php endwhile; endif; wp_reset_postdata(); ?>

			<?php
			wp_die();
		}


		/* Ajax Load Post Click Button */
		public static function filter_archive_default() {
			$filter = $_POST['filter'];

			$order = OVAPO_Settings::archive_project_order();
			$orderby = OVAPO_Settings::archive_project_orderby();
			$posts_per_page = OVAPO_Settings::archive_project_posts_per_page();

			$args_base = array(
				'post_type' => 'project',
				'post_status' => 'publish',
				'order' => $order,
				'orderby' => $orderby,
				'posts_per_page' => $posts_per_page,
			);

			if ($filter != 'all') {
				$args_cat = array(
					'tax_query' => array(
						array(
							'taxonomy' => 'project_cat',
							'field'    => 'slug',
							'terms'    => $filter,
						)
					)
				);
				$args = array_merge_recursive($args_cat, $args_base);
				$my_posts = new WP_Query( $args );

			} else {
				$my_posts = new WP_Query( $args_base );
			}

			?>

			<?php
			if( $my_posts->have_posts() ) : while( $my_posts->have_posts() ) : $my_posts->the_post();
				$id = get_the_ID();

				$ovapo_year = get_post_meta( $id, 'ovapo_year', true ) ? get_post_meta( $id, 'ovapo_year', true ) : '';
				$ovapo_area = get_post_meta( $id, 'ovapo_area', true ) ? get_post_meta( $id, 'ovapo_area', true ) : '';
				$ovapo_area_replace = str_replace(array('{', '}'), array('<sup>', '</sup>'), $ovapo_area);
				$ovapo_address = get_post_meta( $id, 'ovapo_address', true ) ? get_post_meta( $id, 'ovapo_address', true ) : '';

				$ovapo_cat = get_the_terms( $id, 'project_cat' );

				$cat_name = array();
				if ($ovapo_cat != '') {
					foreach ($ovapo_cat as $key => $value) {
						$cat_name[] = $value->name;
					}
				}
				$category_name = join(', ', $cat_name);

				?>

				<?php if ($filter == 'all') { ?>
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
				<?php } else { ?>
					<div class="grid-item">
						<div class="wrap_item">
							<div class="img_feature">
								<?php the_post_thumbnail('feature_image_project'); ?>
							</div>
							<div class="info">
								<a class="title second_font" href="<?php echo esc_attr(get_the_permalink()); ?>"><?php the_title();?></a>
								<p class="address"><?php echo esc_html($ovapo_address) ?></p>
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
				<?php } ?>

			<?php endwhile; endif; wp_reset_postdata(); ?>
			<?php
			wp_die();
		}


		/* Ajax Load Post Click Button */
		public static function filter_archive_compact() {
			$filter = $_POST['filter'];

			$order = OVAPO_Settings::archive_project_order();
			$orderby = OVAPO_Settings::archive_project_orderby();
			$posts_per_page = OVAPO_Settings::archive_project_posts_per_page();

			$args_base = array(
				'post_type' => 'project',
				'post_status' => 'publish',
				'order' => $order,
				'orderby' => $orderby,
				'posts_per_page' => $posts_per_page,
			);

			if ($filter != 'all') {
				$args_cat = array(
					'tax_query' => array(
						array(
							'taxonomy' => 'project_cat',
							'field'    => 'slug',
							'terms'    => $filter,
						)
					)
				);
				$args = array_merge_recursive($args_cat, $args_base);
				$my_posts = new WP_Query( $args );

			} else {
				$my_posts = new WP_Query( $args_base );
			}

			?>

			<?php
			if( $my_posts->have_posts() ) : while( $my_posts->have_posts() ) : $my_posts->the_post();
				$id = get_the_ID();

				$ovapo_year = get_post_meta( $id, 'ovapo_year', true ) ? get_post_meta( $id, 'ovapo_year', true ) : '';
				$ovapo_area = get_post_meta( $id, 'ovapo_area', true ) ? get_post_meta( $id, 'ovapo_area', true ) : '';
				$ovapo_area_replace = str_replace(array('{', '}'), array('<sup>', '</sup>'), $ovapo_area);
				$ovapo_address = get_post_meta( $id, 'ovapo_address', true ) ? get_post_meta( $id, 'ovapo_address', true ) : '';

				$ovapo_cat = get_the_terms( $id, 'project_cat' );

				$cat_name = array();
				if ($ovapo_cat != '') {
					foreach ($ovapo_cat as $key => $value) {
						$cat_name[] = $value->name;
					}
				}
				$category_name = join(', ', $cat_name);

				?>

				<?php if ($filter == 'all') { ?>
					<div class="grid-item">
						<div class="wrap_item">
							<div class="img_feature">
								<?php echo get_the_post_thumbnail($id, 'feature_image_vertical'); ?>
								<?php echo get_the_post_thumbnail($id);?>
							</div>
							<div class="info">
								<a class="title second_font" href="<?php echo esc_attr(get_the_permalink()); ?>"><?php the_title();?></a>
								<p class="cat"><?php echo esc_html($category_name) ?></p>
								<?php if (has_excerpt( $id )) { ?>
									<p class="desc"><?php echo get_the_excerpt(); ?></p>
								<?php } ?>
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
				<?php } else { ?>
					<div class="grid-item">
						<div class="wrap_item">
							<div class="img_feature">
								<?php echo get_the_post_thumbnail($id, 'feature_image_vertical'); ?>
								<?php echo get_the_post_thumbnail($id);?>
							</div>
							<div class="info">
								<a class="title second_font" href="<?php echo esc_attr(get_the_permalink()); ?>"><?php the_title();?></a>
								<p class="address"><?php echo esc_html($ovapo_address) ?></p>
								<?php if (has_excerpt( $id )) { ?>
									<p class="desc"><?php echo get_the_excerpt(); ?></p>
								<?php } ?>
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
				<?php } ?>


			<?php endwhile; endif; wp_reset_postdata(); ?>
			<?php
			wp_die();
		}


		/* Ajax Load Post Click Button */
		public static function filter_archive_full() {
			$filter = $_POST['filter'];

			$order = OVAPO_Settings::archive_project_order();
			$orderby = OVAPO_Settings::archive_project_orderby();
			$posts_per_page_full = OVAPO_Settings::archive_project_full_posts_per_page();

			$args_base = array(
				'post_type' => 'project',
				'post_status' => 'publish',
				'order' => $order,
				'orderby' => $orderby,
				'posts_per_page' => $posts_per_page_full,
			);

			if ($filter != 'all') {
				$args_cat = array(
					'tax_query' => array(
						array(
							'taxonomy' => 'project_cat',
							'field'    => 'slug',
							'terms'    => $filter,
						)
					)
				);
				$args = array_merge_recursive($args_cat, $args_base);
				$my_posts = new WP_Query( $args );

			} else {
				$my_posts = new WP_Query( $args_base );
			}

			?>

			<?php
			if( $my_posts->have_posts() ) : while( $my_posts->have_posts() ) : $my_posts->the_post();
				$id = get_the_ID();

				$ovapo_year = get_post_meta( $id, 'ovapo_year', true ) ? get_post_meta( $id, 'ovapo_year', true ) : '';
				$ovapo_area = get_post_meta( $id, 'ovapo_area', true ) ? get_post_meta( $id, 'ovapo_area', true ) : '';
				$ovapo_area_replace = str_replace(array('{', '}'), array('<sup>', '</sup>'), $ovapo_area);
				$ovapo_address = get_post_meta( $id, 'ovapo_address', true ) ? get_post_meta( $id, 'ovapo_address', true ) : '';

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
						<?php the_post_thumbnail('feature_image_project'); ?>
						<div class="info">
							<a href="<?php the_permalink(); ?>" class="title second_font"><?php the_title();?></a>
							<p class="cat"><?php echo esc_html($category_name); ?></p>
						</div>
					</div>
				</div>

			<?php endwhile; endif; wp_reset_postdata(); ?>
			<?php
			wp_die();
		}


		/* Ajax Load Post Click Elementor */
		public static function filter_elementor_grid() {
			$filter = $_POST['filter'];
			$order = $_POST['order'];
			$orderby = $_POST['orderby'];
			$number_post = $_POST['number_post'];
			$column = $_POST['column'];
			$first_term = $_POST['first_term'];
			$term_id_filter_string = $_POST['term_id_filter_string'];
			$show_featured = $_POST['show_featured'];

			$args_base = array(
				'post_type' => 'project',
				'post_status' => 'publish',
				'posts_per_page' => $number_post,
				'order' => $order,
				'orderby' => $orderby,
			);
			$term_id_filter = explode(', ', $term_id_filter_string);

			/* Show Featured */
			if ($show_featured == 'yes') {
				$args_featured = array(
					'meta_key' => 'ovapo_featured',
					'meta_query'=> array(
						array(
							'key' => 'ovapo_featured',
							'compare' => '=',
							'value' => 'checked',
						)
					)
				);
			} else {
				$args_featured = array();
			}

			if ($filter != 'all') {
				$args_cat = array(
					'tax_query' => array(
						array(
							'taxonomy' => 'project_cat',
							'field'    => 'id',
							'terms'    => $filter,
						)
					)
				);


				$args = array_merge_recursive($args_cat, $args_base, $args_featured);
				$my_posts = new WP_Query( $args );
				// var_dump($my_posts);

			} else {
				$args_cat = array(
					'tax_query' => array(
						array(
							'taxonomy' => 'project_cat',
							'field'    => 'id',
							'terms'    => $term_id_filter,
						)
					)
				);

				$args = array_merge_recursive($args_base, $args_cat, $args_featured);
				$my_posts = new WP_Query( $args );
			}
			// var_dump($args_cat);
			?>

			<?php
			if( $my_posts->have_posts() ) : while( $my_posts->have_posts() ) : $my_posts->the_post();

				$id = get_the_ID();

				$ovapo_cat = get_the_terms( $id, 'project_cat' );

				$cat_name = array();
				if ($ovapo_cat != '') {
					foreach ($ovapo_cat as $key => $value) {
						$cat_name[] = $value->name;
					}
				}
				$category_name = join(', ', $cat_name);
				?>

				<div class="wrap_item <?php echo esc_attr($column); ?>">
					<div class="item ">
						<img data-src="<?php echo esc_attr(the_post_thumbnail_url('feature_image_project')); ?>" src="<?php echo esc_attr(the_post_thumbnail_url('feature_image_project')); ?>" alt="#">
						<div class="info">
							<a href="<?php the_permalink(); ?>" class="title second_font"><?php the_title();?></a>
							<p class="cat"><?php echo esc_html($category_name); ?></p>
						</div>
					</div>	
				</div>

			<?php endwhile; endif; wp_reset_postdata(); ?>
			<?php
			wp_die();
		}


		/* Ajax Load More Category */
		public static function load_more_posts_category() {
			$filter = $_POST['category'];
			$paged = $_POST['paged'];

			$order = OVAPO_Settings::archive_project_order();
			$orderby = OVAPO_Settings::archive_project_orderby();
			$posts_per_page = OVAPO_Settings::archive_project_posts_per_page();

			$args_base = array(
				'post_type' => 'project',
				'post_status' => 'publish',
				'paged' => $paged,
				'order' => $order,
				'orderby' => $orderby,
				'posts_per_page' => $posts_per_page,
				'tax_query' => array(
					array(
						'taxonomy' => 'project_cat',
						'field'    => 'slug',
						'terms'    => $filter,
					)
				)
			);
			
			$my_posts = new WP_Query( $args_base );
			
			?>

			<?php
			if( $my_posts->have_posts() ) : while( $my_posts->have_posts() ) : $my_posts->the_post();
				$id = get_the_ID();

				$ovapo_year = get_post_meta( $id, 'ovapo_year', true ) ? get_post_meta( $id, 'ovapo_year', true ) : '';
				$ovapo_area = get_post_meta( $id, 'ovapo_area', true ) ? get_post_meta( $id, 'ovapo_area', true ) : '';
				$ovapo_area_replace = str_replace(array('{', '}'), array('<sup>', '</sup>'), $ovapo_area);
				$ovapo_address = get_post_meta( $id, 'ovapo_address', true ) ? get_post_meta( $id, 'ovapo_address', true ) : '';

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

			<?php endwhile; endif; wp_reset_postdata(); ?>

			<?php
			wp_die();
		}

	}
	new OVAPO_loadmore();
}
?>