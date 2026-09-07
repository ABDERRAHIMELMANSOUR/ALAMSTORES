<?php
namespace ova_framework\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Core\Schemes\Typography;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Color;
use Elementor\Group_Control_Border;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


class ova_teamwork extends Widget_Base {

	public function get_name() {
		return 'ova_teamwork';
	}

	public function get_title() {
		return __( 'Team Work', 'ova-framework' );
	}

	public function get_icon() {
		return 'fa fa-object-group';
	}

	public function get_categories() {
		return [ 'ovatheme' ];
	}

	public function get_script_depends() {
		wp_enqueue_script( 'video-popup', OVA_PLUGIN_URI.'assets/libs/video.popup.js', array('jquery'), false, true );
		return [ 'script-elementor' ];
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Icon', 'ova-framework' ),
			]
		);
		
		$this->add_control(
			'type_video',
			[
				'label' => __( 'Type Video', 'ova-framework' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'ova-framework' ),
				'label_off' => __( 'Hide', 'ova-framework' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'link_video',
			[
				'label' => __( 'Link Youtube', 'ova-framework' ),
				'type' => Controls_Manager::TEXT,
				'condition' => [
					'type_video' => 'yes', 
				]
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
				'label'   => __( 'Title ', 'ova-framework' ),
				'type'    => \Elementor\Controls_Manager::TEXTAREA,
				'row' => 2,
				'default' => __( 'Best Repair and Renovation', 'ova-framework' ),
			]
		);

		$this->add_control(
			'desc',
			[
				'label'   => __( 'Description ', 'ova-framework' ),
				'type'    => \Elementor\Controls_Manager::TEXTAREA,
				'row' => 5,
				'default' => __( 'Variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words.', 'ova-framework' ),
			]
		);

		$this->add_control(
			'text_button',
			[
				'label'   => __( 'Text button ', 'ova-framework' ),
				'type'    => \Elementor\Controls_Manager::TEXTAREA,
				'row' => 2,
				'default' => __( 'Read More', 'ova-framework' ),
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
			'section_button',
			[
				'label' => __( 'Button', 'ova-framework' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'font_size_button',
			[
				'label' => __( 'Size', 'plugin-domain' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 100,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .ova-teamwork .ova-media .button-video .image-team i' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'color_button',
			[
				'label'     => __( 'Color', 'ova-framework' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ova-teamwork .ova-media .button-video .image-team i' => 'color : {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'color_button_hover',
			[
				'label'     => __( 'Color hover', 'ova-framework' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ova-teamwork .ova-media .button-video .image-team:hover i' => 'color : {{VALUE}};',
				],
			]
		);



		$this->add_control(
			'bg_color_button',
			[
				'label'     => __( 'Background Color', 'ova-framework' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ova-teamwork .ova-media .button-video .image-team i' => 'background-color : {{VALUE}};',
					'{{WRAPPER}} .ova-teamwork .ova-media .button-video .image-team:before' => 'background-color : {{VALUE}};opacity: 0.7;',
				],
			]
		);

		$this->add_control(
			'bg_color_button_hover',
			[
				'label'     => __( 'Background color hover', 'ova-framework' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ova-teamwork .ova-media .button-video .image-team:hover i' => 'background-color : {{VALUE}};',
					'{{WRAPPER}} .ova-teamwork .ova-media .button-video .image-team:hover:before' => 'background-color : {{VALUE}};opacity: 0.7;',
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
					'{{WRAPPER}} .ova-teamwork .ova-media .button-video .image-team' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
				'selector' => '{{WRAPPER}} .ova-teamwork .content .title a',
				'scheme' => Typography::TYPOGRAPHY_1,
			]
		);

		$this->add_control(
			'color_title',
			[
				'label' => __( 'Color ', 'ova-framework' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ova-teamwork .content .title a' => 'color : {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'color_title_hover',
			[
				'label' => __( 'Color hover', 'ova-framework' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ova-teamwork .content .title a:hover' => 'color : {{VALUE}};',
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
					'{{WRAPPER}} .ova-teamwork .content .title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
				'selector' => '{{WRAPPER}} .ova-teamwork .content .desc',
				'scheme' => Typography::TYPOGRAPHY_1,
			]
		);

		$this->add_control(
			'color_desc',
			[
				'label' => __( 'Color ', 'ova-framework' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ova-teamwork .content .desc' => 'color : {{VALUE}};',
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
					'{{WRAPPER}} .ova-teamwork .content .desc' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_text_button',
			[
				'label' => __( 'Button read more', 'ova-framework' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'btn_read_more_typography',
				'selector' => '{{WRAPPER}} .ova-teamwork .content .text_button',
				'scheme' => Typography::TYPOGRAPHY_1,
			]
		);

		$this->add_control(
			'color_btn_reamore',
			[
				'label' => __( 'Color ', 'ova-framework' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ova-teamwork .content .text_button' => 'color : {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'color_btn_reamore_hover',
			[
				'label' => __( 'Color hover', 'ova-framework' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ova-teamwork .content .text_button:hover' => 'color : {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'margin_button_readmore',
			[
				'label' => __( 'Margin', 'ova-framework' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .ova-teamwork .content .text_button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();


	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		?>
		<div class="ova-teamwork">
			<div class="ova-media">
				<img src="<?php echo esc_attr($settings['image']['url']) ?>" alt="<?php echo esc_attr($settings['title']) ?>">
				<?php if ($settings['type_video'] === 'yes') : ?>
					<div class="button-video">
						<a video-url="<?php echo esc_attr($settings['link_video']);?>" class="image-team">
							<i class="fa fa-play"></i>
						</a>
					</div>
				<?php endif ?>
			</div>
			<div class="content">
				<?php if ($settings['title'] !== '') : ?>
					<?php $target = $settings['link']['is_external'] ? '_blank' : '_self';  ?>
					<h3 class="title second_font"><a target="<?php echo esc_attr( $target ); ?>" href="<?php echo esc_attr($settings['link']['url']) ?>"><?php echo esc_html($settings['title']) ?></a></h3>
				<?php endif ?>
				<?php if ($settings['desc'] !== '') : ?>
					<p class="desc"><?php echo esc_html($settings['desc']) ?></p>
				<?php endif ?>
				<?php if($settings['text_button'] !== '') : ?>
					<?php $target = $settings['link']['is_external'] ? '_blank' : '_self';  ?>
					<a target="<?php echo esc_attr( $target ); ?>" href="<?php echo esc_attr($settings['link']['url']) ?>" class="text_button"><?php echo esc_html($settings['text_button']) ?></a>
				<?php endif ?>
			</div>
		</div>
		<?php
	}
}
