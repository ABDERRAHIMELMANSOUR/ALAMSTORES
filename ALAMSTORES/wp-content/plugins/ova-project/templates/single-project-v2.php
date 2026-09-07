<?php if ( !defined( 'ABSPATH' ) ) exit();

get_header( );
$id = get_the_ID();
$ovapo_client = get_post_meta( $id, 'ovapo_client', true ) ? get_post_meta( $id, 'ovapo_client', true ) : '';
$ovapo_address = get_post_meta( $id, 'ovapo_address', true ) ? get_post_meta( $id, 'ovapo_address', true ) : '';
$ovapo_area = get_post_meta( $id, 'ovapo_area', true ) ? get_post_meta( $id, 'ovapo_area', true ) : '';
$ovapo_year = get_post_meta( $id, 'ovapo_year', true ) ? get_post_meta( $id, 'ovapo_year', true ) : '';
$ovapo_value = get_post_meta( $id, 'ovapo_value', true ) ? get_post_meta( $id, 'ovapo_value', true ) : '';
$ovapo_gallery_id = get_post_meta( $id, 'ovapo_gallery_id', true ) ? get_post_meta( $id, 'ovapo_gallery_id', true ) : '';

$ovapo_area_replace = str_replace(array('{', '}'), array('<sup>', '</sup>'), $ovapo_area);
$ovapo_cat = get_the_terms( $id, 'project_cat' );

?>

<?php if( have_posts() ) : while( have_posts() ) : the_post(); ?>
	
	<div class="container">
		<div class="single_project ver_bottom">

			<div class="gallery">
				<div class="slide_gallery">
					<div class="wrap_slide">
						<?php if ($ovapo_gallery_id != '') {
							foreach ($ovapo_gallery_id as $key => $value) { ?>
								<img src="<?php echo esc_attr(wp_get_attachment_image_url($value, '')); ?>" alt="#">
							<?php }
						} ?>
					</div>

					<i class="prev-arrow arrow_flaticon-050-left-arrow-7"></i>
					<i class="next-arrow arrow_flaticon-034-right-arrow-7"></i>

				</div>
				<div class="thumbnail_gallery">
					<?php if ($ovapo_gallery_id != '') {
						foreach ($ovapo_gallery_id as $key => $value) { ?>
							<img src="<?php echo esc_attr(wp_get_attachment_image_url($value, 'feature_image_project_2')); ?>" alt="#">
						<?php }
					} ?>
				</div>
			</div> <!-- gallery -->

			<div class="intro">
				
				<div class="client second_font"><?php echo esc_html($ovapo_client); ?></div>
				<div class="social">
					<span class="second_font"><?php esc_html_e( 'SHARE:', 'ova-project' ); ?></span>
					<span class="icon_share">
						<i class="zmdi zmdi-share"></i>
					</span>
					<?php echo apply_filters( 'ova_share_social', get_the_permalink(), get_the_title() ); ?>
				</div>
			</div> <!-- intro -->

			<div class="content">
				<div class="desc">
					<div class="heading_desc">
						<?php esc_html_e( 'Description', 'ova-project' ); ?>
					</div>
					<div class="content_desc">
						<?php the_content(); ?>
					</div>
				</div>
				<div class="detail">
					<div class="heading_detail">
						<?php esc_html_e( 'Details', 'ova-project' ); ?>
					</div>
					<table>
						<tr class="location">
							<td>
								<span class="icon_detail"><i class="zmdi zmdi-pin"></i></span>
							</td>
							<td>
								<span class="title_detail"><?php esc_html_e( 'Location:', 'ova-project' ); ?></span>
								<span class="info_detail"><?php echo esc_html($ovapo_address); ?></span>
							</td>
						</tr>

						<tr class="area">
							<td>
								<span class="icon_detail"><i class="zmdi zmdi-layers"></i></span>
							</td>
							<td>
								<span class="title_detail"><?php esc_html_e( 'Surface Area:', 'ova-project' ); ?></span>
								<span class="info_detail"><?php echo $ovapo_area_replace; ?></span>
							</td>
						</tr>

						<tr class="year">
							<td>
								<span class="icon_detail"><i class="zmdi zmdi-calendar-check"></i></span>
							</td>
							<td>
								<span class="title_detail"><?php esc_html_e( 'Year:', 'ova-project' ); ?></span>
								<span class="info_detail"><?php echo esc_html($ovapo_year); ?></span>
							</td>
						</tr>

						<tr class="value">
							<td>
								<span class="icon_detail"><i class="zmdi zmdi-money"></i></span>
							</td>
							<td>
								<span class="title_detail"><?php esc_html_e( 'Value:', 'ova-project' ); ?></span>
								<span class="info_detail"><?php echo esc_html($ovapo_value); ?></span>
							</td>
						</tr>

						<tr class="categories">
							<td>
								<span class="icon_detail"><i class="zmdi zmdi-folder-outline"></i></span>
							</td>
							<td>
								<span class="title_detail"><?php esc_html_e( 'Categories:', 'ova-project' ); ?></span>
								<span class="info_detail">
									<?php if ($ovapo_cat) {
										foreach ($ovapo_cat as $key => $value) {
											echo esc_html($value->name) . ', ';
										}
									} ?>
								</span>
							</td>
						</tr>
					</table>
				</div>
			</div> <!-- content -->

			<div class="bottom">
				<div class="nav-prev text-left">
					<?php previous_post_link( '%link', '<i class="pointer_flaticon-025-left-arrow-2"></i>' . '<span>'._x( 'Prev', 'ova-project' ).'</span>' ); ?>
				</div>
				<div class="center">
					<a href="<?php echo home_url('/'.OVAPO_Settings::ovapo_project_post_type_rewrite_slug()); ?>">
						<i class="zmdi zmdi-apps"></i>
					</a>
				</div>
				<div class="nav-next text-right">
					<?php next_post_link( '%link', '<span>'._x( 'Next', 'ova-project' ).'</span>' . '<i class="pointer_flaticon-017-right-arrow-2"></i>'  ); ?>
				</div>
			</div> <!-- bottom -->

		</div>
	</div>

<?php endwhile; endif; wp_reset_postdata(); ?>

<?php get_footer( );