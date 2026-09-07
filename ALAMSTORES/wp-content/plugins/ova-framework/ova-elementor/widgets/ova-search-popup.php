<?php
namespace ova_framework\Widgets;
use Elementor;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Core\Schemes\Color;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


class ova_search_popup extends Widget_Base {

	public function get_name() {
		return 'ova_search_popup';
	}

	public function get_title() {
		return __( 'Search Popup', 'ova-framework' );
	}

	public function get_icon() {
		return 'eicon-search';
	}

	public function get_categories() {
		return [ 'hf' ];
	}

	public function get_keywords() {
		return [ 'search' ];
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_search',
			[
				'label' => __( 'Search', 'ova-framework' ),
			]
		);
			
			$this->add_control(
				'color_icon_search',
				[
					'label' => __( 'Icon Color', 'ova-framework' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .wrap_search_constrau_popup i' => 'color: {{VALUE}}',
					],
				]
			);

			$this->add_control(
				'color_hover_icon_search',
				[
					'label' => __( 'Icon Hover Color', 'ova-framework' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .wrap_search_constrau_popup i:hover' => 'color: {{VALUE}}',
					],
				]
			);

			$this->add_control(
				'size_icon',
				[
					'label' => __( 'Size Icon', 'ova-framework' ),
					'type' => Controls_Manager::SLIDER,
					'size_units' => [ 'px', '%' ],
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 30,
						],
						'%' => [
							'min' => 0,
							'max' => 100,
						],
					],
					'default' => [
						'unit' => 'px',
						'size' => 14,
					],
					'selectors' => [
						'{{WRAPPER}} .wrap_search_constrau_popup i' => 'font-size: {{SIZE}}{{UNIT}};',
					],
				]
			);

			$this->add_control(
				'background_color',
				[
					'label' => __( 'Background Color', 'ova-framework' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} form' => 'background: {{VALUE}}',
					],
				]
			);

			$this->add_control(
				'border_style',
				[
					'label' => __( 'Border Style', 'ova-framework' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'default' => 'none',
					'options' => [
						'solid'  => __( 'Solid', 'ova-framework' ),
						'dashed' => __( 'Dashed', 'ova-framework' ),
						'dotted' => __( 'Dotted', 'ova-framework' ),
						'double' => __( 'Double', 'ova-framework' ),
						'none' => __( 'None', 'ova-framework' ),
					],
					'separator' => 'before',
					'selectors' => [
						'{{WRAPPER}} .wrap_search_constrau_popup i' => 'border-style: {{VALUE}}',
					],
				]
			);

			$this->add_control(
				'button_border_width',
				[
					'label' => __( 'Border Width', 'ova-framework' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => ['px', '%', 'em'],
					'selectors' => [
						'{{WRAPPER}} .wrap_search_constrau_popup i' => 'border-width:  {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'conditions' => [
						'terms' => [
							[
								'name' => 'border_style',
								'operator' => '!=',
								'value' => 'none',
							],
						],
					],
				]
			);

			$this->add_control(
				'color_border',
				[
					'label' => __( 'Border Color', 'ova-framework' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .wrap_search_constrau_popup i' => 'border-color: {{VALUE}}',
					],
					'conditions' => [
						'terms' => [
							[
								'name' => 'border_style',
								'operator' => '!=',
								'value' => 'none',
							],
						],
					],
				]
			);

			$this->add_responsive_control(
				'margin',
				[
					'label' => __( 'Margin', 'ova-framework' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .wrap_search_constrau_popup i' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'separator' => 'before',
				]
			);

			$this->add_responsive_control(
				'padding',
				[
					'label' => __( 'Padding', 'ova-framework' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} i' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->add_responsive_control(
				'align',
				[
					'label' => __( 'Alignment', 'elementor' ),
					'type' => Controls_Manager::CHOOSE,
					'default' => 'center',
					'options' => [
						'flex-start' => [
							'title' => __( 'Left', 'elementor' ),
							'icon' => 'fa fa-align-left',
						],
						'center' => [
							'title' => __( 'Center', 'elementor' ),
							'icon' => 'fa fa-align-center',
						],
						'flex-end' => [
							'title' => __( 'Right', 'elementor' ),
							'icon' => 'fa fa-align-right',
						],
					],
					'selectors' => [
						'{{WRAPPER}} .wrap_search_constrau_popup' => 'justify-content: {{VALUE}};',
					],
				]
			);
			

		$this->end_controls_section();
	}

	protected function render() {
		?>
		<div class="wrap_search_constrau_popup">
			<i class="zmdi zmdi-search"></i>
			<div class="search_constrau_popup">
				<span class="btn_close icon_close"></span>
				<div class="container">
					<form role="search" method="get" class="search-form" action="<?php echo home_url( '/' ); ?>">
					        <input type="search" class="search-field" placeholder="<?php echo esc_attr_x( 'Search …', 'ova-framework' ) ?>" value="<?php echo get_search_query() ?>" name="s" title="<?php echo esc_attr_x( 'Search for:', 'label' ) ?>" />
			   			 	<input type="submit" class="search-submit" value="<?php echo esc_attr_x( 'Search', 'ova-framework' ) ?>" />
					</form>									
				</div>
			</div>
		</div>
		<?php
	}

}

