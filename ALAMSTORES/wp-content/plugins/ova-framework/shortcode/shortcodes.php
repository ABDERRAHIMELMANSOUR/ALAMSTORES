<?php 

add_shortcode('constrau_mini_cart', 'constrau_mini_cart');
function constrau_mini_cart($atts, $content = null) {

	$atts = extract( shortcode_atts(
		array(
			'style' => 'style1',
			'class'  => '',
		), $atts) );
	ob_start();
	?>
	<?php if(class_exists('Woocommerce')){ ?>

		<?php if( $style == 'style1' ){ ?>
			<div class="constrau-cart-wrapper  style1">
				<div class="cart-total">
					<i class="icon_bag_alt"></i>
					<span class="items">
						<?php  
						if( is_object( WC()->cart ) ){
							echo (WC()->cart->get_cart_contents_count( ) > 1) ? WC()->cart->get_cart_contents_count( ) : WC()->cart->get_cart_contents_count( );
						}
						?>
					</span>
				</div>
				<div class="constrau_minicart">
					<?php  if( ( is_object( WC()->cart ) && WC()->cart->get_cart_contents_count( ) > 1 ) ){
						woocommerce_mini_cart();
					} ?>
				</div>
			</div>
		<?php }else if( $style == 'style2' ){ ?>

			<div class="constrau-cart-wrapper style2">

				<div class="d-flex">
					<div class="label">
						<div class="heading"><?php esc_html_e( 'My Cart', 'ova-framework' ); ?></div>
						<div class="info"><?php printf( "Your cart is <span>%d</span> item", WC()->cart->get_cart_contents_count(), 'ova-framework'  ); ?>      </div>
					</div>
					<div class="cart-total">
						<i class="icon_bag_alt"></i>
						<span class="items">
							<?php 
							if( is_object( WC()->cart ) ){
								echo (WC()->cart->get_cart_contents_count( ) > 1) ? WC()->cart->get_cart_contents_count( ) : WC()->cart->get_cart_contents_count( );
							}
							?>
						</span>
					</div>
				</div>
				<div class="constrau_minicart">
					<?php  if( is_object( WC()->cart ) && WC()->cart->get_cart_contents_count( ) > 1  ){
						woocommerce_mini_cart();
					} ?>
				</div>
			</div>

		<?php } else { ?>
			<div class="constrau-cart-wrapper style2 style3">

				<div class="d-flex">
					<div class="label">
						<div class="info"><?php printf( "Your cart is <span>%d</span> item",  WC()->cart->get_cart_contents_count() , 'ova-framework'  ); ?>      </div>
					</div>
					<div class="cart-total">
						<i class="icon_bag_alt"></i>
						<span class="items">
							<?php 
							if( is_object( WC()->cart ) ){
								echo (WC()->cart->get_cart_contents_count( ) > 1) ? WC()->cart->get_cart_contents_count( ) : WC()->cart->get_cart_contents_count( );
							}
							?>
						</span>
					</div>
				</div>
				<div class="constrau_minicart">
					<?php  if( is_object( WC()->cart && WC()->cart->get_cart_contents_count( ) > 1 ) ){
						woocommerce_mini_cart();
					} ?>
				</div>
			</div>
		<?php } ?>

	<?php } ?>
	<?php return ob_get_clean();
}


/* Recent Project Shortcode */
add_shortcode( 'recent_project', 'recent_project_shortcode' );
function recent_project_shortcode( $atts ) {
	$args_base = array(
		'post_type'      => 'project',
		'post_status'    => 'publish',
	);
	$args_basic = shortcode_atts( array(
		'order'          => 'DESC',
		'orderby'        => 'date',
		'posts_per_page' => '3',
	), $atts );

	$args = array_merge_recursive( $args_base, $args_basic );
	$my_posts = new WP_Query( $args );

	ob_start();
	?>
	<ul class="recent_project">
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

			<li>
				<div class="img_feature">
					<?php the_post_thumbnail('feature_image_project'); ?>
				</div>
				<div class="content">
					<a class="title second_font" href="<?php echo esc_attr(get_the_permalink()); ?>"><?php the_title();?></a>
					<p class="cat"><?php echo esc_html($category_name) ?></p>
				</div>
			</li>

		<?php endwhile; endif; wp_reset_postdata(); 
		?>
	</ul>
	<?php
	return ob_get_clean();
}



/* Recent Project Shortcode */
add_shortcode( 'brochures', 'brochures_shortcode' );
function brochures_shortcode( $atts, $content = null ) {
	$args = shortcode_atts( array(
		'title' => 'Download Brochures',
		'desc' => 'Nam scelerisque leo vel interdum scelerisque. Nulla pretium cursus est nec mollis. Fusce luctus nisl quis leo maximus congue.',
	), $atts );

	ob_start();
	?>
	<div class="brochures">
		<p class="title second_font"><?php echo esc_html($args['title']) ?></p>
		<p class="desc"><?php echo esc_html($args['desc']) ?></p>
		<?php echo do_shortcode($content); ?>
	</div>
	<?php
	return ob_get_clean();
}


/* Button Sidebar Shortcode */
add_shortcode( 'button_sidebar', 'button_sidebar_shortcode' );
function button_sidebar_shortcode( $atts ) {
	$args = shortcode_atts( array(
		'icon' => 'far fa-file-pdf',
		'link' => '#',
		'text' => 'Info Company'
	), $atts );

	ob_start();
	?>
	<a class="button_sidebar" href="<?php echo esc_attr($args['link']); ?>">
		<i class="<?php echo esc_attr($args['icon']); ?>"></i>
		<span><?php echo esc_html($args['text']); ?></span>
	</a>
	<?php
	return ob_get_clean();
}

add_shortcode( 'info_contact', 'info_contact_shortcode' );
function info_contact_shortcode( $atts, $content = null ) {
	$args = shortcode_atts( array(
		'title' => 'We can help you',
	), $atts );

	ob_start();
	?>
	<div class="info_contact">
		<p class="title second_font"><?php echo esc_html($args['title']) ?></p>
		<ul class="content">
			<?php echo do_shortcode($content); ?>
		</ul>
	</div>
	<?php
	return ob_get_clean();
}

add_shortcode( 'info_detail', 'info_detail_shortcode' );
function info_detail_shortcode( $atts ) {
	$args = shortcode_atts( array(
		'icon' => 'zmdi zmdi-phone',
		'info' => '(205)-509-1845',
	), $atts );

	ob_start();
	?>
	<li>
		<i class="<?php echo esc_attr($args['icon']); ?>"></i>
		<span class="info"><?php echo esc_html($args['info']) ?></span>
	</li>
	
	<?php
	return ob_get_clean();
}


?>
