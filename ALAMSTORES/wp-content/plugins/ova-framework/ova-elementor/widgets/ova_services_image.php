<?php
namespace ova_framework\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


class ova_services_image extends Widget_Base {

	public function get_name() {
		return 'ova_services_image';
	}

	public function get_title() {
		return __( 'Services Image', 'ova-framework' );
	}

	public function get_icon() {
		return 'eicon-info-box';
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
					'type_3' => __( 'Type 3', 'ova-framework' ),
				],
			]
		);

		$this->add_control(
			'image',
			[
				'label' => __( 'Image', 'ova-framework' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
			]
		);

		$this->add_control(
			'image_mobile',
			[
				'label' => __( 'Image Mobile', 'ova-framework' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
				'condition' => [
					'services_type' => 'type_3',
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
				'condition' => [
					'services_type' => 'type_3',
				],
			]
		);

		$this->add_control(
			'text_title', 
			[
				'label' => __( 'Title', 'ova-framework' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'Design/Build' , 'ova-framework' ),
				'label_block' => true,
			]
		);

		$this->add_control(
			'text_desc', 
			[
				'label' => __( 'Description', 'ova-framework' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'Duis ac eros rutrum, vehicula lorem nec, scelerisque tellus. Donec volutpat, arcu at molestie tincidunt.' , 'ova-framework' ),
				'label_block' => true,
			]
		);

		$this->add_control(
			'text_read_more', 
			[
				'label' => __( 'Read More', 'ova-framework' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'LEARN MORE' , 'ova-framework' ),
				'label_block' => true,
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


		/********* Style Icon *********/
		$this->start_controls_section(
			'section_style_icon',
			[
				'label' => __( 'Icon', 'ova-framework' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'services_type' => 'type_3',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'icon_typo',
				'label' => __( 'Typography', 'ova-framework' ),
				'selector' => '{{WRAPPER}} .ova_services_image .content .icon i:before',
				'condition' => [
					'services_type' => 'type_3',
				],
			]
		);

		$this->add_control(
			'icon_color',
			[
				'label' => __( 'Color', 'ova-framework' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ova_services_image .content .icon' => 'color: {{VALUE}}',
				],
				'condition' => [
					'services_type' => 'type_3',
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
					'{{WRAPPER}} .ova_services_image .content .icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'services_type' => 'type_3',
				],
			]
		);
		$this->end_controls_section();
		/********* End Style Icon *********/



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
				'selector' => '{{WRAPPER}} .ova_services_image .content .title',
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => __( 'Color', 'ova-framework' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ova_services_image .content .title' => 'color: {{VALUE}}',
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
					'{{WRAPPER}} .ova_services_image .content .title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
				'selector' => '{{WRAPPER}} .ova_services_image .content .desc',
			]
		);

		$this->add_control(
			'desc_color',
			[
				'label' => __( 'Color', 'ova-framework' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ova_services_image .content .desc' => 'color: {{VALUE}}',
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
					'{{WRAPPER}} .ova_services_image .content .desc' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
				'selector' => '{{WRAPPER}} .ova_services_image .content .read_more',
			]
		);

		$this->add_control(
			'readmore_color',
			[
				'label' => __( 'Color', 'ova-framework' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ova_services_image .content .read_more, .ova_services_image .type_2 .content .read_more i:before' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'readmore_color_hover',
			[
				'label' => __( 'Hover', 'ova-framework' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ova_services_image .content .read_more:hover, .ova_services_image .type_2 .content .read_more:hover i:before' => 'color: {{VALUE}}',
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
					'{{WRAPPER}} .ova_services_image .content .read_more' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_section();
		/********* End Style Read More *********/
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		
		$services_type = $settings['services_type'];

		$image = $settings['image'];
		$image_mobile = $settings['image_mobile'];
		$class_icon = $settings['class_icon'];
		$text_title = $settings['text_title'];
		$text_desc = $settings['text_desc'];
		$text_read_more = $settings['text_read_more'];

		$read_more_url = $settings['read_more_link']['url'];
		$read_more_target = $settings['read_more_link']['is_external'] ? 'target="_blank"' : '';
		$read_more_nofollow = $settings['read_more_link']['nofollow'] ? 'rel="nofollow"' : '';
		?>

		<div class="ova_services_image">
			<div class="<?php echo esc_attr( $services_type ); ?>">
				<div class="img">
					<img class="img_ipad" src="<?php echo esc_attr( $image['url'] ); ?>" alt="img">
					<img class="img_mobile" src="<?php echo esc_attr( $image_mobile['url'] ); ?>" alt="img">
				</div>
				<div class="content">
					<div class="icon">
						<i class="<?php echo esc_attr($class_icon); ?>"></i>
					</div>
					<p class="title second_font"><?php echo esc_html($text_title) ?></p>
					<p class="desc"><?php echo esc_html($text_desc) ?></p>
					<a class="read_more" href="<?php echo esc_attr($read_more_url); ?>" <?php echo $read_more_target . $read_more_nofollow; ?> >
						<span><?php echo esc_html($text_read_more) ?></span>
						<i class="arrow_flaticon-029-right-arrow-12"></i>
					</a>
				</div>

			</div>
		</div>


		<?php
	}
}