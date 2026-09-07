<?php
namespace ova_framework\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Core\Schemes\Typography;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Color;
use Elementor\Group_Control_Border;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


class ova_industry_solution extends Widget_Base {

	public function get_name() {
		return 'ova_industry_solution';
	}

	public function get_title() {
		return __( 'Industry Solution', 'ova-framework' );
	}

	public function get_icon() {
		return 'fa fa-industry';
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
				'label' => __( 'Icon', 'ova-framework' ),
			]
		);
		
		


		$repeater = new \Elementor\Repeater();


		$repeater->add_control(
			'icon',
			[
				'label' => __( 'Icons', 'ova-framework' ),
				'type' => \Elementor\Controls_Manager::TEXT,
			]
		);

		$repeater->add_control(
			'title',
			[
				'label' => __( 'Title', 'ova-framework' ),
				'type' => Controls_Manager::TEXTAREA,
				'row' => 5,
				'default' => __('Partner Support','ova-framework'),
			]
		);

		$repeater->add_control(
			'desc',
			[
				'label' => __( 'Description', 'ova-framework' ),
				'type' => Controls_Manager::WYSIWYG,
				'default' => __('Working in collaboration with our customers, joint venture partners and supply chain we deliver road.','ova-framework'),
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
						'icon' => 'motivation_flaticon-029-support',
						'title' => __('Partner Support', 'ova-framework'),
						'desc' => __('Working in collaboration with our customers, joint venture partners and supply chain we deliver road.', 'ova-framework'),
						
					],

					[
						'icon' => 'contruc_icon_flaticon-truck',
						'title' => __('Construction', 'ova-framework'),
						'desc' => __('While working on the construction of new roads and the upgrade of existing roads, the safety of the public.', 'ova-framework'),
						
					],

					[
						'icon' => 'contruc_icon_flaticon-barrier',
						'title' => __('Structure Works', 'ova-framework'),
						'desc' => __('Our civil engineering expertise allows us to produce fully value-engineered options for the construction of new.', 'ova-framework'),
						
					],

					[
						'icon' => 'produc_flaticon-rocket',
						'title' => __('New technology', 'ova-framework'),
						'desc' => __('Technological innovation helps us shorten construction time and increase construction quality.', 'ova-framework'),
						
					],
				],
				'title_field' => '{{{ title }}}',
			]
		);

		$this->end_controls_section();

		// end tab section_content

		//section style icon
		$this->start_controls_section(
			'section_icon',
			[
				'label' => __( 'icon', 'ova-framework' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);



		$this->add_control(
			'color_icon',
			[
				'label' => __( 'Color', 'ova-framework' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ova-industry-solution .content-industry .item .icon i:before' => 'color : {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'bg_color_icon',
			[
				'label' => __( 'background color', 'ova-framework' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ova-industry-solution .content-industry .item .icon span' => 'background-color : {{VALUE}};',
				],
			]
		);


		$this->add_responsive_control(
			'margin_icon',
			[
				'label' => __( 'Margin', 'ova-framework' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .ova-industry-solution .content-industry .item .icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();


		//section style title
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
				'selector' => '{{WRAPPER}} .ova-industry-solution .content-industry .item .content .title',
				'scheme' => Typography::TYPOGRAPHY_1,
			]
		);



		$this->add_control(
			'color_title',
			[
				'label' => __( 'Color', 'ova-framework' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ova-industry-solution .content-industry .item .content .title' => 'color : {{VALUE}};',
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
					'{{WRAPPER}} .ova-industry-solution .content-industry .item .content .title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		//section style description
		$this->start_controls_section(
			'section_desc',
			[
				'label' => __( 'Descripion', 'ova-framework' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'desc_typography',
				'selector' => '{{WRAPPER}} .ova-industry-solution .content-industry .item .content .desc',
				'scheme' => Typography::TYPOGRAPHY_1,
			]
		);



		$this->add_control(
			'color_desc',
			[
				'label' => __( 'Color', 'ova-framework' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ova-industry-solution .content-industry .item .content .desc' => 'color : {{VALUE}};',
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
					'{{WRAPPER}} .ova-industry-solution .content-industry .item .content .desc' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$tabs = $settings['tabs'];
		?>
		<div class="ova-industry-solution">
			<div class="content-industry">
				<?php if(!empty($tabs)) : foreach ($tabs as $item) : ?>
					<div class="item">
						<div class="icon">
							<span><i class="<?php echo esc_attr($item['icon']) ?>"></i></span>
						</div>
						<div class="content">
							<h3 class="title second_font"><?php echo esc_html($item['title']) ?></h3>
							<p class="desc"><?php echo esc_html($item['desc']) ?></p>
						</div>
					</div>
				<?php endforeach; endif; ?>
			</div>
		</div>
		<?php
	}
}
