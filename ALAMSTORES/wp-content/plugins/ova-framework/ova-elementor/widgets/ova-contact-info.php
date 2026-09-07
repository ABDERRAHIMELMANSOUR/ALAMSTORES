<?php
namespace ova_framework\Widgets;
use Elementor;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Group_Control_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class ova_contact_info extends Widget_Base {

	public function get_name() {
		return 'ova_contact_info';
	}

	public function get_title() {
		return __( 'Contact Info', 'ova-framework' );
	}

	public function get_icon() {
		return 'fa fa-mobile';
	}

	public function get_categories() {
		return [ 'ovatheme' ];
	}

	public function get_keywords() {
		return [ 'contact', 'address', 'us' ];
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_contact_us',
			[
				'label' => __( 'Contact Us', 'ova-framework' ),
			]
		);
		$this->add_control(
			'info_type',
			[
				'label' => __('Info Type','ova-framework'),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'style1' => __('Type 1','ova-framework'),
					'style2' => __('Type 2','ova-framework'),
					'style3' => __('Type 3','ova-framework'),
					'style4' => __('Type 4','ova-framework'),
				],
				'default' => 'style1'
			]
		);

		$this->add_control(
			'heading_icon',
			[
				'label' => __( 'Icon', 'ova-framework' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'condition' => [
					'info_type!' => ['style3','style4'],  
				]
			]
		);

		$this->add_control(
			'icon',
			[
				'label' => __( 'Choice Icons', 'ova-framework' ),
				'type' => \Elementor\Controls_Manager::ICON,
				'default' => 'fa fa-facebook',
				'condition' => [
					'info_type!' => ['style3','style4'],  
				]
			]
		);

		$this->add_control(
			'icon_class',
			[
				'label' => __( 'Class Icons', 'ova-framework' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'placeholder' => __( 'zmdi zmdi-pin', 'ova-framework' ),
				'condition' => [
					'info_type!' => ['style3','style4'],  
				]
			]
		);

		$this->add_control(
			'color_icon',
			[
				'label' => __( 'Color', 'ova-framework' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ova_contact_us i' => 'color: {{VALUE}};',
				],
				'condition' => [
					'info_type!' => ['style3','style4'],  
				]
			]
		);

		$this->add_control(
			'hover_color_icon',
			[
				'label' => __( 'Hover Color', 'ova-framework' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ova_contact_us i:hover' => 'color: {{VALUE}};',
				],
				'condition' => [
					'info_type!' => ['style3','style4'],  
				]
			]
		);

		$this->add_control(
			'size_icon',
			[
				'label' => __( 'Size', 'ova-framework' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 5,
						'max' => 1000,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .ova_contact_us .icon' => 'font-size: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'info_type!' => ['style3','style4'],  
				]
			]
		);

		$this->add_responsive_control(
			'icon_margin',
			[
				'label' => __( 'Margin', 'ova-framework' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .ova_contact_us .icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'info_type!' => ['style3','style4'],  
				]
			]
		);

		$this->add_control(
			'heading_text',
			[
				'label' => __( 'Text', 'ova-framework' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'type_link_1',
			[
				'label' => __( 'Type Link', 'ova-framework' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'none',
				'options' => [
					'email' => __('Email', 'ova-framework'),
					'tell' => __('Tell', 'ova-framework'),
					'none' => __('None', 'ova-framework'),
				],
				'conditions'   => [
					'terms'    => [
						[
							'name'  => 'info_type',
							'value' => 'style1',
						],
					],
				]
			]
		);

		$this->add_control(
			'text',
			[
				'type' => \Elementor\Controls_Manager::TEXT,
				'placeholder' => __( 'Text', 'ova-framework' ),
				'label_block' => true,
				'default' => __( '(+88) 12 345 6789', 'ova-framework' ),
				'conditions'   => [
					'terms'    => [
						[
							'name'  => 'info_type',
							'value' => 'style1',
						],
					],
				]
			]
		);

		$this->add_control(
			'link_1',
			[
				'type' => \Elementor\Controls_Manager::TEXT,
				'label_block' => true,
				'default' => __( '(+88)123456789', 'ova-framework' ),
				'conditions'   => [
					'terms'    => [
						[
							'name'  => 'info_type',
							'value' => 'style1',
						],
					],
				]
			]
		);

		$this->add_control(
			'type_link_2_1',
			[
				'label' => __( 'Type Link', 'ova-framework' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'none',
				'options' => [
					'email' => __('Email', 'ova-framework'),
					'tell' => __('Tell', 'ova-framework'),
					'none' => __('None', 'ova-framework'),
				],
				'conditions'   => [
					'terms'    => [
						[
							'name'  => 'info_type',
							'value' => 'style2',
						],
					],
				]
			]
		);

		$this->add_control(
			'text_line_1',
			[
				'label' => __('Text Line 1','ova-framework'),
				'type' => \Elementor\Controls_Manager::TEXT,
				'placeholder' => __( 'Text', 'ova-framework' ),
				'label_block' => true,
				'default' => __( '(205)-509-1845', 'ova-framework' ),
				'conditions'   => [
					'terms'    => [
						[
							'name'  => 'info_type',
							'value' => 'style2',
						],
					],
				]
			]
		);

		$this->add_control(
			'link_line_1',
			[
				'label' => __('Link Line 1','ova-framework'),
				'type' => \Elementor\Controls_Manager::TEXT,
				'label_block' => true,
				'default' => __( '(205)-509-1845', 'ova-framework' ),
				'conditions'   => [
					'terms'    => [
						[
							'name'  => 'info_type',
							'value' => 'style2',
						],
					],
				]
			]
		);

		$this->add_control(
			'text_line_1_1',
			[
				'label' => __('Text Line 1','ova-framework'),
				'type' => \Elementor\Controls_Manager::TEXT,
				'placeholder' => __( 'Text', 'ova-framework' ),
				'label_block' => true,
				'default' => __( 'Call us for any question', 'ova-framework' ),
				'conditions'   => [
					'terms'    => [
						[
							'name'  => 'info_type',
							'value' => 'style3',
						],
					],
				]
			]
		);

		$this->add_control(
			'text_phone_4',
			[
				'label' => __('Text phone','ova-framework'),
				'type' => \Elementor\Controls_Manager::TEXT,
				'placeholder' => __( '(205)-509-1845', 'ova-framework' ),
				'label_block' => true,
				'default' => __( 'Phone: ', 'ova-framework' ),
				'conditions'   => [
					'terms'    => [
						[
							'name'  => 'info_type',
							'value' => 'style4',
						],
					],
				]
			]
		);

		$this->add_control(
			'text_line_1_2',
			[
				'label' => __('Phone Number','ova-framework'),
				'type' => \Elementor\Controls_Manager::TEXT,
				'placeholder' => __( '(205)-509-1845', 'ova-framework' ),
				'label_block' => true,
				'default' => __( '(205)-509-1845', 'ova-framework' ),
				'conditions'   => [
					'terms'    => [
						[
							'name'  => 'info_type',
							'value' => 'style4',
						],
					],
				]
			]
		);

		$this->add_control(
			'link_line_1_2',
			[
				'label' => __('Link Phone Number','ova-framework'),
				'type' => \Elementor\Controls_Manager::TEXT,
				'placeholder' => __( '(205)-509-1845', 'ova-framework' ),
				'label_block' => true,
				'default' => __( '(205)-509-1845', 'ova-framework' ),
				'conditions'   => [
					'terms'    => [
						[
							'name'  => 'info_type',
							'value' => 'style4',
						],
					],
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'text_line1_typo',
				'selector' => '{{WRAPPER}} .ova_contact_us.style2 .text-line span.line-info-1, {{WRAPPER}} .ova_contact_us.style2 .text-line span.line-info-1 a',
				'conditions'   => [
					'terms'    => [
						[
							'name'  => 'info_type',
							'value' => 'style2',
						],
					],
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'text_line1_1_typo',
				'selector' => '{{WRAPPER}} .ova_contact_us.style3 .text-line span.line-info-1, {{WRAPPER}} .ova_contact_us.style3 .text-line span.line-info-1 a',
				'conditions'   => [
					'terms'    => [
						[
							'name'  => 'info_type',
							'value' => 'style3',
						],
					],
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'text_line1_2_typo',
				'selector' => '{{WRAPPER}} .ova_contact_us.style4 .text-line span.line-info-1, {{WRAPPER}} .ova_contact_us.style4 .text-line span.line-info-1 a',
				'conditions'   => [
					'terms'    => [
						[
							'name'  => 'info_type',
							'value' => 'style4',
						],
					],
				]
			]
		);

		$this->add_control(
			'text_line1_color',
			[
				'label' => __( 'Color', 'ova-framework' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ova_contact_us.style2 .text-line span.line-info-1' => 'color: {{VALUE}};',
					'{{WRAPPER}} .ova_contact_us.style2 .text-line span.line-info-1 a' => 'color: {{VALUE}};',
				],
				'conditions'   => [
					'terms'    => [
						[
							'name'  => 'info_type',
							'value' => 'style2',
						],
					],
				]
			]
		);

		$this->add_control(
			'text_line1_1_color',
			[
				'label' => __( 'Color', 'ova-framework' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ova_contact_us.style3 .text-line span.line-info-1' => 'color: {{VALUE}};',
					'{{WRAPPER}} .ova_contact_us.style3 .text-line span.line-info-1 a' => 'color: {{VALUE}};',
				],
				'conditions'   => [
					'terms'    => [
						[
							'name'  => 'info_type',
							'value' => 'style3',
						],
					],
				]
			]
		);

		$this->add_control(
			'text_line1_2_color',
			[
				'label' => __( 'Color', 'ova-framework' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ova_contact_us.style4 .text-line span.line-info-1' => 'color: {{VALUE}};',
					'{{WRAPPER}} .ova_contact_us.style4 .text-line span.line-info-1 a' => 'color: {{VALUE}};',
				],
				'conditions'   => [
					'terms'    => [
						[
							'name'  => 'info_type',
							'value' => 'style4',
						],
					],
				]
			]
		);

		$this->add_control(
			'type_link_2_2',
			[
				'label' => __( 'Type Link', 'ova-framework' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'none',
				'options' => [
					'email' => __('Email', 'ova-framework'),
					'tell' => __('Tell', 'ova-framework'),
					'none' => __('None', 'ova-framework'),
				],
				'conditions'   => [
					'terms'    => [
						[
							'name'  => 'info_type',
							'value' => 'style2',
						],
					],
				]
			]
		);

		$this->add_control(
			'text_line_2',
			[
				'label' => __('Text Line 2','ova-framework'),
				'type' => \Elementor\Controls_Manager::TEXT,
				'placeholder' => __( 'Text', 'ova-framework' ),
				'label_block' => true,
				'default' => __( 'contact@constrau.com', 'ova-framework' ),
				'conditions'   => [
					'terms'    => [
						[
							'name'  => 'info_type',
							'value' => 'style2',
						],
					],
				]
			]
		);

		$this->add_control(
			'link_line_2',
			[
				'label' => __('Link Line 2','ova-framework'),
				'type' => \Elementor\Controls_Manager::TEXT,
				'label_block' => true,
				'default' => __( 'contact@constrau.com', 'ova-framework' ),
				'conditions'   => [
					'terms'    => [
						[
							'name'  => 'info_type',
							'value' => 'style2',
						],
					],
				]
			]
		);

		$this->add_control(
			'type_link_3',
			[
				'label' => __( 'Type Link', 'ova-framework' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'none',
				'options' => [
					'email' => __('Email', 'ova-framework'),
					'tell' => __('Tell', 'ova-framework'),
					'none' => __('None', 'ova-framework'),
				],
				'conditions'   => [
					'terms'    => [
						[
							'name'  => 'info_type',
							'value' => 'style3',
						],
					],
				]
			]
		);

		$this->add_control(
			'link_line_2_1',
			[
				'label' => __('Link Line 2','ova-framework'),
				'type' => \Elementor\Controls_Manager::TEXT,
				'placeholder' => __( 'Text', 'ova-framework' ),
				'label_block' => true,
				'default' => __( '(205)-509-1845', 'ova-framework' ),
				'conditions'   => [
					'terms'    => [
						[
							'name'  => 'info_type',
							'value' => 'style3',
						],
					],
				]
			]
		);

		$this->add_control(
			'text_line_2_1',
			[
				'label' => __('Link Line 2','ova-framework'),
				'type' => \Elementor\Controls_Manager::TEXT,
				'label_block' => true,
				'default' => __( '(205)-509-1845', 'ova-framework' ),
				'conditions'   => [
					'terms'    => [
						[
							'name'  => 'info_type',
							'value' => 'style3',
						],
					],
				]
			]
		);

		$this->add_control(
			'text_email_4',
			[
				'label' => __('Text Email','ova-framework'),
				'type' => \Elementor\Controls_Manager::TEXT,
				'label_block' => true,
				'default' => __( 'Email: ', 'ova-framework' ),
				'conditions'   => [
					'terms'    => [
						[
							'name'  => 'info_type',
							'value' => 'style4',
						],
					],
				]
			]
		);

		$this->add_control(
			'text_line_2_2',
			[
				'label' => __('Email','ova-framework'),
				'type' => \Elementor\Controls_Manager::TEXT,
				'placeholder' => __( 'contact@constrau.com', 'ova-framework' ),
				'label_block' => true,
				'default' => __( 'contact@constrau.com', 'ova-framework' ),
				'conditions'   => [
					'terms'    => [
						[
							'name'  => 'info_type',
							'value' => 'style4',
						],
					],
				]
			]
		);

		$this->add_control(
			'link_line_2_2',
			[
				'label' => __('Link Email','ova-framework'),
				'type' => \Elementor\Controls_Manager::TEXT,
				'placeholder' => __( 'contact@constrau.com', 'ova-framework' ),
				'label_block' => true,
				'default' => __( 'contact@constrau.com', 'ova-framework' ),
				'conditions'   => [
					'terms'    => [
						[
							'name'  => 'info_type',
							'value' => 'style4',
						],
					],
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'text_line2_typo',
				'selector' => '{{WRAPPER}} .ova_contact_us.style2 .text-line span.line-info-2, {{WRAPPER}} .ova_contact_us.style2 .text-line span.line-info-2 a',
				'conditions'   => [
					'terms'    => [
						[
							'name'  => 'info_type',
							'value' => 'style2',
						],
					],
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'text_line2_1_typo',
				'selector' => '{{WRAPPER}} .ova_contact_us.style3 .text-line span.line-info-2, {{WRAPPER}} .ova_contact_us.style3 .text-line span.line-info-2 a',
				'conditions'   => [
					'terms'    => [
						[
							'name'  => 'info_type',
							'value' => 'style3',
						],
					],
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'text_line2_2_typo',
				'selector' => '{{WRAPPER}} .ova_contact_us.style4 .text-line span.line-info-2, {{WRAPPER}} .ova_contact_us.style4 .text-line span.line-info-2 a',
				'conditions'   => [
					'terms'    => [
						[
							'name'  => 'info_type',
							'value' => 'style4',
						],
					],
				]
			]
		);

		$this->add_control(
			'text_line2_color',
			[
				'label' => __( 'Color', 'ova-framework' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ova_contact_us.style2 .text-line span.line-info-2' => 'color: {{VALUE}};',
					'{{WRAPPER}} .ova_contact_us.style2 .text-line span.line-info-2 a' => 'color: {{VALUE}};',
				],
				'conditions'   => [
					'terms'    => [
						[
							'name'  => 'info_type',
							'value' => 'style2',
						],
					],
				]
			]
		);

		$this->add_control(
			'text_line2_1_color',
			[
				'label' => __( 'Color', 'ova-framework' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ova_contact_us.style3 .text-line span.line-info-2' => 'color: {{VALUE}};',
					'{{WRAPPER}} .ova_contact_us.style3 .text-line span.line-info-2 a' => 'color: {{VALUE}};',
				],
				'conditions'   => [
					'terms'    => [
						[
							'name'  => 'info_type',
							'value' => 'style3',
						],
					],
				]
			]
		);

		$this->add_control(
			'text_line2_2_color',
			[
				'label' => __( 'Color', 'ova-framework' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ova_contact_us.style4 .text-line span.line-info-2' => 'color: {{VALUE}};',
					'{{WRAPPER}} .ova_contact_us.style4 .text-line span.line-info-2 a' => 'color: {{VALUE}};',
				],
				'conditions'   => [
					'terms'    => [
						[
							'name'  => 'info_type',
							'value' => 'style4',
						],
					],
				]
			]
		);


		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'text_typo',
				'selector' => '{{WRAPPER}} .ova_contact_us .text a, {{WRAPPER}} .ova_contact_us .text span',
				'conditions'   => [
					'terms'    => [
						[
							'name'  => 'info_type',
							'value' => 'style1',
						],
					],
				]
			]
		);

		$this->add_control(
			'text_color',
			[
				'label' => __( 'Color', 'ova-framework' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ova_contact_us .text a' => 'color: {{VALUE}};',
					'{{WRAPPER}} .ova_contact_us .text span' => 'color: {{VALUE}};',
				],
				'conditions'   => [
					'terms'    => [
						[
							'name'  => 'info_type',
							'value' => 'style1',
						],
					],
				]
			]
		);

		$this->add_control(
			'text_hover_color',
			[
				'label' => __( 'Hover Color', 'ova-framework' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ova_contact_us .text a:hover' => 'color: {{VALUE}};',
				],
				'conditions'   => [
					'terms'    => [
						[
							'name'  => 'info_type',
							'value' => 'style1',
						],
					],
				]
			]
		);

		$this->add_control(
			'icon_spacing',
			[
				'label' => __( 'Spacing', 'ova-framework' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 5,
						'max' => 200,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .ova_contact_us i' => 'padding-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'heading_des',
			[
				'label' => __( 'Color All', 'ova-framework' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'content_color',
			[
				'label' => __( 'Color', 'ova-framework' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ova_contact_us .icon, {{WRAPPER}} .ova_contact_us .text a, {{WRAPPER}} .ova_contact_us .text span, {{WRAPPER}} .ova_contact_us.style2 i, {{WRAPPER}} .ova_contact_us.style2 .text-line span, {{WRAPPER}} .ova_contact_us.style2 .text-line a,{{WRAPPER}} .ova_contact_us.style3 .text-line .line-info-1,{{WRAPPER}} .ova_contact_us.style3 .text-line .line-info-2,{{WRAPPER}} .ova_contact_us.style4 .text-line .line-info-1,{{WRAPPER}} .ova_contact_us.style4 .text-line .line-info-1 a, {{WRAPPER}} .ova_contact_us.style4 .text-line .line-info-2, {{WRAPPER}} .ova_contact_us.style4 .text-line .line-info-2 a' => 'color: {{VALUE}} !important;',
				],
			]
		);

		$this->add_control(
			'content_hover_color',
			[
				'label' => __( 'Hover Color', 'ova-framework' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ova_contact_us:hover .icon, {{WRAPPER}} .ova_contact_us:hover .text a, {{WRAPPER}} .ova_contact_us:hover .text span, {{WRAPPER}} .ova_contact_us.style2:hover i, {{WRAPPER}} .ova_contact_us.style2 .line-info-1:hover a, {{WRAPPER}} .ova_contact_us.style2 .line-info-2:hover a,{{WRAPPER}} .ova_contact_us.style3 .text-line .line-info-1:hover,{{WRAPPER}} .ova_contact_us.style3 .text-line .line-info-1:hover a, {{WRAPPER}} .ova_contact_us.style3 .text-line .line-info-2:hover,{{WRAPPER}} .ova_contact_us.style3 .text-line .line-info-2:hover a,{{WRAPPER}} .ova_contact_us.style4 .text-line .line-info-1:hover,{{WRAPPER}} .ova_contact_us.style4 .text-line .line-info-1:hover a,{{WRAPPER}} .ova_contact_us.style4 .text-line .line-info-2:hover,{{WRAPPER}} .ova_contact_us.style4 .text-line .line-info-2:hover a' => 'color: {{VALUE}} !important;',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings();
		?>
		<div class="ova_contact_us <?php echo esc_attr($settings['info_type']);?>">
			<?php if( $settings['icon'] != '' || $settings['icon_class'] != '' ) : ?>
				<i class="icon <?php echo esc_attr( $settings['icon'] ) . esc_attr( $settings['icon_class']);?>"></i>
			<?php endif; ?>
			<?php if( $settings['text'] != '' && $settings['info_type'] == 'style1' ){ ?>
				<?php 
				$tag_a = "";
				switch ($settings['type_link_1']) {
					case 'email':
					$tag_a = "<a href='mailto:".$settings['link_1']."'>".$settings['text']."</a>";
					break;

					case 'tell':
					$tag_a = "<a  href='tel:".$settings['link_1']."'>".$settings['text']."</a>";
					break;

					default:
					$tag_a = "<span >" . $settings['text'] . "</span>";
					break;
				}
				?>
				<div class="text">
					<?php echo $tag_a;?>
				</div>
			<?php } ?>
			<?php if( $settings['info_type'] == 'style2' && $settings['text_line_1'] != '' || $settings['info_type'] == 'style2' && $settings['text_line_2'] != '' ){ ?>
				<?php 
				$type_link_2_1 = $settings['type_link_2_1'];
				$tag_a = "";
				switch ($settings['type_link_2_1']) {
					case 'email':
					$tag_a = "<a href='mailto:".$settings['link_line_1']."'>".$settings['text_line_1']."</a>";
					break;

					case 'tell':
					$tag_a = "<a  href='tel:".$settings['link_line_1']."'>".$settings['text_line_1']."</a>";
					break;

					default:
					$tag_a =  $settings['text_line_1'];
					break;
				}

				$tag_a2 = "";
				switch ($settings['type_link_2_2']) {
					case 'email':
					$tag_a2 = "<a href='mailto:".$settings['link_line_2']."'>".$settings['text_line_2']."</a>";
					break;

					case 'tell':
					$tag_a2 = "<a  href='tel:".$settings['link_line_2']."'>".$settings['text_line_2']."</a>";
					break;

					default:
					$tag_a2 =  $settings['text_line_2'];
					break;
				}
				?>
				<div class="text-line">
					<span class="line-info-1"><?php echo $tag_a;?></span>
					<span class="line-info-2"><?php echo $tag_a2;?></span>
				</div>
			<?php } ?>
			<?php if( $settings['info_type'] == 'style3' ) : ?>

				<?php 
				$type_link_3 = $settings['type_link_3'];
				$tag_a = "";
				switch ($settings['type_link_3']) {
					case 'email':
					$tag_a = "<a href='mailto:".$settings['link_line_2_1']."'>".$settings['text_line_2_1']."</a>";
					break;

					case 'tell':
					$tag_a = "<a  href='tel:".$settings['link_line_2_1']."'>".$settings['text_line_2_1']."</a>";
					break;

					default:
					$tag_a =  $settings['text_line_2_1'];
					break;
				}
				?>
				<div class="text-line">
					<span class="line-info-1"><?php echo $settings['text_line_1_1'];?></span>
					<span class="line-info-2"><?php echo $tag_a;?></span>
				</div>
			<?php endif; ?>
			<?php if( $settings['info_type'] == 'style4' ) : ?>
				<div class="text-line">
					<span class="line-info-1"><?php echo esc_html($settings['text_phone_4']) ?><span><a href="tel:<?php echo esc_attr($settings['link_line_1_2']);?>"><?php echo esc_html($settings['text_line_1_2']);?></a></span></span>
					<span class="line-info-2"><?php echo esc_html($settings['text_email_4']) ?><span><a href="mailto:<?php echo esc_attr($settings['link_line_2_2']);?>"><?php echo esc_html($settings['text_line_2_2']);?></a></span></span>
				</div>
			<?php endif; ?>
		</div>
		
	<?php }
	
}

