<?php
namespace ova_framework\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Core\Schemes\Typography;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Color;
use Elementor\Group_Control_Border;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


class ova_partner extends Widget_Base {

	public function get_name() {
		return 'ova_partner';
	}

	public function get_title() {
		return __( 'Partnership', 'ova-framework' );
	}

	public function get_icon() {
		return 'fa fa-handshake-o';
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
			'image',
			[
				'label'   => 'Image',
				'type'    => \Elementor\Controls_Manager::MEDIA,
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
			]
		);
		
		$this->add_control(
			'title',
			[
				'label' => __( 'Title', 'ova-framework' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __('Corparetion','ova-framework'),
			]
		);

		$this->add_control(
			'sub_title',
			[
				'label' => __( 'Sub Title', 'ova-framework' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __('Roof manufacturing company','ova-framework'),
			]
		);

		$this->add_control(
			'desc',
			[
				'label' => __( 'Description', 'ova-framework' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'default' => __('Vivamus a hendrerit diam. Sed ultricies est et sapien sollicitudin viverra. Praesent viverra est quis eros congue, ut efficitur massa mattis. Etiam risus metus, feugiat varius neque nec.','ova-framework'),
			]
		);

		$this->add_control(
			'text_button',
			[
				'label' => __( 'Text Button', 'ova-framework' ),
				'type' => Controls_Manager::TEXT,
				'default' => __('Open Website','ova-framework'),
			]
		);

		$this->add_control(
			'link',
			[
				'label' => __( 'Link', 'ova-framework' ),
				'type' => \Elementor\Controls_Manager::URL,
				'placeholder' => __( 'https://your-link.com', 'ova-framework' ),
				'show_external' => true,
				'default' => [
					'url' => '#',
					'is_external' => false,
					'nofollow' => false,
				],
			]
		);

		$this->end_controls_section();
		// end tab section_content


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
				'selector' => '{{WRAPPER}} .ova-partner .wp-content .content .title a',
				'scheme' => Typography::TYPOGRAPHY_1,
			]
		);

		$this->add_control(
			'color_title',
			[
				'label' => __( 'Color ', 'ova-framework' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}  .ova-partner .wp-content .content .title a' => 'color : {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'color_title_hover',
			[
				'label' => __( 'Color hover', 'ova-framework' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}  .ova-partner .wp-content .content .title a:hover' => 'color : {{VALUE}};',
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
					'{{WRAPPER}}  .ova-partner .wp-content .content .title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();


		$this->start_controls_section(
			'section_sub_title',
			[
				'label' => __( 'Sub Title', 'ova-framework' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'sub_title_typography',
				'selector' => '{{WRAPPER}} .ova-partner .wp-content .content .sub-title',
				'scheme' => Typography::TYPOGRAPHY_1,
			]
		);

		$this->add_control(
			'color_sub_title',
			[
				'label' => __( 'Color ', 'ova-framework' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}  .ova-partner .wp-content .content .sub-title' => 'color : {{VALUE}};',
				],
			]
		);


		$this->add_responsive_control(
			'margin_sub_title',
			[
				'label' => __( 'Margin', 'ova-framework' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}}  .ova-partner .wp-content .content .sub-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
				'selector' => '{{WRAPPER}} .ova-partner .wp-content .content .desc',
				'scheme' => Typography::TYPOGRAPHY_1,
			]
		);

		$this->add_control(
			'color_desc',
			[
				'label' => __( 'Color ', 'ova-framework' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}  .ova-partner .wp-content .content .desc' => 'color : {{VALUE}};',
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
					'{{WRAPPER}}  .ova-partner .wp-content .content .desc' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_button',
			[
				'label' => __( 'Button', 'ova-framework' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'button_typography',
				'selector' => '{{WRAPPER}} .ova-partner .wp-content .content .text-button',
				'scheme' => Typography::TYPOGRAPHY_1,
			]
		);

		$this->add_control(
			'color_button',
			[
				'label' => __( 'Color ', 'ova-framework' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}  .ova-partner .wp-content .content .text-button' => 'color : {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'color_button_hover',
			[
				'label' => __( 'Color Hover', 'ova-framework' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}  .ova-partner .wp-content .content .text-button:hover' => 'color : {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'margin_button',
			[
				'label' => __( 'Margin', 'ova-framework' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}}  .ova-partner .wp-content .content .text-button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		?>
		<div class="ova-partner">
			<div class="wp-content">
				<div class="ova-media">
					<div class="image">
						<img src="<?php echo esc_attr($settings['image']['url']) ?>" alt="">
					</div>
				</div>
				<div class="content">

					<?php $target = $settings['link']['is_external'] ? '_blank' : '_self';  ?>

					<p class="title"><a target="<?php echo esc_attr($target); ?>" href="<?php echo esc_attr($settings['link']['url']) ?>"><?php echo esc_html($settings['title']) ?></a></p>
					<p class="sub-title"><?php echo esc_html($settings['sub_title']) ?></p>
					<p class="desc"><?php echo esc_html($settings['desc']) ?></p>
					<a target="<?php echo esc_attr($target); ?>" href="<?php echo esc_attr($settings['link']['url']) ?>" class="text-button"><?php echo esc_html($settings['text_button']) ?></a>
				</div>
			</div>
		</div>
		<?php
	}
}
