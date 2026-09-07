<?php
namespace ova_framework\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Control_Media;
use Elementor\Core\Schemes\Color;

if ( ! defined( 'ABSPATH' ) ) exit; 

class ova_video_popup extends Widget_Base {


	public function get_name() {
		return 'ova_video_popup';
	}

	public function get_title() {
		return __( 'Video Popup', 'ova-framework' );
	}

	public function get_icon() {
		return 'eicon-youtube';
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
				'label' => __( 'Content', 'ova-framework' ),
			]
		);

		$this->add_control(
			'version',
			[
				'label' => __('Version', 'ova-framework'),
				'type' => Controls_Manager::SELECT,
				'default' => 'version_1',
				'options' => [
					'version_1' => __('Version 1', 'ova-framework'),
					'version_2' => __('Version 2', 'ova-framework'),
				]
			]
		);


		$this->add_control(
			'link',
			[
				'label' => __( 'Link Youtube', 'ova-framework' ),
				'type' => Controls_Manager::TEXT,
			]
		);

		$this->add_control(
			'image_bgr',
			[
				'label'   => __( 'Image', 'ova-framework' ),
				'type'    => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
		);


		$this->add_control(
			'bgr_overlay',
			[
				'label'   => __('Background Overlay','ova-framework'),
				'type'    => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .video-player-work:after' => 'background-color: {{VALUE}};'
				]
			]
		);

		$this->add_control(
			'height',
			[
				'label' => __( 'Height', 'plugin-domain' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 400,
						'max' => 1000,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .video-player-work' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'show_border',
			[
				'label' => __( 'Show border coner', 'ova-framework' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'ova-framework' ),
				'label_off' => __( 'Hide', 'ova-framework' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);
		


		$this->end_controls_section();

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
					'{{WRAPPER}} .video-player-work .video-inside-work .video i' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'color_button',
			[
				'label'     => __( 'Color', 'ova-framework' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .video-player-work .video-inside-work .video i' => 'color : {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'color_button_hover',
			[
				'label'     => __( 'Color hover', 'ova-framework' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .video-player-work .video-inside-work .video:hover i' => 'color : {{VALUE}};',
				],
			]
		);



		$this->add_control(
			'bg_color_button',
			[
				'label'     => __( 'Background Color', 'ova-framework' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .video-player-work .video-inside-work .video' => 'background-color : {{VALUE}};',
					'{{WRAPPER}} .video-player-work .video-inside-work .video:before' => 'background-color : {{VALUE}};opacity: 0.6;',
					'{{WRAPPER}} .video-player-work .video-inside-work .video:after' => 'background-color : {{VALUE}};opacity: 0.4;',
				],
			]
		);

		$this->add_control(
			'bg_color_button_hover',
			[
				'label'     => __( 'Background color hover', 'ova-framework' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .video-player-work .video-inside-work .video:hover' => 'background-color : {{VALUE}};',
					'{{WRAPPER}} .video-player-work .video-inside-work .video:hover:before' => 'background-color : {{VALUE}};opacity: 0.6;',
					'{{WRAPPER}} .video-player-work .video-inside-work .video:hover:after' => 'background-color : {{VALUE}};opacity: 0.4;',
				],
			]
		);

		$this->add_control(
			'color_border',
			[
				'label'     => __( 'Color border', 'ova-framework' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .video-player-work.version_2 .video-inside-work .video:before' => 'border-color : {{VALUE}};',
					'{{WRAPPER}} .video-player-work.version_2 .video-inside-work .video:after' => 'border-color : {{VALUE}};',
				],
				'condition' => [
					'version' => 'version_2'
				]
			]
		);


		$this->add_responsive_control(
			'margin_button',
			[
				'label' => __( 'Margin', 'ova-framework' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .video-player-work .video-inside-work .video' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);


		$this->add_responsive_control(
			'align',
			[
				'label' => __( 'Alignment', 'ova-framework' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'ova-framework' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'ova-framework' ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'ova-framework' ),
						'icon' => 'fa fa-align-right',
					],
				],
				'default' => 'center',
				'selectors' => [
					'{{WRAPPER}} .video-inside-work' => 'text-align: {{VALUE}}',
				]
			]
		);

		$this->end_controls_section();

	}

	protected function render() {
		$settings = $this->get_settings();
		$class_show_border = $settings['show_border'] !== '' ? 'border_conner' : '';
		?>

		<div class="video-player-work <?php echo esc_attr($settings['version']) ?> <?php echo esc_attr($class_show_border) ?>" style="background: url(<?php echo esc_attr($settings['image_bgr']['url']);?>);">
			<div class="content">
				<div class="video-inside-work">
					<a  class="video btn" video-url="<?php echo esc_attr($settings['link']);?>" style="cursor: pointer;"><i class="fa fa-play"></i></a>
				</div>
			</div>
		</div>
		
	<?php }

}
