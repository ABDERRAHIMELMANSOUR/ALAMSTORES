<?php
namespace ova_framework\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Core\Schemes\Typography;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Color;
use Elementor\Group_Control_Border;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class ova_position extends Widget_Base {

	public function get_name() {
		return 'ova_position';
	}

	public function get_title() {
		return __( 'Position', 'ova-framework' );
	}

	public function get_icon() {
		return 'eicon-slider-push';
	}

	public function get_categories() {
		return [ 'ovatheme' ];
	}

	public function get_script_depends() {
		wp_enqueue_style( 'owl-carousel', OVA_PLUGIN_URI.'assets/libs/owl-carousel/assets/owl.carousel.min.css' );
		wp_enqueue_script( 'owl-carousel', OVA_PLUGIN_URI.'assets/libs/owl-carousel/owl.carousel.min.js', array('jquery'), false, true );
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
			'title',
			[
				'label' => __( 'Title', 'ova-framework' ),
				'type' => Controls_Manager::TEXTAREA,
				'row' => 2,
				'default' => __('Environmental technology', 'ova-framework'),
			]
		);

		$repeater->add_control(
			'sub_title',
			[
				'label' => __( 'Sub title', 'ova-framework' ),
				'type' => Controls_Manager::TEXT,
				'default' => __('Experience Required', 'ova-framework'),
			]
		);

		$repeater->add_control(
			'desc',
			[
				'label' => __( 'Description', 'ova-framework' ),
				'type' => Controls_Manager::TEXTAREA,
				'row' => 5,
				'default' => __('Nulla facilisi. Phasellus suscipit ac orci et suscipit. Suspendisse ultrices, nibh vitae lacinia.', 'ova-framework'),
			]
		);

		$repeater->add_control(
			'link',
			[
				'label'   => 'Link',
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
			'tabs',
			[
				'label'       => 'Item',
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default' => [
					[
						'title' => __('Environmental technology', 'ova-framework'),
						'sub_title' => __('Experience Required', 'ova-framework'),
						'desc' =>  __( 'Nulla facilisi. Phasellus suscipit ac orci et suscipit. Suspendisse ultrices, nibh vitae lacinia.', 'ova-framework' ),
					],
					[
						'title' => __('Engineering and manufacturing', 'ova-framework'),
						'sub_title' => __('High Salary', 'ova-framework'),
						'desc' =>  __( 'Nulla facilisi. Phasellus suscipit ac orci et suscipit. Suspendisse ultrices, nibh vitae lacinia.', 'ova-framework' ),
					],
					[
						'title' => __('Construction management', 'ova-framework'),
						'sub_title' => __('Experience Required', 'ova-framework'),
						'desc' =>  __( 'Nulla facilisi. Phasellus suscipit ac orci et suscipit. Suspendisse ultrices, nibh vitae lacinia.', 'ova-framework' ),
					],
				],
				'title_field' => '{{{ title }}}',
			]
		);



		$this->end_controls_section();
		//END SECTION CONTENT

		$this->start_controls_section(
			'section_additional_options',
			[
				'label' => __( 'Additional Options', 'ova-framework' ),
			]
		);


		$this->add_control(
			'margin_items',
			[
				'label' => __( 'Margin Right Items', 'ova-framework' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 30,
			]

		);


		$this->add_control(
			'slides_to_scroll',
			[
				'label' => __( 'Slides to Scroll', 'ova-framework' ),
				'type' => Controls_Manager::NUMBER,
				'description' => __( 'Set how many slides are scrolled per swipe.', 'ova-framework' ),
				'default' => '1',
			]
		);

		$this->add_control(
			'pause_on_hover',
			[
				'label' => __( 'Pause on Hover', 'ova-framework' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'options' => [
					'yes' => __( 'Yes', 'ova-framework' ),
					'no' => __( 'No', 'ova-framework' ),
				],
				'frontend_available' => true,
			]
		);


		$this->add_control(
			'infinite',
			[
				'label' => __( 'Infinite Loop', 'ova-framework' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'options' => [
					'yes' => __( 'Yes', 'ova-framework' ),
					'no' => __( 'No', 'ova-framework' ),
				],
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'autoplay',
			[
				'label' => __( 'Autoplay', 'ova-framework' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'options' => [
					'yes' => __( 'Yes', 'ova-framework' ),
					'no' => __( 'No', 'ova-framework' ),
				],
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'autoplay_speed',
			[
				'label' => __( 'Autoplay Speed', 'ova-framework' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 3000,
				'step' => 500,
				'condition' => [
					'autoplay' => 'yes',
				],
				'frontend_available' => true,
				'condition' => [
					'autoplay' => 'yes',
				],
			]
		);

		$this->add_control(
			'smartspeed',
			[
				'label'   => __( 'Smart Speed', 'ova-framework' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 500,

			]
		);

		$this->add_control(
			'dot_control',
			[
				'label'   => __( 'Show Dots', 'ova-framework' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'options' => [
					'yes' => __( 'Yes', 'ova-framework' ),
					'no'  => __( 'No', 'ova-framework' ),
				],
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'nav_control',
			[
				'label'   => __( 'Show Nav', 'ova-framework' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'options' => [
					'yes' => __( 'Yes', 'ova-framework' ),
					'no'  => __( 'No', 'ova-framework' ),
				],
				'frontend_available' => true,
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
				'selector' => '{{WRAPPER}} .ova-position .item .wp-content .content .title a',
				'scheme'   => Typography::TYPOGRAPHY_1,
			]
		);

		$this->add_control(
			'title_color',
			[
				'label'     => __( 'Color', 'ova-framework' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ova-position .item .wp-content .content .title a' => 'color : {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'title_color_hover',
			[
				'label'     => __( 'Color hover', 'ova-framework' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ova-position .item .wp-content .content .title a:hover' => 'color : {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'title_margin',
			[
				'label'      => __( 'Margin', 'ova-framework' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .ova-position .item .wp-content .content .title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_sub_title',
			[
				'label' => __( 'Sub Title', 'ova-framework' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'sub_title_typography',
				'selector' => '{{WRAPPER}} .ova-position .item .wp-content .content .sub-title',
				'scheme'   => Typography::TYPOGRAPHY_1,
			]
		);

		$this->add_control(
			'sub_title_color',
			[
				'label'     => __( 'Color', 'ova-framework' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ova-position .item .wp-content .content .sub-title' => 'color : {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'sub_title_margin',
			[
				'label'      => __( 'Margin', 'ova-framework' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .ova-position .item .wp-content .content .sub-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
				'selector' => '{{WRAPPER}} .ova-position .item .wp-content .desc p',
				'scheme'   => Typography::TYPOGRAPHY_1,
			]
		);

		$this->add_control(
			'desc_color',
			[
				'label'     => __( 'Color', 'ova-framework' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ova-position .item .wp-content .desc p' => 'color : {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'desc_margin',
			[
				'label'      => __( 'Margin', 'ova-framework' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .ova-position .item .wp-content .desc p' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_border',
			[
				'label' => __( 'Border', 'ova-framework' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);


		$this->add_control(
			'boder_color',
			[
				'label'     => __( 'Color', 'ova-framework' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ova-position .item .wp-content .content' => 'border-color : {{VALUE}};',
					'{{WRAPPER}} .ova-position .item .wp-content .desc' => 'border-color : {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'boder_color_hover',
			[
				'label'     => __( 'Color hover', 'ova-framework' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ova-position .item:hover .wp-content .content' => 'border-color : {{VALUE}}!important;',
					'{{WRAPPER}} .ova-position .item:hover .wp-content .desc' => 'border-color : {{VALUE}}!important;',
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

		$data_options['slideBy'] 			= $settings['slides_to_scroll'];
		$data_options['margin'] 			= $settings['margin_items'];
		$data_options['autoplayHoverPause'] = $settings['pause_on_hover'] === 'yes' ? true : false;
		$data_options['loop'] 			 	= $settings['infinite'] === 'yes' ? true : false;
		$data_options['autoplay'] 			= $settings['autoplay'] === 'yes' ? true : false;
		$data_options['autoplayTimeout']	= $data_options['autoplay'] ? $settings['autoplay_speed'] : 3000;
		$data_options['smartSpeed']			= $settings['smartspeed'];
		$data_options['dots']               = $settings['dot_control'] === 'yes' ? true : false;
		$data_options['nav']               	= $settings['nav_control'] === 'yes' ? true : false;

		?>
		<div class="ova-position">
			<div class="position-slider  owl-carousel owl-theme owl-loaded" data-options="<?php echo esc_attr(json_encode($data_options)) ?>">
				<?php if(!empty($tabs)) : foreach ($tabs as $item) : ?>
					<div class="item">
						<div class="ova-media">
							<img src="<?php echo esc_attr($item['image']['url']) ?>" alt="<?php echo esc_attr($item['title']) ?>">
						</div>
						<div class="wp-content">
							<div class="content">
								<?php $target = $item['link']['is_external'] ? '_blank' : '_self';  ?>
								<h3 class="title"><a target="<?php echo esc_attr( $target ); ?>" class="second_font" href="<?php echo esc_attr($item['link']['url']) ?>"><?php echo esc_html($item['title']) ?></a></h3>
								<p class="sub-title"><?php echo esc_html($item['sub_title']) ?></p>
							</div>
							<div class="desc">
								<p><?php echo esc_html($item['desc']) ?></p>
							</div>
						</div>
					</div>
				<?php endforeach; endif; ?>
			</div>
		</div>
		<?php
	}
}
