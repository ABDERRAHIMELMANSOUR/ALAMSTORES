<?php

namespace ova_framework\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Core\Schemes\Typography;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Color;


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class ova_heading extends Widget_Base {

	public function get_name() {
		return 'ova_heading';
	}

	public function get_title() {
		return __( 'Ova Heading', 'ova-framework' );
	}

	public function get_icon() {
		return 'eicon-heading';
	}

	public function get_categories() {
		return [ 'ovatheme' ];
	}

	public function get_script_depends() {
		return [ 'script-elementor' ];
	}

	protected function register_controls() {


		$this->start_controls_section(
			'section_heading_content',
			[
				'label' => __( 'Content', 'ova-framework' ),
			]
		);


		$this->add_control(
			'sub_title',
			[
				'label' => __( 'Sub Title', 'ova-framework' ),
				'type' => Controls_Manager::TEXTAREA,
				'row' => 2,
				'default' => __('Working With Us','ova-framework'),
			]
		);

		$this->add_control(
			'html_tag_sub_title',
			[
				'label' => __( 'HTML Tag Sub Title', 'ova-framework' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'h4',
				'options' => [
					'h1' => "H1",
					'h2' => "H2",
					'h3' => "H3",
					'h4' => "H4",
					'h5' => "H5",
					'h6' => "H6",
					'div' => "div",
					'span' => "Span",
					'p' => "p",
				]
			]
		);

		$this->add_control(
			'title',
			[
				'label' => __( 'Heading Title', 'ova-framework' ),
				'type' => Controls_Manager::TEXTAREA,
				'row' => 5,
				'default' => __('Where Your Dreams Are Built','ova-framework'),
			]
		);

		$this->add_control(
			'html_tag_title',
			[
				'label' => __( 'HTML Tag Title', 'ova-framework' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'h3',
				'options' => [
					'h1' => "H1",
					'h2' => "H2",
					'h3' => "H3",
					'h4' => "H4",
					'h5' => "H5",
					'h6' => "H6",
					'div' => "div",
					'span' => "Span",
					'p' => "p",
				]
			]
		);

		$this->add_control(
			'desc',
			[
				'label' => __( 'Description', 'ova-framework' ),
				'type' => Controls_Manager::WYSIWYG,
				'default' => __('If you have had the experience, we have had great opportunities to develop and develop your career.','ova-framework'),
			]
		);

		$this->add_control(
			'button',
			[
				'label' => __( 'Text Button', 'ova-framework' ),
				'type' => Controls_Manager::TEXT,
				'default' => __('See More Job opportunities','ova-framework'),
			]
		);

		$this->add_control(
			'link',
			[
				'label' => __( 'Link', 'ova-framework' ),
				'type' => \Elementor\Controls_Manager::URL,
				'placeholder' => __( 'https://your-link.com', 'ova-framework' ),
				'show_external' => true,
				'default' => [
					'url' => '#',
					'is_external' => false,
					'nofollow' => false,
				],
			]
		);

		$this->add_control(
			'show_line_left',
			[
				'label' => __( 'Show Line Left', 'ova-framework' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'ova-framework' ),
				'label_off' => __( 'Hide', 'ova-framework' ),
				'return_value' => 'yes',
				'default' => 'yes',
				'selectors' => [
					'{{WRAPPER}} .ova-heading .sub-title span' => 'display: inline-block;',
				],
			]
		);

		$this->add_control(
			'show_line_right',
			[
				'label' => __( 'Show Line Right', 'ova-framework' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'ova-framework' ),
				'label_off' => __( 'Hide', 'ova-framework' ),
				'return_value' => 'yes',
				'default' => 'yes',
				'selectors' => [
					'{{WRAPPER}} .ova-heading .sub-title:before' => 'display: inline-block;',
					'{{WRAPPER}} .ova-heading .sub-title:after' => 'display: inline-block;',
				],
			]
		);

		$this->add_responsive_control(
			'align',
			[
				'label' => __( 'Alignment', 'ova-framework' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'ova-framework' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'ova-framework' ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'ova-framework' ),
						'icon' => 'fa fa-align-right',
					],
				],
				'default' => 'center',
				'selectors' => [
					'{{WRAPPER}} .ova-heading' => 'text-align: {{VALUE}}',
				]
			]
		);



		$this->end_controls_section();


		$this->start_controls_section(
			'section_sub_title',
			[
				'label' => __( 'Sub Title', 'ova-framework' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'sub_title_typography',
				'selector' => '{{WRAPPER}} .ova-heading .sub-title',
				'scheme' => Typography::TYPOGRAPHY_1,
			]
		);

		$this->add_control(
			'color_sub_title',
			[
				'label' => __( 'Color', 'ova-framework' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ova-heading .sub-title' => 'color : {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'bg_color',
			[
				'label' => __( 'Background Color Line', 'ova-framework' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ova-heading .sub-title:before' => 'background-color : {{VALUE}};',
					'{{WRAPPER}} .ova-heading .sub-title:after' => 'background-color : {{VALUE}};',
					'{{WRAPPER}} .ova-heading .sub-title span:before' => 'background-color : {{VALUE}};',
					'{{WRAPPER}} .ova-heading .sub-title span:after' => 'background-color : {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'position_left',
			[
				'label' => __( 'Position Line left', 'ova-framework' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => -100,
						'max' => 100,
						'step' => 1,
					],
				],
				'selectors' => [
					
					'{{WRAPPER}} .ova-heading .sub-title span:before' => 'bottom: calc(4px + {{SIZE}}{{UNIT}});',
					'{{WRAPPER}} .ova-heading .sub-title span:after' => 'bottom: calc(0px + {{SIZE}}{{UNIT}});',
				],
			]
		);

		$this->add_control(
			'position_right',
			[
				'label' => __( 'Position Line right', 'ova-framework' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => -100,
						'max' => 100,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .ova-heading .sub-title:before' => 'bottom: calc(11px + {{SIZE}}{{UNIT}});',
					'{{WRAPPER}} .ova-heading .sub-title:after' => 'bottom: calc(7px + {{SIZE}}{{UNIT}});',
				],
			]
		);

		$this->add_responsive_control(
			'margin_sub_title',
			[
				'label' => __( 'Margin', 'ova-framework' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .ova-heading .sub-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

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
				'name' => 'title_typography',
				'selector' => '{{WRAPPER}} .ova-heading .title',
				'scheme' => Typography::TYPOGRAPHY_1,
			]
		);

		$this->add_control(
			'color_title',
			[
				'label' => __( 'Color ', 'ova-framework' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ova-heading .title' => 'color : {{VALUE}};',
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
					'{{WRAPPER}} .ova-heading .title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

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
				'selector' => '{{WRAPPER}} .ova-heading .desc',
				'scheme' => Typography::TYPOGRAPHY_1,
			]
		);

		$this->add_control(
			'color_desc',
			[
				'label' => __( 'Color ', 'ova-framework' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ova-heading .desc' => 'color : {{VALUE}};',
					'{{WRAPPER}} .ova-heading .desc p' => 'color : {{VALUE}};',
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
					'{{WRAPPER}} .ova-heading .desc' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_button',
			[
				'label' => __( 'Button', 'ova-framework' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'button_typography',
				'selector' => '{{WRAPPER}} .ova-heading .button a',
				'scheme' => Typography::TYPOGRAPHY_1,
			]
		);

		$this->add_control(
			'color_button',
			[
				'label' => __( 'Color ', 'ova-framework' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ova-heading .button a' => 'color : {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'color_button_hover',
			[
				'label' => __( 'Color Hover', 'ova-framework' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ova-heading .button a:hover' => 'color : {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'bg_color_button',
			[
				'label' => __( 'Background Color ', 'ova-framework' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ova-heading .button a' => 'background-color : {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'bg_color_button_hover',
			[
				'label' => __( 'Background Color Hover', 'ova-framework' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ova-heading .button a:hover' => 'background-color : {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'margin_button',
			[
				'label' => __( 'Margin', 'ova-framework' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .ova-heading .button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();


	}

	protected function render() {
		$settings = $this->get_settings();

		$sub_title = $settings['sub_title'];
		$title = $settings['title'];
		$desc = $settings['desc'];
		$button = $settings['button'];
		$tag_sub_title = $settings['html_tag_sub_title'];
		$tag_title = $settings['html_tag_title'];
		
		?>
		<div class="ova-heading">
			<?php if (!empty($sub_title)) : ?>
				<<?php echo esc_attr($tag_sub_title) ?> class="sub-title second_font"><span></span><?php echo esc_html($sub_title) ?></<?php echo esc_attr($tag_sub_title) ?>>
			<?php endif ?>
			<?php if (!empty($title)) : ?>
				<<?php echo esc_attr($tag_title) ?> class="title second_font"><?php echo esc_html($title) ?></<?php echo esc_attr($tag_title) ?>>
			<?php endif ?>

			<?php if (!empty($desc)) : ?>
				<div class="desc"><?php echo do_shortcode($desc) ?></div>
			<?php endif ?>
			<?php if (!empty($button)) : ?>
				<div class="button">
					<?php $target = $settings['link']['is_external'] ? '_blank' : '_self';  ?>
					<a target="<?php echo esc_attr($target); ?>" href="<?php echo esc_attr($settings['link']['url']) ?>"><?php echo esc_html($button) ?></a>
				</div>
			<?php endif ?>
		</div>
		<?php

	}
}


