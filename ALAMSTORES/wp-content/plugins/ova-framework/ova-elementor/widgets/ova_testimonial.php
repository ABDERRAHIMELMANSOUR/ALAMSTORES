<?php

namespace ova_framework\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Core\Schemes\Typography;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Color;
use Elementor\Utils;


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class ova_testimonial extends Widget_Base {

	public function get_name() {
		return 'ova_testimonial';
	}

	public function get_title() {
		return __( 'Testimonial', 'ova-framework' );
	}

	public function get_icon() {
		return 'eicon-testimonial';
	}

	public function get_categories() {
		return [ 'ovatheme' ];
	}

	public function get_script_depends() {
		// Carousel
		wp_enqueue_style( 'owl-carousel', OVA_PLUGIN_URI.'assets/libs/owl-carousel/assets/owl.carousel.min.css' );
		wp_enqueue_script( 'owl-carousel', OVA_PLUGIN_URI.'assets/libs/owl-carousel/owl.carousel.min.js', array('jquery'), false, true );
		return [ 'script-elementor' ];
	}

	protected function register_controls() {


		$this->start_controls_section(
			'section_version',
			[
				'label' => __( 'Version', 'ova-framework' ),
			]
		);

		$this->add_control(
			'version',
			[
				'label'   => __( 'Version', 'ova-framework' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'version_1',
				'options' => [
					'version_1' => __('Version 1', 'ova-framework'),
					'version_2' => __('Version 2', 'ova-framework'),
					'version_3' => __('Version 3', 'ova-framework'),
					'version_4' => __('Version 4', 'ova-framework'),
				],
			]

		);


		$this->end_controls_section();

		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Content', 'ova-framework' ),
				'condition' => [
					'version' => ['version_1','version_2','version_3'],
				]
				
			]
		);


		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'name_author',
			[
				'label'   => 'Name Author',
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => __('DAVID WOODS', 'ova-framework'),
			]
		);

		$repeater->add_control(
			'job',
			[
				'label'   => 'Job',
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => __('CUSTOMER', 'ova-framework'),
			]
		);

		$repeater->add_control(
			'star',
			[
				'label'   => 'Star',
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 4,
				'options' => [1,2,3,4,5],

			]
		);

		$repeater->add_control(
			'image_author',
			[
				'label'   => 'Image Author',
				'type'    => \Elementor\Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
		);

		$repeater->add_control(
			'testimonial',
			[
				'label'   => __( 'Testimonial ', 'ova-framework' ),
				'type'    => \Elementor\Controls_Manager::TEXTAREA,
				'default' => __( 'I would say I highly recommend this to my friends, acquaintances and family members. The attitude and services are always dedicated.', 'ova-framework' ),
			]
		);



		$this->add_control(
			'tab_item',
			[
				'label'       => 'Item Testimonial',
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default' => [
					[
						'name_author' => __('DAVID WOODS', 'ova-framework'),
						'job' => __('CUSTOMER', 'ova-framework'),
						'star' => 3,
						'testimonial' =>  __( 'I would say I highly recommend this to my friends, acquaintances and family members. The attitude and services are always dedicated.', 'ova-framework' ),
					],
					[
						'name_author' => __('GREGORY KENNEDY', 'ova-framework'),
						'job' => __('EMPLOYER', 'ova-framework'),
						'star' => 4,
						'testimonial' =>  __( 'Their services are among the best to be honest. Making everything simple and easy, even for beginners and novices like me and my family.', 'ova-framework' ),
					],
				],
				'title_field' => '{{{ name_author }}}',
			]
		);


		$this->end_controls_section();

		$this->start_controls_section(
			'section_conten_v4',
			[
				'label' => __( 'Content', 'ova-framework' ),
				'condition' => [
					'version' => 'version_4',
				]
				
			]
		);


		$repeater_v4 = new \Elementor\Repeater();

		$repeater_v4->add_control(
			'name_author_v4',
			[
				'label'   => 'Name Author',
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => __('DAVID WOODS', 'ova-framework'),
			]
		);

		$repeater_v4->add_control(
			'job_v4',
			[
				'label'   => 'Job',
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => __('CUSTOMER', 'ova-framework'),
			]
		);

		$repeater_v4->add_control(
			'image_author_v4',
			[
				'label'   => 'Image Author',
				'type'    => \Elementor\Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
		);

		$repeater_v4->add_control(
			'testimonial_v4',
			[
				'label'   => __( 'Testimonial ', 'ova-framework' ),
				'type'    => \Elementor\Controls_Manager::TEXTAREA,
				'default' => __( 'I would say I highly recommend this to my friends, acquaintances and family members. The attitude and services are always dedicated.', 'ova-framework' ),
			]
		);

		$repeater_v4->add_control(
			'link_facebook',
			[
				'label'   => 'Link Facebook',
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

		$repeater_v4->add_control(
			'link_twitter',
			[
				'label'   => 'Link Twitter',
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

		$repeater_v4->add_control(
			'link_linkedin',
			[
				'label'   => 'Link Linkedin',
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
			'tab_item_v4',
			[
				'label'       => 'Item Testimonial',
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater_v4->get_controls(),
				'default' => [
					[
						'name_author_v4' => __('ANDREW MENDEZ', 'ova-framework'),
						'job_v4' => __('Vice President', 'ova-framework'),
						'testimonial_v4' =>  __( 'Cras molestie nunc nibh, faucibus aliquet leo hendrerit vulputate. Integer vulputate felis non orci fringilla, ac volutpat metus mattis. Curabitur gravida mattis congue. Cras faucibus nulla velit, a aliquam ipsum.', 'ova-framework' ),
					],
					[
						'name_author_v4' => __('WALTER ANDERSON', 'ova-framework'),
						'job_v4' => __('Chief Executive Officer ', 'ova-framework'),
						'testimonial_v4' =>  __( 'Cras molestie nunc nibh, faucibus aliquet leo hendrerit vulputate. Integer vulputate felis non orci fringilla, ac volutpat metus mattis. Curabitur gravida mattis congue. Cras faucibus nulla velit, a aliquam ipsum.', 'ova-framework' ),
					],
				],
				'title_field' => '{{{ name_author_v4 }}}',
			]
		);


		$this->end_controls_section();



		$this->start_controls_section(
			'section_additional_options',
			[
				'label' => __( 'Additional Options', 'ova-framework' ),
			]
		);


		$this->add_control(
			'margin_items',
			[
				'label'   => __( 'Margin Right Items', 'ova-framework' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 30,
			]

		);


		$this->add_control(
			'slides_to_scroll',
			[
				'label'       => __( 'Slides to Scroll', 'ova-framework' ),
				'type'        => Controls_Manager::NUMBER,
				'description' => __( 'Set how many slides are scrolled per swipe.', 'ova-framework' ),
				'default'     => 1,
			]
		);

		$this->add_control(
			'pause_on_hover',
			[
				'label'   => __( 'Pause on Hover', 'ova-framework' ),
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
			'infinite',
			[
				'label'   => __( 'Infinite Loop', 'ova-framework' ),
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
			'autoplay',
			[
				'label'   => __( 'Autoplay', 'ova-framework' ),
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
			'autoplay_speed',
			[
				'label'     => __( 'Autoplay Speed', 'ova-framework' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 3000,
				'step'      => 500,
				'condition' => [
					'autoplay' => 'yes',
				],
				'frontend_available' => true,
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
			'section_box',
			[
				'label' => __( 'Box', 'ova-framework' ),
				'tab'   => Controls_Manager::TAB_STYLE,
				'condition' => [
					'version' => ['version_2'],
				]
			]
		);

		$this->add_control(
			'bg_color',
			[
				'label'     => __( 'Background Color', 'ova-framework' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ova-testimonial .slide-testimonials.version_2 .client_info .info' => 'background-color : {{VALUE}};',
					'{{WRAPPER}} .ova-testimonial .slide-testimonials.version_2 .client_info .info:after' => 'border-top-color : {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_content_testimonial',
			[
				'label' => __( 'Content Testimonial', 'ova-framework' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'content_testimonial_typography',
				'selector' => '{{WRAPPER}} .ova-testimonial .slide-testimonials .client_info .info .testimonial',
				'scheme'   => Typography::TYPOGRAPHY_1,
			]
		);

		$this->add_control(
			'content_color',
			[
				'label'     => __( 'Color', 'ova-framework' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ova-testimonial .slide-testimonials .client_info .info .testimonial' => 'color : {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'content_margin',
			[
				'label'      => __( 'Margin', 'ova-framework' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .ova-testimonial .slide-testimonials .client_info .info' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_star',
			[
				'label' => __( 'Star', 'ova-framework' ),
				'tab'   => Controls_Manager::TAB_STYLE,
				'condition' => [
					'version' => ['version_1','version_2','version_3'],
				]
			]
		);

		$this->add_control(
			'star_color',
			[
				'label'     => __( 'Color', 'ova-framework' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ova-testimonial .slide-testimonials .client_info .info .star i' => 'color : {{VALUE}};',
				],
			]
		);


		$this->add_control(
			'star_color_active',
			[
				'label'     => __( 'Color Active', 'ova-framework' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ova-testimonial .slide-testimonials .client_info .info .star i.active' => 'color : {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'star_margin',
			[
				'label'      => __( 'Margin', 'ova-framework' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .ova-testimonial .slide-testimonials .client_info .info .star' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);


		$this->end_controls_section();


		$this->start_controls_section(
			'section_social',
			[
				'label' => __( 'Social', 'ova-framework' ),
				'tab'   => Controls_Manager::TAB_STYLE,
				'condition' => [
					'version' => ['version_4'],
				]
			]
		);

		$this->add_control(
			'social_color',
			[
				'label'     => __( 'Color', 'ova-framework' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ova-testimonial .slide-testimonials.version_4 .client_info .info .social a i' => 'color : {{VALUE}};',
				],
			]
		);


		$this->add_control(
			'social_color_hover',
			[
				'label'     => __( 'Color Hover', 'ova-framework' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ova-testimonial .slide-testimonials.version_4 .client_info .info .social a:hover i' => 'color : {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'social_margin',
			[
				'label'      => __( 'Margin', 'ova-framework' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .ova-testimonial .slide-testimonials.version_4 .client_info .info .social a:not(:last-child)' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);


		$this->end_controls_section();

		$this->start_controls_section(
			'section_author_name',
			[
				'label' => __( 'Author Name', 'ova-framework' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'author_name_typography',
				'selector' => '{{WRAPPER}} .ova-testimonial .slide-testimonials .client_info .info span.name, {{WRAPPER}} .ova-testimonial .slide-testimonials .client_info .title .name',
				'scheme'   => Typography::TYPOGRAPHY_1,
			]
		);

		$this->add_control(
			'author_name_color',
			[
				'label'     => __( 'Color Author', 'ova-framework' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ova-testimonial .slide-testimonials .client_info .info span.name' => 'color : {{VALUE}};',
					'{{WRAPPER}} .ova-testimonial .slide-testimonials .client_info .title .name' => 'color : {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'author_name_margin',
			[
				'label'      => __( 'Margin', 'ova-framework' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .ova-testimonial .slide-testimonials .client_info .info span.name' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .ova-testimonial .slide-testimonials .client_info .title .name' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);


		$this->end_controls_section();


		$this->start_controls_section(
			'section_job',
			[
				'label' => __( 'Job', 'ova-framework' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'job_typography',
				'selector' => '{{WRAPPER}} .ova-testimonial .slide-testimonials .client_info .info span.job, {{WRAPPER}} .ova-testimonial .slide-testimonials .client_info .title .job',
				'scheme'   => Typography::TYPOGRAPHY_1,
			]
		);

		$this->add_control(
			'job_color',
			[
				'label'     => __( 'Color Job', 'ova-framework' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ova-testimonial .slide-testimonials .client_info .info span.job' => 'color : {{VALUE}};',
					'{{WRAPPER}} .ova-testimonial .slide-testimonials.version_1 .client_info .info span small' => 'color : {{VALUE}};',
					'{{WRAPPER}} .ova-testimonial .slide-testimonials .client_info .title .job' => 'color : {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'job_margin',
			[
				'label'      => __( 'Margin', 'ova-framework' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .ova-testimonial .slide-testimonials .client_info .info span.job' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .ova-testimonial .slide-testimonials .client_info .title .job' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_nav',
			[
				'label' => __( 'Nav', 'ova-framework' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'nav_color',
			[
				'label'     => __( 'Color', 'ova-framework' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ova-testimonial .slide-testimonials .owl-nav button i' => 'color : {{VALUE}};',
					'{{WRAPPER}} .ova-testimonial .slide-testimonials .owl-nav button' => 'border-color : {{VALUE}}!important;',
				],
			]
		);


		$this->add_control(
			'nav_color_hover',
			[
				'label'     => __( 'Color Hover', 'ova-framework' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ova-testimonial .slide-testimonials .owl-nav button:hover i' => 'color : {{VALUE}};',
					'{{WRAPPER}} .ova-testimonial .slide-testimonials .owl-nav button:hover' => 'border-color : {{VALUE}}!important;',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_dot',
			[
				'label' => __( 'Dot', 'ova-framework' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'dot_color',
			[
				'label'     => __( 'Color', 'ova-framework' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ova-testimonial .slide-testimonials .owl-dots .owl-dot.active span' => 'background-color : {{VALUE}};',
					'{{WRAPPER}} .ova-testimonial .slide-testimonials .owl-dots .owl-dot span' => 'border-color : {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'dot_space',
			[
				'label' => __( 'Space', 'plugin-domain' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 5,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .ova-testimonial .slide-testimonials .owl-dots .owl-dot:not(:last-child) span' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

	}

	protected function render() {
		$settings = $this->get_settings();
		$tab_item = $settings['tab_item'];
		$tab_item_v4 = $settings['tab_item_v4'];
		$version = $settings['version'];

		$data_options['slideBy']            = $settings['slides_to_scroll'];
		$data_options['margin']             = $settings['margin_items'];
		$data_options['autoplayHoverPause'] = $settings['pause_on_hover'] === 'yes' ? true : false;
		$data_options['loop']               = $settings['infinite'] === 'yes' ? true : false;
		$data_options['autoplay']           = $settings['autoplay'] === 'yes' ? true : false;
		$data_options['autoplayTimeout']    = $settings['autoplay_speed'];
		$data_options['smartSpeed']         = $settings['smartspeed'];
		$data_options['dots']               = $settings['dot_control'] === 'yes' ? true : false;
		$data_options['nav']               	= $settings['nav_control'] === 'yes' ? true : false;

		$class_active_nav = $data_options['nav'] ? 'active-nav' : '';
		


		?>
		<section class="ova-testimonial <?php echo esc_attr($class_active_nav) ?>">
			<?php if ( $version === 'version_1' || $version === 'version_3' ) : ?>
				<div class="slide-testimonials owl-carousel owl-theme <?php echo esc_attr($version) ?>" data-options="<?php echo esc_attr(json_encode($data_options)) ?>">
					<?php if(!empty($tab_item)) : foreach ($tab_item as $item) : ?>
						<div class="item">
							<div class="client_info">
								<div class="ova-media">
									<?php if( $item['image_author'] != '' ) : ?>
										<img class="client" src="<?php echo esc_attr($item['image_author']['url']) ?>" alt="<?php echo esc_attr($item['name_author']) ?>">
									<?php endif; ?>
								</div>

								<div class="info">
									<?php if( $item['name_author'] != '' ) : ?>
										<span class="name second_font"><?php echo esc_html($item['name_author']) ?></span>
									<?php endif; ?>
									<?php if( $item['job'] != '' ) : ?>
										<span class="job second_font"><small><?php echo __('-', 'ova-framework') ?></small><?php echo esc_html($item['job']) ?></span>
									<?php endif; ?>
									<div class="star">
										<?php 
										$i = $item['star'];
										for ($j = 0; $j < 5; $j++) : 
											$class_active = ($j <= $i) ? 'active' : '';
											?>
											<i class="fa fa-star <?php echo esc_attr($class_active) ?>" ></i>
										<?php endfor ?>
									</div>
									<?php if( $item['testimonial'] != '' ) : ?>
										<p class="testimonial"><?php echo esc_html($item['testimonial']) ?></p>
									<?php endif; ?>
								</div>
							</div>
						</div>
					<?php endforeach; endif; ?>
				</div>
			<?php endif ?>


			<?php if ($version === 'version_2') : ?>
				<div class="slide-testimonials owl-carousel owl-theme <?php echo esc_attr($version) ?>" data-options="<?php echo esc_attr(json_encode($data_options)) ?>">
					<?php if(!empty($tab_item)) : foreach ($tab_item as $item) : ?>
						<div class="item">
							<div class="client_info">

								<div class="info">
									<?php if( $item['testimonial'] != '' ) : ?>
										<p class="testimonial"><?php echo esc_html($item['testimonial']) ?></p>
									<?php endif; ?>
									<div class="star">
										<?php 
										$i = $item['star'];
										for ($j = 0; $j < 5; $j++) : 
											$class_active = ($j <= $i) ? 'active' : '';
											?>
											<i class="fa fa-star <?php echo esc_attr($class_active) ?>" ></i>
										<?php endfor ?>
									</div>
								</div>
								<div class="ova-media">
									<?php if( $item['image_author'] != '' ) : ?>
										<img class="client" src="<?php echo esc_attr($item['image_author']['url']) ?>" alt="<?php echo esc_attr($item['name_author']) ?>">
									<?php endif; ?>
								</div>
								<div class="title">
									<?php if( $item['job'] != '' ) : ?>
										<span class="job"><?php echo esc_html($item['job']) ?></span>
									<?php endif; ?>

									<?php if( $item['name_author'] != '' ) : ?>
										<span class="name second_font"><?php echo esc_html($item['name_author']) ?></span>
									<?php endif; ?>
									
								</div>
							</div>
						</div>
					<?php endforeach; endif; ?>
				</div>
			<?php endif ?>


			<?php if ( $version === 'version_4' ) : ?>
				<?php $class_active_dot = $data_options['dots'] ? 'active-dots' : ''; ?>
				<div class="slide-testimonials owl-carousel owl-theme <?php echo esc_attr($version) ?> <?php echo esc_attr($class_active_dot) ?>" data-options="<?php echo esc_attr(json_encode($data_options)) ?>">
					<?php if(!empty($tab_item_v4)) : foreach ($tab_item_v4 as $item_v4) : ?>
						<div class="item">
							<div class="client_info">
								<div class="ova-media ova-hide-mobile" style="background-image: url(<?php echo esc_attr($item_v4['image_author_v4']['url']) ?>) ">
									
								</div>
								<div class="ova-media-mobile ova-hide-desktop ova-hide-ipad ova-visible-mobile">
									<img src="<?php echo esc_attr($item_v4['image_author_v4']['url']) ?>" alt="<?php echo esc_attr($item_v4['testimonial_v4']) ?>">
								</div>

								<div class="info">
									<?php if( $item_v4['name_author_v4'] != '' ) : ?>
										<span class="name second_font"><?php echo esc_html($item_v4['name_author_v4']) ?></span>
									<?php endif; ?>
									<?php if( $item_v4['job_v4'] != '' ) : ?>
										<span class="job"><?php echo esc_html($item_v4['job_v4']) ?></span>
									<?php endif; ?>
									<div class="social">
										<?php if($item_v4['link_facebook']['url'] !== '') : ?>
											<?php $target = $item_v4['link_facebook']['is_external'] ? '_blank' : '_self';  ?>
											<a target="<?php echo esc_attr( $target ); ?>" href="<?php echo esc_attr($item_v4['link_facebook']['url']) ?>"><i class="fa fa-facebook" ></i></a>
										<?php endif ?>
										<?php if($item_v4['link_twitter']['url'] !== '') : ?>
											<?php $target = $item_v4['link_twitter']['is_external'] ? '_blank' : '_self';  ?>
											<a target="<?php echo esc_attr( $target ); ?>" href="<?php echo esc_attr($item_v4['link_twitter']['url']) ?>"><i class="fab fa-twitter"></i></a>
										<?php endif ?>

										<?php if($item_v4['link_linkedin']['url'] !== '') : ?>
											<?php $target = $item_v4['link_linkedin']['is_external'] ? '_blank' : '_self';  ?>
											<a target="<?php echo esc_attr( $target ); ?>" href="<?php echo esc_attr($item_v4['link_linkedin']['url']) ?>"><i class="fa fa-linkedin" ></i></a>
										<?php endif ?>
									</div>
									<?php if( $item_v4['testimonial_v4'] != '' ) : ?>
										<p class="testimonial"><?php echo esc_html($item_v4['testimonial_v4']) ?></p>
									<?php endif; ?>
								</div>
							</div>
						</div>
					<?php endforeach; endif; ?>
				</div>
			<?php endif ?>

		</section>
		<?php
	}
	// end render
}


