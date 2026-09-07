<?php
namespace ovapo_elementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class ovapo_project_slide extends Widget_Base {

	public function get_name() {
		return 'ovapo_project_slide';
	}

	public function get_title() {
		return __( 'Project Slide', 'ova-project' );
	}

	public function get_icon() {
		return 'eicon-carousel';
	}

	public function get_categories() {
		return [ 'ovatheme' ];
	}

	public function get_script_depends() {

		wp_enqueue_style( 'owl-carousel', OVAPO_PLUGIN_URI.'assets/libs/owl-carousel/assets/owl.carousel.min.css' );
		wp_enqueue_script( 'owl-carousel', OVAPO_PLUGIN_URI.'assets/libs/owl-carousel/owl.carousel.min.js', array('jquery'), false, true );
		
		return [ 'script-elementor' ];
	}

	protected function register_controls() {

		$this->start_controls_section(
			'section_settings',
			[
				'label' => __( 'Settings', 'ova-project' ),
			]
		);

		$this->add_control(
			'heading_setting_post',
			[
				'label' => __( 'Setting Post', 'ova-project' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'number_post',
			[
				'label' => __( 'Number Post', 'ova-project' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'default' => 8,
			]
		);

		$this->add_control(
			'orderby_post',
			[
				'label' => __( 'OrderBy Post', 'ova-project' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'date',
				'options' => [
					'date' => __( 'Date', 'ova-project' ),
					'id'  => __( 'ID', 'ova-project' ),
					'title' => __( 'Title', 'ova-project' ),
				],
			]
		);

		$this->add_control(
			'order_post',
			[
				'label' => __( 'Order Post', 'ova-project' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'DESC',
				'options' => [
					'ASC' => __( 'Ascending', 'ova-project' ),
					'DESC'  => __( 'Descending', 'ova-project' ),
				],
			]
		);

		$this->add_control(
			'text_readmore',
			[
				'label' => __( 'Text Read More', 'ova-project' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => 'Read More',
			]
		);

		$this->add_control(
			'heading_setting_slide',
			[
				'label' => __( 'Setting Slide', 'ova-project' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'owl_margin',
			[
				'label' => __( 'Margin', 'ova-project' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'default' => 30,
			]
		);

		$this->add_control(
			'owl_show_nav',
			[
				'label' => __( 'Show Nav', 'ova-project' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		/*	$this->add_control(
			'owl_show_dots_mobile',
			[
				'label' => __( 'Show Dots Mobile', 'ova-project' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',
			]
		);*/


		$this->add_control(
			'owl_autoplay',
			[
				'label' => __( 'Autoplay', 'ova-project' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',
			]

		);

		$this->add_control(
			'owl_autoplay_speed',
			[
				'label' => __( 'Autoplay Speed (ms)', 'ova-project' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 5000,
				'condition' => [
					'owl_autoplay' => 'yes',
				],
				
			]
		);

		$this->add_control(
			'owl_loop',
			[
				'label' => __( 'Infinite Loop', 'ova-project' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'owl_lazyload',
			[
				'label' => __( 'Lazy Load', 'ova-project' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'owl_nav_prev',
			[
				'label' => __( 'Class Nav Prev', 'ova-project' ),
				'type' => Controls_Manager::TEXT,
				'default' => 'pointer_flaticon-025-left-arrow-2',
				'placeholder' => 'pointer_flaticon-025-left-arrow-2'
			]
		);

		$this->add_control(
			'owl_nav_next',
			[
				'label' => __( 'Class Nav Next', 'ova-project' ),
				'type' => Controls_Manager::TEXT,
				'default' => 'pointer_flaticon-017-right-arrow-2',
				'placeholder' => 'pointer_flaticon-017-right-arrow-2'
			]
		);


		$this->end_controls_section();

		/* Start Style Section */
		$this->start_controls_section(
			'section_style',
			[
				'label' => __( 'Style', 'ova-project' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		/* Style Tittle */
		$this->add_control(
			'heading_style_title',
			[
				'label' => __( 'Title', 'ova-project' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typo',
				'label' => __( 'Typography', 'ova-project' ),
				'selector' => '{{WRAPPER}} .ovapo_project_slide .grid .grid-item .title',
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => __( 'Color', 'ova-project' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ovapo_project_slide .grid .grid-item .title' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'title_hover_color',
			[
				'label' => __( 'Hover', 'ova-project' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ovapo_project_slide .grid .grid-item .title:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'title_margin',
			[
				'label' => __( 'Margin', 'ova-project' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .ovapo_project_slide .grid .grid-item .title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'title_padding',
			[
				'label' => __( 'Padding', 'ova-project' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .ovapo_project_slide .grid .grid-item .title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		/* End Style Tittle */

		/* Style Read More */
		$this->add_control(
			'cat_style_heading',
			[
				'label' => __( 'Category', 'ova-project' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'cat_typo',
				'label' => __( 'Typography', 'ova-project' ),
				'selector' => '{{WRAPPER}} .ovapo_project_slide .grid .grid-item .cat',
			]
		);

		$this->add_control(
			'cat_margin',
			[
				'label' => __( 'Margin', 'ova-project' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .ovapo_project_slide .grid .grid-item .cat' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'cat_padding',
			[
				'label' => __( 'Padding', 'ova-project' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .ovapo_project_slide .grid .grid-item .cat' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'cat_color',
			[
				'label' => __( 'Hover', 'ova-project' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ovapo_project_slide .grid .grid-item .cat' => 'color: {{VALUE}}',
				],
			]
		);

		/* End Style Read More */


		$this->end_controls_section();
		/* End Style Section */

	}

	protected function render() {

		$settings = $this->get_settings();

		$number_post = $settings['number_post'];
		$order_post = $settings['order_post'];
		$orderby_post = $settings['orderby_post'];
		$text_readmore = $settings['text_readmore'];
		$owl_nav_prev = $settings['owl_nav_prev'];
		$owl_nav_next = $settings['owl_nav_next'];

		$args_base = array(
			'post_type' => 'project',
			'post_status' => 'publish',
			'posts_per_page' => $number_post,
			'order' => $order_post,
			'orderby' => $orderby_post,
		);
		$args = new \WP_Query( $args_base );


		// $owl_show_dots_mobile = ( 'yes' === $settings['owl_show_dots_mobile'] ) ? true : false;
		$owl_show_nav         = ( 'yes' === $settings['owl_show_nav'] ) ? true : false;
		$owl_margin           = $settings['owl_margin'];
		$owl_autoplay         = ( 'yes' === $settings['owl_autoplay'] ) ? true : false;
		$owl_loop             = ( 'yes' === $settings['owl_loop'] ) ? true : false;
		$owl_lazyload         = ( 'yes' === $settings['owl_lazyload'] ) ? true : false;
		$owl_autoplay_speed   = $settings['owl_autoplay_speed'];
		$owl_mouseDrag        = $args->found_posts == 1 ? false : true;

		$data = [
			'items'           => 3,
			'margin'          => $owl_margin,
			'dots'            => false,
			'nav'             => $owl_show_nav,
			'autoplay'        => $owl_autoplay,
			'autoplayTimeout' => $owl_autoplay_speed,
			'loop'            => $owl_loop,
			'lazyLoad'        => $owl_lazyload,
			'mouseDrag'       => $owl_mouseDrag,
			'navText' => [
				'<i class="'.$owl_nav_prev.'"></i>',
				'<i class="'.$owl_nav_next.'"></i>'
			],
			'responsive' => [
				'0'  => [
					'items'  => 1,
				],
				'500'  => [
					'items'  => 2,
					// 'dots' => $owl_show_dots_mobile,
				],
				'768'  => [
					'items'  => 3,
				]
			]
		];

		?>

		<div class="ovapo_project_slide">
			<?php if ($args->have_posts()) { ?>
				<div class="grid owl-carousel owl-theme" data-owl="<?php echo esc_attr(wp_json_encode( $data )); ?>">
					<?php while( $args->have_posts() ) : $args->the_post(); 
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

						<div class="grid-item">
							<img class="<?php echo $owl_lazyload == 'yes' ? esc_attr('owl-lazy') : '' ?>" data-src="<?php echo esc_attr(the_post_thumbnail_url('feature_image_project')); ?>" src="<?php echo esc_attr(the_post_thumbnail_url('feature_image_project')); ?>" alt="#">
							<div class="info">
								<a href="<?php the_permalink(); ?>" class="title second_font"><?php the_title();?></a>
								<p class="cat"><?php echo esc_html($category_name); ?></p>
							</div>
						</div>
					<?php endwhile ?>
				</div>
			<?php } else { ?>
				<div class="no_project"><?php esc_html_e( 'Not Found Project', 'ova-project' ); ?></div>
			<?php } ?>
		</div>

		<?php
	}
}
?>