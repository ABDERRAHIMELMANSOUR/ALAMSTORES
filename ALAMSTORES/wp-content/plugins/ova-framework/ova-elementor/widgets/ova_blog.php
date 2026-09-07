<?php
namespace ova_framework\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Core\Schemes\Typography;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Color;
use Elementor\Group_Control_Border;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class ova_blog extends Widget_Base {

	public function get_name() {
		return 'ova_blog';
	}

	public function get_title() {
		return __( 'Blog', 'ova-framework' );
	}

	public function get_icon() {
		return 'eicon-posts-ticker';
	}

	public function get_categories() {
		return [ 'ovatheme' ];
	}

	public function get_script_depends() {
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
				'default' => 4,
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

		//section style title
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
					'{{WRAPPER}} .ova-blog .item-blog .ova-media .date .unit' => 'color : {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'bg_color_date',
			[
				'label' => __( 'Background color', 'ova-framework' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ova-blog .item-blog .ova-media .date' => 'background-color : {{VALUE}};',
				],
			]
		);


		$this->end_controls_section();

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
				'selector' => '{{WRAPPER}} .ova-blog .item-blog .content .title h3 a',
				'scheme' => Typography::TYPOGRAPHY_1,
			]
		);



		$this->add_control(
			'color_title',
			[
				'label' => __( 'Color Title', 'ova-framework' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ova-blog .item-blog .content .title h3 a' => 'color : {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'color_title_hover',
			[
				'label' => __( 'Color title hover', 'ova-framework' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ova-blog .item-blog .content .title h3 a:hover' => 'color : {{VALUE}};',
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
					'{{WRAPPER}} .ova-blog .item-blog .content .title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
				'selector' => '{{WRAPPER}} .ova-blog .item-blog .content .post-meta-blog a',
				'scheme' => Typography::TYPOGRAPHY_1,
			]
		);



		$this->add_control(
			'color_meta',
			[
				'label' => __( 'Color', 'ova-framework' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ova-blog .item-blog .content .post-meta-blog a' => 'color : {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'color_meta_hover',
			[
				'label' => __( 'Color hover', 'ova-framework' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ova-blog .item-blog .content .post-meta-blog a:hover' => 'color : {{VALUE}};',
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
					'{{WRAPPER}} .ova-blog .item-blog .content .post-meta-blog' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
				'selector' => '{{WRAPPER}} .ova-blog .item-blog .content .excerpt p',
				'scheme' => Typography::TYPOGRAPHY_1,
			]
		);



		$this->add_control(
			'color_desc',
			[
				'label' => __( 'Color', 'ova-framework' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ova-blog .item-blog .content .excerpt p' => 'color : {{VALUE}};',
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
					'{{WRAPPER}} .ova-blog .item-blog .content .excerpt' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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

		?>
		<div class="ova-blog">

			<?php
			$i = 0;
			if($blog->have_posts()) : while($blog->have_posts()) : $blog->the_post();
				$i++;
				$thumbnail_url = $i == 1 ? wp_get_attachment_image_url(get_post_thumbnail_id() , 'full' ) : "";
				?>

				<div class="item-blog">
					<div class="ova-media">
						<?php if (!empty($thumbnail_url)) : ?>
							<img src="<?php echo esc_attr($thumbnail_url) ?>" alt="<?php echo esc_attr(get_the_title()) ?>">
						<?php endif ?>
						<span class="date">
							<span class="unit second_font day"><?php the_time( 'd' );?></span>
							<span class="unit second_font month"><?php the_time( 'M, Y' );?></span>
						</span>
					</div>
					
					<div class="content">

						<?php if($settings['show_title']) : ?>
							<div class="title">
								<h3><a class="second_font" href="<?php echo esc_attr(get_the_permalink()) ?>"><?php echo esc_html(constrau_custom_text(get_the_title(), $settings['number_title'])) ?></a></h3>
							</div>
						<?php endif ?>

						<?php if($settings['show_meta']) : ?>
							<div class="post-meta-blog second_font">
								<i class="zmdi zmdi-tag"></i>
								<?php the_category('&sbquo;&nbsp;'); ?>
							</div>
						<?php endif ?>					

						<?php if ($settings['show_excerpt']) : ?>
							<div class="excerpt">
								<p><?php echo esc_html(constrau_custom_text(get_the_excerpt(), $settings['number_excerpt'])) ?></p>
							</div>
						<?php endif ?>

					</div>
				</div>
				<?php
			endwhile; endif; wp_reset_postdata();
			?>

		</div>
		<?php
	}
}
