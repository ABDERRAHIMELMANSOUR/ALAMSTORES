<?php
namespace ova_framework\Widgets;
use Elementor;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Core\Schemes\Color;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Repeater;
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


class ova_language extends Widget_Base {

	public function get_name() {
		return 'ova_language';
	}

	public function get_title() {
		return __( 'Choice Language', 'ova-framework' );
	}

	public function get_icon() {
		return 'eicon-post-list';
	}

	public function get_categories() {
		return [ 'hf' ];
	}

	public function get_keywords() {
		return [ 'image', 'photo', 'visual' ];
	}

	protected function register_controls() {
		$this->start_controls_section(
			'choice_language',
			[
				'label' => __( 'Language', 'ova-framework' ),
			]
		);

		$this->add_control(
			'current_lang',
			[
				'label' => __( 'Current Language', 'ova-framework' ),
				'type' => \Elementor\Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'flag_title',
			[
				'label' => __( 'Nation', 'ova-framework' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'ENG', 'ova-framework' ),
			]
		);

		$this->add_control(
			'flag_image',
			[
				'label' => __( 'Flag Image', 'ova-framework' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
			]
		);

		$this->add_control(
			'dropdown_lang',
			[
				'label' => __( 'Dropdown Language', 'ova-framework' ),
				'type' => \Elementor\Controls_Manager::HEADING,
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'flag_title_items',
			[
				'label'   => __( 'Nation', 'ova-framework' ),
				'type'    => Controls_Manager::TEXT,
				'default' => 'Type your title here',
			]
		);

		$repeater->add_control(
			'flag_image_items',
			[
				'label' => __( 'Flag Image', 'ova-framework' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
			]
		);

		$this->add_control(
			'lang_list',
			[
				'label' => __( 'Language', 'ova-framework' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'flag_title_items' => 'ES',
					],
				],
			]
		);

		// $this->add_control(
		// 	'color',
		// 	[
		// 		'label' => __( 'Color', 'ova-framework' ),
		// 		'type' => \Elementor\Controls_Manager::COLOR,
		// 		'selectors' => [
		// 			'{{WRAPPER}} .ova_language .select2-selection__rendered' => 'color: {{VALUE}}',
		// 			'{{WRAPPER}} .ova_language .select2-selection__arrow' => 'color: {{VALUE}}',

		// 		],
		// 	]
		// );

		// $this->add_control(
		// 	'size',
		// 	[
		// 		'label' => __( 'Size', 'ova-framework' ),
		// 		'type' => Controls_Manager::SLIDER,
		// 		'size_units' => [ 'px', '%' ],
		// 		'range' => [
		// 			'px' => [
		// 				'min' => 0,
		// 				'max' => 30,
		// 			],
		// 			'%' => [
		// 				'min' => 0,
		// 				'max' => 100,
		// 			],
		// 		],
		// 		'default' => [
		// 			'unit' => 'px',
		// 			'size' => 14,
		// 		],
		// 		'selectors' => [
		// 			'{{WRAPPER}} .select2-selection__rendered' => 'font-size: {{SIZE}}{{UNIT}};',
		// 		],
		// 	]
		// );
		// $this->add_responsive_control(
		// 	'text_align',
		// 	[
		// 		'label' => __( 'Alignment', 'ova-framework' ),
		// 		'type' => \Elementor\Controls_Manager::CHOOSE,
		// 		'default' => 'center',
		// 		'toggle' => true,
		// 		'options' => [
		// 			'left' => [
		// 				'title' => __( 'Left', 'ova-framework' ),
		// 				'icon' => 'fa fa-align-left',
		// 			],
		// 			'center' => [
		// 				'title' => __( 'Center', 'ova-framework' ),
		// 				'icon' => 'fa fa-align-center',
		// 			],
		// 			'right' => [
		// 				'title' => __( 'Right', 'ova-framework' ),
		// 				'icon' => 'fa fa-align-right',
		// 			],
		// 		],
		// 		'selectors' => [
		// 			'{{WRAPPER}} .ova_language' => 'text-align: {{VALUE}}',
		// 		],
		// 	]
		// );

		$this->end_controls_section();

		$this->start_controls_section(
			'current_lang_style',
			[
				'label' => __( 'Current Language', 'ova-framework' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'flag_title_typo',
				'selector' => '{{WRAPPER}} .switch-lang .current-lang .lang-text',
			]
		);

		$this->add_control(
			'flag_title_color',
			[
				'label' => __( 'Color', 'ova-framework' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .switch-lang .current-lang .lang-text, {{WRAPPER}} .switch-lang .current-lang .lang-text:after' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'flag_title_color_hover',
			[
				'label' => __( 'Color Hover', 'ova-framework' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .switch-lang .current-lang .lang-text:hover, {{WRAPPER}} .switch-lang .current-lang .lang-text:hover:after' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'lang_dropdown_style',
			[
				'label' => __( 'Dropdown Language', 'ova-framework' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'lang_dropdown_typo',
				'selector' => '{{WRAPPER}} .switch-lang .lang-dropdown .selecting-lang .lang-text',
			]
		);

		$this->add_control(
			'lang_dropdown_color',
			[
				'label' => __( 'Color', 'ova-framework' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .switch-lang .lang-dropdown .selecting-lang .lang-text' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'lang_dropdown_color_hover',
			[
				'label' => __( 'Color Hover', 'ova-framework' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .switch-lang .lang-dropdown .selecting-lang .lang-text:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings();

		$item_lang = $settings['lang_list'];

		?>


		<div class="switch-lang">
			<div class="current-lang"><img class="lang-flag" src="<?php echo esc_url( $settings['flag_image']['url'] ); ?>" alt="<?php echo esc_attr($settings['flag_title']) ?>">
				<p class="lang-text"><?php echo esc_html($settings['flag_title']);?></p>
			</div>
			<div class="lang-dropdown">
				<?php foreach( $item_lang as $items  ) : ?>
					<div class="selecting-lang"><img class="lang-flag" src="<?php echo esc_url( $items['flag_image_items']['url'] ); ?>" alt="<?php echo esc_attr($items['flag_title_items']) ?>">
						<p class="lang-text"><?php echo $items['flag_title_items'];?></p>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	<?php }	
}

