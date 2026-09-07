<?php
namespace ovapo_elementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Background;


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class ovapo_project_grid extends Widget_Base {

	public function get_name() {
		return 'ovapo_project_grid';
	}

	public function get_title() {
		return __( 'Project Grid', 'ova-project' );
	}

	public function get_icon() {
		return 'eicon-gallery-grid';
	}

	public function get_categories() {
		return [ 'ovatheme' ];
	}

	public function get_script_depends() {
		wp_enqueue_script( 'slick-script', OVAPO_PLUGIN_URI.'assets/libs/slick/slick/slick.min.js', array('jquery'), false, true );
		
		return [ 'script-elementor' ];
	}

	protected function register_controls() {

		$this->start_controls_section(
			'section_setting',
			[
				'label' => __( 'Settings', 'ova-project' ),
			]
		);

		$this->add_control(
			'heading_setting_layout',
			[
				'label' => __( 'Layout', 'ova-project' ),
				'type' => \Elementor\Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'type',
			[
				'label' => __( 'Type', 'ova-project' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'grid',
				'options' => [
					'grid' => __( 'Grid', 'ova-project' ),
					'full_width'  => __( 'Full Width', 'ova-project' ),
				],
			]
		);

		$this->add_control(
			'column',
			[
				'label' => __( 'Column', 'ova-project' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'four_column',
				'options' => [
					'three_column' => __( '3 Columns', 'ova-project' ),
					'four_column'  => __( '4 Columns', 'ova-project' ),
				],
			]
		);

		$this->add_responsive_control(
			'filter_align',
			[
				'label' => __( 'Alignment', 'ova-project' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options' => [
					'flex-start' => [
						'title' => __( 'Left', 'ova-project' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'ova-project' ),
						'icon' => 'fa fa-align-center',
					],
					'flex-end' => [
						'title' => __( 'Right', 'ova-project' ),
						'icon' => 'fa fa-align-right',
					],
				],
				'default' => 'center',
				'selectors' => [
					'{{WRAPPER}} .ovapo_project_grid .button-filter' => 'justify-content: {{VALUE}}',
				],
				'toggle' => false,
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
			'exclude_cat',
			[
				'label' => __( 'Excluded Categories', 'ova-project' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'placeholder' => 'ID category',
				'description' => 'ID category, example: 5, 7'
			]
		);

		$this->add_control(
			'show_filter',
			[
				'label' => __( 'Show Filter', 'ova-project' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'ova-project' ),
				'label_off' => __( 'Hide', 'ova-project' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'show_all',
			[
				'label' => __( 'Show All', 'ova-project' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'ova-project' ),
				'label_off' => __( 'Hide', 'ova-project' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'show_featured',
			[
				'label' => __( 'Show Only Featured', 'ova-project' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'ova-project' ),
				'label_off' => __( 'Hide', 'ova-project' ),
				'return_value' => 'yes',
				'default' => 'no',
			]
		);


		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_fillter',
			[
				'label' => __( 'Button Fillter', 'ova-project' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'button_filter_typo',
				'label' => __( 'Typography', 'ova-project' ),
				'selector' => '{{WRAPPER}} .ovapo_project_grid .button-filter button',
			]
		);

		$this->add_control(
			'button_filter_color',
			[
				'label' => __( 'Color', 'ova-project' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ovapo_project_grid .button-filter button' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'button_filter_color_hover',
			[
				'label' => __( 'Color Hover', 'ova-project' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ovapo_project_grid .button-filter button:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'button_filter_color_active',
			[
				'label' => __( 'Color Active', 'ova-project' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ovapo_project_grid .button-filter button.active' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'button_filter_padding',
			[
				'label' => __( 'Padding', 'ova-project' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .ovapo_project_grid .button-filter' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_content',
			[
				'label' => __( 'Content', 'ova-project' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'background_heading',
			[
				'label' => __( 'Background', 'ova-project' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'item_background',
				'label' => __( 'Background', 'ova-project' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .ovapo_project_grid .items',
			]
		);

		$this->add_control(
			'title_heading',
			[
				'label' => __( 'Title', 'ova-project' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => __( 'Color', 'ova-project' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ovapo_project_grid .item .title' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'title_hover_color',
			[
				'label' => __( 'Hover', 'ova-project' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ovapo_project_grid .item .title:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typo',
				'label' => __( 'Typography', 'ova-project' ),
				'selector' => '{{WRAPPER}} .ovapo_project_grid .item .title',
			]
		);

		$this->add_control(
			'cat_heading',
			[
				'label' => __( 'Category', 'ova-project' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'cat_color',
			[
				'label' => __( 'Color', 'ova-project' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ovapo_project_grid .item .cat' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'cat_typo',
				'label' => __( 'Typography', 'ova-project' ),
				'selector' => '{{WRAPPER}} .ovapo_project_grid .item .cat',
			]
		);


		$this->end_controls_section();

	}

	protected function render() {

		$settings = $this->get_settings();

		$number_post = $settings['number_post'];
		$order_post = $settings['order_post'];
		$orderby_post = $settings['orderby_post'];
		$type = $settings['type'];
		$column = $settings['column'];
		$show_all = $settings['show_all'];
		$show_featured = $settings['show_featured'];
		$show_filter = $settings['show_filter'];
		$exclude_cat = $settings['exclude_cat'];

		$cat_exclude = array(
			'exclude' => explode(", ",$exclude_cat), 
		);


		$terms = get_terms('project_cat', $cat_exclude);
		$count = count($terms);

		$term_id_filter = array();
		foreach ( $terms as $term ) {
			$term_id_filter[] = $term->term_id;
		}

		$term_id_filter_string = implode(", ", $term_id_filter);

		$first_term = $terms[0]->term_id;
		$args_base = array(
			'post_type' => 'project',
			'post_status' => 'publish',
			'posts_per_page' => $number_post,
			'order' => $order_post,
			'orderby' => $orderby_post,
		);


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

		if ($show_all !== 'yes') {
			$args_cat = array(
				'tax_query' => array(
					array(
						'taxonomy' => 'project_cat',
						'field'    => 'id',
						'terms'    => $first_term,
					)
				)
			);

			$args = array_merge_recursive($args_cat, $args_base, $args_featured);
			$my_posts = new \WP_Query( $args );

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

			$args = array_merge_recursive($args_cat, $args_base, $args_featured);
			$my_posts = new \WP_Query( $args );
		}

		?>
		<?php if( $my_posts->have_posts() ): ?>

			<div class="ovapo_project_grid <?php echo esc_attr($type); ?>">
				<?php if ($show_filter == 'yes') { ?>
					<div class="button-filter <?php echo esc_attr($type == 'full_width' ? 'container' : '' );?> ">
						<?php if ($show_all == 'yes') { ?>
							<button data-filter="<?php echo esc_attr( 'all' ); ?>" data-order="<?php echo esc_attr($order_post); ?>" data-orderby="<?php echo esc_attr($orderby_post); ?>" data-first_term="<?php echo esc_attr($first_term); ?>" data-term_id_filter_string="<?php echo esc_attr($term_id_filter_string); ?>" data-number_post="<?php echo esc_attr($number_post); ?>" data-column='<?php echo esc_attr($column); ?>' data-show_featured="<?php echo esc_attr($show_featured); ?>" class="second_font" >
								<?php esc_html_e( 'All', 'ova-project' ); ?>
							</button>
						<?php } ?>

						<?php if ( $count > 0 ){
							foreach ( $terms as $term ) { ?>
								<button data-filter="<?php echo esc_attr($term->term_id); ?>" data-order="<?php echo esc_attr($order_post); ?>" data-orderby="<?php echo esc_attr($orderby_post); ?>" data-first_term="<?php echo esc_attr($first_term); ?>" data-term_id_filter_string="<?php echo esc_attr($term_id_filter_string); ?>" data-number_post="<?php echo esc_attr($number_post); ?>" data-column='<?php echo esc_attr($column); ?>' data-show_featured="<?php echo esc_attr($show_featured); ?>" class="second_font" 
									>
									<?php esc_html_e( $term->name, 'ova-project' ); ?>
								</button>
							<?php }
						} ?>
					</div>
				<?php } ?>
				
				<div class="content">
					
					<div class="items">
						<?php while( $my_posts->have_posts() ) : $my_posts->the_post(); 

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
						<?php endwhile ?>
					</div>

					<div class="wrap_loader">
						<svg class="loader" width="50" height="50">
							<circle cx="25" cy="25" r="10" stroke="#a1a1a1"/>
							<circle cx="25" cy="25" r="20" stroke="#a1a1a1"/>
						</svg>
					</div>
				</div>

			</div>

		<?php else : 
			?>
			<div class="search_not_found">
				<?php esc_html_e( 'Not Found Project', 'ova-project' ); ?>
			</div>
		<?php endif ?>
		<?php
	}
}
?>