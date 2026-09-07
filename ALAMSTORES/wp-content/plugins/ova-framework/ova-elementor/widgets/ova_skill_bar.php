<?php
namespace ova_framework\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Core\Schemes\Typography;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Color;
use Elementor\Group_Control_Border;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class ova_skill_bar extends Widget_Base {

	public function get_name() {
		return 'ova_skill_bar';
	}

	public function get_title() {
		return __( 'Skill bar', 'ova-framework' );
	}

	public function get_icon() {
		return 'fa fa-minus';
	}

	public function get_categories() {
		return [ 'ovatheme' ];
	}

	public function get_script_depends() {
		wp_enqueue_script( 'jquery-appear', OVA_PLUGIN_URI.'assets/libs/jquery_appear/jquery.appear.js', array('jquery'), false, true );
		return [ 'script-elementor' ];
	}

	protected function register_controls() {

		//SECTION CONTENT
		$this->start_controls_section(
			'section_version',
			[
				'label' => __( 'Version', 'ova-framework' ),
			]
		);

		$this->add_control(
			'version',
			[
				'label' => __( 'Version', 'ova-framework' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'version_1',
				'options' => [
					'version_1' => __( 'Version 1', 'ova-framework' ),
					'version_2' => __( 'Version 2', 'ova-framework' ),
				],
			]
		);

		$this->end_controls_section();
		//END SECTION CONTENT

		//SECTION CONTENT
		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Content', 'ova-framework' ),
			]
		);


		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'text_skill',
			[
				'label' => __( 'Text Skill', 'ova-framework' ),
				'type' => \Elementor\Controls_Manager::TEXT,
			]
		);

		$repeater->add_control(
			'percent',
			[
				'label' => __( 'Percent', 'ova-framework' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
			]
		);


		$this->add_control(
			'tabs',
			[
				'label' => __( 'Tabs', 'ova-framework' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'text_skill' => __('Natural Materials', 'ova-framework'),
						'percent' => 80,
					],
					[
						'text_skill' => __('Construction Safety', 'ova-framework'),
						'percent' => 100,
					],
					[
						'text_skill' => __('Upgrade & Renovation', 'ova-framework'),
						'percent' => 60,
					],
					[
						'text_skill' => __('Other Materials', 'ova-framework'),
						'percent' => 50,
					],
				],
				'title_field' => '{{{ text_skill }}}',
			]
		);

		$this->add_control(
			'space',
			[
				'label' => __( 'Space bars', 'ova-framework' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px'],
				'range' => [
					'px' => [
						'max' => 100,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .ova-skill-bar .skillbar:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
		//END SECTION CONTENT

		//section style title
		$this->start_controls_section(
			'section_text',
			[
				'label' => __( 'Text skill', 'ova-framework' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'text_skill_typography',
				'selector' => '{{WRAPPER}} .ova-skill-bar .text-skill-bar',
				'scheme' => Typography::TYPOGRAPHY_1,
			]
		);



		$this->add_control(
			'color_text',
			[
				'label' => __( 'Color', 'ova-framework' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ova-skill-bar .text-skill-bar' => 'color : {{VALUE}};',
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
					'{{WRAPPER}} .ova-skill-bar .text-skill-bar' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		//section style 
		$this->start_controls_section(
			'section_color_bar',
			[
				'label' => __( 'Color Bar', 'ova-framework' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);


		$this->add_control(
			'color_bar',
			[
				'label' => __( 'Color bar', 'ova-framework' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ova-skill-bar .cove-killbar .skillbar-bar' => 'background-color : {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'color_all_bar',
			[
				'label' => __( 'Color all bar', 'ova-framework' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ova-skill-bar .cove-killbar' => 'background-color : {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		//section style 
		$this->start_controls_section(
			'section_text_percent',
			[
				'label' => __( 'Text percent', 'ova-framework' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);


		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'text_percent_typography',
				'selector' => '{{WRAPPER}} .ova-skill-bar .skillbar .percent',
				'scheme' => Typography::TYPOGRAPHY_1, 
			]
		);



		$this->add_control(
			'color_text_percent',
			[
				'label' => __( 'Color', 'ova-framework' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ova-skill-bar .skillbar .percent .relative span' => 'color : {{VALUE}};',
					'{{WRAPPER}} .ova-skill-bar .skillbar .percent' => 'color : {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'bg_color_text_percent',
			[
				'label' => __( 'Background Color', 'ova-framework' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ova-skill-bar.version_1 .percent .relative span' => 'background-color : {{VALUE}};',
					'{{WRAPPER}} .ova-skill-bar.version_1 .percent .relative:after' => 'background-color : {{VALUE}};',
					'{{WRAPPER}} .ova-skill-bar.version_2 .skillbar .percent .relative:after' => 'background-color : {{VALUE}};',
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
		$tabs = $settings['tabs'];
		$version = $settings['version'];

		?>
		
		<div class="ova-skill-bar <?php echo esc_attr($version) ?>" id="ova-skill-bar">
			<?php if(!empty($tabs)) : ?>
				<?php 
				$i = 0;
				foreach($tabs as $item) : 
					$i++;
					?>
					<div class="skillbar " id="text-skill-bar-<?php echo $i ?>" data-percent="<?php echo esc_attr($item['percent']) ?>%" > 
						
						<div class="text-skill-bar" ><?php echo esc_html($item['text_skill']) ?></div>
						<div class="cove-killbar">
							<div class="skillbar-bar" ></div>
						</div>
						<span class="percent" data-percent="<?php echo esc_attr($item['percent']) ?>%"><span class="relative"><?php  echo '<span></span>';  ?></span></span>
					</div>
				<?php endforeach ?>
			<?php endif ?>

		</div>

		<?php
	}
}
