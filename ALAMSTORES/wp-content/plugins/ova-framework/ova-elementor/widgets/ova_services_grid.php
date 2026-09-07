<?php
namespace ova_framework\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


class ova_services_grid extends Widget_Base {

	public function get_name() {
		return 'ova_services_grid';
	}

	public function get_title() {
		return __( 'Services Grid', 'ova-framework' );
	}

	public function get_icon() {
		return 'eicon-posts-grid';
	}

	public function get_categories() {
		return [ 'ovatheme' ];
	}

	public function get_script_depends() {
		return [ 'script-elementor' ];
	}

	protected function register_controls() {

		/********* Section Content *********/
		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Content', 'ova-framework' ),
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

		$this->add_control(
			'class_icon',
			[
				'label' => __( 'Class Icon', 'ova-framework' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'label_block' => true,
				'default' => __( 'contrc_line_flaticon-ruler-and-pencil', 'ova-framework' ),
				'placeholder' => __( 'contrc_line_flaticon-ruler-and-pencil', 'ova-framework' ),
			]
		);


		$this->add_control(
			'text_title',
			[
				'label' => __( 'Title', 'ova-framework' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'label_block' => true,
				'default' => __( 'Design/Build', 'ova-framework' ),
				'placeholder' => __( 'Design/Build', 'ova-framework' ),
			]
		);

		$this->add_control(
			'text_desc',
			[
				'label' => __( 'Description', 'ova-framework' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'label_block' => true,
				'default' => __( 'Nunc a aliquam nibh, ac mollis lectus. Integer laoreet orci dtictum blandit blandit. Cras aliquam, quam mattis.', 'ova-framework' ),
				'placeholder' => __( 'Nunc a aliquam nibh, ac mollis lectus. Integer laoreet orci dtictum blandit blandit. Cras aliquam, quam mattis.', 'ova-framework' ),
			]
		);

		$this->add_control(
			'text_read_more',
			[
				'label' => __( 'Read More', 'ova-framework' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'Learn More', 'ova-framework' ),
				'placeholder' => __( 'Learn More', 'ova-framework' ),
			]
		);

		$this->add_control(
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

		
		$this->end_controls_section();
		/********* End Section Content *********/
		

		/********* Style General *********/
		$this->start_controls_section(
			'section_general_style',
			[
				'label' => __( 'Genral', 'ova-framework' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'box_shadow',
				'label' => __( 'Box Shadow', 'ova-framework' ),
				'selector' => '{{WRAPPER}} .ova_services_grid .type_1, .ova_services_grid .type_2',
			]
		);

		$this->add_control(
			'background_color',
			[
				'label' => __( 'Background', 'ova-framework' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ova_services_grid .type_1, .ova_services_grid .type_2' => 'background: {{VALUE}}!important',
				],
			]
		);

		$this->add_control(
			'background_color_hover',
			[
				'label' => __( 'Background Hover', 'ova-framework' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ova_services_grid .type_1:hover, .ova_services_grid .type_2:hover' => 'background: {{VALUE}}!important',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'border',
				'label' => __( 'Border', 'ova-framework' ),
				'selector' => '{{WRAPPER}} .ova_services_grid .type_1, .ova_services_grid .type_2',
			]
		);

		$this->end_controls_section();
		/********* End Style General *********/


		/*** Style Title ***/
		$this->start_controls_section(
			'section_title_style',
			[
				'label' => __( 'Title', 'ova-framework' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => __( 'Color', 'ova-framework' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ova_services_grid .title' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'title_color_hover',
			[
				'label' => __( 'Color Hover', 'ova-framework' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ova_services_grid:hover .title' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typo',
				'label' => __( 'Typography', 'ova-framework' ),
				'selector' => '{{WRAPPER}} .ova_services_grid .title',
			]
		);

		$this->add_control(
			'title_margin',
			[
				'label' => __( 'Margin', 'ova-framework' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .ova_services_grid .title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .ova_services_grid .title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_section();


		/*** Style Icon ***/
		$this->start_controls_section(
			'section_icon_style',
			[
				'label' => __( 'Icon', 'ova-framework' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'icon_color',
			[
				'label' => __( 'Color', 'ova-framework' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ova_services_grid .icon' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'icon_color_hover',
			[
				'label' => __( 'Color Hover', 'ova-framework' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ova_services_grid:hover .icon' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'icon_typo',
				'label' => __( 'Typography', 'ova-framework' ),
				'selector' => '{{WRAPPER}} .ova_services_grid .icon i:before',
			]
		);

		$this->add_control(
			'icon_margin',
			[
				'label' => __( 'Margin', 'ova-framework' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .ova_services_grid .icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'icon_padding',
			[
				'label' => __( 'Padding', 'ova-framework' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .ova_services_grid .icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_section();


		/*** Style Description ***/
		$this->start_controls_section(
			'section_desc_style',
			[
				'label' => __( 'Description', 'ova-framework' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'desc_color',
			[
				'label' => __( 'Color', 'ova-framework' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ova_services_grid .desc' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'desc_color_hover',
			[
				'label' => __( 'Color Hover', 'ova-framework' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ova_services_grid:hover .desc' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'desc_typo',
				'label' => __( 'Typography', 'ova-framework' ),
				'selector' => '{{WRAPPER}} .ova_services_grid .desc',
			]
		);

		$this->add_control(
			'desc_margin',
			[
				'label' => __( 'Margin', 'ova-framework' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .ova_services_grid .desc' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .ova_services_grid .desc' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_section();



		/*** Style Description ***/
		$this->start_controls_section(
			'section_read_more_style',
			[
				'label' => __( 'Read More', 'ova-framework' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'read_more_color',
			[
				'label' => __( 'Color', 'ova-framework' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ova_services_grid .read_more' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'read_more_color_hover_wrap',
			[
				'label' => __( 'Color Hover Wrap', 'ova-framework' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ova_services_grid:hover .read_more' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'read_more_color_hover',
			[
				'label' => __( 'Color Hover', 'ova-framework' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ova_services_grid .read_more:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'read_more_typo',
				'label' => __( 'Typography', 'ova-framework' ),
				'selector' => '{{WRAPPER}} .ova_services_grid .read_more',
			]
		);

		$this->add_control(
			'read_more_margin',
			[
				'label' => __( 'Margin', 'ova-framework' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .ova_services_grid .read_more' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'read_more_padding',
			[
				'label' => __( 'Padding', 'ova-framework' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .ova_services_grid .read_more' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_section();

	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		$services_type = $settings['services_type'];
		$text_title = $settings['text_title'];
		$text_desc = $settings['text_desc'];
		$text_read_more = $settings['text_read_more'];

		$read_more_url = $settings['read_more_link']['url'];
		$read_more_target = $settings['read_more_link']['is_external'] ? ' target="_blank"' : '';
		$read_more_nofollow = $settings['read_more_link']['nofollow'] ? ' rel="nofollow"' : '';

		$class_icon = $settings['class_icon'];
		// 'data-owl_carousel' => wp_json_encode( $owl_carousel),

		?>

		<div class="ova_services_grid" >
			<div class="<?php echo esc_attr( $services_type ); ?>">
				<div class="icon">
					<i class="<?php echo esc_attr($class_icon); ?>"></i>
				</div>
				<div class="content">
					<p class="title second_font"><?php echo esc_html($text_title); ?></p>
					<p class="desc"><?php echo esc_html($text_desc); ?></p>
					<a class="read_more" href="<?php echo esc_attr($read_more_url); ?>" <?php echo $read_more_target . $read_more_nofollow; ?>><?php echo esc_html($text_read_more); ?></a>
				</div>
			</div>
		</div>

		<?php
	}
}
