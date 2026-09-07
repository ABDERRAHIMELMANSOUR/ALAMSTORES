<?php
namespace ova_framework\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class ova_slideshow extends Widget_Base {

	public function get_name() {
		return 'ova_slideshow';
	}

	public function get_title() {
		return __( 'Slideshow', 'ova-framework' );
	}

	public function get_icon() {
		return 'eicon-posts-carousel';
	}

	public function get_categories() {
		return [ 'ovatheme' ];
	}

	public function get_script_depends() {
		wp_enqueue_style( 'slick-style', OVA_PLUGIN_URI.'assets/libs/slick/slick/slick.css', array(), null );
		wp_enqueue_style( 'slick-theme-style', OVA_PLUGIN_URI.'assets/libs/slick/slick/slick-theme.css', array(), null);
		wp_enqueue_script( 'slick-script', OVA_PLUGIN_URI.'assets/libs/slick/slick/slick.min.js', array('jquery'), false, true );
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
			'gallery',
			[
				'label' => __( 'Add Images', 'ova-framework' ),
				'type' => \Elementor\Controls_Manager::GALLERY,
				'default' => [],
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		?>

		<div class="ova_slideshow">
			<div class="slide_gallery">
				<div class="wrap_slide">
					<?php if ($settings['gallery'] != '') {
						foreach (  $settings['gallery'] as $key => $item ) { ?>
							<img src="<?php echo esc_attr($item['url']); ?>" alt="#">
						<?php }
					} ?>
				</div>
				<i class="prev-arrow arrow_flaticon-050-left-arrow-7"></i>
				<i class="next-arrow arrow_flaticon-034-right-arrow-7"></i>

			</div>
			<div class="thumbnail_gallery">
				<?php if ($settings['gallery'] != '') {
					foreach (  $settings['gallery'] as $key => $item ) { ?>
						<img src="<?php echo esc_attr(wp_get_attachment_image_url($item['id'], 'services_detail')); ?>" alt="#">
					<?php }
				} ?>
			</div>
		</div>

		<?php
	}
}
