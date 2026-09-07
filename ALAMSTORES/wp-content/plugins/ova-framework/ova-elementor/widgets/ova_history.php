<?php
namespace ova_framework\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Core\Schemes\Typography;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Color;
use Elementor\Group_Control_Border;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


class ova_history extends Widget_Base {

	public function get_name() {
		return 'ova_history';
	}

	public function get_title() {
		return __( 'History', 'ova-framework' );
	}

	public function get_icon() {
		return 'fa fa-line-chart';
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


		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'title',
			[
				'label' => __( 'Title', 'ova-framework' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'row' => 2
			]
		);

		$repeater->add_control(
			'sub_title',
			[
				'label' => __( 'Sub Title', 'ova-framework' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'rows' => 2,
			]
		);

		$repeater->add_control(
			'year',
			[
				'label' => __( 'Year', 'ova-framework' ),
				'type' => \Elementor\Controls_Manager::TEXT,
			]
		);

		$repeater->add_control(
			'image',
			[
				'label'   => 'Image',
				'type'    => \Elementor\Controls_Manager::MEDIA,
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
			]
		);

		$repeater->add_control(
			'desc',
			[
				'label' => __( 'Description', 'ova-framework' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'row' => 5,
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
						'title' => __('Company Founding', 'ova-framework'),
						'sub_title' => __('Founding of the company', 'ova-framework'),
						'year' => 1989,
						'desc' => __('Duis pellentesque leo purus, in sollicitudin enim faucibus et. Ut dolor purus, iaculis quis leo a, volutpat porttitor purus. Nulla vel dolor quis elit varius mollis. Integer id ipsum ac est tempus ullamcorper. Etiam venenatis odio sed augue feugiat malesuada.', 'ova-framework'),
					],
					[
						'title' => __('Partnership', 'ova-framework'),
						'sub_title' => __('Partnership with CORPATETION', 'ova-framework'),
						'year' => 1995,
						'desc' => __('In malesuada purus dui, eu posuere risus faucibus vitae. Curabitur et elementum est. Nunc efficitur eget libero sed lobortis. Sed eget dolor sollicitudin, faucibus neque in, commodo erat. Vivamus a hendrerit diam.', 'ova-framework'),
					],
					[
						'title' => __('International Conference Awards', 'ova-framework'),
						'sub_title' => __('International awards', 'ova-framework'),
						'year' => 2001,
						'desc' => __('Workplace safety and health awards - project management & construction and retail businesses.Nulla vel dolor quis elit varius mollis. Integer id ipsum ac est tempus ullamcorper. Etiam venenatis odio sed augue feugiat malesuada.', 'ova-framework'),
					],
					[
						'title' => __('ColorLex Company', 'ova-framework'),
						'sub_title' => __('Acquired COLORLEX company', 'ova-framework'),
						'year' => 2007,
						'desc' => __('Etiam risus metus, feugiat varius neque nec, pellentesque imperdiet odio. Nulla gravida, orci ut elementum rhoncus, augue velit ultrices velit, eget bibendum nunc tellus nec odio. Ut rhoncus faucibus neque, ut gravida urna tincidunt nec.', 'ova-framework'),
					],
					[
						'title' => __('International leadership training', 'ova-framework'),
						'sub_title' => __('Leadership organization scale', 'ova-framework'),
						'year' => 2013,
						'desc' => __('Integer id ipsum ac est tempus ullamcorper. Etiam venenatis odio sed augue feugiat malesuada. Ut a sollicitudin ligula, vitae lacinia mauris. Suspendisse sed lectus id felis imperdiet consectetur at et ante. Integer quis aliquet ipsum.', 'ova-framework'),
					],
					[
						'title' => __('Environmental Excellence', 'ova-framework'),
						'sub_title' => __('UDIA national awards', 'ova-framework'),
						'year' => 2019,
						'desc' => __('Etiam risus metus, feugiat varius neque nec, pellentesque imperdiet odio. Nulla gravida, orci ut elementum rhoncus, augue velit ultrices velit, eget bibendum nunc tellus nec odio. Ut rhoncus faucibus neque, ut gravida urna tincidunt nec.', 'ova-framework'),
					],
				],
				'title_field' => '{{{ title }}}',
			]
		);

		$this->end_controls_section();


		$this->start_controls_section(
			'section_title',
			[
				'label' => __( 'Title', 'ova-framework' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .ova-history .wp-item .wp-content .title p',
				'scheme'   => Typography::TYPOGRAPHY_1,
			]
		);

		$this->add_control(
			'color_title',
			[
				'label'     => __( 'Color', 'ova-framework' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ova-history .wp-item .wp-content .title p' => 'color : {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'bg_color_title',
			[
				'label'     => __( 'Background color', 'ova-framework' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ova-history .wp-item .wp-content .title p' => 'background-color : {{VALUE}};',
					'{{WRAPPER}} .ova-history .wp-item .wp-content .title p:after' => 'border-left-color : {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_sub_title',
			[
				'label' => __( 'Sub title', 'ova-framework' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'sub_title_typography',
				'selector' => '{{WRAPPER}} .ova-history .wp-item .wp-year .sub-title',
				'scheme'   => Typography::TYPOGRAPHY_1,
			]
		);

		$this->add_control(
			'color_sub_title',
			[
				'label'     => __( 'Color', 'ova-framework' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ova-history .wp-item .wp-year .sub-title' => 'color : {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'color_line_sub_title',
			[
				'label'     => __( 'Color line', 'ova-framework' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ova-history .wp-item .wp-year .sub-title' => 'border-bottom-color : {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'padding_subtitle',
			[
				'label'      => __( 'Margin', 'ova-framework' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .ova-history .wp-item .wp-year .sub-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'margin_subtitle',
			[
				'label'      => __( 'Padding', 'ova-framework' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .ova-history .wp-item .wp-year .sub-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();


		$this->start_controls_section(
			'section_year',
			[
				'label' => __( 'Year', 'ova-framework' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'year_typography',
				'selector' => '{{WRAPPER}} .ova-history .wp-item .wp-year .year',
				'scheme'   => Typography::TYPOGRAPHY_1,
			]
		);

		$this->add_control(
			'color_year',
			[
				'label'     => __( 'Color', 'ova-framework' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ova-history .wp-item .wp-year .year' => 'color : {{VALUE}};',
				],
			]
		);


		$this->add_responsive_control(
			'margin_year',
			[
				'label'      => __( 'Margin', 'ova-framework' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .ova-history .wp-item .wp-year .year' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_desc',
			[
				'label' => __( 'Description', 'ova-framework' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'desc_typography',
				'selector' => '{{WRAPPER}} .ova-history .wp-item .wp-content .content .desc p',
				'scheme'   => Typography::TYPOGRAPHY_1,
			]
		);

		$this->add_control(
			'color_desc',
			[
				'label'     => __( 'Color', 'ova-framework' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ova-history .wp-item .wp-content .content .desc p' => 'color : {{VALUE}};',
				],
			]
		);


		$this->add_responsive_control(
			'margin_desc',
			[
				'label'      => __( 'Margin', 'ova-framework' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .ova-history .wp-item .wp-content .content .desc p' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_dot',
			[
				'label' => __( 'Dots', 'ova-framework' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);


		$this->add_control(
			'bg_color_dot',
			[
				'label'     => __( 'Color', 'ova-framework' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ova-history .wp-item .wp-year .sub-title .ova-point' => 'background-color : {{VALUE}};',
					'{{WRAPPER}} .ova-history .wp-item .wp-year .sub-title .ova-point span:before' => 'background-color : {{VALUE}};',
					'{{WRAPPER}} .ova-history .wp-item .wp-year .sub-title .ova-point span:after' => 'background-color : {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$tabs = $settings['tabs'];
		?>
		<div class="ova-history ">
			<?php if (is_array($tabs)) : ?>
				<?php foreach($tabs as $item) : ?>
					<div class="wp-item">
						<div class="wp-content">
							<div class="title">
								<?php if($item['title']) : ?>
									<p><?php echo $item['title'] ?></p>
								<?php endif ?>
							</div>
							<div class="content">
								<div class="ova-media">
									<img src="<?php echo esc_attr($item['image']['url']) ?>" alt="">
								</div>
								<div class="desc">
									<p><?php echo esc_html($item['desc']) ?></p>
								</div>
							</div>
						</div>
						<div class="wp-year">
							<p class="sub-title"><?php echo esc_html($item['sub_title']) ?><span class="ova-point"><span></span></span></p>
							<span class="year second_font"><?php echo esc_html($item['year']) ?></span>
						</div>
					</div>
				<?php endforeach ?>
			<?php endif ?>
		</div>
		<?php
	}
}
