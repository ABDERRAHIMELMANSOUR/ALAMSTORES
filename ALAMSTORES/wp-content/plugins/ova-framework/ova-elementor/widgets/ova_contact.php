<?php

namespace ova_framework\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Core\Schemes\Typography;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Color;


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class ova_contact extends Widget_Base {

	public function get_name() {
		return 'ova_contact';
	}

	public function get_title() {
		return __( 'Contact', 'ova-framework' );
	}

	public function get_icon() {
		return 'fa fa-map-marker';
	}

	public function get_categories() {
		return [ 'ovatheme' ];
	}

	public function get_script_depends() {
		return [ 'script-elementor' ];
	}

	protected function register_controls() {


		$this->start_controls_section(
			'section_version_contact',
			[
				'label' => __( 'Version', 'ova-framework' ),
			]
		);
		$this->add_control(
			'version',
			[
				'label' => __( 'version', 'ova-framework' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'version_1',
				'options' => [
					'version_1' => __('Version 1', 'ova-framework'),
					'version_2' => __('Version 2', 'ova-framework'),
				]
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_heading_content',
			[
				'label' => __( 'Content', 'ova-framework' ),
				'condition' => [
					'version' => ['version_1','version_2'],
				]	
			]
		);


		$this->add_control(
			'image',
			[
				'label'   => 'Image',
				'type'    => \Elementor\Controls_Manager::MEDIA,
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
				'condition' => [
					'version' => 'version_1',
				]
			]
		);

		$this->add_control(
			'title',
			[
				'label'   => __( 'Title ', 'ova-framework' ),
				'type'    => \Elementor\Controls_Manager::TEXTAREA,
				'row' => 2,
				'default'    => __('Contact info', 'ova-framework'),
				'condition' => [
					'version' => 'version_2',
				]
			]
		);

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'type_link',
			[
				'label' => __( 'Type Link', 'ova-framework' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'none',
				'options' => [
					'email' => __('Email', 'ova-framework'),
					'tell' => __('Tell', 'ova-framework'),
					'none' => __('None', 'ova-framework'),
				]
			]
		);

		$repeater->add_control(
			'link',
			[
				'label' => __( 'Link', 'ova-framework' ),
				'type' => \Elementor\Controls_Manager::URL,
				'placeholder' => __( 'https://your-link.com', 'ova-framework' ),
				'show_external' => false,
				'default' => [
					'url' => '#',
					'is_external' => false,
					'nofollow' => false,
				],
			]
		);

		

		$repeater->add_control(
			'text_info',
			[
				'label'   => __( 'Info ', 'ova-framework' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
			]
		);



		$this->add_control(
			'tabs',
			[
				'label'       => 'Item',
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default' => [
					[
						'type_link' => 'tell',
						'text_info' => __('(205)-509-1845', 'ova-framework'),
					],
					[
						'type_link' => 'email',
						'text_info' => __('contact@constrau.com', 'ova-framework'),
					],
					[
						'type_link' => 'none',
						'text_info' => __('34 Steuben St, Brooklyn, NY 11205', 'ova-framework'),
					],
				],
				'title_field' => '{{{ text_info }}}',
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
				'default' => 'left',
				'selectors' => [
					'{{WRAPPER}} .ova-contact' => 'text-align: {{VALUE}}',
				]
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_content_version_3',
			[
				'label' => __( 'Content', 'ova-framework' ),
				'condition' => [
					'version' => 'version_3',
				]	
			]
		);

		$this->add_control(
			'icon',
			[
				'label'   => __( 'Icon', 'ova-framework' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => 'contact_flaticon-036-customer-service',
			]
		);

		$this->add_control(
			'type_link_v_3',
			[
				'label' => __( 'Type Link', 'ova-framework' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'none',
				'options' => [
					'email' => __('Email', 'ova-framework'),
					'tell' => __('Tell', 'ova-framework'),
					'none' => __('None', 'ova-framework'),
				]
			]
		);

		$this->add_control(
			'link_v3',
			[
				'label' => __( 'Link', 'ova-framework' ),
				'type' => \Elementor\Controls_Manager::URL,
				'placeholder' => __( 'https://your-link.com', 'ova-framework' ),
				'show_external' => false,
				'default' => [
					'url' => '#',
					'is_external' => false,
					'nofollow' => false,
				],
			]
		);

		$this->add_control(
			'text_contact_v3',
			[
				'label'   => __( 'Text contact', 'ova-framework' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
			]
		);

		$this->end_controls_section();



		//section style sub title
		$this->start_controls_section(
			'section_image',
			[
				'label' => __( 'Image', 'ova-framework' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'version' => 'version_1',
				]
			]
		);


		$this->add_responsive_control(
			'margin_image',
			[
				'label' => __( 'Margin', 'ova-framework' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .ova-contact.version_1 .ova-media' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_title',
			[
				'label' => __( 'Title', 'ova-framework' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'version' => 'version_2',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'selector' => '{{WRAPPER}} .ova-contact.version_2 .ova-title .title',
				'scheme' => Typography::TYPOGRAPHY_1,
			]
		);

		$this->add_control(
			'color_title',
			[
				'label' => __( 'Color ', 'ova-framework' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ova-contact.version_2 .ova-title .title' => 'color : {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'position_line',
			[
				'label' => __( 'Position Line', 'ova-framework' ),
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
					
					'{{WRAPPER}} .ova-contact.version_2 .ova-title .title:after' => 'bottom: calc(-11px + {{SIZE}}{{UNIT}});',
				],
			]
		);

		$this->add_control(
			'width_line',
			[
				'label' => __( 'Width Line', 'ova-framework' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 300,
						'step' => 1,
					],
				],
				'selectors' => [
					
					'{{WRAPPER}} .ova-contact.version_2 .ova-title .title:after' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'height_line',
			[
				'label' => __( 'Height Line', 'ova-framework' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 15,
						'step' => 1,
					],
				],
				'selectors' => [
					
					'{{WRAPPER}} .ova-contact.version_2 .ova-title .title:after' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'color_line',
			[
				'label' => __( 'Color line', 'ova-framework' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ova-contact.version_2 .ova-title .title:after' => 'background-color : {{VALUE}};',
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
					'{{WRAPPER}} .ova-contact.version_2 .ova-title .title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_info',
			[
				'label' => __( 'Info', 'ova-framework' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'version' => ['version_1','version_2'],
				]	
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'info_typography',
				'selector' => '{{WRAPPER}} .ova-contact .contact li a, {{WRAPPER}} .ova-contact .contact li',
				'scheme' => Typography::TYPOGRAPHY_1,
			]
		);

		$this->add_control(
			'color_info',
			[
				'label' => __( 'Color ', 'ova-framework' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ova-contact .contact li a' => 'color : {{VALUE}};',
					'{{WRAPPER}} .ova-contact .contact li' => 'color : {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'color_info_hover',
			[
				'label' => __( 'Color Hover', 'ova-framework' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ova-contact .contact li a:hover' => 'color : {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'space_info',
			[
				'label' => __( 'Space', 'ova-framework' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => -20,
						'max' => 100,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .ova-contact .contact li:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

	}

	protected function render() {
		$settings = $this->get_settings();
		$version = $settings['version'];
		$tabs = $settings['tabs'];

		?>
		<?php if ($version === 'version_1' || $version === 'version_2') : ?>
			<div class="ova-contact <?php echo esc_attr($version) ?>">
				<?php if(!empty($settings['image']['url']) && $version === 'version_1') : ?>
					<div class="ova-media">
						<img src="<?php echo esc_attr($settings['image']['url']) ?>" alt="">
					</div>
				<?php endif ?>
				<?php if($settings['title'] !== '' && $version === 'version_2') : ?>
					<div class="ova-title">
						<p class="title"><?php echo esc_attr($settings['title']) ?></p>
					</div>
				<?php endif ?>
				<ul class="contact">
					<?php 
					if (!empty($tabs)) : foreach($tabs as $item) : 
						$tag_a = "";
						switch ($item['type_link']) {
							case 'email':
							$tag_a = "<a href='mailto:".$item['link']['url']."'>".$item['text_info']."</a>";
							break;

							case 'tell':
							$tag_a = "<a href='tel:".$item['link']['url']."'>".$item['text_info']."</a>";
							break;

							default:
							$tag_a = $item['text_info'];
							break;
						}
						?>
						<li><?php echo $tag_a ?></li>
					<?php endforeach ?>
				<?php endif ?>
			</ul>
		</div>
	<?php endif ?>
	<?php
}
// end render
}


