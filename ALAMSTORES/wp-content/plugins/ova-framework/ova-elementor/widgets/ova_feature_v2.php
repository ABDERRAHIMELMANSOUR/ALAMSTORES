<?php
namespace ova_framework\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Core\Schemes\Typography;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Color;
use Elementor\Group_Control_Border;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


class ova_feature_v2 extends Widget_Base {

	public function get_name() {
		return 'ova_feature_v2';
	}

	public function get_title() {
		return __( 'Feature version 2', 'ova-framework' );
	}

	public function get_icon() {
		return 'fa fa-address-card-o';
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
				'label' => __( 'Feature vesion 2', 'ova-framework' ),
			]
		);
		
		$this->add_control(
			'title',
			[
				'label' => __( 'Title', 'ova-framework' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __('U.S. Certified Contractors','ova-framework'),
			]
		);

		$this->add_control(
			'desc',
			[
				'label' => __( 'Description', 'ova-framework' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'default' => __('Donec tempor quis ante non mollis. Phasellus ultrices risus sem, eget molestie neque facilisis vitae. Mauris quis condimentum nunc.','ova-framework'),
			]
		);

		$this->add_control(
			'icon',
			[
				'label' => __( 'Icons', 'ova-framework' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => 'motivation_flaticon-005-motivational',
			]
		);

		$this->end_controls_section();
		// end tab section_content


		$this->start_controls_section(
			'section_icon',
			[
				'label' => __( 'Icon', 'ova-framework' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);


		$this->add_control(
			'color_icon',
			[
				'label' => __( 'Color ', 'ova-framework' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}  .ova-feature-v2 .wp-content .icon span i:before' => 'color : {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'font_size_icon',
			[
				'label' => __( 'Font Size Icon', 'ova-framework' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 200,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .ova-feature-v2 .wp-content .icon span i:before' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);


		$this->add_control(
			'bg_color_icon',
			[
				'label' => __( 'Background Color ', 'ova-framework' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}  .ova-feature-v2 .wp-content .icon span' => 'background-color : {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'width_height_icon',
			[
				'label' => __( 'Width height', 'ova-framework' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 40,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .ova-feature-v2 .wp-content .icon span' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
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
				'selector' => '{{WRAPPER}} .ova-feature-v2 .wp-content .content .title',
				'scheme' => Typography::TYPOGRAPHY_1,
			]
		);

		$this->add_control(
			'color_title',
			[
				'label' => __( 'Color ', 'ova-framework' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}  .ova-feature-v2 .wp-content .content .title' => 'color : {{VALUE}};',
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
					'{{WRAPPER}}  .ova-feature-v2 .wp-content .content .title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
				'selector' => '{{WRAPPER}} .ova-feature-v2 .wp-content .content .desc',
				'scheme' => Typography::TYPOGRAPHY_1,
			]
		);

		$this->add_control(
			'color_desc',
			[
				'label' => __( 'Color ', 'ova-framework' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}  .ova-feature-v2 .wp-content .content .desc' => 'color : {{VALUE}};',
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
					'{{WRAPPER}}  .ova-feature-v2 .wp-content .content .desc' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		?>
		<div class="ova-feature-v2">
			<div class="wp-content">
				<div class="icon">
					<span><i class=<?php echo esc_attr($settings['icon']) ?>></i></span>
				</div>
				<div class="content">
					<h3 class="title second_font"><?php echo esc_html($settings['title']) ?></h3>
					<p class="desc"><?php echo esc_html($settings['desc']) ?></p>
				</div>
			</div>
		</div>
		<?php
	}
}
