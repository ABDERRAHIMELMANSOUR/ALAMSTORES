<?php
namespace ova_framework\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Core\Schemes\Typography;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Color;
use Elementor\Group_Control_Border;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


class ova_icon_landing_page extends Widget_Base {

	public function get_name() {
		return 'ova_icon_landing_page';
	}

	public function get_title() {
		return __( 'Icon Landing Page', 'ova-framework' );
	}

	public function get_icon() {
		return 'eicon-social-icons';
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
				'label' => __( 'Icon Landing', 'ova-framework' ),
			]
		);
 
			$this->add_control(
				'icon',
				[
					'label' => __( 'Icons', 'ova-framework' ),
					'type' => \Elementor\Controls_Manager::TEXT,
					'default' => 'zmdi zmdi-smartphone-iphone',
				]
			);

			$this->add_control(
				'title',
				[
					'label' => __( 'Title', 'ova-framework' ),
					'type' => \Elementor\Controls_Manager::TEXT,
					'default' => __('Responsive Retina Ready', 'ova-framework'),
				]
			);


			$this->add_control(
				'desc',
				[
					'label' => __( 'Description', 'ova-framework' ),
					'type' => \Elementor\Controls_Manager::TEXT,
					'default' => __('When we hover this area will come. Please change real content here for each items separately.', 'ova-framework'),
				]
			);

			$this->add_responsive_control(
				'align',
				[
					'label' => __( 'Alignment', 'elementor' ),
					'type' => Controls_Manager::CHOOSE,
					'options' => [
						'left'    => [
							'title' => __( 'Left', 'elementor' ),
							'icon' => 'fa fa-align-left',
						],
						'center' => [
							'title' => __( 'Center', 'elementor' ),
							'icon' => 'fa fa-align-center',
						],
						'right' => [
							'title' => __( 'Right', 'elementor' ),
							'icon' => 'fa fa-align-right',
						],
					],
					'default' => 'center',
					'selectors' => [
						'{{WRAPPER}}' => 'text-align: {{VALUE}};',
					],
				]
			);
			

		$this->end_controls_section();
		// end tab section_content

		$this->start_controls_section(
			'section_icon',
			[
				'label' => __( 'Description', 'ova-framework' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'with_height_icon',
			[
				'label' => __( 'Width height icon', 'ova-framework' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 50,
						'max' => 150,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .ova-icon-landing-page .wp-icon .icon ' => 'width: {{SIZE}}{{UNIT}}; height:{{SIZE}}{{UNIT}};',
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
						'max' => 150,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .ova-icon-landing-page .wp-icon .icon i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .ova-icon-landing-page .wp-icon .icon i:before' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'color_icon',
			[
				'label' => __( 'Color ', 'ova-framework' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ova-icon-landing-page .wp-icon .icon i' => 'color : {{VALUE}};',
					'{{WRAPPER}} .ova-icon-landing-page .wp-icon .icon i:before' => 'color : {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'bg_color_icon',
			[
				'label' => __( 'Background Color ', 'ova-framework' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ova-icon-landing-page .wp-icon .icon' => 'background-color : {{VALUE}};',
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
				'selector' => '{{WRAPPER}} .ova-icon-landing-page .content h3.title',
				'scheme' => Typography::TYPOGRAPHY_1,
			]
		);

		$this->add_control(
			'color_title',
			[
				'label' => __( 'Color ', 'ova-framework' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ova-icon-landing-page .content h3.title' => 'color : {{VALUE}};',
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
					'{{WRAPPER}} .ova-icon-landing-page .content h3.title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
				'selector' => '{{WRAPPER}} .ova-icon-landing-page .content p.desc',
				'scheme' => Typography::TYPOGRAPHY_1,
			]
		);

		$this->add_control(
			'color_desc',
			[
				'label' => __( 'Color ', 'ova-framework' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ova-icon-landing-page .content p.desc' => 'color : {{VALUE}};',
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
					'{{WRAPPER}} .ova-icon-landing-page .content p.desc' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

	}

	protected function render() {
		$settings = $this->get_settings_for_display();
	?>
		<div class="ova-icon-landing-page">
			<div class="wp-icon">
				<div class="icon">
					<i class="<?php echo $settings['icon'] ?>"></i>
				</div>
			</div>
			<div class="content">
				<h3 class="title"><?php echo esc_html($settings['title']) ?></h3>
				<p class="desc"><?php echo esc_html($settings['desc']) ?></p>
			</div>
		</div>
	<?php
	}
}
