<?php
namespace ova_framework\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Core\Schemes\Typography;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Color;
use Elementor\Group_Control_Border;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class ova_blog_slider extends Widget_Base {

	public function get_name() {
		return 'ova_blog_slider';
	}

	public function get_title() {
		return __( 'Blog SLider', 'ova-framework' );
	}

	public function get_icon() {
		return 'eicon-post-slider';
	}

	public function get_categories() {
		return [ 'ovatheme' ];
	}

	public function get_script_depends() {
		wp_enqueue_style( 'owl-carousel', OVA_PLUGIN_URI.'assets/libs/owl-carousel/assets/owl.carousel.min.css' );
		wp_enqueue_script( 'owl-carousel', OVA_PLUGIN_URI.'assets/libs/owl-carousel/owl.carousel.min.js', array('jquery'), false, true );
		return [ 'script-elementor' ];
	}

	protected function register_controls() {

		$args = array(
			'orderby' => 'name',
			'order' => 'ASC'
		);

		$categories=get_categories($args);
		$cate_array = array();
		$arrayCateAll = array( 'all' => 'All categories ' );
		if ($categories) {
			foreach ( $categories as $cate ) {
				$cate_array[$cate->cat_name] = $cate->slug;
			}
		} else {
			$cate_array["No content Category found"] = 0;
		}




		//SECTION CONTENT
		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Content', 'ova-framework' ),
			]
		);

		$this->add_control(
			'version',
			[
				'label' => __('Version', 'ova-framework'),
				'type' => Controls_Manager::SELECT,
				'default' => 'version_1',
				'options' => [
					'version_1' => __('Version 1', 'ova-framework'),
					'version_2' => __('Version 2', 'ova-framework'),
				]
			]
		);


		$this->add_control(
			'category',
			[
				'label' => __( 'Category', 'ova-framework' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'all',
				'options' => array_merge($arrayCateAll,$cate_array),
			]
		);

		$this->add_control(
			'total_count',
			[
				'label' => __( 'Total Post', 'ova-framework' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 3,
			]
		);

		$this->add_control(
			'number_title',
			[
				'label' => __( 'Number Word Title', 'ova-framework' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 6,
			]
		);

		$this->add_control(
			'number_excerpt',
			[
				'label' => __( 'Number Word Excerpt', 'ova-framework' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 25,
			]
		);


		$this->add_control(
			'order_by',
			[
				'label' => __('Order By', 'ova-framework'),
				'type' => Controls_Manager::SELECT,
				'default' => 'desc',
				'options' => [
					'asc' => __('ASC', 'ova-framework'),
					'desc' => __('DESC', 'ova-framework'),
				]
			]
		);

		$this->add_control(
			'readmore',
			[
				'label' => __( 'Text Read More', 'ova-framework' ),
				'type' => Controls_Manager::TEXT,
				'default' => __('READ MORE', 'ova-framework'),
			]
		);

		$this->add_control(
			'show_meta',
			[
				'label' => __( 'Show Meta', 'ova-framework' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'ova-framework' ),
				'label_off' => __( 'Hide', 'ova-framework' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'show_date',
			[
				'label' => __( 'Show Date', 'ova-framework' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'ova-framework' ),
				'label_off' => __( 'Hide', 'ova-framework' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'show_title',
			[
				'label' => __( 'Show Title', 'ova-framework' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'ova-framework' ),
				'label_off' => __( 'Hide', 'ova-framework' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);



		$this->add_control(
			'show_excerpt',
			[
				'label' => __( 'Show Excerpt', 'ova-framework' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'ova-framework' ),
				'label_off' => __( 'Hide', 'ova-framework' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);
		

		$this->end_controls_section();
		//END SECTION CONTENT

		/*****************************************************************
		START SECTION ADDITIONAL VERSIONT 1 TESTIMONIAL
		******************************************************************/

		$this->start_controls_section(
			'section_additional_options',
			[
				'label' => __( 'Additional Options', 'ova-framework' ),
			]
		);


		$this->add_control(
			'margin_items',
			[
				'label' => __( 'Margin Right Items', 'ova-framework' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 30,
			]

		);


		$this->add_control(
			'slides_to_scroll',
			[
				'label' => __( 'Slides to Scroll', 'ova-framework' ),
				'type' => Controls_Manager::NUMBER,
				'description' => __( 'Set how many slides are scrolled per swipe.', 'ova-framework' ),
				'default' => '1',
			]
		);

		$this->add_control(
			'pause_on_hover',
			[
				'label' => __( 'Pause on Hover', 'ova-framework' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'options' => [
					'yes' => __( 'Yes', 'ova-framework' ),
					'no' => __( 'No', 'ova-framework' ),
				],
				'frontend_available' => true,
			]
		);


		$this->add_control(
			'infinite',
			[
				'label' => __( 'Infinite Loop', 'ova-framework' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'options' => [
					'yes' => __( 'Yes', 'ova-framework' ),
					'no' => __( 'No', 'ova-framework' ),
				],
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'autoplay',
			[
				'label' => __( 'Autoplay', 'ova-framework' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'options' => [
					'yes' => __( 'Yes', 'ova-framework' ),
					'no' => __( 'No', 'ova-framework' ),
				],
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'autoplay_speed',
			[
				'label' => __( 'Autoplay Speed', 'ova-framework' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 3000,
				'step' => 500,
				'condition' => [
					'autoplay' => 'yes',
				],
				'frontend_available' => true,
				'condition' => [
					'autoplay' => 'yes',
				],
			]
		);

		$this->add_control(
			'smartspeed',
			[
				'label'   => __( 'Smart Speed', 'ova-framework' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 500,

			]
		);

		$this->add_control(
			'nav',
			[
				'label' => __('Show Navigation', 'ova-framework'),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'dots',
			[
				'label'   => __('Show dot', 'ova-framework'),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'no',
			]
		);


		$this->end_controls_section();
		#########################    END SECTION ADDITIONAL  VERSION 1  #########################


		//section style title
		$this->start_controls_section(
			'section_title',
			[
				'label' => __( 'Title', 'ova-framework' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_link_typography',
				'selector' => '{{WRAPPER}} .ova-blog-slider .item-blog .content .title h3 a',
				'scheme' => Typography::TYPOGRAPHY_1,
			]
		);



		$this->add_control(
			'color_title',
			[
				'label' => __( 'Color Title', 'ova-framework' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ova-blog-slider .item-blog .content .title h3 a' => 'color : {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'color_title_hover',
			[
				'label' => __( 'Color title hover', 'ova-framework' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ova-blog-slider .item-blog .content .title h3 a:hover' => 'color : {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'margin_title',
			[
				'label' => __( 'Margin', 'ova-framework' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .ova-blog-slider .item-blog .content .title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();


		//section style meta
		$this->start_controls_section(
			'section_meta',
			[
				'label' => __( 'Meta', 'ova-framework' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'meta_link_typography',
				'selector' => '{{WRAPPER}} .ova-blog-slider .item-blog .content .post-meta-blog a',
				'scheme' => Typography::TYPOGRAPHY_1,
			]
		);



		$this->add_control(
			'color_meta',
			[
				'label' => __( 'Color', 'ova-framework' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ova-blog-slider .item-blog .content .post-meta-blog a' => 'color : {{VALUE}};',
					'{{WRAPPER}} .ova-blog-slider .item-blog .content .post-meta-blog .meta-slash' => 'color : {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'color_meta_hover',
			[
				'label' => __( 'Color hover', 'ova-framework' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ova-blog-slider .item-blog .content .post-meta-blog a:hover' => 'color : {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'margin_meta',
			[
				'label' => __( 'Margin', 'ova-framework' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .ova-blog-slider .item-blog .content .post-meta-blog ' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_date',
			[
				'label' => __( 'Date', 'ova-framework' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);



		$this->add_control(
			'color_date',
			[
				'label' => __( 'Color ', 'ova-framework' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ova-blog-slider .item-blog .ova-media .date .unit' => 'color : {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'bg_color_date',
			[
				'label' => __( 'Background color', 'ova-framework' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ova-blog-slider .item-blog .ova-media .date' => 'background-color : {{VALUE}};',
				],
			]
		);


		$this->end_controls_section();

		//section style meta
		$this->start_controls_section(
			'section_icon',
			[
				'label' => __( 'Icon', 'ova-framework' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);


		$this->add_control(
			'color_icon',
			[
				'label' => __( 'Color', 'ova-framework' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ova-blog-slider .item-blog .content .post-meta-blog i' => 'color : {{VALUE}};',
				],
			]
		);


		$this->end_controls_section();

		//section style desc
		$this->start_controls_section(
			'section_desc',
			[
				'label' => __( 'Description', 'ova-framework' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'desc_typography',
				'selector' => '{{WRAPPER}} .ova-blog-slider .item-blog .content .excerpt p',
				'scheme' => Typography::TYPOGRAPHY_1,
			]
		);



		$this->add_control(
			'color_desc',
			[
				'label' => __( 'Color', 'ova-framework' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ova-blog-slider .item-blog .content .excerpt p' => 'color : {{VALUE}};',
				],
			]
		);


		$this->add_responsive_control(
			'margin_desc',
			[
				'label' => __( 'Margin', 'ova-framework' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .ova-blog-slider .item-blog .content .excerpt' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		//section style meta
		$this->start_controls_section(
			'section_readmore',
			[
				'label' => __( 'Read More', 'ova-framework' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'readmorek_typography',
				'selector' => '{{WRAPPER}} .ova-blog-slider .blog-slider .item-blog .content .readmore a',
				'scheme' => Typography::TYPOGRAPHY_1,
			]
		);



		$this->add_control(
			'color_readmore',
			[
				'label' => __( 'Color', 'ova-framework' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ova-blog-slider .blog-slider .item-blog .content .readmore a' => 'color : {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'color_readmore_hover',
			[
				'label' => __( 'Color hover', 'ova-framework' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ova-blog-slider .blog-slider .item-blog .content .readmore a:hover' => 'color : {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'bg_color_readmore',
			[
				'label' => __( 'Background Color', 'ova-framework' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ova-blog-slider .blog-slider .item-blog .content .readmore a' => 'background-color : {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'bg_color_readmore_hover',
			[
				'label' => __( 'Background Color hover', 'ova-framework' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ova-blog-slider .blog-slider .item-blog .content .readmore a:hover' => 'background-color : {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'margin_readmore',
			[
				'label' => __( 'Margin', 'ova-framework' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .ova-blog-slider .blog-slider .item-blog .content .readmore ' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_nav',
			[
				'label' => __( 'Nav', 'ova-framework' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);




		$this->add_control(
			'color_nav',
			[
				'label' => __( 'Color', 'ova-framework' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ova-blog-slider .blog-slider .owl-nav button i:before' => 'color : {{VALUE}};',
					'{{WRAPPER}} .ova-blog-slider .blog-slider .owl-nav button' => 'border-color : {{VALUE}}!important;',
				],
			]
		);

		$this->add_control(
			'color_nav_hover',
			[
				'label' => __( 'Color hover', 'ova-framework' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ova-blog-slider .blog-slider .owl-nav button:hover i:before' => 'color : {{VALUE}};',
					'{{WRAPPER}} .ova-blog-slider .blog-slider .owl-nav button:hover' => 'border-color : {{VALUE}}!important;',
				],
			]
		);


		$this->end_controls_section();
	}

	/**
	 * Render the widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		
		$category = $settings['category'];
		$total_count = $settings['total_count'];
		$order = $settings['order_by'];

		$number_title = $settings['number_title'] ? $settings['number_title'] : 8;

		$args = [];
		if ($category == 'all') {
			$args=[
				'post_type' => 'post',
				'posts_per_page' => $total_count,
				'order' => $order,
			];
		} else {
			$args=[
				'post_type' => 'post', 
				'category_name'=>$category,
				'posts_per_page' => $total_count,
				'order' => $order,
			];
		}

		$blog = new \WP_Query($args);

		$data_options['slideBy'] 			= $settings['slides_to_scroll'];
		$data_options['margin'] 			= $settings['margin_items'];
		$data_options['autoplayHoverPause'] = $settings['pause_on_hover'] === 'yes' ? true : false;
		$data_options['loop'] 			 	= $settings['infinite'] === 'yes' ? true : false;
		$data_options['autoplay'] 			= $settings['autoplay'] === 'yes' ? true : false;
		$data_options['autoplayTimeout']	= $data_options['autoplay'] ? $settings['autoplay_speed'] : 3000;
		$data_options['smartSpeed']			= $settings['smartspeed'];
		$data_options['dots']                =  ( $settings['dots'] == 'yes') ? true : false;
		$data_options['nav']            	=  ( $settings['nav'] == 'yes') ? true : false;
		?>
		<div class="ova-blog-slider <?php echo esc_attr($settings['version']) ?>">
			<div class="blog-slider  owl-carousel owl-theme owl-loaded" data-options="<?php echo esc_attr(json_encode($data_options)) ?>">
				<?php
				if($blog->have_posts()) : while($blog->have_posts()) : $blog->the_post();
					$thumbnail_url =wp_get_attachment_image_url(get_post_thumbnail_id() , 'full' );
					?>

					<div class="item-blog">
						<?php if (!empty($thumbnail_url)) : ?>
							<div class="ova-media">
								<img src="<?php echo esc_attr($thumbnail_url) ?>" alt="<?php echo esc_attr(get_the_title()) ?>">
								<?php if($settings['show_date']) : ?>
									<span class="date">
										<span class="unit second_font day"><?php the_time( 'd' );?></span>
										<span class="unit second_font month"><?php the_time( 'M, Y' );?></span>
									</span>
								<?php endif ?>
							</div>
						<?php endif ?>

						<div class="content">

							<?php if($settings['show_title']) : ?>
								<div class="title">
									<h3><a class="second_font" href="<?php echo esc_attr(get_the_permalink()) ?>"><?php echo esc_html(constrau_custom_text(get_the_title(), $settings['number_title'])) ?></a></h3>
								</div>
							<?php endif ?>

							<?php if($settings['show_meta']) : ?>
								<div class="post-meta-blog">
									<span class="category second_font">
										<i class="zmdi zmdi-tag"></i>
										<?php the_category('&sbquo;&nbsp;'); ?>
										<span class="meta-slash">|</span>
									</span>
									<span class="author second_font">
										<span class="left"><i class="zmdi zmdi-edit"></i></span>
										<span class="right"><a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"><?php the_author_meta( 'display_name' ); ?></a></span>
									</span>
								</div>
							<?php endif ?>					

							<?php if ($settings['show_excerpt']) : ?>
								<div class="excerpt">
									<p><?php echo esc_html(constrau_custom_text(get_the_excerpt(), $settings['number_excerpt'])) ?></p>
								</div>
							<?php endif ?>

							<?php if ($settings['readmore']) : ?>
								<div class="readmore">
									<a href="<?php echo esc_attr(get_the_permalink()) ?>"><?php echo esc_html($settings['readmore']) ?>
								</a>
							</div>
						<?php endif ?>

					</div>
				</div>
				<?php
			endwhile; endif; wp_reset_postdata();
			?>

		</div>
	</div>
	<?php
}
}
