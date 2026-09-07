<?php
namespace ova_framework\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Core\Schemes\Typography;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Color;
use Elementor\Group_Control_Border;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


class ova_feature extends Widget_Base {

	public function get_name() {
		return 'ova_feature';
	}

	public function get_title() {
		return __( 'Feature', 'ova-framework' );
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
				'label' => __( 'Feature', 'ova-framework' ),
			]
		);
		
		$this->add_control(
			'title',
			[
				'label' => __( 'Title', 'ova-framework' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __('Health And Safety','ova-framework'),
			]
		);

		$this->add_control(
			'desc',
			[
				'label' => __( 'Description', 'ova-framework' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'default' => __('Everyone has the right to go home safely to their families, friends and loved ones, every day.Cras nec leo efficitur, pellentesque massa nec, pretium purus. Aenean sed volutpat lorem.','ova-framework'),
			]
		);

		$this->add_control(
			'text_button',
			[
				'label' => __( 'Text Button', 'ova-framework' ),
				'type' => Controls_Manager::TEXT,
				'default' => __('LEARN MORE','ova-framework'),
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
			'icon',
			[
				'label' => __( 'Icons', 'ova-framework' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => 'produc_flaticon-balance',
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
				'selector' => '{{WRAPPER}} .ova-feature .content .title',
				'scheme' => Typography::TYPOGRAPHY_1,
			]
		);

		$this->add_control(
			'color_title',
			[
				'label' => __( 'Color ', 'ova-framework' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}  .ova-feature .content .title' => 'color : {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'color_title_box_hover',
			[
				'label' => __( 'Color box hover', 'ova-framework' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}  .ova-feature .content:hover .title' => 'color : {{VALUE}};',
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
					'{{WRAPPER}}  .ova-feature .content .title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
				'selector' => '{{WRAPPER}} .ova-feature .content .desc',
				'scheme' => Typography::TYPOGRAPHY_1,
			]
		);

		$this->add_control(
			'color_desc',
			[
				'label' => __( 'Color ', 'ova-framework' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}  .ova-feature .content .desc' => 'color : {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'color_desc_box_hover',
			[
				'label' => __( 'Color box hover', 'ova-framework' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}  .ova-feature .content:hover .desc' => 'color : {{VALUE}};',
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
					'{{WRAPPER}}  .ova-feature .content .desc' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
				'selector' => '{{WRAPPER}} .ova-feature .content .text_button',
				'scheme' => Typography::TYPOGRAPHY_1,
			]
		);

		$this->add_control(
			'color_button',
			[
				'label' => __( 'Color ', 'ova-framework' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}  .ova-feature .content .text_button' => 'color : {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'color_button_hover',
			[
				'label' => __( 'Color hover', 'ova-framework' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}  .ova-feature .content:hover .text_button:hover' => 'color : {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'color_button_box_hover',
			[
				'label' => __( 'Color box hover', 'ova-framework' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}  .ova-feature .content:hover .text_button' => 'color : {{VALUE}};',
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
					'{{WRAPPER}}  .ova-feature .content .text_button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

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
					'{{WRAPPER}}  .ova-feature .content i:before' => 'color : {{VALUE}};',
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
					'{{WRAPPER}} .ova-feature .content i:before' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_bg',
			[
				'label' => __( 'Background overlay', 'ova-framework' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);


		$this->add_control(
			'bg_color_1',
			[
				'label' => __( 'Background color overlay 1', 'ova-framework' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}  .ova-feature .content:after' => 'background-color : {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'bg_color_2',
			[
				'label' => __( 'Background color overlay 2', 'ova-framework' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}  .ova-feature .content:before' => 'background-color : {{VALUE}};',
				],
			]
		);

		

		$this->end_controls_section();

	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		?>
		<div class="ova-feature">
			<div class="content" style="background-image: url('<?php echo esc_attr($settings['image']['url']) ?>')">
				<?php if ($settings['title'] !== '') : ?>
					<h3 class="title second_font"><?php echo esc_html($settings['title']) ?></h3>
				<?php endif ?>
				<?php if ($settings['desc'] !== '') : ?>
					<p class="desc"><?php echo esc_html($settings['desc']) ?></p>
				<?php endif ?>
				<?php if ($settings['text_button'] !== '') : ?>
					<?php $target = $settings['link']['is_external'] ? '_blank' : '_self';  ?>
					<a class="text_button" target="<?php echo esc_attr($target); ?>" href="<?php echo esc_attr($settings['link']['url']) ?>"><?php echo esc_html($settings['text_button']) ?></a>
				<?php endif ?>
				<?php if ($settings['icon'] !== '') : ?>
					<i class="<?php echo esc_attr($settings['icon']) ?>"></i>
				<?php endif ?>
			</div>
		</div>
		<?php
	}
}
