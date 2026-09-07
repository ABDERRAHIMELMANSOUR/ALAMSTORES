<?php
namespace ova_framework\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


class ova_services_slide extends Widget_Base {

	public function get_name() {
		return 'ova_services_slide';
	}

	public function get_title() {
		return __( 'Services Slide', 'ova-framework' );
	}

	public function get_icon() {
		return 'eicon-posts-carousel';
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
		
		/********* Section Content *********/
		$this->start_controls_section(
			'content_section',
			[
				'label' => __( 'Content', 'ova-framework' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'services_type',
			[
				'label' => __( 'Services Type', 'ova-framework' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'type_1',
				'options' => [
					'type_1'  => __( 'Type 1', 'ova-framework' ),
					'type_2' => __( 'Type 2', 'ova-framework' ),
				],
			]
		);

		$repeater = new \Elementor\Repeater();


		$repeater->add_control(
			'image',
			[
				'label' => __( 'Choose Image', 'ova-framework' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
			]
		);
		
		$repeater->add_control(
			'list_title', 
			[
				'label' => __( 'Title', 'ova-framework' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'Design/Build' , 'ova-framework' ),
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'list_desc', [
				'label' => __( 'Description', 'ova-framework' ),
				'type' => \Elementor\Controls_Manager::WYSIWYG,
				'default' => __( 'Duis ac eros rutrum, vehicula lorem nec, scelerisque tellus. Donec volutpat, arcu at molestie tincidunt.' , 'ova-framework' ),
				'show_label' => false,
			]
		);

		$repeater->add_control(
			'list_read_more', 
			[
				'label' => __( 'Read More', 'ova-framework' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'LEARN MORE' , 'ova-framework' ),
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'read_more_link',
			[
				'label' => __( 'Link Read More', 'ova-framework' ),
				'type' => \Elementor\Controls_Manager::URL,
				'placeholder' => __( 'https://your-link.com', 'ova-framework' ),
				'show_external' => true,
				'default' => [
					'url' => '',
					'is_external' => false,
					'nofollow' => false,
				],
			]
		);

		$this->add_control(
			'list',
			[
				'label' => __( 'Repeater List', 'ova-framework' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'image' => \Elementor\Utils::get_placeholder_image_src(),
						'list_title' => __( 'Design/Build', 'ova-framework' ),
						'list_desc' => __( 'Duis ac eros rutrum, vehicula lorem nec, scelerisque tellus. Donec volutpat, arcu at molestie tincidunt.', 'ova-framework' ),
						'list_read_more'=> __( 'LEARN MORE', 'ova-framework' ),
						'read_more_link'=> \Elementor\Controls_Manager::URL,
					],
					[
						'image' => \Elementor\Utils::get_placeholder_image_src(),
						'list_title' => __( 'Bridge Construction', 'ova-framework' ),
						'list_desc' => __( 'Duis ac eros rutrum, vehicula lorem nec, scelerisque tellus. Donec volutpat, arcu at molestie tincidunt.', 'ova-framework' ),
						'list_read_more'=> __( 'LEARN MORE', 'ova-framework' ),
						'read_more_link'=> \Elementor\Controls_Manager::URL,
					],
					[
						'image' => \Elementor\Utils::get_placeholder_image_src(),
						'list_title' => __( 'Renovation and Expansion', 'ova-framework' ),
						'list_desc' => __( 'Duis ac eros rutrum, vehicula lorem nec, scelerisque tellus. Donec volutpat, arcu at molestie tincidunt.', 'ova-framework' ),
						'list_read_more'=> __( 'LEARN MORE', 'ova-framework' ),
						'read_more_link'=> \Elementor\Controls_Manager::URL,
					],
				],
				'title_field' => '{{{ list_title }}}',
			]
		);

		$this->end_controls_section();
		/********* End Section Content *********/

		$this->start_controls_section(
			'section_slider_options',
			[
				'label' => __( 'Slider Options', 'ova-framework' ),
				'type' => Controls_Manager::SECTION,
			]
		);

		$this->add_control(
			'items_owl',
			[
				'label' => __( 'Items', 'ova-framework' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 3,
			]
		);

		$this->add_control(
			'margin_owl',
			[
				'label' => __( 'Margin', 'ova-framework' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 30,
			]
		);
		
		$this->add_control(
			'autoplay_owl',
			[
				'label' => __( 'Autoplay', 'ova-framework' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',
			]

		);

		$this->add_control(
			'autoplay_speed_owl',
			[
				'label' => __( 'Autoplay Speed (ms)', 'ova-framework' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 1000,
				'condition' => [
					'autoplay_owl' => 'yes',
				],
				
			]
		);

		$this->add_control(
			'infinite_owl',
			[
				'label' => __( 'Infinite Loop', 'ova-framework' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		// $this->add_control(
		// 	'lazy_load_owl',
		// 	[
		// 		'label' => __( 'Lazy Load', 'ova-framework' ),
		// 		'type' => Controls_Manager::SWITCHER,
		// 		'default' => 'yes',
		// 	]
		// );

		$this->add_control(
			'owl_nav_prev_1',
			[
				'label' => __( 'Class Nav Prev', 'ova-framework' ),
				'type' => Controls_Manager::TEXT,
				'default' => 'pointer_flaticon-025-left-arrow-2',
				'placeholder' => 'pointer_flaticon-025-left-arrow-2',
				'conditions' => [
					'terms' => [
						[
							'name' => 'services_type',
							'operator' => '=',
							'value' => 'type_1',
						],
					],
				],
			]
		);

		$this->add_control(
			'owl_nav_next_1',
			[
				'label' => __( 'Class Nav Next', 'ova-framework' ),
				'type' => Controls_Manager::TEXT,
				'default' => 'pointer_flaticon-017-right-arrow-2',
				'placeholder' => 'pointer_flaticon-017-right-arrow-2',
				'conditions' => [
					'terms' => [
						[
							'name' => 'services_type',
							'operator' => '=',
							'value' => 'type_1',
						],
					],
				],
			]
		);

		$this->add_control(
			'owl_nav_prev_2',
			[
				'label' => __( 'Class Nav Prev', 'ova-framework' ),
				'type' => Controls_Manager::TEXT,
				'default' => 'arrow_flaticon-050-left-arrow-7',
				'placeholder' => 'arrow_flaticon-050-left-arrow-7',
				'conditions' => [
					'terms' => [
						[
							'name' => 'services_type',
							'operator' => '=',
							'value' => 'type_2',
						],
					],
				],
			]
		);

		$this->add_control(
			'owl_nav_next_2',
			[
				'label' => __( 'Class Nav Next', 'ova-framework' ),
				'type' => Controls_Manager::TEXT,
				'default' => 'arrow_flaticon-034-right-arrow-7',
				'placeholder' => 'arrow_flaticon-034-right-arrow-7',
				'conditions' => [
					'terms' => [
						[
							'name' => 'services_type',
							'operator' => '=',
							'value' => 'type_2',
						],
					],
				],
			]
		);
		$this->end_controls_section();
		

		/********* Style Title *********/
		$this->start_controls_section(
			'section_style_title',
			[
				'label' => __( 'Title', 'ova-framework' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typo',
				'label' => __( 'Typography', 'ova-framework' ),
				'selector' => '{{WRAPPER}} .ova_services_slide .content .title',
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => __( 'Color', 'ova-framework' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ova_services_slide .content .title' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'title_color_hover',
			[
				'label' => __( 'Hover', 'ova-framework' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ova_services_slide .item:hover .content .title' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'title_padding',
			[
				'label' => __( 'Padding', 'ova-framework' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .ova_services_slide .content .title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_section();
		/********* End Style Title *********/
		

		/********* Style Description *********/
		$this->start_controls_section(
			'section_style_desc',
			[
				'label' => __( 'Description', 'ova-framework' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'desc_typo',
				'label' => __( 'Typography', 'ova-framework' ),
				'selector' => '{{WRAPPER}} .ova_services_slide .content .desc',
			]
		);

		$this->add_control(
			'desc_color',
			[
				'label' => __( 'Color', 'ova-framework' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ova_services_slide .content .desc' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'desc_color_hover',
			[
				'label' => __( 'Hover', 'ova-framework' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ova_services_slide .item:hover .content .desc' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'desc_padding',
			[
				'label' => __( 'Padding', 'ova-framework' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .ova_services_slide .content .desc' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_section();
		/********* End Style Description *********/
		

		/********* Style Read More *********/
		$this->start_controls_section(
			'section_style_readmore',
			[
				'label' => __( 'Read More', 'ova-framework' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'readmore_typo',
				'label' => __( 'Typography', 'ova-framework' ),
				'selector' => '{{WRAPPER}} .ova_services_slide .content .read_more',
			]
		);

		$this->add_control(
			'readmore_color',
			[
				'label' => __( 'Color', 'ova-framework' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ova_services_slide .content .read_more, .ova_services_slide .type_2 .item .content .read_more i:before' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'readmore_color_hover',
			[
				'label' => __( 'Hover', 'ova-framework' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ova_services_slide .content .read_more:hover, .ova_services_slide .type_2 .item .content .read_more:hover i:before' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'readmore_padding',
			[
				'label' => __( 'Padding', 'ova-framework' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .ova_services_slide .content .read_more' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_section();
		/********* End Style Read More *********/
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		$autoplay_owl = ( 'yes' === $settings['autoplay_owl'] ) ? true : false;
		$loop_owl = ( 'yes' === $settings['infinite_owl'] ) ? true : false;
		// $lazyLoad_owl = ( 'yes' === $settings['lazy_load_owl'] ) ? true : false;
		// $class_lazyLoad_owl = ( 'yes' === $settings['lazy_load_owl'] ) ? 'owl-lazy' : '';
		$nav_prev = $settings['services_type'] == 'type_1' ? $settings['owl_nav_prev_1'] : $settings['owl_nav_prev_2'];
		$nav_next = $settings['services_type'] == 'type_1' ? $settings['owl_nav_next_1'] : $settings['owl_nav_next_2'];

		$owl_carousel = [
			'items'           => $settings['items_owl'],
			'autoplayTimeout' => $settings['autoplay_speed_owl'],
			'autoplay'        => $autoplay_owl,
			'loop'            => $loop_owl,
			// 'lazyLoad'        => $lazyLoad_owl,
			'nav'             => true,
			'dots'            => true,
			'margin'          => $settings['margin_owl'],
			'navText' => [
				'<i class="'.$nav_prev.'"></i>',
				'<i class="'.$nav_next.'"></i>'
			],
			'responsive' => [
				'0' => [
					'items' => 1,
					'nav'=> true
				],
				'600' => [
					'items' => 2,
					'nav'=> true
				],
				'1025' => [
					'items' => $settings['items_owl'],
					'nav'=> true
				],
			]
		];

		?>
		<div class="ova_services_slide">
			<div class="<?php echo esc_attr($settings['services_type']); ?>">
				<?php if ( $settings['list'] ) { ?> 
					<div class="wrap_items owl-carousel" data-owl_carousel="<?php echo esc_attr( wp_json_encode( $owl_carousel) ); ?>">
						<?php foreach (  $settings['list'] as $key => $item ) { 

							$read_more_url = $item['read_more_link']['url'];
							$read_more_target = $item['read_more_link']['is_external'] ? 'target="_blank"' : '';
							$read_more_nofollow = $item['read_more_link']['nofollow'] ? 'rel="nofollow"' : '';

							?>
							<div class="item">
								<img class="img" src="<?php echo esc_attr( $item['image']['url'] ); ?>" alt="img">
								<div class="content">
									<p class="title second_font"><?php echo esc_html($item['list_title']) ?></p>
									<p class="desc"><?php echo esc_html($item['list_desc']) ?></p>
									<a class="read_more" href="<?php echo esc_attr($read_more_url); ?>" <?php echo $read_more_target . $read_more_nofollow; ?> >
										<span><?php echo esc_html($item['list_read_more']) ?></span>
										<i class="arrow_flaticon-029-right-arrow-12"></i>
									</a>
								</div>
							</div>
						<?php } ?>
					</div>
				<?php } ?>
			</div>
		</div>
		<?php
	}
}