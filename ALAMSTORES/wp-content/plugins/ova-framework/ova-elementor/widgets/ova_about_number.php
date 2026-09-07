<?php
namespace ova_framework\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Core\Schemes\Typography;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Color;
use Elementor\Group_Control_Border;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class ova_about_number extends Widget_Base {

	public function get_name() {
		return 'ova_about_number';
	}

	public function get_title() {
		return __( 'About number', 'ova-framework' );
	}

	public function get_icon() {
		return 'fa fa-text-width';
	}

	public function get_categories() {
		return [ 'ovatheme' ];
	}

	public function get_script_depends() {
		return [ 'script-elementor' ];
	}

	protected function register_controls() {

		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Content', 'ova-framework' ),
			]
		);


		$this->add_control(
			'number_1',
			[
				'label' => __( 'Number 1', 'ova-framework' ),
				'type' => Controls_Manager::TEXT,
				'default' => 30,
			]
		);

		$this->add_control(
			'text_1',
			[
				'label' => __( 'Text 1', 'ova-framework' ),
				'type' => Controls_Manager::TEXT,
				'default' => __('Years of Experience', 'ova-framework')
			]
		);

		$this->add_control(
			'number_2',
			[
				'label' => __( 'Number 2', 'ova-framework' ),
				'type' => Controls_Manager::TEXT,
				'default' => 289,
			]
		);

		$this->add_control(
			'text_2',
			[
				'label' => __( 'Text 2', 'ova-framework' ),
				'type' => Controls_Manager::TEXT,
				'default' => __('Team Members', 'ova-framework')
			]
		);

		$this->add_control(
			'number_3',
			[
				'label' => __( 'Number 3', 'ova-framework' ),
				'type' => Controls_Manager::TEXT,
				'default' => 18,
			]
		);

		$this->add_control(
			'text_3',
			[
				'label' => __( 'Text 3', 'ova-framework' ),
				'type' => Controls_Manager::TEXT,
				'default' => __('Awards Winning', 'ova-framework')
			]
		);

		$this->add_control(
			'number_4',
			[
				'label' => __( 'Number 4', 'ova-framework' ),
				'type' => Controls_Manager::TEXT,
				'default' => 369,
			]
		);

		$this->add_control(
			'text_4',
			[
				'label' => __( 'Text 4', 'ova-framework' ),
				'type' => Controls_Manager::TEXT,
				'default' => __('Completed Projects', 'ova-framework')
			]
		);

		$this->add_responsive_control(
			'align',
			[
				'label' => __( 'Alignment', 'ova-framework' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'unset' => [
						'title' => __( 'Left', 'ova-framework' ),
						'icon' => 'fa fa-align-left',
					],
					'0 auto' => [
						'title' => __( 'Center', 'ova-framework' ),
						'icon' => 'fa fa-align-center',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .ova-about-number' => 'margin: {{VALUE}}',
				]
			]
		);

		$this->end_controls_section();
		//END SECTION CONTENT

		//section style title
		$this->start_controls_section(
			'section_number',
			[
				'label' => __( 'Number', 'ova-framework' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'number_typography',
				'selector' => '{{WRAPPER}} .ova-about-number .box span.number',
				'scheme' => Typography::TYPOGRAPHY_1,
			]
		);



		$this->add_control(
			'color_number',
			[
				'label' => __( 'Color', 'ova-framework' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ova-about-number .box span.number' => 'color : {{VALUE}};',
				],
			]
		);


		$this->add_responsive_control(
			'margin_number',
			[
				'label' => __( 'Margin', 'ova-framework' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .ova-about-number .box span.number' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		//section style title
		$this->start_controls_section(
			'section_text',
			[
				'label' => __( 'Text', 'ova-framework' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'text_typography',
				'selector' => '{{WRAPPER}} .ova-about-number .box span.text',
				'scheme' => Typography::TYPOGRAPHY_1,
			]
		);



		$this->add_control(
			'color_text',
			[
				'label' => __( 'Color', 'ova-framework' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ova-about-number .box span.text' => 'color : {{VALUE}};',
				],
			]
		);


		$this->add_responsive_control(
			'margin_text',
			[
				'label' => __( 'Margin', 'ova-framework' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .ova-about-number .box span.text' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		//section style 
		$this->start_controls_section(
			'section_box',
			[
				'label' => __( 'Box', 'ova-framework' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'color_border',
			[
				'label' => __( 'Color border', 'ova-framework' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ova-about-number .box' => 'border-color : {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'color_hidden',
			[
				'label' => __( 'Background color hidden', 'ova-framework' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ova-about-number .box.box-1:before' => 'background-color : {{VALUE}};',
					'{{WRAPPER}} .ova-about-number .box.box-1:after' => 'background-color : {{VALUE}};',
					'{{WRAPPER}} .ova-about-number .box.box-2:before' => 'background-color : {{VALUE}};',
					'{{WRAPPER}} .ova-about-number .box.box-2:after' => 'background-color : {{VALUE}};',
					'{{WRAPPER}} .ova-about-number .box.box-3:before' => 'background-color : {{VALUE}};',
					'{{WRAPPER}} .ova-about-number .box.box-3:after' => 'background-color : {{VALUE}};',
					'{{WRAPPER}} .ova-about-number .box.box-4:before' => 'background-color : {{VALUE}};',
					'{{WRAPPER}} .ova-about-number .box.box-4:after' => 'background-color : {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'width',
			[
				'label' => __( 'Width', 'ova-framework' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 370,
						'max' => 1000,
						'step' => 1,
					]
				],
				'selectors' => [
					'{{WRAPPER}} .ova-about-number' => 'width: {{SIZE}}{{UNIT}};',
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
		?>
		
		<div class="ova-about-number">
			<div class="box box-1">
				<span class="number"><?php echo esc_html($settings['number_1']) ?></span>
				<span class="text"><?php echo esc_html($settings['text_1']) ?></span>
			</div>
			<div class="box box-2">
				<span class="number"><?php echo esc_html($settings['number_2']) ?></span>
				<span class="text"><?php echo esc_html($settings['text_2']) ?></span>
			</div>
			<div class="box box-3">
				<span class="number"><?php echo esc_html($settings['number_3']) ?></span>
				<span class="text"><?php echo esc_html($settings['text_3']) ?></span>
			</div>
			<div class="box box-4">
				<span class="number"><?php echo esc_html($settings['number_4']) ?></span>
				<span class="text"><?php echo esc_html($settings['text_4']) ?></span>
			</div>
		</div>

		<?php
	}
}
