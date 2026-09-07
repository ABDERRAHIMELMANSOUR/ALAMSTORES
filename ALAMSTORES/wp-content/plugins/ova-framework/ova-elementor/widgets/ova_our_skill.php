<?php

namespace ova_framework\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Core\Schemes\Typography;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Color;


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class ova_our_skill extends Widget_Base {

	public function get_name() {
		return 'ova_our_skill';
	}

	public function get_title() {
		return __( 'Our Skill', 'ova-framework' );
	}

	public function get_icon() {
		return 'fa fa-circle-o-notch';
	}

	public function get_categories() {
		return [ 'ovatheme' ];
	}

	public function get_script_depends() {
		wp_enqueue_script( 'jquery-circle-progress', OVA_PLUGIN_URI.'assets/libs/jquery_circle_progress/circle-progress.min.js', array('jquery'), false, true );
		wp_enqueue_script( 'jquery-appear', OVA_PLUGIN_URI.'assets/libs/jquery_appear/jquery.appear.js', array('jquery'), false, true );
		return [ 'script-elementor' ];
	}

	protected function register_controls() {


		/**********************************************
		CONTENT SECTION
		**********************************************/
		$this->start_controls_section(
			'section_heading_content',
			[
				'label' => __( 'Content', 'ova-framework' ),
			]
		);

		$this->add_control(
			'color_value',
			[
				'label' => __( 'Color Value', 'ova-framework' ),
				'type' => Controls_Manager::COLOR,
			]
		);

		$this->add_control(
			'color_empty',
			[
				'label' => __( 'Color Empty', 'ova-framework' ),
				'type' => Controls_Manager::COLOR,
				'default' => "#e5e5e5",
			]
		);

		$this->add_control(
			'color_number',
			[
				'label' => __( 'Color Number', 'ova-framework' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ova-our-skill .circle strong' => 'color : {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'size',
			[
				'label' => __( 'Size (px)', 'ova-framework' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 250,
			]
		);

		$this->add_control(
			'thin',
			[
				'label' => __( 'Thin (px)', 'ova-framework' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 2,
			]
		);

		$this->add_control(
			'number',
			[
				'label' => __( 'Number Percent (%)', 'ova-framework' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 70,
			]
		);

		$this->add_control(
			'title',
			[
				'label' => __( 'Title', 'ova-framework' ),
				'type' => Controls_Manager::TEXT,
				'default' => __('Project Progress', 'ova-framework'),
			]
		);

		$this->add_control(
			'color_title',
			[
				'label' => __( 'Color Title', 'ova-framework' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ova-our-skill .title' => 'color : {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'time_duration',
			[
				'label' => __( 'Time Duration', 'ova-framework' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 1500,
			]
		);



		$this->end_controls_section();

	}

	protected function render() {
		$settings = $this->get_settings();
		$primary_color = get_theme_mod( 'primary_color');
		$color_value = $settings['color_value'];
		$color = $color_value !== '' ? $color_value : $primary_color;
		?>
		<div class="ova-our-skill">
			<div data-time="<?php echo esc_attr($settings['time_duration']) ?>" data-thickness="<?php echo esc_attr($settings['thin']) ?>" class="circle" data-empty="<?php echo esc_attr($settings['color_empty']) ?>" data-color="<?php echo $color ?>" data-number="<?php echo esc_attr($settings['number']) ?>" data-size="<?php echo esc_attr($settings['size']) ?>">
				<div class="content">
					<h3 class="title second_font"><?php echo esc_html($settings['title']) ?></h3>
					<strong class="second_font"></strong>
				</div>
			</div>
		</div>
		
		<?php

	}
// end render
}


