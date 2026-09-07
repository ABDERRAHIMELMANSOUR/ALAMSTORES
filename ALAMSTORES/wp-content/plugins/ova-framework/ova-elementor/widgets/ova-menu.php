<?php

namespace ova_framework\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class ova_menu extends Widget_Base {

	public function get_name() {
		return 'ova_menu';
	}

	public function get_title() {
		return __( 'Menu', 'ova-framework' );
	}

	public function get_icon() {
		return 'fa fas fa-bars';
	}

	public function get_categories() {
		return [ 'hf' ];
	}

	public function get_script_depends() {
		return [ 'script-elementor' ];
	}

	protected function register_controls() {


		/* Global Section *******************************/
		/***********************************************/
		$this->start_controls_section(
			'section_menu_type',
			[
				'label' => __( 'Global', 'ova-framework' ),
			]
		);


		$menus = \wp_get_nav_menus();
		$list_menu = array();
		foreach ($menus as $menu) {
			$list_menu[$menu->slug] = $menu->name;
		}

		$this->add_control(
			'menu_slug',
			[
				'label' => __( 'Select Menu', 'ova-framework' ),
				'type' => Controls_Manager::SELECT,
				'options' => $list_menu,
				'default' => '',
				'prefix_class' => 'elementor-view-',
			]
		);


		$this->add_control(
			'menu_type',
			[
				'label' => __( 'Display Horizontal | Vertical', 'ova-framework' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'row' => [
						'title' => __( 'Horizontal', 'ova-framework' ),
						'icon' => 'fa fa-arrows-h',
					],
					'column' => [
						'title' => __( 'Vertical', 'ova-framework' ),
						'icon' => 'fa fa-arrows-v',
					]
				],
				'toggle' => 'true',
				'default'	=> 'row',
				'selectors' => [
					'{{WRAPPER}} .ova_nav ul.menu' => 'flex-direction: {{VALUE}};',
				]
			]
		);
		
		$this->end_controls_section();	


		/* Parent Menu Section *******************************/
		/***********************************************/
		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Parent Menu', 'ova-framework' ),
			]
		);

		

		$this->add_responsive_control(
			'padding_menu',
			[
				'label' => __( 'Padding Menu', 'ova-framework' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .ova_nav ul.menu' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'padding_menu_item',
			[
				'label' => __( 'Padding Menu Items', 'ova-framework' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .ova_nav ul.menu > li > a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_control(
			'menu_alignment',
			[
				'label' => __( 'Alignment', 'ova-framework' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'flex-start' => [
						'title' => __( 'Left', 'ova-framework' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'ova-framework' ),
						'icon' => 'fa fa-align-center',
					],
					'flex-end' => [
						'title' => __( 'Right', 'ova-framework' ),
						'icon' => 'fa fa-align-right',
					],
				],
				'default' => 'flex-end',
				'selectors' => [
					'{{WRAPPER}} .ova_nav ul.menu' => 'justify-content: {{VALUE}}; align-items: {{VALUE}}'
				]
				
			]
		);

		$this->add_control(
			'text_color',
			[
				'label' => __( 'Text Color', 'ova-framework' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .ova_nav ul.menu > li > a' => 'color: {{VALUE}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_control(
			'text_color_hover',
			[
				'label' => __( 'Text Color Hover', 'ova-framework' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .ova_nav ul.menu > li > a:hover' => 'color: {{VALUE}};',
				]
				
			]
		);

		$this->add_control(
			'text_color_active',
			[
				'label' => __( 'Text Color Active', 'ova-framework' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .ova_nav ul.menu > li.active>a' => 'color: {{VALUE}};',
				]
				
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'typography',
				'selector'	=> '{{WRAPPER}} .ova_nav ul.menu > li > a'
			]
		);

		$this->add_control(
			'border_menu_item',
			[
				'label' => __( 'Border Menu Item', 'ova-framework' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'menu_border_item',
				'placeholder' => '1px',
				'default' => '1px',
				'selector' => '{{WRAPPER}} .ova_nav ul.menu > li:after',
				
			]
		);

		$this->add_control(
			'label_menu_latest_item',
			[
				'label' => __( 'Border Menu Latest Item', 'ova-framework' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);	

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'menu_border_latest_item',
				'label' => __( 'Border Menu Latest Item', 'ova-framework' ),
				'placeholder' => '0px',
				'default' => '0px',
				'selector' => '{{WRAPPER}} .ova_nav ul.menu > li:last-child:after',
				
			]
		);
		

		$this->end_controls_section();


		/* Sub Menu Section *******************************/
		/***********************************************/
		$this->start_controls_section(
			'section_submenu_content',
			[
				'label' => __( 'Sub Menu', 'ova-framework' ),
			]
		);

		$this->add_control(
			'submenu_min_width',
			[
				'label' => __( 'Width', 'ova-framework' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'rem' ],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 1000,
						'step' => 1,
					],
					'rem' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'rem',
					'size' => 13,
				],
				'selectors' => [
					'{{WRAPPER}} .ova_nav .dropdown-menu' => 'min-width: {{SIZE}}{{UNIT}};',
				],
			]
		);


		$this->add_control(
			'sub_menu_dir',
			[
				'label' => __( 'Menu Direction', 'ova-framework' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'sub_menu_dir_left' => [
						'title' => __( 'Left', 'ova-framework' ),
						'icon' => 'fa fa-align-right',
					],
					'sub_menu_dir_right' => [
						'title' => __( 'Right', 'ova-framework' ),
						'icon' => 'fa fa-align-left',
					],
				],
				'default' => 'sub_menu_dir_left'
			]
		);

		$this->add_responsive_control(
			'padding_sub_menu',
			[
				'label' => __( 'Padding Menu', 'ova-framework' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .ova_nav ul.menu .dropdown-menu' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'padding_sub_menu_item',
			[
				'label' => __( 'Padding Menu Item', 'ova-framework' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .ova_nav ul.menu .dropdown-menu li a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'default' => [
					'top' => '5',
					'right' => '15',
					'bottom' => '5',
					'left' => '15',
					'isLinked' => true,
				]
			]
		);


		$this->add_control(
			'submenu_bg_color',
			[
				'label' => __( 'Background Color', 'ova-framework' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .ova_nav ul.menu .dropdown-menu' => 'background-color: {{VALUE}};',
				],
				'separator' => 'before'
				
			]
		);

		$this->add_control(
			'submenu_text_color',
			[
				'label' => __( 'Text Color', 'ova-framework' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .ova_nav ul.menu .dropdown-menu li > a' => 'color: {{VALUE}};',
				]
				
			]
		);

		$this->add_control(
			'submenu_text_color_hover',
			[
				'label' => __( 'Text Color Hover', 'ova-framework' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .ova_nav ul.menu .dropdown-menu li > a:hover' => 'color: {{VALUE}};',
				]
				
			]
		);

		$this->add_control(
			'submenu_text_color_active',
			[
				'label' => __( 'Text Color Active', 'ova-framework' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .ova_nav ul.menu .dropdown-menu li.active > a' => 'color: {{VALUE}};',
				]
				
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'submenu_typography',
				'selector'	=> '{{WRAPPER}} .ova_nav ul.menu .dropdown-menu li a',

			]
		);


		$this->add_control(
			'border_submenu',
			[
				'label' => __( 'Border Menu', 'ova-framework' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);


		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'submenu_border',
				'placeholder' => '1px',
				'default' => '1px',
				'selector' => '{{WRAPPER}} .ova_nav ul.menu .dropdown-menu',
				
			]
		);

		$this->add_control(
			'border_radius',
			[
				'label' => __( 'Border Radius', 'ova-framework' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .ova_nav ul.menu .dropdown-menu' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				
			]
		);

		$this->add_control(
			'border_submenu_item',
			[
				'label' => __( 'Border Menu Item', 'ova-framework' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'submenu_border_item',
				'placeholder' => '1px',
				'default' => '1px',
				'selector' => '{{WRAPPER}} .ova_nav ul.menu .dropdown-menu li',
				
			]
		);

		$this->add_control(
			'label_submenu_latest_item',
			[
				'label' => __( 'Border Menu Latest Item', 'ova-framework' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);	

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'submenu_border_latest_item',
				'label' => __( 'Border Sub-Menu Latest Item', 'ova-framework' ),
				'placeholder' => '0px',
				'default' => '0px',
				'selector' => '{{WRAPPER}} .ova_nav ul.menu .dropdown-menu li:last-child',
				
			]
		);
		

		$this->end_controls_section();


		/* IPad, Mobile Section *******************************/
		/***********************************************/
		$this->start_controls_section(
			'section_submenu_ipad_mobile',
			[
				'label' => __( 'Ipad | Mobile', 'ova-framework' ),
			]
		);


		
		$this->add_control(
			'canvas_dir',
			[
				'label' => __( 'Display Canvas', 'ova-framework' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'canvas_left' => [
						'title' => __( 'Left', 'ova-framework' ),
						'icon' => 'fa fa-align-left',
					],
					'canvas_right' => [
						'title' => __( 'Right', 'ova-framework' ),
						'icon' => 'fa fa-align-right',
					],
				],
				'default' => 'canvas_left'
			]
		);

		$this->add_control(
			'canvas_bg',
			[
				'label' => __( 'Background Color', 'ova-framework' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'canvas_bg_gray'  => __( 'Gray', 'ova-framework' ),
					'canvas_bg_white' => __( 'White', 'ova-framework' ),
					
				],
				'default' => 'canvas_bg_gray',
			]
		);

		$this->add_control(
			'ipad_mobile_openNav',
			[
				'label' => __( 'Menu Button', 'ova-framework' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);	

		$this->add_control(
			'openNavBtn_align',
			[
				'label' => __( 'Align Ipad', 'ova-framework' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'NavBtn_left' => [
						'title' => __( 'Left', 'ova-framework' ),
						'icon' => 'fa fa-align-left',
					],
					'NavBtn_center' => [
						'title' => __( 'Center', 'ova-framework' ),
						'icon' => 'fa fa-align-center',
					],
					'NavBtn_right' => [
						'title' => __( 'Right', 'ova-framework' ),
						'icon' => 'fa fa-align-right',
					],
					
				],
				'default' => 'NavBtn_left'
			]
		);

		$this->add_control(
			'openNavBtn_align_mobile',
			[
				'label' => __( 'Align Mobile', 'ova-framework' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'NavBtn_left_m' => [
						'title' => __( 'Left', 'ova-framework' ),
						'icon' => 'fa fa-align-left',
					],
					'NavBtn_center_m' => [
						'title' => __( 'Center', 'ova-framework' ),
						'icon' => 'fa fa-align-center',
					],
					'NavBtn_right_m' => [
						'title' => __( 'Right', 'ova-framework' ),
						'icon' => 'fa fa-align-right',
					],
					
				],
				'default' => 'NavBtn_left'
			]
		);

		$this->add_control(
			'openNavBtn_margin',
			[
				'label' => __( 'Margin', 'ova-framework' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .ova_openNav' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'openNavBtn_padding',
			[
				'label' => __( 'Padding', 'ova-framework' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .ova_openNav' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'openNavBtn_bg_color',
			[
				'label' => __( 'Background Color', 'ova-framework' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .ova_openNav' => 'background-color: {{VALUE}};',
				]
				
			]
		);
		$this->add_control(
			'openNavBtn_color',
			[
				'label' => __( 'Color', 'ova-framework' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .ova_openNav .bar .bar-menu-line' => 'background-color: {{VALUE}};',
				]
				
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'openNavBtn_border',
				'placeholder' => '1px',
				'default' => '1px',
				'selector' => '{{WRAPPER}} .ova_openNav',
				
			]
		);

		$this->add_control(
			'openNavBtn_border',
			[
				'label' => __( 'Border Radius', 'ova-framework' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .ova_openNav' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				
			]
		);

		$this->add_control(
			'ipad_mobile_arrow_dropdown',
			[
				'label' => __( 'Allow Dropdown', 'ova-framework' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);	

		$this->add_control(
			'ipad_mobile_arrow_dropdown_padding',
			[
				'label' => __( 'Margin', 'ova-framework' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .dropdown button.dropdown-toggle' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);


		
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
		
		$settings = $this->get_settings();
		
		?>
		<div class="ova_menu_clasic">
			<div class="ova_wrap_nav <?php echo esc_attr( $settings['menu_type'] ).' '.esc_attr( $settings['openNavBtn_align'] ).' '.esc_attr( $settings['openNavBtn_align_mobile'] ); ?>">

				<button class="ova_openNav" type="button">
					<span class="bar">
						<span class="bar-menu-line"></span>
						<span class="bar-menu-line"></span>
						<span class="bar-menu-line"></span>
					</span>
				</button>

				<div class="ova_nav <?php echo esc_attr( $settings['canvas_dir'] ).' '.esc_attr( $settings['canvas_bg'] ); ?>">
					<a href="javascript:void(0)" class="ova_closeNav"><i class="fas fa-times"></i></a>
					<?php
					wp_nav_menu( array(
						'menu'              => $settings['menu_slug'],
						'depth'             => 3,
						'container'         => '',
						'container_class'   => '',
						'container_id'      => '',
						'menu_class'        => 'menu'.' '.$settings['sub_menu_dir']
					));
					?>
				</div>

				<div class="ova_closeCanvas ova_closeNav"></div>
			</div>
		</div>
		
		

	<?php }



	
}


