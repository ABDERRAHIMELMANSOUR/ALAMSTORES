<?php

namespace ova_framework\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Core\Schemes\Typography;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Color;
use Elementor\Utils;


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class ova_about_team extends Widget_Base {

	public function get_name() {
		return 'ova_about_team';
	}

	public function get_title() {
		return __( 'About Team', 'ova-framework' );
	}

	public function get_icon() {
		return 'eicon-person';
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


		/**********************************************
		CONTENT SECTION
		**********************************************/
		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Content', 'ova-framework' ),
			]
		);

		$this->add_control(
			'team_version',
			[
				'label' => __('Version','ova-framework'),
				'type' => Controls_Manager::SELECT,
				'default' => 'version_1',
				'options' => [
					'version_1' => __('Version 1','ova-framework'),
					'version_2' => __('Version 2','ova-framework'),
					'version_3' => __('Version 3','ova-framework'),
					'version_4' => __('Version 4','ova-framework'),
				]
			]
		);

		$this->add_responsive_control(
			'align',
			[
				'label'   => __( 'Alignment', 'ova-framework' ),
				'type'    => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'ova-framework' ),
						'icon'  => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'ova-framework' ),
						'icon'  => 'fa fa-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'ova-framework' ),
						'icon'  => 'fa fa-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .about-team .ova-volunteer' => 'text-align: {{VALUE}}',
				]
			]
		);


		$repeater = new \Elementor\Repeater();


		$repeater->add_control(
			'name',
			[
				'label' => __( 'Name', 'ova-framework' ),
				'type' => Controls_Manager::TEXT,
				'default' => __('Andrew Mendez', 'ova-framework'),
			]
		);

		$repeater->add_control(
			'job',
			[
				'label' => __( 'Job', 'ova-framework' ),
				'type' => Controls_Manager::TEXTAREA,
				'row' => 2,
				'default' => __("Vice President", 'ova-framework'),
			]
		);

		$repeater->add_control(
			'image',
			[
				'label' => __( 'Image Thumbnail', 'ova-framework' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],

			]
		);

		$repeater->add_control(
			'link_about',
			[
				'label' => __( 'Link', 'ova-framework' ),
				'type' => \Elementor\Controls_Manager::URL,
				'placeholder' => __( 'https://your-link.com', 'ova-framework' ),
				'default' => [
					'url' => '#',
					'is_external' => false,
					'nofollow' => false,
				],
			]
		);

		$repeater->add_control(
			'icon_fb',
			[
				'label' => __('Icon Facebook','ova-framework'),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'default' => 'fa fa-facebook',
				'rows' => 2,
			]
		);

		$repeater->add_control(
			'link_fb',
			[
				'label' => __('Link Fb','ova-framework'),
				'type' => \Elementor\Controls_Manager::URL,
				'placeholder' => __( 'https://your-link.com', 'ova-framework' ),
				'default' => [
					'url' => '#',
					'is_external' => false,
					'nofollow' => false,
				],
			]
		);

		$repeater->add_control(
			'icon_tw',
			[
				'label' => __('Icon Twitter','ova-framework'),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'default' => 'fa fa-twitter',
				'rows' => 2,
			]
		);

		$repeater->add_control(
			'link_tw',
			[
				'label' => __('Link twitter','ova-framework'),
				'type' => \Elementor\Controls_Manager::URL,
				'placeholder' => __( 'https://your-link.com', 'ova-framework' ),
				'default' => [
					'url' => '#',
					'is_external' => false,
					'nofollow' => false,
				],
			]
		);

		$repeater->add_control(
			'icon_pin',
			[
				'label' => __('Icon Pinterest','ova-framework'),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'default' => 'fa fa-pinterest-p',
				'rows' => 2,
			]
		);

		$repeater->add_control(
			'link_pin',
			[
				'label' => __('Link Pinterest','ova-framework'),
				'type' => \Elementor\Controls_Manager::URL,
				'placeholder' => __( 'https://your-link.com', 'ova-framework' ),
				'default' => [
					'url' => '#',
					'is_external' => false,
					'nofollow' => false,
				],
			]
		);

		$repeater->add_control(
			'icon_tumb',
			[
				'label' => __('Icon Tumblr','ova-framework'),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'default' => 'fa fa-tumblr',
				'rows' => 2,
			]
		);

		$repeater->add_control(
			'link_tumb',
			[
				'label' => __('Link Tumblr','ova-framework'),
				'type' => \Elementor\Controls_Manager::URL,
				'placeholder' => __( 'https://your-link.com', 'ova-framework' ),
				'default' => [
					'url' => '#',
					'is_external' => false,
					'nofollow' => false,
				],
			]
		);

		$repeater->add_control(
			'contents',
			[
				'label' => __( 'Content', 'ova-framework' ),
				'type' => Controls_Manager::TEXTAREA,
				'default' => __('Ut suscipit tincidunt dictum. Cras euismod, ipsum non placerat facilisis, lorem mauris lobortis ante, ac vehicula odio ligula viverra purus. Nam suscipit id ante ut pellentesque.', 'ova-framework'),
			]
		);

		$this->add_control(
			'list_content',
			[
				'label' => __( 'Tabs Content', 'ova-framework' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'name' => __('Andrew Mendez', 'ova-framework'),
						'job'  => __('Vice President', 'ova-framework'),
					],
					[
						'name' => __('alter Anderso', 'ova-framework'),
						'job'  => __('Chief Executive Officer', 'ova-framework'),
					],
					[
						'name' => __('Rachel Hanson', 'ova-framework'),
						'job'  => __('Project Menager', 'ova-framework'),
					],
					[
						'name' => __('Christian Santos', 'ova-framework'),
						'job'  => __('Building Worker', 'ova-framework'),
					],
					
				],
				'title_field' => '{{{ name }}}',
				'condition' => [
					'team_version' => ['version_1', 'version_2']
				]
			]
		);

		/* About Team Version 3 */

		$this->add_control(
			'name_v3',
			[
				'label' => __( 'Name', 'ova-framework' ),
				'type' => Controls_Manager::TEXT,
				'default' => __('Andrew Mendez', 'ova-framework'),
				'condition' => [
					'team_version' => ['version_3']
				]
			]
		);

		$this->add_control(
			'job_v3',
			[
				'label' => __( 'Job', 'ova-framework' ),
				'type' => Controls_Manager::TEXTAREA,
				'row' => 2,
				'default' => __("Vice President", 'ova-framework'),
				'condition' => [
					'team_version' => ['version_3']
				]
			]
		);

		$this->add_control(
			'image_v3',
			[
				'label' => __( 'Image Thumbnail', 'ova-framework' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'condition' => [
					'team_version' => ['version_3']
				]

			]
		);

		$this->add_control(
			'hight_hover',
			[
				'label' => __( 'Height hight hover', 'ova-framework' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'ova-framework' ),
				'label_off' => __( 'Hide', 'ova-framework' ),
				'return_value' => 'yes',
				'default' => 'no',
				'condition' => [
					'team_version' => ['version_3']
				]
			]
		);

		$this->add_control(
			'link_about_v3',
			[
				'label' => __( 'Link', 'ova-framework' ),
				'type' => \Elementor\Controls_Manager::URL,
				'placeholder' => __( 'https://your-link.com', 'ova-framework' ),
				'default' => [
					'url' => '#',
					'is_external' => false,
					'nofollow' => false,
				],
				'condition' => [
					'team_version' => ['version_3']
				]
			]
		);

		$this->add_control(
			'icon_fb_v3',
			[
				'label' => __('Icon Facebook','ova-framework'),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'default' => 'fa fa-facebook',
				'rows' => 2,
				'condition' => [
					'team_version' => ['version_3']
				]
			]
		);

		$this->add_control(
			'icon_fb_color_v3',
			[
				'label' => __('Color','ova-framework'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .about-team .ova-volunteer .ova-content .list-con ul li:nth-child(1) a i' => 'color: {{VALUE}};'
				],
				'condition' => [
					'team_version' => ['version_3']
				]
			]
		);

		$this->add_control(
			'icon_fb_color_hover_v3',
			[
				'label' => __('Color Hover','ova-framework'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .about-team .ova-volunteer .ova-content .list-con ul li:nth-child(1) a:hover i' => 'color: {{VALUE}};'
				],
				'condition' => [
					'team_version' => ['version_3']
				]
			]
		);

		$this->add_control(
			'link_fb_v3',
			[
				'label' => __('Link Fb','ova-framework'),
				'type' => \Elementor\Controls_Manager::URL,
				'placeholder' => __( 'https://your-link.com', 'ova-framework' ),
				'default' => [
					'url' => '#',
					'is_external' => false,
					'nofollow' => false,
				],
				'condition' => [
					'team_version' => ['version_3']
				]
			]
		);

		$this->add_control(
			'icon_tw_v3',
			[
				'label' => __('Icon Twitter','ova-framework'),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'default' => 'fa fa-twitter',
				'rows' => 2,
				'condition' => [
					'team_version' => ['version_3']
				],
				'condition' => [
					'team_version' => ['version_3']
				]
			]
		);

		$this->add_control(
			'icon_tw_color_v3',
			[
				'label' => __('Color','ova-framework'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .about-team .ova-volunteer .ova-content .list-con ul li:nth-child(2) a i' => 'color: {{VALUE}};'
				],
				'condition' => [
					'team_version' => ['version_3']
				]
			]
		);

		$this->add_control(
			'icon_tw_color_hover_v3',
			[
				'label' => __('Color Hover','ova-framework'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .about-team .ova-volunteer .ova-content .list-con ul li:nth-child(2) a:hover i' => 'color: {{VALUE}};'
				],
				'condition' => [
					'team_version' => ['version_3']
				]
			]
		);

		$this->add_control(
			'link_tw_v3',
			[
				'label' => __('Link twitter','ova-framework'),
				'type' => \Elementor\Controls_Manager::URL,
				'placeholder' => __( 'https://your-link.com', 'ova-framework' ),
				'default' => [
					'url' => '#',
					'is_external' => false,
					'nofollow' => false,
				],
				'condition' => [
					'team_version' => ['version_3']
				]
			]
		);

		$this->add_control(
			'icon_tumb_v3',
			[
				'label' => __('Icon Tumblr','ova-framework'),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'default' => 'fa fa-tumblr',
				'rows' => 2,
				'condition' => [
					'team_version' => ['version_3']
				]
			]
		);

		$this->add_control(
			'icon_tumb_color_v3',
			[
				'label' => __('Color','ova-framework'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .about-team .ova-volunteer .ova-content .list-con ul li:nth-child(3) a i' => 'color: {{VALUE}};'
				],
				'condition' => [
					'team_version' => ['version_3']
				]
			]
		);

		$this->add_control(
			'icon_tumb_color_hover_v3',
			[
				'label' => __('Color Hover','ova-framework'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .about-team .ova-volunteer .ova-content .list-con ul li:nth-child(3) a:hover i' => 'color: {{VALUE}};'
				],
				'condition' => [
					'team_version' => ['version_3']
				]
			]
		);

		$this->add_control(
			'link_tumb_v3',
			[
				'label' => __('Link Tumblr','ova-framework'),
				'type' => \Elementor\Controls_Manager::URL,
				'placeholder' => __( 'https://your-link.com', 'ova-framework' ),
				'default' => [
					'url' => '#',
					'is_external' => false,
					'nofollow' => false,
				],
				'condition' => [
					'team_version' => ['version_3']
				],
				'condition' => [
					'team_version' => ['version_3']
				]
			]
		);


		$this->add_control(
			'icon_pin_v3',
			[
				'label' => __('Icon Pinterest','ova-framework'),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'default' => 'fa fa-pinterest-p',
				'rows' => 2,
				'condition' => [
					'team_version' => ['version_3']
				],
				'condition' => [
					'team_version' => ['version_3']
				]
			]
		);

		$this->add_control(
			'icon_pin_color_v3',
			[
				'label' => __('Color','ova-framework'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .about-team .ova-volunteer .ova-content .list-con ul li:nth-child(4) a i' => 'color: {{VALUE}};'
				],
				'condition' => [
					'team_version' => ['version_3']
				]
			]
		);

		$this->add_control(
			'icon_pin_color_hover_v3',
			[
				'label' => __('Color Hover','ova-framework'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .about-team .ova-volunteer .ova-content .list-con ul li:nth-child(4) a:hover i' => 'color: {{VALUE}};'
				],
				'condition' => [
					'team_version' => ['version_3']
				]
			]
		);

		$this->add_control(
			'link_pin_v3',
			[
				'label' => __('Link Pinterest','ova-framework'),
				'type' => \Elementor\Controls_Manager::URL,
				'placeholder' => __( 'https://your-link.com', 'ova-framework' ),
				'default' => [
					'url' => '#',
					'is_external' => false,
					'nofollow' => false,
				],
				'condition' => [
					'team_version' => ['version_3']
				]
			]
		);

		$this->add_control(
			'show_content',
			[
				'label' => __( 'Show Content', 'ova-framework' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'ova-framework' ),
				'label_off' => __( 'Hide', 'ova-framework' ),
				'return_value' => 'yes',
				'default' => 'no',
			]
		);

		$this->add_control(
			'content_v3',
			[
				'label' => __( 'Content', 'ova-framework' ),
				'type' => Controls_Manager::TEXTAREA,
				'default' => __("Sed id dignissim risus. Interdum et malesuada fames ac ante ipsum primis in faucibus.", 'ova-framework'),
				'condition' => [
					'team_version' => ['version_3'],
					'show_content' => 'yes'
				]
			]
		);

		/* About Team Version 4 */

		$this->add_control(
			'name_v4',
			[
				'label' => __( 'Name', 'ova-framework' ),
				'type' => Controls_Manager::TEXT,
				'default' => __('Andrew Mendez', 'ova-framework'),
				'condition' => [
					'team_version' => ['version_4']
				]
			]
		);

		$this->add_control(
			'job_v4',
			[
				'label' => __( 'Job', 'ova-framework' ),
				'type' => Controls_Manager::TEXTAREA,
				'row' => 2,
				'default' => __("Vice President", 'ova-framework'),
				'condition' => [
					'team_version' => ['version_4']
				]
			]
		);

		$this->add_control(
			'content_v4',
			[
				'label' => __( 'Content', 'ova-framework' ),
				'type' => Controls_Manager::TEXTAREA,
				'default' => __("Cras molestie nunc nibh, faucibus aliquet leo hendrerit vulputate. Integer vulputate felis non orci fringilla, ac volutpat metus mattis. Curabitur gravida mattis congue. Cras faucibus nulla velit, a aliquam ipsum.", 'ova-framework'),
				'condition' => [
					'team_version' => ['version_4']
				]
			]
		);

		$this->add_control(
			'image_v4',
			[
				'label' => __( 'Image Thumbnail', 'ova-framework' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'condition' => [
					'team_version' => ['version_4']
				]
			]
		);

		$this->add_control(
			'button_text',
			[
				'label' => __('Button Text','ova-framework'),
				'type'  => Controls_Manager::TEXT,
				'default' => __('View Profile on','ova-framework'),
				'condition' => [
					'team_version' => ['version_4']
				]
			]
		);

		$this->add_control(
			'image_icon_v4',
			[
				'label' => __( 'Icon Social', 'ova-framework' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
		);

		$this->add_control(
			'link_about_v4',
			[
				'label' => __( 'Link', 'ova-framework' ),
				'type' => \Elementor\Controls_Manager::URL,
				'placeholder' => __( 'https://your-link.com', 'ova-framework' ),
				'default' => [
					'url' => '#',
					'is_external' => false,
					'nofollow' => false,
				],
				'condition' => [
					'team_version' => ['version_4']
				]
			]
		);


		$this->end_controls_section();

		$this->start_controls_section(
			'section_additional_options',
			[
				'label' => __( 'Additional Options', 'ova-framework' ),
				'condition' => [
					'team_version' => ['version_1', 'version_2']
				]
			]
		);

		$this->add_control(
			'total_columns_slide',
			[
				'label'   => __( 'Desktop: Total item each slide', 'ova-framework' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '4',
				'options' => [
					'1' => __('1 items', 'ova-framework'),
					'2' => __( '2 items', 'ova-framework' ),
					'3' => __( '3 items', 'ova-framework' ),
					'4' => __( '4 items', 'ova-framework' ),
				],
			]
		);

		$this->add_control(
			'items_ipad',
			[
				'label'   => __( 'Ipad: Total item each slide', 'ova-framework'),
				'type'    => Controls_Manager::SELECT,
				'default' => '2',
				'options' => [
					'1' => __( '1 items', 'ova-framework'),
					'2' => __( '2 items', 'ova-framework'),
					'3' => __( '3 items', 'ova-framework'),
					'4' => __( '4 items', 'ova-framework'),
				],
			]
		);

		$this->add_control(
			'items_mobile',
			[
				'label'   => __( 'Mobile: Total item each slide', 'ova-framework'),
				'type'    => Controls_Manager::SELECT,
				'default' => '1',
				'options' => [
					'1' => __( '1 items', 'ova-framework'),
					'2' => __( '2 items', 'ova-framework'),
					'3' => __( '3 items', 'ova-framework'),
					'4' => __( '4 items', 'ova-framework'),
				],
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
			'arows_control',
			[
				'label' => __('Show Navigation', 'ova-framework'),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'dots_control',
			[
				'label'   => __('Show Paging', 'ova-framework'),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'no',
			]
		);


		$this->add_control(
			'pause_on_hover',
			[
				'label'   => __( 'Pause on Hover', 'ova-framework' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'autoplay',
			[
				'label'   => __( 'Autoplay', 'ova-framework' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'autoplay_speed',
			[
				'label'     => __( 'Autoplay Speed', 'ova-framework' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 5000,
				'condition' => [
					'autoplay' => 'yes',
				]
			]
		);

		$this->add_control(
			'infinite',
			[
				'label'   => __( 'Infinite Loop', 'ova-framework' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'smartspeed',
			[
				'label'   => __( 'Smart Speed', 'ova-framework' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 500
			]
		);

		$this->add_control(
			'slideby',
			[
				'label' => __( "Navigation slide by x. 'page' string can be set to slide by page.", 'ova-framework' ),
				'type'  => Controls_Manager::NUMBER,
				'description' => __( 'Example: 1', 'ova-framework' ),
				'default'     => '1'
			]
		);

		$this->add_control(
			'nav_prev',
			[
				'label' => __( 'Prev Navigation', 'ova-framework' ),
				'type'  => Controls_Manager::TEXT,
				'default'     => '<i class="arrow_carrot-left"></i>'
			]
		);

		$this->add_control(
			'nav_next',
			[
				'label' => __( 'Next Navigation', 'ova-framework' ),
				'type'  => Controls_Manager::TEXT,
				'default'     => '<i class="arrow_carrot-right"></i>'
			]
		);

		$this->end_controls_section();


		//section style title
		$this->start_controls_section(
			'section_title',
			[
				'label' => __( 'Name', 'ova-framework' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'selector' => '{{WRAPPER}} .ova-volunteer .ova-content .name',
				'scheme' => Typography::TYPOGRAPHY_1,
			]
		);

		$this->add_control(
			'color_title',
			[
				'label' => __( 'Color', 'ova-framework' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ova-volunteer .ova-content .name a' => 'color : {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'color_hover_title',
			[
				'label' => __( 'Color Hover', 'ova-framework' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ova-volunteer .ova-content .name a:hover' => 'color : {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'padding_title',
			[
				'label' => __( 'Padding', 'ova-framework' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .ova-volunteer .ova-content .name' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		//section style sub title
		$this->start_controls_section(
			'section_sub_title',
			[
				'label' => __( 'Job', 'ova-framework' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'sub_title_typography',
				'selector' => '{{WRAPPER}} .ova-volunteer .ova-content .job',
				'scheme' => Typography::TYPOGRAPHY_1,
			]
		);

		$this->add_control(
			'color_sub_title',
			[
				'label' => __( 'Color Sub Title', 'ova-framework' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ova-volunteer .ova-content .job' => 'color : {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'margin_sub_title',
			[
				'label' => __( 'Padding', 'ova-framework' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .ova-heading .sub-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Media

		$this->start_controls_section(
			'section_meida',
			[
				'label' => __( 'Media', 'ova-framework' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'team_version' => ['version_2']
				]
			]
		); 

		$this->add_control(
			'bgr_image_overlay',
			[
				'label' => __( 'Background Overlay', 'ova-framework' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .about-team .ova-volunteer .ova-media:after' => 'background-color: {{VALUE}};'
				]
			]
		);

		$this->end_controls_section();

		// Social

		$this->start_controls_section(
			'section_social',
			[
				'label' => __( 'Social', 'ova-framework' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'social_size',
			[
				'label' => __( 'Size', 'ova-framework' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 5,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 18,
				],
				'selectors' => [
					'{{WRAPPER}} .ova-volunteer .list-con ul li a i' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'social_spacing',
			[
				'label' => __( 'Spacing', 'ova-framework' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 5,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 22,
				],
				'selectors' => [
					'{{WRAPPER}} .ova-volunteer .list-con ul li:not(:last-child)' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'color_social',
			[
				'label' => __( 'Color', 'ova-framework' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ova-volunteer .list-con ul li a i' => 'color : {{VALUE}};',
				],
				'condition' => [
					'team_version' => ['version_1','version_2']
				]
			]
		);

		$this->add_control(
			'color_hover_social',
			[
				'label' => __( 'Color Hover', 'ova-framework' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ova-volunteer .list-con ul li a:hover i' => 'color : {{VALUE}};',
				],
				'condition' => [
					'team_version' => ['version_1','version_2']
				]
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_line_content',
			[
				'label' => __( 'Content', 'ova-framework' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'bgr_color_line',
			[
				'label' => __('Line Background Color','ova-framework'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .about-team .ova-volunteer .ova-content:after' => 'background-color: {{VALUE}};'
				],
				'condition' => [
					'team_version' => ['version_3']
				]
			]
		);

		$this->add_control(
			'bgr_content_color',
			[
				'label' => __('Content Background Color','ova-framework'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .about-team .ova-volunteer .ova-content' => 'background-color: {{VALUE}};'
				]
			]
		);

		$this->end_controls_section();

	}

	protected function render() {
		$settings = $this->get_settings();


		$list_content = $settings['list_content'];

		$data_option['smartSpeed']          = absint( $settings['smartspeed'] );
		$data_option['margin']              = absint( $settings['margin_items'] ); 
		$data_option['loop']                =  ( $settings['infinite'] == 'yes') ? true : false;
		$data_option['autoplay']            =  ( $settings['autoplay'] == 'yes') ? true : false;
		$data_option['autoplayTimeout']     = absint( $settings['autoplay_speed'] );
		$data_option['autoplayHoverPause']  =  ( $settings['pause_on_hover'] == 'yes') ? true : false;
		$data_option['dots']                =  ( $settings['dots_control'] == 'yes') ? true : false;
		$data_option['nav']            	=  ( $settings['arows_control'] == 'yes') ? true : false;
		$data_option['slideBy']             = absint( $settings['slideby'] );
		$data_option['prev'] = $settings['nav_prev'];
		$data_option['next'] = $settings['nav_next'];

		$data_option['total_columns_slide'] = absint( $settings['total_columns_slide'] ); 
		$data_option['items_ipad']          = absint( $settings['items_ipad'] ); 
		$data_option['items_mobile']        = absint( $settings['items_mobile'] ); 

		$data_option_encode = wp_json_encode($data_option);

		
		?>
		<div class="about-team <?php echo esc_attr($settings['team_version']);?>">
			<?php if( $settings['team_version'] === 'version_1' || $settings['team_version'] === 'version_2' ) : ?>
				<div class="owl-carousel owl-theme" data-options="<?php echo esc_attr($data_option_encode) ?>">

					<?php foreach( $list_content as $items_abt) : ?>
						<div class="ova-volunteer">
							<div class="ova-media">
								<img src="<?php echo esc_attr($items_abt['image']['url']) ?>" alt="<?php echo esc_attr($items_abt['name']); ?>">
							</div>
							<div class="ova-content">
								<?php if ($items_abt['name'] !== "") : ?>
									
									<?php $target = $items_abt['link_about']['is_external'] ? '_blank' : '_self';  ?>

									<h3 class="name second_font"><a target="<?php echo esc_attr($target); ?>" href="<?php echo esc_attr($items_abt['link_about']['url']) ?>"><?php echo esc_html($items_abt['name']); ?></a></h3>
								<?php endif ?>
								<?php if ( $items_abt['job'] !== "" ) : ?>
									<p class="job"><?php echo esc_html($items_abt['job']); ?></p>
								<?php endif ?>
								<div class="list-con">
									<ul>
										<?php  if( $items_abt['icon_fb'] != '' ) : ?>
											<li><a target="_blank" href="<?php echo esc_attr($items_abt['link_fb']['url']) ?>"><i class="<?php echo esc_attr($items_abt['icon_fb']) ?>"></i></a></li>
										<?php endif; ?>
										<?php  if( $items_abt['icon_tw'] != '' ) : ?>
											<li><a target="_blank" href="<?php echo esc_attr($items_abt['link_tw']['url']) ?>"><i class="<?php echo esc_attr($items_abt['icon_tw']) ?>"></i></a></li>
										<?php endif; ?>
										<?php  if( $items_abt['icon_pin'] != '' ) : ?>
											<li><a target="_blank" href="<?php echo esc_attr($items_abt['link_pin']['url']) ?>"><i class="<?php echo esc_attr($items_abt['icon_pin']) ?>"></i></a></li>
										<?php endif; ?>
										<?php  if( $items_abt['icon_tumb'] != '' ) : ?>
											<li><a target="_blank" href="<?php echo esc_attr($items_abt['link_tumb']['url']) ?>"><i class="<?php echo esc_attr($items_abt['icon_tumb']) ?>"></i></a></li>
										<?php endif; ?>
									</ul>
								</div>
								<?php if( $items_abt['contents'] != '' ) : ?>
									<p class="desc"><?php echo $items_abt['contents'];?></p>
								<?php endif; ?>
							</div>
							<?php if( $settings['team_version'] === 'version_2' ) : ?>
								<?php $target = $items_abt['link_about']['is_external'] ? '_blank' : '_self';  ?>
								<a target="<?php echo esc_attr($target); ?>" href="<?php echo esc_attr($items_abt['link_about']['url']) ?>" class="view_volunteer"><i class="icon_plus"></i></a>
							<?php endif;?>
						</div>
					<?php endforeach; ?>

				</div>
			<?php endif; ?>

			<?php
				$class_hover_hight = $settings['hight_hover'] === 'yes' ? 'hight' :  '';
				$class_hover = '';
				if( $settings['show_content'] === 'yes' ){
					$class_hover = 'overlay';
				} 
				if( $settings['team_version'] === 'version_3' ) : ?>
				<div class="ova-volunteer">
					<div class="ova-media">
						<img src="<?php echo esc_attr($settings['image_v3']['url']) ?>" alt="<?php echo esc_attr($settings['name_v3']); ?>">
					</div>
					<div class="ova-content <?php echo esc_attr($class_hover);?>  <?php echo esc_attr($class_hover_hight);?>">
						<?php if ($settings['name_v3'] !== "") : ?>
							<?php $target = $settings['link_about_v3']['is_external'] ? '_blank' : '_self';  ?>
							<h3 class="name second_font"><a target="<?php echo esc_attr($target); ?>" href="<?php echo esc_attr($settings['link_about_v3']['url']) ?>"><?php echo esc_html($settings['name_v3']); ?></a></h3>
						<?php endif ?>
						<?php if ( $settings['job_v3'] !== "" ) : ?>
							<p class="job"><?php echo esc_html($settings['job_v3']); ?></p>
						<?php endif ?>
						<div class="list-con">
							<ul>
								<?php  if( $settings['icon_fb_v3'] != '' ) : ?>
									<li><a target="_blank" href="<?php echo esc_attr($settings['link_fb_v3']['url']) ?>"><i class="<?php echo esc_attr($settings['icon_fb_v3']) ?>"></i></a></li>
								<?php endif; ?>
								<?php  if( $settings['icon_tw_v3'] != '' ) : ?>
									<li><a target="_blank" href="<?php echo esc_attr($settings['link_tw_v3']['url']) ?>"><i class="<?php echo esc_attr($settings['icon_tw_v3']) ?>"></i></a></li>
								<?php endif; ?>
								<?php  if( $settings['icon_pin_v3'] != '' ) : ?>
									<li><a target="_blank" href="<?php echo esc_attr($settings['link_pin_v3']['url']) ?>"><i class="<?php echo esc_attr($settings['icon_pin_v3']) ?>"></i></a></li>
								<?php endif; ?>
								<?php  if( $settings['icon_tumb_v3'] != '' ) : ?>
									<li><a target="_blank" href="<?php echo esc_attr($settings['link_tumb_v3']['url']) ?>"><i class="<?php echo esc_attr($settings['icon_tumb_v3']) ?>"></i></a></li>
								<?php endif; ?>
							</ul>
						</div>
						<?php if( $settings['show_content'] === 'yes' && $settings['content_v3'] != '' ) : ?>
							<p class="content"><?php echo esc_html( $settings['content_v3'] ); ?></p>
						<?php endif;?>
					</div>
				</div>
			<?php endif; ?>

			<?php if( $settings['team_version'] === 'version_4' ) : ?>
				<div class="ova-volunteer">
					<div class="ova-media">
						<img src="<?php echo esc_attr($settings['image_v4']['url']) ?>" alt="<?php echo esc_attr($items_abt['name_v4']); ?>">
					</div>
					<div class="ova-content">
						<?php if ($settings['name_v3'] !== "") : ?>
							<?php $target = $settings['link_about_v4']['is_external'] ? '_blank' : '_self';  ?>
							<h3 class="name second_font"><a target="<?php echo esc_attr($target); ?>" href="<?php echo esc_attr($settings['link_about_v4']['url']) ?>"><?php echo esc_html($settings['name_v4']); ?></a></h3>
						<?php endif ?>
						<?php if ( $settings['job_v3'] !== "" ) : ?>
							<p class="job"><?php echo esc_html($settings['job_v4']); ?></p>
						<?php endif ?>
						<?php if( $settings['content_v4'] != '' ) : ?>
							<p class="content"><?php echo esc_html( $settings['content_v4'] ); ?></p>
						<?php endif;?>

						<?php $target = $settings['link_about_v4']['is_external'] ? '_blank' : '_self';  ?>
						<a target="<?php echo esc_attr($target); ?>" class="view_profile" href="<?php echo esc_attr($settings['link_about_v4']['url']) ?>"><?php echo esc_html($settings['button_text']);?><img class="social_img" src="<?php echo esc_attr($settings['image_icon_v4']['url']);?>"></a>
					</div>
				</div>

			<?php endif; ?>
		</div>
		<?php
	}
// end render
}


