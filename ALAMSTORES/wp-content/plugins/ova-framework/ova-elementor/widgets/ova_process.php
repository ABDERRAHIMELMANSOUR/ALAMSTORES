<?php

namespace ova_framework\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Core\Schemes\Typography;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Color;


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class ova_process extends Widget_Base {

	public function get_name() {
		return 'ova_process';
	}

	public function get_title() {
		return __( 'Process', 'ova-framework' );
	}

	public function get_icon() {
		return 'fa fa-terminal';
	}

	public function get_categories() {
		return [ 'ovatheme' ];
	}

	public function get_script_depends() {
		return [ 'script-elementor' ];
	}

	protected function register_controls() {

		$this->start_controls_section(
			'section_category',
			[
				'label' => __( 'Category', 'ova-framework' ),
			]
		);


		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'category',
			[
				'label' => __( 'Category', 'ova-framework' ),
				'type' => \Elementor\Controls_Manager::TEXT,
			]
		);

		$repeater->add_control(
			'class_cat_filter',
			[
				'label' => __( 'Class Category Filter', 'ova-framework' ),
				'type' => \Elementor\Controls_Manager::TEXT,
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
						'category' => __('Our process', 'ova-framework'),
						'class_cat_filter' => __('cat_filter_1', 'ova-framework'),
					],
					[
						'category' => __('Build process', 'ova-framework'),
						'class_cat_filter' => __('cat_filter_2', 'ova-framework'),
					],
					[
						'category' => __('Energy', 'ova-framework'),
						'class_cat_filter' => __('cat_filter_3', 'ova-framework'),
					],
					[
						'category' => __('Renovation Step', 'ova-framework'),
						'class_cat_filter' => __('cat_filter_4', 'ova-framework'),
					],
					[
						'category' => __('Check Quality', 'ova-framework'),
						'class_cat_filter' => __('cat_filter_5', 'ova-framework'),
					],
				],
				'title_field' => '{{{ category }}}',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Content', 'ova-framework' ),
			]
		);

		$this->add_control(
			'container',
			[
				'label' => __( 'Container', 'ova-framework' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'ova-framework' ),
				'label_off' => __( 'Hide', 'ova-framework' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$repeater_2 = new \Elementor\Repeater();

		$repeater_2->add_control(
			'number',
			[
				'label' => __( 'Number', 'ova-framework' ),
				'type' => \Elementor\Controls_Manager::TEXT,
			]
		);

		$repeater_2->add_control(
			'title',
			[
				'label' => __( 'Title', 'ova-framework' ),
				'type' => \Elementor\Controls_Manager::TEXT,
			]
		);

		$repeater_2->add_control(
			'desc',
			[
				'label' => __( 'Description', 'ova-framework' ),
				'type' => \Elementor\Controls_Manager::TEXT,
			]
		);

		$repeater_2->add_control(
			'class_filter_cat',
			[
				'label' => __( 'Class filter category', 'ova-framework' ),
				'type' => \Elementor\Controls_Manager::TEXT,
			]
		);

		$this->add_control(
			'tabs_2',
			[
				'label' => __( 'Tabs', 'ova-framework' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater_2->get_controls(),
				'default' => [
					[
						'number' => __('1', 'ova-framework'),
						'title' => __('Client Request', 'ova-framework'),
						'desc' => __('Suspendisse in dapibus tellus. Praesent at ultrices est. Proin vel quam urna. Integer id velit nec nulla accumsan tristique. Vivamus molestie velit mauris, sed laoreet dui vehicula vel. Donec varius in diam sed vulputate. Nullam eu nisi sit amet erat euismod.', 'ova-framework'),
						'class_filter_cat' => __('cat_filter_1', 'ova-framework'),
					],
					[
						'number' => __('2', 'ova-framework'),
						'title' => __('Quotation', 'ova-framework'),
						'desc' => __('Etiam justo mi, sollicitudin a hendrerit eget, pharetra non mi. Etiam quis dui vel ante condimentum fermentum. Suspendisse convallis sem a neque suscipit, vitae imperdiet urna lacinia. Aliquam posuere nisl finibus ante consectetur elementum varius pretium nibh.', 'ova-framework'),
						'class_filter_cat' => __('cat_filter_1', 'ova-framework'),
					],
					[
						'number' => __('3', 'ova-framework'),
						'title' => __('Project Start', 'ova-framework'),
						'desc' => __('Etiam justo mi, sollicitudin a hendrerit eget, pharetra non mi. Etiam quis dui vel ante condimentum fermentum. Suspendisse convallis sem a neque suscipit, vitae imperdiet urna lacinia. Aliquam posuere nisl finibus ante consectetur elementum varius pretium nibh.', 'ova-framework'),
						'class_filter_cat' => __('cat_filter_1', 'ova-framework'),
					],

					[
						'number' => __('1', 'ova-framework'),
						'title' => __('Client Request Build', 'ova-framework'),
						'desc' => __('Suspendisse in dapibus tellus. Praesent at ultrices est. Proin vel quam urna. Integer id velit nec nulla accumsan tristique. Vivamus molestie velit mauris, sed laoreet dui vehicula vel. Donec varius in diam sed vulputate. Nullam eu nisi sit amet erat euismod.', 'ova-framework'),
						'class_filter_cat' => __('cat_filter_2', 'ova-framework'),
					],
					[
						'number' => __('2', 'ova-framework'),
						'title' => __('Quotation', 'ova-framework'),
						'desc' => __('Etiam justo mi, sollicitudin a hendrerit eget, pharetra non mi. Etiam quis dui vel ante condimentum fermentum. Suspendisse convallis sem a neque suscipit, vitae imperdiet urna lacinia. Aliquam posuere nisl finibus ante consectetur elementum varius pretium nibh.', 'ova-framework'),
						'class_filter_cat' => __('cat_filter_2', 'ova-framework'),
					],

					[
						'number' => __('1', 'ova-framework'),
						'title' => __('Client Request Energy', 'ova-framework'),
						'desc' => __('Etiam justo mi, sollicitudin a hendrerit eget, pharetra non mi. Etiam quis dui vel ante condimentum fermentum. Suspendisse convallis sem a neque suscipit, vitae imperdiet urna lacinia. Aliquam posuere nisl finibus ante consectetur elementum varius pretium nibh.', 'ova-framework'),
						'class_filter_cat' => __('cat_filter_3', 'ova-framework'),
					],

					[
						'number' => __('1', 'ova-framework'),
						'title' => __('Quotation Renovation', 'ova-framework'),
						'desc' => __('Suspendisse in dapibus tellus. Praesent at ultrices est. Proin vel quam urna. Integer id velit nec nulla accumsan tristique. Vivamus molestie velit mauris, sed laoreet dui vehicula vel. Donec varius in diam sed vulputate. Nullam eu nisi sit amet erat euismod.', 'ova-framework'),
						'class_filter_cat' => __('cat_filter_4', 'ova-framework'),
					],

					[
						'number' => __('1', 'ova-framework'),
						'title' => __('Client Request Quality', 'ova-framework'),
						'desc' => __('Suspendisse in dapibus tellus. Praesent at ultrices est. Proin vel quam urna. Integer id velit nec nulla accumsan tristique. Vivamus molestie velit mauris, sed laoreet dui vehicula vel. Donec varius in diam sed vulputate. Nullam eu nisi sit amet erat euismod.', 'ova-framework'),
						'class_filter_cat' => __('cat_filter_5', 'ova-framework'),
					],
					[
						'number' => __('2', 'ova-framework'),
						'title' => __('Quotation Quality', 'ova-framework'),
						'desc' => __('Etiam justo mi, sollicitudin a hendrerit eget, pharetra non mi. Etiam quis dui vel ante condimentum fermentum. Suspendisse convallis sem a neque suscipit, vitae imperdiet urna lacinia. Aliquam posuere nisl finibus ante consectetur elementum varius pretium nibh.', 'ova-framework'),
						'class_filter_cat' => __('cat_filter_5', 'ova-framework'),
					],
				],
				'title_field' => '{{{ title }}}',
			]
		);


$this->end_controls_section();


}

protected function render() {
	$settings = $this->get_settings();
	$tabs = $settings['tabs'];
	$tabs_2 = $settings['tabs_2'];
	?>
	<div class="ova-process">
		<?php if ($settings['container'] === 'yes') : ?>
			<div class="container">
			<?php endif ?>
			<div class="category">
				<ul>
					<?php if (!empty($tabs)) : foreach($tabs as $item) : ?>
						<li ><a data-class="<?php echo esc_attr($item['class_cat_filter']) ?>" class="second_font" href="javascript:void(0)"><?php echo esc_html($item['category']) ?></a></li>
					<?php endforeach; endif; ?>
				</ul>
			</div>
			<div class="content">
				<?php if (!empty($tabs_2)) : foreach($tabs_2 as $item_2) : ?>
					<div class="item <?php echo esc_attr($item_2['class_filter_cat']) ?>">
						<i class="arrow_flaticon-034-right-arrow-7"></i>
						<div class="number">
							<span class="second_font"><?php echo esc_html($item_2['number']) ?></span>
						</div>
						<div class="title-desc">
							<h3 class="title second_font"><?php echo esc_html($item_2['title']) ?></h3>
							<p class="desc"><?php echo esc_html($item_2['desc']) ?></p>
						</div>
					</div>
				<?php endforeach; endif; ?>
			</div>
			<?php if ($settings['container'] === 'yes') : ?>
			</div>
		<?php endif ?>
		
	</div>
	<?php
}
}


