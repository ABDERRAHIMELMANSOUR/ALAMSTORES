<?php
namespace ova_framework\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Core\Schemes\Typography;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Color;
use Elementor\Group_Control_Border;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class ova_contact_v2 extends Widget_Base {

	public function get_name() {
		return 'ova_contact_v2';
	}

	public function get_title() {
		return __( 'Contatc version 2', 'ova-framework' );
	}

	public function get_icon() {
		return 'fa fa-map-marker';
	}

	public function get_categories() {
		return [ 'ovatheme' ];
	}

	public function get_script_depends() {
		return [ 'script-elementor' ];
	}

	protected function register_controls() {

		//SECTION CONTENT
		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Content', 'ova-framework' ),
			]
		);


		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'icon',
			[
				'label'   => __( 'Icon', 'ova-framework' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => 'contact_flaticon-036-customer-service',
			]
		);

		$repeater->add_control(
			'type_link',
			[
				'label' => __( 'Type Link', 'ova-framework' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'none',
				'options' => [
					'email' => __('Email', 'ova-framework'),
					'tell' => __('Tell', 'ova-framework'),
					'none' => __('None', 'ova-framework'),
				]
			]
		);

		$repeater->add_control(
			'link',
			[
				'label' => __( 'Link', 'ova-framework' ),
				'type' => \Elementor\Controls_Manager::URL,
				'placeholder' => __( 'https://your-link.com', 'ova-framework' ),
				'show_external' => false,
				'default' => [
					'url' => '#',
					'is_external' => false,
					'nofollow' => false,
				],
			]
		);

		$repeater->add_control(
			'text_contact',
			[
				'label'   => __( 'Text contact', 'ova-framework' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
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
						'icon' => 'contact_flaticon-036-customer-service',
						'type_link' => 'tell',
						'text_contact' => 'Support: (205)-509-1845',
					],
					[
						'icon' => 'contact_flaticon-013-mail',
						'type_link' => 'email',
						'text_contact' => 'Email: info@buildcos.com',
					],
					
				],
				'title_field' => '{{{ text_contact }}}',
			]
		);

		$this->end_controls_section();
		//END SECTION CONTENT

		$this->start_controls_section(
			'section_contact_box',
			[
				'label' => __( 'Box', 'ova-framework' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);


		$this->add_control(
			'color_contact_first',
			[
				'label' => __( 'Contact first', 'ova-framework' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ova-contact-v-2:before' => 'background-color : {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'color_contact_last',
			[
				'label' => __( 'Color last', 'ova-framework' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ova-contact-v-2:after' => 'background-color : {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'padding_box',
			[
				'label' => __( 'Padding', 'ova-framework' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .ova-contact-v-2' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
			'color_icon_v3',
			[
				'label' => __( 'Color ', 'ova-framework' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ova-contact-v-2 .wp-content .item .icon i:before' => 'color : {{VALUE}};',
				],
			]
		);


		$this->add_control(
			'font_size_icon',
			[
				'label' => __( 'Font size', 'ova-framework' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 200,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .ova-contact-v-2 .wp-content .item .icon i:before' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_contact',
			[
				'label' => __( 'Contact', 'ova-framework' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'contact_v3_typography',
				'selector' => '{{WRAPPER}} .ova-contact-v-2 .wp-content .item .content a, {{WRAPPER}} .ova-contact-v-2 .wp-content .item .content span',
				'scheme' => Typography::TYPOGRAPHY_1,
			]
		);

		$this->add_control(
			'color_contact',
			[
				'label' => __( 'Contact ', 'ova-framework' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ova-contact-v-2 .wp-content .item .content a' => 'color : {{VALUE}};',
					'{{WRAPPER}} .ova-contact-v-2 .wp-content .item .content span' => 'color : {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'color_contact_hover',
			[
				'label' => __( 'Color Hover', 'ova-framework' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ova-contact-v-2 .wp-content .item .content a:hover' => 'color : {{VALUE}};',
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

		?>	
		<div class="ova-contact-v-2">
			<div class="container">
				<div class="wp-content">
					<?php if(!empty($tabs)) : ?>
						<?php 
						$i = 0;
						foreach($tabs as $item) : 
							$i++;
							$tag_a = "";
							switch ($item['type_link']) {
								case 'email':
								$tag_a = "<a class='second_font' href='mailto:".$item['link']['url']."'>".$item['text_contact']."</a>";
								break;

								case 'tell':
								$tag_a = "<a class='second_font' href='tel:".$item['link']['url']."'>".$item['text_contact']."</a>";
								break;

								default:
								$tag_a = "<span class='second_font'>" . $item['text_contact'] . "<span>";
								break;
							}
							?>
							<div class="item">
								<div class="icon">
									<i class="<?php echo esc_attr($item['icon']) ?>"></i>
								</div>
								<div class="content">
									<?php echo $tag_a; ?>
								</div>
							</div>

						<?php endforeach ?>
					<?php endif ?>
				</div>
			</div>
		</div>
		<?php
	}
}
