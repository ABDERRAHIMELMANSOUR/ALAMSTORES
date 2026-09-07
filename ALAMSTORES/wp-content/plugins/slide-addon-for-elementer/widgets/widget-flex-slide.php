<?php

class Flex_Slide_Widget extends \Elementor\Widget_Base {

	public function get_name() {
        return 'flex-slider';
    }
    
    public function get_title() {
        return esc_html__( 'TF E Slider', 'slide-addon-for-elementer' );
    }

    public function get_icon() {
        return 'eicon-slider-push';
    }
    
    public function get_categories() {
        return [ 'themesflat_addons' ];
    }

    public function get_style_depends() {
		return ['font-awesome','flexslider','slide-animate','slide-addon-for-elementor-style'];
	}

	public function get_script_depends() {
		return ['jquery-easing','typed','flexslider','slide-addon-for-elementor-script'];
	}

	protected function register_controls() {
		/* Start Flex Slide Option*/
			$this->start_controls_section('section_flex_slider',
	            [
	                'label'         => esc_html__('General','slide-addon-for-elementer'),
	            ]
	        );
	        $this->add_control( 'height_slider',
				[
					'label' => esc_html__( 'Height Slider', 'slide-addon-for-elementer' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ 'px' ],
					'range' => [
						'px' => [
							'min' => 400,
							'max' => 2000,
							'step' => 1,
						]
					],
					'default' => [
						'unit' => 'px',
						'size' => 900,
					],
				]
			);
			$this->add_control( 'animation_images',
	            [
	                'label' => esc_html__( 'Background Effect', 'slide-addon-for-elementer' ),
	                'type' => \Elementor\Controls_Manager::SELECT,
	                'default' => 'fade',
	                'options' => [
	                	'fade' => esc_html__( 'Fade', 'slide-addon-for-elementer' ),
	                	'slide' => esc_html__( 'Slide', 'slide-addon-for-elementer' ),
	                ],
	            ]
	        );
	        $this->add_control( 'kenburns_effect',
				[
					'label' => esc_html__( 'Ken Burns Effect', 'slide-addon-for-elementer' ),
					'type' => \Elementor\Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'On', 'slide-addon-for-elementer' ),
					'label_off' => esc_html__( 'Off', 'slide-addon-for-elementer' ),
					'return_value' => 'yes',
					'default' => 'no',
				]
			);
			$this->add_control( 'kenburns_effect_images',
	            [
	                'label' => esc_html__( 'Direction', 'slide-addon-for-elementer' ),
	                'type' => \Elementor\Controls_Manager::SELECT,
	                'default' => 'bg_zoomOut',
	                'options' => [
	                    'bg_zoomIn' => esc_html__( 'In', 'slide-addon-for-elementer' ),
	                    'bg_zoomOut' => esc_html__( 'Out', 'slide-addon-for-elementer' ),
	                ],
	                'condition' => [
	                    'kenburns_effect' => 'yes',
	                ]
	            ]
	        );
	        $this->add_control( 'bg_images_size',
	            [
	                'label' => esc_html__( 'Background Size', 'slide-addon-for-elementer' ),
	                'type' => \Elementor\Controls_Manager::SELECT,
	                'default' => '',
	                'options' => [
	                	'' => esc_html__( 'Default', 'slide-addon-for-elementer' ),
	                    'auto' => esc_html__( 'Auto', 'slide-addon-for-elementer' ),
	                    'cover' => esc_html__( 'Cover', 'slide-addon-for-elementer' ),
	                    'contain' => esc_html__( 'Contain', 'slide-addon-for-elementer' ),
	                ],
	            ]
	        );
	        $this->add_control( 'bg_images_position',
	            [
	                'label' => esc_html__( 'Background Position', 'slide-addon-for-elementer' ),
	                'type' => \Elementor\Controls_Manager::SELECT,
	                'default' => '',
	                'options' => [
	                	'' => esc_html__( 'Default', 'slide-addon-for-elementer' ),
	                	'center center' => esc_html__( 'Center Center', 'slide-addon-for-elementer' ),
	                	'center left' => esc_html__( 'Center Left', 'slide-addon-for-elementer' ),
	                	'center right' => esc_html__( 'Center Right', 'slide-addon-for-elementer' ),
	                	'top center' => esc_html__( 'Top Center', 'slide-addon-for-elementer' ),
	                	'top left' => esc_html__( 'Top Left', 'slide-addon-for-elementer' ),
	                	'top right' => esc_html__( 'Top Right', 'slide-addon-for-elementer' ),
	                	'bottom center' => esc_html__( 'Bottom Center', 'slide-addon-for-elementer' ),
	                	'bottom left' => esc_html__( 'Bottom Left', 'slide-addon-for-elementer' ),
	                	'bottom right' => esc_html__( 'Bottom Right', 'slide-addon-for-elementer' ),
	                ],
	            ]
	        );
	        $this->add_control( 'slideshow_autoplay',
	            [
	                'label'         => esc_html__( 'Autoplay', 'slide-addon-for-elementer' ),
	                'type'          => \Elementor\Controls_Manager::SWITCHER,
	                'label_on'      => esc_html__( 'On', 'slide-addon-for-elementer' ),
	                'label_off'     => esc_html__( 'Off', 'slide-addon-for-elementer' ),
	                'return_value'  => 'true',
	                'default'       => 'true',
	            ]
	        );
	        $this->add_control( 'infinite_loop',
	            [
	                'label'         => esc_html__( 'Infinite Loop', 'slide-addon-for-elementer' ),
	                'type'          => \Elementor\Controls_Manager::SWITCHER,
	                'label_on'      => esc_html__( 'On', 'slide-addon-for-elementer' ),
	                'label_off'     => esc_html__( 'Off', 'slide-addon-for-elementer' ),
	                'return_value'  => 'true',
	                'default'       => 'true',
	            ]
	        );
	        $this->add_control( 'slideshowspeed',
				[
					'label' => esc_html__( 'Duration (ms)', 'slide-addon-for-elementer' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ 'px' ],
					'range' => [
						'px' => [
							'min' => 1000,
							'max' => 10000,
							'step' => 100,
						]
					],
					'default' => [
						'unit' => 'px',
						'size' => 3000,
					],
				]
			);
			$this->add_control( 'animationspeed',
				[
					'label' => esc_html__( 'Transition Duration (ms)', 'slide-addon-for-elementer' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ 'px' ],
					'range' => [
						'px' => [
							'min' => 1000,
							'max' => 10000,
							'step' => 100,
						]
					],
					'default' => [
						'unit' => 'px',
						'size' => 600,
					],
				]
			);
			$this->add_control( 'content_into_grid',
	            [
	                'label'         => esc_html__( 'Content Area Into Grid?', 'slide-addon-for-elementer' ),
	                'type'          => \Elementor\Controls_Manager::SWITCHER,
	                'label_on'      => esc_html__( 'On', 'slide-addon-for-elementer' ),
	                'label_off'     => esc_html__( 'Off', 'slide-addon-for-elementer' ),
	                'return_value'  => 'yes',
	                'default'       => 'yes',
	            ]
	        );
	        $repeater = new \Elementor\Repeater();
				$repeater->add_control( 'flexslider_image',
		            [
		                'label'     => esc_html__( 'Image', 'slide-addon-for-elementer' ), 
		                'type'      => \Elementor\Controls_Manager::MEDIA, 
		                'dynamic' => [
		                    'active' => true,
		                ],
		                'default' => [
		                    'url' => URL_PLUGIN_SLIDE_ELEMENTOR."assets/img/default.jpg",
		                ], 
		            ]
		        );
		        $repeater->add_control( 'color_overlay',
		            [
		                'label'     => esc_html__( 'Color Overlay', 'slide-addon-for-elementer' ),
		                'type'      => \Elementor\Controls_Manager::COLOR,
		                'default'	=> '',
		            ]
		        );
		        $repeater->add_control( 'align_content',
					[
						'label' => esc_html__( 'Alignment', 'slide-addon-for-elementer' ),
						'type' => \Elementor\Controls_Manager::CHOOSE,
						'options' => [
							'left' => [
								'title' => esc_html__( 'Left', 'slide-addon-for-elementer' ),
								'icon' => 'eicon-text-align-left',
							],
							'center' => [
								'title' => esc_html__( 'Center', 'slide-addon-for-elementer' ),
								'icon' => 'eicon-text-align-center',
							],
							'right' => [
								'title' => esc_html__( 'Right', 'slide-addon-for-elementer' ),
								'icon' => 'eicon-text-align-right',
							]
						],
						'default' => ''
					]
				);
		        $repeater->add_control( 'heading_title_text',
					[
						'label' => esc_html__( 'Title', 'slide-addon-for-elementer' ),
						'type' => \Elementor\Controls_Manager::HEADING,
					]
				);
		        $repeater->add_control('title_text',
		        	[
		                'label'   => esc_html__( 'Title Text', 'slide-addon-for-elementer' ),
		                'type'    => \Elementor\Controls_Manager::TEXTAREA,
		                'default' => 'Take control of your business',                    
		            ]
		    	);
		    	$repeater->add_control( 'title_animation',
		            [
		                'label' => esc_html__( 'Animation', 'slide-addon-for-elementer' ),
		                'type' => \Elementor\Controls_Manager::SELECT,
		                'default' => 'fromTop',
		                'options' => [
		                	'' => esc_html__( 'None', 'slide-addon-for-elementer' ),	                	
		                	'vivify fadeInUp_tf' => esc_html__( 'Fade In Up', 'slide-addon-for-elementer' ),
		                	'vivify fadeInDown_tf' => esc_html__( 'Fade In Down', 'slide-addon-for-elementer' ),
		                	'vivify fadeInLeft_tf' => esc_html__( 'Fade In Left', 'slide-addon-for-elementer' ),
		                	'vivify fadeInRight_tf' => esc_html__( 'Fade In Right', 'slide-addon-for-elementer' ),
		                	'fromTop' => esc_html__( 'From Top', 'slide-addon-for-elementer' ),
		                    'fromBottom' => esc_html__( 'From Bottom', 'slide-addon-for-elementer' ),
		                    'fromLeft' => esc_html__( 'From Left', 'slide-addon-for-elementer' ),
		                    'fromRight' => esc_html__( 'From Right', 'slide-addon-for-elementer' ),
		                    'vivify pulsate' => esc_html__( 'Pulsate', 'slide-addon-for-elementer' ),
		                    'vivify ball' => esc_html__( 'Ball', 'slide-addon-for-elementer' ),
		                    'vivify pullUp' => esc_html__( 'Pull Up', 'slide-addon-for-elementer' ),
		                    'vivify pullDown' => esc_html__( 'Pull Down', 'slide-addon-for-elementer' ),
		                    'vivify pullLeft' => esc_html__( 'Pull Left', 'slide-addon-for-elementer' ),
		                    'vivify pullRight' => esc_html__( 'Pull Right', 'slide-addon-for-elementer' ),
		                    'vivify jumpInLeft' => esc_html__( 'Jump In Left', 'slide-addon-for-elementer' ),
		                    'vivify jumpInRight' => esc_html__( 'Jump In Right', 'slide-addon-for-elementer' ),
		                    'vivify rollInLeft' => esc_html__( 'Roll In Left', 'slide-addon-for-elementer' ),
		                    'vivify rollInRight' => esc_html__( 'Roll In Right', 'slide-addon-for-elementer' ),
		                    'vivify rollInTop' => esc_html__( 'Roll In Top', 'slide-addon-for-elementer' ),
		                    'vivify rollInBottom' => esc_html__( 'Roll In Bottom', 'slide-addon-for-elementer' ),
		                    'vivify popIn' => esc_html__( 'Pop In', 'slide-addon-for-elementer' ),
		                    'vivify popInLeft' => esc_html__( 'Pop In Left', 'slide-addon-for-elementer' ),
		                    'vivify popInRight' => esc_html__( 'Pop In Right', 'slide-addon-for-elementer' ),
		                    'vivify popInTop' => esc_html__( 'Pop In Top', 'slide-addon-for-elementer' ),
		                    'vivify popInBottom' => esc_html__( 'Pop In Bottom', 'slide-addon-for-elementer' ),
		                    'vivify swoopInLeft' => esc_html__( 'Swoop In Left', 'slide-addon-for-elementer' ),
		                    'vivify swoopInRight' => esc_html__( 'Swoop In Right', 'slide-addon-for-elementer' ),
		                    'vivify swoopInTop' => esc_html__( 'Swoop In Top', 'slide-addon-for-elementer' ),
		                    'vivify swoopInBottom' => esc_html__( 'Swoop In Bottom', 'slide-addon-for-elementer' ),
		                    'vivify flip' => esc_html__( 'Flip', 'slide-addon-for-elementer' ),
		                    'vivify spin' => esc_html__( 'Spin', 'slide-addon-for-elementer' ),
		                ],
		                'condition' => [
		                    'title_text[value]!' => '',
		                ]
		            ]
		        );
		        $repeater->add_control( 'title_delay',
		            [
		                'label' => esc_html__( 'Delay', 'slide-addon-for-elementer' ),
		                'type' => \Elementor\Controls_Manager::SELECT,
		                'default' => 'captionDelay6',
		                'options' => [
		                	'' => esc_html__( 'None', 'slide-addon-for-elementer' ),
		                	'captionDelay1' => esc_html__( 'Delay 0.1 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay2' => esc_html__( 'Delay 0.2 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay3' => esc_html__( 'Delay 0.3 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay4' => esc_html__( 'Delay 0.4 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay5' => esc_html__( 'Delay 0.5 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay6' => esc_html__( 'Delay 0.6 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay7' => esc_html__( 'Delay 0.7 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay8' => esc_html__( 'Delay 0.8 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay9' => esc_html__( 'Delay 0.9 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay10' => esc_html__( 'Delay 1 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay11' => esc_html__( 'Delay 1.1 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay12' => esc_html__( 'Delay 1.2 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay13' => esc_html__( 'Delay 1.3 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay14' => esc_html__( 'Delay 1.4 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay15' => esc_html__( 'Delay 1.5 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay16' => esc_html__( 'Delay 1.6 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay17' => esc_html__( 'Delay 1.7 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay18' => esc_html__( 'Delay 1.8 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay19' => esc_html__( 'Delay 1.9 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay20' => esc_html__( 'Delay 2 Seconds', 'slide-addon-for-elementer' ),
		                ],
		                'condition' => [
		                    'title_text[value]!' => '',
		                ]
		            ]
		        );
				$repeater->add_control( 'heading_subtitle_text',
					[
						'label' => esc_html__( 'Sub Title', 'slide-addon-for-elementer' ),
						'type' => \Elementor\Controls_Manager::HEADING,
					]
				);
		    	$repeater->add_control('subtitle_text',
		        	[
		                'label'   => esc_html__( 'Sub Title Text', 'slide-addon-for-elementer' ),
		                'type'    => \Elementor\Controls_Manager::TEXTAREA,
		                'default' => 'Sub title text',                    
		            ]
		    	); 
		    	$repeater->add_control( 'subtitle_animation',
		            [
		                'label' => esc_html__( 'Animation', 'slide-addon-for-elementer' ),
		                'type' => \Elementor\Controls_Manager::SELECT,
		                'default' => 'fromTop',
		                'options' => [
		                	'' => esc_html__( 'None', 'slide-addon-for-elementer' ),	                	
		                	'vivify fadeInUp_tf' => esc_html__( 'Fade In Up', 'slide-addon-for-elementer' ),
		                	'vivify fadeInDown_tf' => esc_html__( 'Fade In Down', 'slide-addon-for-elementer' ),
		                	'vivify fadeInLeft_tf' => esc_html__( 'Fade In Left', 'slide-addon-for-elementer' ),
		                	'vivify fadeInRight_tf' => esc_html__( 'Fade In Right', 'slide-addon-for-elementer' ),
		                	'fromTop' => esc_html__( 'From Top', 'slide-addon-for-elementer' ),
		                    'fromBottom' => esc_html__( 'From Bottom', 'slide-addon-for-elementer' ),
		                    'fromLeft' => esc_html__( 'From Left', 'slide-addon-for-elementer' ),
		                    'fromRight' => esc_html__( 'From Right', 'slide-addon-for-elementer' ),
		                    'vivify pulsate' => esc_html__( 'Pulsate', 'slide-addon-for-elementer' ),
		                    'vivify ball' => esc_html__( 'Ball', 'slide-addon-for-elementer' ),
		                    'vivify pullUp' => esc_html__( 'Pull Up', 'slide-addon-for-elementer' ),
		                    'vivify pullDown' => esc_html__( 'Pull Down', 'slide-addon-for-elementer' ),
		                    'vivify pullLeft' => esc_html__( 'Pull Left', 'slide-addon-for-elementer' ),
		                    'vivify pullRight' => esc_html__( 'Pull Right', 'slide-addon-for-elementer' ),
		                    'vivify jumpInLeft' => esc_html__( 'Jump In Left', 'slide-addon-for-elementer' ),
		                    'vivify jumpInRight' => esc_html__( 'Jump In Right', 'slide-addon-for-elementer' ),
		                    'vivify rollInLeft' => esc_html__( 'Roll In Left', 'slide-addon-for-elementer' ),
		                    'vivify rollInRight' => esc_html__( 'Roll In Right', 'slide-addon-for-elementer' ),
		                    'vivify rollInTop' => esc_html__( 'Roll In Top', 'slide-addon-for-elementer' ),
		                    'vivify rollInBottom' => esc_html__( 'Roll In Bottom', 'slide-addon-for-elementer' ),
		                    'vivify popIn' => esc_html__( 'Pop In', 'slide-addon-for-elementer' ),
		                    'vivify popInLeft' => esc_html__( 'Pop In Left', 'slide-addon-for-elementer' ),
		                    'vivify popInRight' => esc_html__( 'Pop In Right', 'slide-addon-for-elementer' ),
		                    'vivify popInTop' => esc_html__( 'Pop In Top', 'slide-addon-for-elementer' ),
		                    'vivify popInBottom' => esc_html__( 'Pop In Bottom', 'slide-addon-for-elementer' ),
		                    'vivify swoopInLeft' => esc_html__( 'Swoop In Left', 'slide-addon-for-elementer' ),
		                    'vivify swoopInRight' => esc_html__( 'Swoop In Right', 'slide-addon-for-elementer' ),
		                    'vivify swoopInTop' => esc_html__( 'Swoop In Top', 'slide-addon-for-elementer' ),
		                    'vivify swoopInBottom' => esc_html__( 'Swoop In Bottom', 'slide-addon-for-elementer' ),
		                    'vivify flip' => esc_html__( 'Flip', 'slide-addon-for-elementer' ),
		                    'vivify spin' => esc_html__( 'Spin', 'slide-addon-for-elementer' ),
		                ],
		                'condition' => [
		                    'subtitle_text[value]!' => '',
		                ]
		            ]
		        );
		        $repeater->add_control( 'subtitle_delay',
		            [
		                'label' => esc_html__( 'Delay', 'slide-addon-for-elementer' ),
		                'type' => \Elementor\Controls_Manager::SELECT,
		                'default' => 'captionDelay8',
		                'options' => [
		                	'' => esc_html__( 'None', 'slide-addon-for-elementer' ),
		                	'captionDelay1' => esc_html__( 'Delay 0.1 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay2' => esc_html__( 'Delay 0.2 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay3' => esc_html__( 'Delay 0.3 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay4' => esc_html__( 'Delay 0.4 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay5' => esc_html__( 'Delay 0.5 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay6' => esc_html__( 'Delay 0.6 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay7' => esc_html__( 'Delay 0.7 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay8' => esc_html__( 'Delay 0.8 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay9' => esc_html__( 'Delay 0.9 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay10' => esc_html__( 'Delay 1 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay11' => esc_html__( 'Delay 1.1 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay12' => esc_html__( 'Delay 1.2 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay13' => esc_html__( 'Delay 1.3 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay14' => esc_html__( 'Delay 1.4 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay15' => esc_html__( 'Delay 1.5 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay16' => esc_html__( 'Delay 1.6 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay17' => esc_html__( 'Delay 1.7 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay18' => esc_html__( 'Delay 1.8 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay19' => esc_html__( 'Delay 1.9 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay20' => esc_html__( 'Delay 2 Seconds', 'slide-addon-for-elementer' ),
		                ],
		                'condition' => [
		                    'subtitle_text[value]!' => '',
		                ]
		            ]
		        );
				$repeater->add_control( 'heading_desc_text',
					[
						'label' => esc_html__( 'Desciption', 'slide-addon-for-elementer' ),
						'type' => \Elementor\Controls_Manager::HEADING,
					]
				);
		    	$repeater->add_control('desc_text',
		        	[
		            'label'         => esc_html__('Desciption Text','slide-addon-for-elementer'),
		            'type'        => \Elementor\Controls_Manager::TEXTAREA,
		            'default'     => 'We are design agency united in keeping creative design</br>and marketing a straight forward affaire.',
		        	]
		        );	
		        $repeater->add_control( 'desc_animation',
		            [
		                'label' => esc_html__( 'Animation', 'slide-addon-for-elementer' ),
		                'type' => \Elementor\Controls_Manager::SELECT,
		                'default' => 'fromTop',
		                'options' => [
		                	'' => esc_html__( 'None', 'slide-addon-for-elementer' ),
		                	'vivify fadeInUp_tf' => esc_html__( 'Fade In Up', 'slide-addon-for-elementer' ),
		                	'vivify fadeInDown_tf' => esc_html__( 'Fade In Down', 'slide-addon-for-elementer' ),
		                	'vivify fadeInLeft_tf' => esc_html__( 'Fade In Left', 'slide-addon-for-elementer' ),
		                	'vivify fadeInRight_tf' => esc_html__( 'Fade In Right', 'slide-addon-for-elementer' ),
		                	'fromTop' => esc_html__( 'From Top', 'slide-addon-for-elementer' ),
		                    'fromBottom' => esc_html__( 'From Bottom', 'slide-addon-for-elementer' ),
		                    'fromLeft' => esc_html__( 'From Left', 'slide-addon-for-elementer' ),
		                    'fromRight' => esc_html__( 'From Right', 'slide-addon-for-elementer' ),
		                    'vivify pulsate' => esc_html__( 'Pulsate', 'slide-addon-for-elementer' ),
		                    'vivify ball' => esc_html__( 'Ball', 'slide-addon-for-elementer' ),
		                    'vivify pullUp' => esc_html__( 'Pull Up', 'slide-addon-for-elementer' ),
		                    'vivify pullDown' => esc_html__( 'Pull Down', 'slide-addon-for-elementer' ),
		                    'vivify pullLeft' => esc_html__( 'Pull Left', 'slide-addon-for-elementer' ),
		                    'vivify pullRight' => esc_html__( 'Pull Right', 'slide-addon-for-elementer' ),
		                    'vivify jumpInLeft' => esc_html__( 'Jump In Left', 'slide-addon-for-elementer' ),
		                    'vivify jumpInRight' => esc_html__( 'Jump In Right', 'slide-addon-for-elementer' ),
		                    'vivify rollInLeft' => esc_html__( 'Roll In Left', 'slide-addon-for-elementer' ),
		                    'vivify rollInRight' => esc_html__( 'Roll In Right', 'slide-addon-for-elementer' ),
		                    'vivify rollInTop' => esc_html__( 'Roll In Top', 'slide-addon-for-elementer' ),
		                    'vivify rollInBottom' => esc_html__( 'Roll In Bottom', 'slide-addon-for-elementer' ),
		                    'vivify popIn' => esc_html__( 'Pop In', 'slide-addon-for-elementer' ),
		                    'vivify popInLeft' => esc_html__( 'Pop In Left', 'slide-addon-for-elementer' ),
		                    'vivify popInRight' => esc_html__( 'Pop In Right', 'slide-addon-for-elementer' ),
		                    'vivify popInTop' => esc_html__( 'Pop In Top', 'slide-addon-for-elementer' ),
		                    'vivify popInBottom' => esc_html__( 'Pop In Bottom', 'slide-addon-for-elementer' ),
		                    'vivify swoopInLeft' => esc_html__( 'Swoop In Left', 'slide-addon-for-elementer' ),
		                    'vivify swoopInRight' => esc_html__( 'Swoop In Right', 'slide-addon-for-elementer' ),
		                    'vivify swoopInTop' => esc_html__( 'Swoop In Top', 'slide-addon-for-elementer' ),
		                    'vivify swoopInBottom' => esc_html__( 'Swoop In Bottom', 'slide-addon-for-elementer' ),
		                    'vivify flip' => esc_html__( 'Flip', 'slide-addon-for-elementer' ),
		                    'vivify spin' => esc_html__( 'Spin', 'slide-addon-for-elementer' ),
		                ],
		                'condition' => [
		                    'desc_text[value]!' => '',
		                ]
		            ]
		        );
		        $repeater->add_control( 'desc_delay',
		            [
		                'label' => esc_html__( 'Delay', 'slide-addon-for-elementer' ),
		                'type' => \Elementor\Controls_Manager::SELECT,
		                'default' => 'captionDelay2',
		                'options' => [
		                	'' => esc_html__( 'None', 'slide-addon-for-elementer' ),
		                	'captionDelay1' => esc_html__( 'Delay 0.1 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay2' => esc_html__( 'Delay 0.2 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay3' => esc_html__( 'Delay 0.3 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay4' => esc_html__( 'Delay 0.4 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay5' => esc_html__( 'Delay 0.5 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay6' => esc_html__( 'Delay 0.6 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay7' => esc_html__( 'Delay 0.7 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay8' => esc_html__( 'Delay 0.8 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay9' => esc_html__( 'Delay 0.9 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay10' => esc_html__( 'Delay 1 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay11' => esc_html__( 'Delay 1.1 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay12' => esc_html__( 'Delay 1.2 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay13' => esc_html__( 'Delay 1.3 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay14' => esc_html__( 'Delay 1.4 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay15' => esc_html__( 'Delay 1.5 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay16' => esc_html__( 'Delay 1.6 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay17' => esc_html__( 'Delay 1.7 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay18' => esc_html__( 'Delay 1.8 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay19' => esc_html__( 'Delay 1.9 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay20' => esc_html__( 'Delay 2 Seconds', 'slide-addon-for-elementer' ),
		                ],
		                'condition' => [
		                    'desc_text[value]!' => '',
		                ]
		            ]
		        );
				$repeater->add_control( 'heading_shape',
					[
						'label' => esc_html__( 'Shape', 'slide-addon-for-elementer' ),
						'type' => \Elementor\Controls_Manager::HEADING,
					]
				);
				$repeater->add_control( 'shape_one', 
		        	[
		                'label' => esc_html__( 'Shape One', 'slide-addon-for-elementer' ),
		                'type' => \Elementor\Controls_Manager::MEDIA
		            ]
		        );
		        $repeater->add_control( 'index_shape_one',
					[
						'label' => esc_html__( 'z-index Shape One', 'slide-addon-for-elementer' ),
						'type' => \Elementor\Controls_Manager::NUMBER,
						'min' => -1,
						'max' => 1000,
						'step' => 1,
						'selectors' => [
		                    '{{WRAPPER}} {{CURRENT_ITEM}}_shape_one' => 'z-index: {{SIZE}}',
		                ],
		                'condition' => [
		                    'shape_one[url]!' => '',
		                ],
					]
				);
		        $repeater->add_responsive_control( 'shape_one_width',
		            [
		                'label' => esc_html__( 'Width Shape One', 'slide-addon-for-elementer' ),
		                'type' => \Elementor\Controls_Manager::SLIDER,
		                'size_units' => [ 'px', '%' ],
		                'range' => [
		                    'px' => [
		                        'min' => 0,
		                        'max' => 2000,
		                        'step' => 1,
		                    ],
		                    '%' => [
		                        'min' => 0,
		                        'max' => 100,
		                    ],
		                ],
		                'selectors' => [
		                    '{{WRAPPER}} {{CURRENT_ITEM}}_shape_one' => 'width: {{SIZE}}{{UNIT}}',
		                ],
		                'condition' => [
		                    'shape_one[url]!' => '',
		                ],
		            ]
		        );
		        $repeater->add_responsive_control( 'shape_one_height',
		            [
		                'label' => esc_html__( 'Height Shape One', 'slide-addon-for-elementer' ),
		                'type' => \Elementor\Controls_Manager::SLIDER,
		                'size_units' => [ 'px', '%' ],
		                'range' => [
		                    'px' => [
		                        'min' => 0,
		                        'max' => 2000,
		                        'step' => 1,
		                    ],
		                    '%' => [
		                        'min' => 0,
		                        'max' => 100,
		                    ],
		                ],
		                'selectors' => [
		                    '{{WRAPPER}} {{CURRENT_ITEM}}_shape_one' => 'height: {{SIZE}}{{UNIT}}',
		                ],
		                'condition' => [
		                    'shape_one[url]!' => '',
		                ],
		            ]
		        );
		        $repeater->add_responsive_control( 'shape_one_offset_x',
		            [
		                'label' => esc_html__( 'Offset X Shape One', 'slide-addon-for-elementer' ),
		                'type' => \Elementor\Controls_Manager::SLIDER,
		                'size_units' => [ 'px', '%' ],
		                'range' => [
		                    'px' => [
		                        'min' => -2000,
		                        'max' => 2000,
		                        'step' => 1,
		                    ],
		                    '%' => [
		                        'min' => -200,
		                        'max' => 200,
		                    ],
		                ],	                
		                'default' => [
							'unit' => 'px',
							'size' => 0,
						],
		                'selectors' => [
		                    '{{WRAPPER}} {{CURRENT_ITEM}}_shape_one' => 'left: {{SIZE}}{{UNIT}}',
		                ],
		                'condition' => [
		                    'shape_one[url]!' => '',
		                ],
		            ]
		        );
		        $repeater->add_responsive_control( 'shape_one_offset_y',
		            [
		                'label' => esc_html__( 'Offset Y Shape One', 'slide-addon-for-elementer' ),
		                'type' => \Elementor\Controls_Manager::SLIDER,
		                'size_units' => [ 'px', '%' ],
		                'range' => [
		                    'px' => [
		                        'min' => -2000,
		                        'max' => 2000,
		                        'step' => 1,
		                    ],
		                    '%' => [
		                        'min' => -200,
		                        'max' => 200,
		                    ],
		                ],	                
		                'default' => [
							'unit' => 'px',
							'size' => 0,
						],
		                'selectors' => [
		                    '{{WRAPPER}} {{CURRENT_ITEM}}_shape_one' => 'top: {{SIZE}}{{UNIT}}',
		                ],
		                'condition' => [
		                    'shape_one[url]!' => '',
		                ],
		            ]
		        );
		        $repeater->add_control( 'shape_one_animation',
		            [
		                'label' => esc_html__( 'Shape One Animation', 'slide-addon-for-elementer' ),
		                'type' => \Elementor\Controls_Manager::SELECT,
		                'default' => '',
		                'options' => [
		                	'' => esc_html__( 'None', 'slide-addon-for-elementer' ),
		                	'vivify fadeInUp_tf' => esc_html__( 'Fade In Up', 'slide-addon-for-elementer' ),
		                	'vivify fadeInDown_tf' => esc_html__( 'Fade In Down', 'slide-addon-for-elementer' ),
		                	'vivify fadeInLeft_tf' => esc_html__( 'Fade In Left', 'slide-addon-for-elementer' ),
		                	'vivify fadeInRight_tf' => esc_html__( 'Fade In Right', 'slide-addon-for-elementer' ),
		                	'fromTop' => esc_html__( 'From Top', 'slide-addon-for-elementer' ),
		                    'fromBottom' => esc_html__( 'From Bottom', 'slide-addon-for-elementer' ),
		                    'fromLeft' => esc_html__( 'From Left', 'slide-addon-for-elementer' ),
		                    'fromRight' => esc_html__( 'From Right', 'slide-addon-for-elementer' ),
		                    'vivify pulsate' => esc_html__( 'Pulsate', 'slide-addon-for-elementer' ),
		                    'vivify ball' => esc_html__( 'Ball', 'slide-addon-for-elementer' ),
		                    'vivify pullUp' => esc_html__( 'Pull Up', 'slide-addon-for-elementer' ),
		                    'vivify pullDown' => esc_html__( 'Pull Down', 'slide-addon-for-elementer' ),
		                    'vivify pullLeft' => esc_html__( 'Pull Left', 'slide-addon-for-elementer' ),
		                    'vivify pullRight' => esc_html__( 'Pull Right', 'slide-addon-for-elementer' ),
		                    'vivify jumpInLeft' => esc_html__( 'Jump In Left', 'slide-addon-for-elementer' ),
		                    'vivify jumpInRight' => esc_html__( 'Jump In Right', 'slide-addon-for-elementer' ),
		                    'vivify rollInLeft' => esc_html__( 'Roll In Left', 'slide-addon-for-elementer' ),
		                    'vivify rollInRight' => esc_html__( 'Roll In Right', 'slide-addon-for-elementer' ),
		                    'vivify rollInTop' => esc_html__( 'Roll In Top', 'slide-addon-for-elementer' ),
		                    'vivify rollInBottom' => esc_html__( 'Roll In Bottom', 'slide-addon-for-elementer' ),
		                    'vivify popIn' => esc_html__( 'Pop In', 'slide-addon-for-elementer' ),
		                    'vivify popInLeft' => esc_html__( 'Pop In Left', 'slide-addon-for-elementer' ),
		                    'vivify popInRight' => esc_html__( 'Pop In Right', 'slide-addon-for-elementer' ),
		                    'vivify popInTop' => esc_html__( 'Pop In Top', 'slide-addon-for-elementer' ),
		                    'vivify popInBottom' => esc_html__( 'Pop In Bottom', 'slide-addon-for-elementer' ),
		                    'vivify swoopInLeft' => esc_html__( 'Swoop In Left', 'slide-addon-for-elementer' ),
		                    'vivify swoopInRight' => esc_html__( 'Swoop In Right', 'slide-addon-for-elementer' ),
		                    'vivify swoopInTop' => esc_html__( 'Swoop In Top', 'slide-addon-for-elementer' ),
		                    'vivify swoopInBottom' => esc_html__( 'Swoop In Bottom', 'slide-addon-for-elementer' ),
		                    'vivify flip' => esc_html__( 'Flip', 'slide-addon-for-elementer' ),
		                    'vivify spin' => esc_html__( 'Spin', 'slide-addon-for-elementer' ),
		                ],
		                'condition' => [
		                    'shape_one[url]!' => '',
		                ]
		            ]
		        );
		        $repeater->add_control( 'shape_one_delay',
		            [
		                'label' => esc_html__( 'Shape One Delay', 'slide-addon-for-elementer' ),
		                'type' => \Elementor\Controls_Manager::SELECT,
		                'default' => '',
		                'options' => [
		                	'' => esc_html__( 'None', 'slide-addon-for-elementer' ),
		                	'captionDelay1' => esc_html__( 'Delay 0.1 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay2' => esc_html__( 'Delay 0.2 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay3' => esc_html__( 'Delay 0.3 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay4' => esc_html__( 'Delay 0.4 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay5' => esc_html__( 'Delay 0.5 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay6' => esc_html__( 'Delay 0.6 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay7' => esc_html__( 'Delay 0.7 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay8' => esc_html__( 'Delay 0.8 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay9' => esc_html__( 'Delay 0.9 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay10' => esc_html__( 'Delay 1 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay11' => esc_html__( 'Delay 1.1 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay12' => esc_html__( 'Delay 1.2 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay13' => esc_html__( 'Delay 1.3 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay14' => esc_html__( 'Delay 1.4 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay15' => esc_html__( 'Delay 1.5 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay16' => esc_html__( 'Delay 1.6 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay17' => esc_html__( 'Delay 1.7 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay18' => esc_html__( 'Delay 1.8 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay19' => esc_html__( 'Delay 1.9 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay20' => esc_html__( 'Delay 2 Seconds', 'slide-addon-for-elementer' ),
		                ],
		                'condition' => [
		                    'shape_one[url]!' => '',
		                ]
		            ]
		        );
		        $repeater->add_control( 'hr_shape_one',
					[
						'type' => \Elementor\Controls_Manager::DIVIDER,
						'condition' => [
		                    'shape_one[url]!' => '',
		                ]
					]
				);
				$repeater->add_control( 'shape_two', 
		        	[
		                'label' => esc_html__( 'Shape Two', 'slide-addon-for-elementer' ),
		                'type' => \Elementor\Controls_Manager::MEDIA
		            ]
		        );
		        $repeater->add_control( 'index_shape_two',
					[
						'label' => esc_html__( 'z-index Shape Two', 'slide-addon-for-elementer' ),
						'type' => \Elementor\Controls_Manager::NUMBER,
						'min' => -1,
						'max' => 1000,
						'step' => 1,
						'selectors' => [
		                    '{{WRAPPER}} {{CURRENT_ITEM}}_shape_two' => 'z-index: {{SIZE}}',
		                ],
		                'condition' => [
		                    'shape_two[url]!' => '',
		                ],
					]
				);
		        $repeater->add_responsive_control( 'shape_two_width',
		            [
		                'label' => esc_html__( 'Width Shape Two', 'slide-addon-for-elementer' ),
		                'type' => \Elementor\Controls_Manager::SLIDER,
		                'size_units' => [ 'px', '%' ],
		                'range' => [
		                    'px' => [
		                        'min' => 0,
		                        'max' => 2000,
		                        'step' => 1,
		                    ],
		                    '%' => [
		                        'min' => 0,
		                        'max' => 100,
		                    ],
		                ],
		                'selectors' => [
		                    '{{WRAPPER}} {{CURRENT_ITEM}}_shape_two' => 'width: {{SIZE}}{{UNIT}}',
		                ],
		                'condition' => [
		                    'shape_two[url]!' => '',
		                ],
		            ]
		        );
		        $repeater->add_responsive_control( 'shape_two_height',
		            [
		                'label' => esc_html__( 'Height Shape Two', 'slide-addon-for-elementer' ),
		                'type' => \Elementor\Controls_Manager::SLIDER,
		                'size_units' => [ 'px', '%' ],
		                'range' => [
		                    'px' => [
		                        'min' => 0,
		                        'max' => 2000,
		                        'step' => 1,
		                    ],
		                    '%' => [
		                        'min' => 0,
		                        'max' => 100,
		                    ],
		                ],
		                'selectors' => [
		                    '{{WRAPPER}} {{CURRENT_ITEM}}_shape_two' => 'height: {{SIZE}}{{UNIT}}',
		                ],
		                'condition' => [
		                    'shape_two[url]!' => '',
		                ],
		            ]
		        );
		        $repeater->add_responsive_control( 'shape_two_offset_x',
		            [
		                'label' => esc_html__( 'Offset X Shape Two', 'slide-addon-for-elementer' ),
		                'type' => \Elementor\Controls_Manager::SLIDER,
		                'size_units' => [ 'px', '%' ],
		                'range' => [
		                    'px' => [
		                        'min' => -2000,
		                        'max' => 2000,
		                        'step' => 1,
		                    ],
		                    '%' => [
		                        'min' => -200,
		                        'max' => 200,
		                    ],
		                ],	                
		                'default' => [
							'unit' => 'px',
							'size' => 0,
						],
		                'selectors' => [
		                    '{{WRAPPER}} {{CURRENT_ITEM}}_shape_two' => 'left: {{SIZE}}{{UNIT}}',
		                ],
		                'condition' => [
		                    'shape_two[url]!' => '',
		                ],
		            ]
		        );
		        $repeater->add_responsive_control( 'shape_two_offset_y',
		            [
		                'label' => esc_html__( 'Offset Y Shape Two', 'slide-addon-for-elementer' ),
		                'type' => \Elementor\Controls_Manager::SLIDER,
		                'size_units' => [ 'px', '%' ],
		                'range' => [
		                    'px' => [
		                        'min' => -2000,
		                        'max' => 2000,
		                        'step' => 1,
		                    ],
		                    '%' => [
		                        'min' => -200,
		                        'max' => 200,
		                    ],
		                ],	                
		                'default' => [
							'unit' => 'px',
							'size' => 0,
						],
		                'selectors' => [
		                    '{{WRAPPER}} {{CURRENT_ITEM}}_shape_two' => 'top: {{SIZE}}{{UNIT}}',
		                ],
		                'condition' => [
		                    'shape_two[url]!' => '',
		                ],
		            ]
		        );
		        $repeater->add_control( 'shape_two_animation',
		            [
		                'label' => esc_html__( 'Shape Two Animation', 'slide-addon-for-elementer' ),
		                'type' => \Elementor\Controls_Manager::SELECT,
		                'default' => '',
		                'options' => [
		                	'' => esc_html__( 'None', 'slide-addon-for-elementer' ),
		                	'vivify fadeInUp_tf' => esc_html__( 'Fade In Up', 'slide-addon-for-elementer' ),
		                	'vivify fadeInDown_tf' => esc_html__( 'Fade In Down', 'slide-addon-for-elementer' ),
		                	'vivify fadeInLeft_tf' => esc_html__( 'Fade In Left', 'slide-addon-for-elementer' ),
		                	'vivify fadeInRight_tf' => esc_html__( 'Fade In Right', 'slide-addon-for-elementer' ),
		                	'fromTop' => esc_html__( 'From Top', 'slide-addon-for-elementer' ),
		                    'fromBottom' => esc_html__( 'From Bottom', 'slide-addon-for-elementer' ),
		                    'fromLeft' => esc_html__( 'From Left', 'slide-addon-for-elementer' ),
		                    'fromRight' => esc_html__( 'From Right', 'slide-addon-for-elementer' ),
		                    'vivify pulsate' => esc_html__( 'Pulsate', 'slide-addon-for-elementer' ),
		                    'vivify ball' => esc_html__( 'Ball', 'slide-addon-for-elementer' ),
		                    'vivify pullUp' => esc_html__( 'Pull Up', 'slide-addon-for-elementer' ),
		                    'vivify pullDown' => esc_html__( 'Pull Down', 'slide-addon-for-elementer' ),
		                    'vivify pullLeft' => esc_html__( 'Pull Left', 'slide-addon-for-elementer' ),
		                    'vivify pullRight' => esc_html__( 'Pull Right', 'slide-addon-for-elementer' ),
		                    'vivify jumpInLeft' => esc_html__( 'Jump In Left', 'slide-addon-for-elementer' ),
		                    'vivify jumpInRight' => esc_html__( 'Jump In Right', 'slide-addon-for-elementer' ),
		                    'vivify rollInLeft' => esc_html__( 'Roll In Left', 'slide-addon-for-elementer' ),
		                    'vivify rollInRight' => esc_html__( 'Roll In Right', 'slide-addon-for-elementer' ),
		                    'vivify rollInTop' => esc_html__( 'Roll In Top', 'slide-addon-for-elementer' ),
		                    'vivify rollInBottom' => esc_html__( 'Roll In Bottom', 'slide-addon-for-elementer' ),
		                    'vivify popIn' => esc_html__( 'Pop In', 'slide-addon-for-elementer' ),
		                    'vivify popInLeft' => esc_html__( 'Pop In Left', 'slide-addon-for-elementer' ),
		                    'vivify popInRight' => esc_html__( 'Pop In Right', 'slide-addon-for-elementer' ),
		                    'vivify popInTop' => esc_html__( 'Pop In Top', 'slide-addon-for-elementer' ),
		                    'vivify popInBottom' => esc_html__( 'Pop In Bottom', 'slide-addon-for-elementer' ),
		                    'vivify swoopInLeft' => esc_html__( 'Swoop In Left', 'slide-addon-for-elementer' ),
		                    'vivify swoopInRight' => esc_html__( 'Swoop In Right', 'slide-addon-for-elementer' ),
		                    'vivify swoopInTop' => esc_html__( 'Swoop In Top', 'slide-addon-for-elementer' ),
		                    'vivify swoopInBottom' => esc_html__( 'Swoop In Bottom', 'slide-addon-for-elementer' ),
		                    'vivify flip' => esc_html__( 'Flip', 'slide-addon-for-elementer' ),
		                    'vivify spin' => esc_html__( 'Spin', 'slide-addon-for-elementer' ),
		                ],
		                'condition' => [
		                    'shape_two[url]!' => '',
		                ]
		            ]
		        );
		        $repeater->add_control( 'shape_two_delay',
		            [
		                'label' => esc_html__( 'Shape Two Delay', 'slide-addon-for-elementer' ),
		                'type' => \Elementor\Controls_Manager::SELECT,
		                'default' => '',
		                'options' => [
		                	'' => esc_html__( 'None', 'slide-addon-for-elementer' ),
		                	'captionDelay1' => esc_html__( 'Delay 0.1 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay2' => esc_html__( 'Delay 0.2 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay3' => esc_html__( 'Delay 0.3 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay4' => esc_html__( 'Delay 0.4 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay5' => esc_html__( 'Delay 0.5 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay6' => esc_html__( 'Delay 0.6 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay7' => esc_html__( 'Delay 0.7 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay8' => esc_html__( 'Delay 0.8 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay9' => esc_html__( 'Delay 0.9 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay10' => esc_html__( 'Delay 1 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay11' => esc_html__( 'Delay 1.1 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay12' => esc_html__( 'Delay 1.2 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay13' => esc_html__( 'Delay 1.3 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay14' => esc_html__( 'Delay 1.4 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay15' => esc_html__( 'Delay 1.5 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay16' => esc_html__( 'Delay 1.6 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay17' => esc_html__( 'Delay 1.7 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay18' => esc_html__( 'Delay 1.8 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay19' => esc_html__( 'Delay 1.9 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay20' => esc_html__( 'Delay 2 Seconds', 'slide-addon-for-elementer' ),
		                ],
		                'condition' => [
		                    'shape_two[url]!' => '',
		                ]
		            ]
		        );
		        $repeater->add_control( 'hr_shape_two',
					[
						'type' => \Elementor\Controls_Manager::DIVIDER,
						'condition' => [
		                    'shape_two[url]!' => '',
		                ]
					]
				);
				$repeater->add_control( 'shape_three', 
		        	[
		                'label' => esc_html__( 'Shape Three', 'slide-addon-for-elementer' ),
		                'type' => \Elementor\Controls_Manager::MEDIA
		            ]
		        );
		        $repeater->add_control( 'index_shape_three',
					[
						'label' => esc_html__( 'z-index Shape Three', 'slide-addon-for-elementer' ),
						'type' => \Elementor\Controls_Manager::NUMBER,
						'min' => -1,
						'max' => 1000,
						'step' => 1,
						'selectors' => [
		                    '{{WRAPPER}} {{CURRENT_ITEM}}_shape_three' => 'z-index: {{SIZE}}',
		                ],
		                'condition' => [
		                    'shape_three[url]!' => '',
		                ],
					]
				);
		        $repeater->add_responsive_control( 'shape_three_width',
		            [
		                'label' => esc_html__( 'Width Shape Three', 'slide-addon-for-elementer' ),
		                'type' => \Elementor\Controls_Manager::SLIDER,
		                'size_units' => [ 'px', '%' ],
		                'range' => [
		                    'px' => [
		                        'min' => 0,
		                        'max' => 2000,
		                        'step' => 1,
		                    ],
		                    '%' => [
		                        'min' => 0,
		                        'max' => 100,
		                    ],
		                ],
		                'selectors' => [
		                    '{{WRAPPER}} {{CURRENT_ITEM}}_shape_three' => 'width: {{SIZE}}{{UNIT}}',
		                ],
		                'condition' => [
		                    'shape_three[url]!' => '',
		                ],
		            ]
		        );
		        $repeater->add_responsive_control( 'shape_three_height',
		            [
		                'label' => esc_html__( 'Height Shape Three', 'slide-addon-for-elementer' ),
		                'type' => \Elementor\Controls_Manager::SLIDER,
		                'size_units' => [ 'px', '%' ],
		                'range' => [
		                    'px' => [
		                        'min' => 0,
		                        'max' => 2000,
		                        'step' => 1,
		                    ],
		                    '%' => [
		                        'min' => 0,
		                        'max' => 100,
		                    ],
		                ],
		                'selectors' => [
		                    '{{WRAPPER}} {{CURRENT_ITEM}}_shape_three' => 'height: {{SIZE}}{{UNIT}}',
		                ],
		                'condition' => [
		                    'shape_three[url]!' => '',
		                ],
		            ]
		        );
		        $repeater->add_responsive_control( 'shape_three_offset_x',
		            [
		                'label' => esc_html__( 'Offset X Shape Three', 'slide-addon-for-elementer' ),
		                'type' => \Elementor\Controls_Manager::SLIDER,
		                'size_units' => [ 'px', '%' ],
		                'range' => [
		                    'px' => [
		                        'min' => -2000,
		                        'max' => 2000,
		                        'step' => 1,
		                    ],
		                    '%' => [
		                        'min' => -200,
		                        'max' => 200,
		                    ],
		                ],	                
		                'default' => [
							'unit' => 'px',
							'size' => 0,
						],
		                'selectors' => [
		                    '{{WRAPPER}} {{CURRENT_ITEM}}_shape_three' => 'left: {{SIZE}}{{UNIT}}',
		                ],
		                'condition' => [
		                    'shape_three[url]!' => '',
		                ],
		            ]
		        );
		        $repeater->add_responsive_control( 'shape_three_offset_y',
		            [
		                'label' => esc_html__( 'Offset Y Shape Three', 'slide-addon-for-elementer' ),
		                'type' => \Elementor\Controls_Manager::SLIDER,
		                'size_units' => [ 'px', '%' ],
		                'range' => [
		                    'px' => [
		                        'min' => -2000,
		                        'max' => 2000,
		                        'step' => 1,
		                    ],
		                    '%' => [
		                        'min' => -200,
		                        'max' => 200,
		                    ],
		                ],	                
		                'default' => [
							'unit' => 'px',
							'size' => 0,
						],
		                'selectors' => [
		                    '{{WRAPPER}} {{CURRENT_ITEM}}_shape_three' => 'top: {{SIZE}}{{UNIT}}',
		                ],
		                'condition' => [
		                    'shape_three[url]!' => '',
		                ],
		            ]
		        );
		        $repeater->add_control( 'shape_three_animation',
		            [
		                'label' => esc_html__( 'Shape Three Animation', 'slide-addon-for-elementer' ),
		                'type' => \Elementor\Controls_Manager::SELECT,
		                'default' => '',
		                'options' => [
		                	'' => esc_html__( 'None', 'slide-addon-for-elementer' ),
		                	'vivify fadeInUp_tf' => esc_html__( 'Fade In Up', 'slide-addon-for-elementer' ),
		                	'vivify fadeInDown_tf' => esc_html__( 'Fade In Down', 'slide-addon-for-elementer' ),
		                	'vivify fadeInLeft_tf' => esc_html__( 'Fade In Left', 'slide-addon-for-elementer' ),
		                	'vivify fadeInRight_tf' => esc_html__( 'Fade In Right', 'slide-addon-for-elementer' ),
		                	'fromTop' => esc_html__( 'From Top', 'slide-addon-for-elementer' ),
		                    'fromBottom' => esc_html__( 'From Bottom', 'slide-addon-for-elementer' ),
		                    'fromLeft' => esc_html__( 'From Left', 'slide-addon-for-elementer' ),
		                    'fromRight' => esc_html__( 'From Right', 'slide-addon-for-elementer' ),
		                    'vivify pulsate' => esc_html__( 'Pulsate', 'slide-addon-for-elementer' ),
		                    'vivify ball' => esc_html__( 'Ball', 'slide-addon-for-elementer' ),
		                    'vivify pullUp' => esc_html__( 'Pull Up', 'slide-addon-for-elementer' ),
		                    'vivify pullDown' => esc_html__( 'Pull Down', 'slide-addon-for-elementer' ),
		                    'vivify pullLeft' => esc_html__( 'Pull Left', 'slide-addon-for-elementer' ),
		                    'vivify pullRight' => esc_html__( 'Pull Right', 'slide-addon-for-elementer' ),
		                    'vivify jumpInLeft' => esc_html__( 'Jump In Left', 'slide-addon-for-elementer' ),
		                    'vivify jumpInRight' => esc_html__( 'Jump In Right', 'slide-addon-for-elementer' ),
		                    'vivify rollInLeft' => esc_html__( 'Roll In Left', 'slide-addon-for-elementer' ),
		                    'vivify rollInRight' => esc_html__( 'Roll In Right', 'slide-addon-for-elementer' ),
		                    'vivify rollInTop' => esc_html__( 'Roll In Top', 'slide-addon-for-elementer' ),
		                    'vivify rollInBottom' => esc_html__( 'Roll In Bottom', 'slide-addon-for-elementer' ),
		                    'vivify popIn' => esc_html__( 'Pop In', 'slide-addon-for-elementer' ),
		                    'vivify popInLeft' => esc_html__( 'Pop In Left', 'slide-addon-for-elementer' ),
		                    'vivify popInRight' => esc_html__( 'Pop In Right', 'slide-addon-for-elementer' ),
		                    'vivify popInTop' => esc_html__( 'Pop In Top', 'slide-addon-for-elementer' ),
		                    'vivify popInBottom' => esc_html__( 'Pop In Bottom', 'slide-addon-for-elementer' ),
		                    'vivify swoopInLeft' => esc_html__( 'Swoop In Left', 'slide-addon-for-elementer' ),
		                    'vivify swoopInRight' => esc_html__( 'Swoop In Right', 'slide-addon-for-elementer' ),
		                    'vivify swoopInTop' => esc_html__( 'Swoop In Top', 'slide-addon-for-elementer' ),
		                    'vivify swoopInBottom' => esc_html__( 'Swoop In Bottom', 'slide-addon-for-elementer' ),
		                    'vivify flip' => esc_html__( 'Flip', 'slide-addon-for-elementer' ),
		                    'vivify spin' => esc_html__( 'Spin', 'slide-addon-for-elementer' ),
		                ],
		                'condition' => [
		                    'shape_three[url]!' => '',
		                ]
		            ]
		        );
		        $repeater->add_control( 'shape_three_delay',
		            [
		                'label' => esc_html__( 'Shape Three Delay', 'slide-addon-for-elementer' ),
		                'type' => \Elementor\Controls_Manager::SELECT,
		                'default' => '',
		                'options' => [
		                	'' => esc_html__( 'None', 'slide-addon-for-elementer' ),
		                	'captionDelay1' => esc_html__( 'Delay 0.1 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay2' => esc_html__( 'Delay 0.2 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay3' => esc_html__( 'Delay 0.3 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay4' => esc_html__( 'Delay 0.4 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay5' => esc_html__( 'Delay 0.5 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay6' => esc_html__( 'Delay 0.6 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay7' => esc_html__( 'Delay 0.7 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay8' => esc_html__( 'Delay 0.8 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay9' => esc_html__( 'Delay 0.9 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay10' => esc_html__( 'Delay 1 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay11' => esc_html__( 'Delay 1.1 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay12' => esc_html__( 'Delay 1.2 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay13' => esc_html__( 'Delay 1.3 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay14' => esc_html__( 'Delay 1.4 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay15' => esc_html__( 'Delay 1.5 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay16' => esc_html__( 'Delay 1.6 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay17' => esc_html__( 'Delay 1.7 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay18' => esc_html__( 'Delay 1.8 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay19' => esc_html__( 'Delay 1.9 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay20' => esc_html__( 'Delay 2 Seconds', 'slide-addon-for-elementer' ),
		                ],
		                'condition' => [
		                    'shape_three[url]!' => '',
		                ]
		            ]
		        );
		        $repeater->add_control( 'hr_shape_three',
					[
						'type' => \Elementor\Controls_Manager::DIVIDER,
						'condition' => [
		                    'shape_three[url]!' => '',
		                ]
					]
				);
				$repeater->add_control( 'shape_four', 
		        	[
		                'label' => esc_html__( 'Shape Four', 'slide-addon-for-elementer' ),
		                'type' => \Elementor\Controls_Manager::MEDIA
		            ]
		        );
		        $repeater->add_control( 'index_shape_four',
					[
						'label' => esc_html__( 'z-index Shape Four', 'slide-addon-for-elementer' ),
						'type' => \Elementor\Controls_Manager::NUMBER,
						'min' => -1,
						'max' => 1000,
						'step' => 1,
						'selectors' => [
		                    '{{WRAPPER}} {{CURRENT_ITEM}}_shape_four' => 'z-index: {{SIZE}}',
		                ],
		                'condition' => [
		                    'shape_four[url]!' => '',
		                ],
					]
				);
		        $repeater->add_responsive_control( 'shape_four_width',
		            [
		                'label' => esc_html__( 'Width Shape Four', 'slide-addon-for-elementer' ),
		                'type' => \Elementor\Controls_Manager::SLIDER,
		                'size_units' => [ 'px', '%' ],
		                'range' => [
		                    'px' => [
		                        'min' => 0,
		                        'max' => 2000,
		                        'step' => 1,
		                    ],
		                    '%' => [
		                        'min' => 0,
		                        'max' => 100,
		                    ],
		                ],
		                'selectors' => [
		                    '{{WRAPPER}} {{CURRENT_ITEM}}_shape_four' => 'width: {{SIZE}}{{UNIT}}',
		                ],
		                'condition' => [
		                    'shape_four[url]!' => '',
		                ],
		            ]
		        );
		        $repeater->add_responsive_control( 'shape_four_height',
		            [
		                'label' => esc_html__( 'Height Shape Four', 'slide-addon-for-elementer' ),
		                'type' => \Elementor\Controls_Manager::SLIDER,
		                'size_units' => [ 'px', '%' ],
		                'range' => [
		                    'px' => [
		                        'min' => 0,
		                        'max' => 2000,
		                        'step' => 1,
		                    ],
		                    '%' => [
		                        'min' => 0,
		                        'max' => 100,
		                    ],
		                ],
		                'selectors' => [
		                    '{{WRAPPER}} {{CURRENT_ITEM}}_shape_four' => 'height: {{SIZE}}{{UNIT}}',
		                ],
		                'condition' => [
		                    'shape_four[url]!' => '',
		                ],
		            ]
		        );
		        $repeater->add_responsive_control( 'shape_four_offset_x',
		            [
		                'label' => esc_html__( 'Offset X Shape Four', 'slide-addon-for-elementer' ),
		                'type' => \Elementor\Controls_Manager::SLIDER,
		                'size_units' => [ 'px', '%' ],
		                'range' => [
		                    'px' => [
		                        'min' => -2000,
		                        'max' => 2000,
		                        'step' => 1,
		                    ],
		                    '%' => [
		                        'min' => -200,
		                        'max' => 200,
		                    ],
		                ],	                
		                'default' => [
							'unit' => 'px',
							'size' => 0,
						],
		                'selectors' => [
		                    '{{WRAPPER}} {{CURRENT_ITEM}}_shape_four' => 'left: {{SIZE}}{{UNIT}}',
		                ],
		                'condition' => [
		                    'shape_four[url]!' => '',
		                ],
		            ]
		        );
		        $repeater->add_responsive_control( 'shape_four_offset_y',
		            [
		                'label' => esc_html__( 'Offset Y Shape Four', 'slide-addon-for-elementer' ),
		                'type' => \Elementor\Controls_Manager::SLIDER,
		                'size_units' => [ 'px', '%' ],
		                'range' => [
		                    'px' => [
		                        'min' => -2000,
		                        'max' => 2000,
		                        'step' => 1,
		                    ],
		                    '%' => [
		                        'min' => -200,
		                        'max' => 200,
		                    ],
		                ],	                
		                'default' => [
							'unit' => 'px',
							'size' => 0,
						],
		                'selectors' => [
		                    '{{WRAPPER}} {{CURRENT_ITEM}}_shape_four' => 'top: {{SIZE}}{{UNIT}}',
		                ],
		                'condition' => [
		                    'shape_four[url]!' => '',
		                ],
		            ]
		        );
		        $repeater->add_control( 'shape_four_animation',
		            [
		                'label' => esc_html__( 'Shape Four Animation', 'slide-addon-for-elementer' ),
		                'type' => \Elementor\Controls_Manager::SELECT,
		                'default' => '',
		                'options' => [
		                	'' => esc_html__( 'None', 'slide-addon-for-elementer' ),
		                	'vivify fadeInUp_tf' => esc_html__( 'Fade In Up', 'slide-addon-for-elementer' ),
		                	'vivify fadeInDown_tf' => esc_html__( 'Fade In Down', 'slide-addon-for-elementer' ),
		                	'vivify fadeInLeft_tf' => esc_html__( 'Fade In Left', 'slide-addon-for-elementer' ),
		                	'vivify fadeInRight_tf' => esc_html__( 'Fade In Right', 'slide-addon-for-elementer' ),
		                	'fromTop' => esc_html__( 'From Top', 'slide-addon-for-elementer' ),
		                    'fromBottom' => esc_html__( 'From Bottom', 'slide-addon-for-elementer' ),
		                    'fromLeft' => esc_html__( 'From Left', 'slide-addon-for-elementer' ),
		                    'fromRight' => esc_html__( 'From Right', 'slide-addon-for-elementer' ),
		                    'vivify pulsate' => esc_html__( 'Pulsate', 'slide-addon-for-elementer' ),
		                    'vivify ball' => esc_html__( 'Ball', 'slide-addon-for-elementer' ),
		                    'vivify pullUp' => esc_html__( 'Pull Up', 'slide-addon-for-elementer' ),
		                    'vivify pullDown' => esc_html__( 'Pull Down', 'slide-addon-for-elementer' ),
		                    'vivify pullLeft' => esc_html__( 'Pull Left', 'slide-addon-for-elementer' ),
		                    'vivify pullRight' => esc_html__( 'Pull Right', 'slide-addon-for-elementer' ),
		                    'vivify jumpInLeft' => esc_html__( 'Jump In Left', 'slide-addon-for-elementer' ),
		                    'vivify jumpInRight' => esc_html__( 'Jump In Right', 'slide-addon-for-elementer' ),
		                    'vivify rollInLeft' => esc_html__( 'Roll In Left', 'slide-addon-for-elementer' ),
		                    'vivify rollInRight' => esc_html__( 'Roll In Right', 'slide-addon-for-elementer' ),
		                    'vivify rollInTop' => esc_html__( 'Roll In Top', 'slide-addon-for-elementer' ),
		                    'vivify rollInBottom' => esc_html__( 'Roll In Bottom', 'slide-addon-for-elementer' ),
		                    'vivify popIn' => esc_html__( 'Pop In', 'slide-addon-for-elementer' ),
		                    'vivify popInLeft' => esc_html__( 'Pop In Left', 'slide-addon-for-elementer' ),
		                    'vivify popInRight' => esc_html__( 'Pop In Right', 'slide-addon-for-elementer' ),
		                    'vivify popInTop' => esc_html__( 'Pop In Top', 'slide-addon-for-elementer' ),
		                    'vivify popInBottom' => esc_html__( 'Pop In Bottom', 'slide-addon-for-elementer' ),
		                    'vivify swoopInLeft' => esc_html__( 'Swoop In Left', 'slide-addon-for-elementer' ),
		                    'vivify swoopInRight' => esc_html__( 'Swoop In Right', 'slide-addon-for-elementer' ),
		                    'vivify swoopInTop' => esc_html__( 'Swoop In Top', 'slide-addon-for-elementer' ),
		                    'vivify swoopInBottom' => esc_html__( 'Swoop In Bottom', 'slide-addon-for-elementer' ),
		                    'vivify flip' => esc_html__( 'Flip', 'slide-addon-for-elementer' ),
		                    'vivify spin' => esc_html__( 'Spin', 'slide-addon-for-elementer' ),
		                ],
		                'condition' => [
		                    'shape_four[url]!' => '',
		                ]
		            ]
		        );
		        $repeater->add_control( 'shape_four_delay',
		            [
		                'label' => esc_html__( 'Shape Four Delay', 'slide-addon-for-elementer' ),
		                'type' => \Elementor\Controls_Manager::SELECT,
		                'default' => '',
		                'options' => [
		                	'' => esc_html__( 'None', 'slide-addon-for-elementer' ),
		                	'captionDelay1' => esc_html__( 'Delay 0.1 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay2' => esc_html__( 'Delay 0.2 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay3' => esc_html__( 'Delay 0.3 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay4' => esc_html__( 'Delay 0.4 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay5' => esc_html__( 'Delay 0.5 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay6' => esc_html__( 'Delay 0.6 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay7' => esc_html__( 'Delay 0.7 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay8' => esc_html__( 'Delay 0.8 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay9' => esc_html__( 'Delay 0.9 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay10' => esc_html__( 'Delay 1 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay11' => esc_html__( 'Delay 1.1 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay12' => esc_html__( 'Delay 1.2 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay13' => esc_html__( 'Delay 1.3 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay14' => esc_html__( 'Delay 1.4 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay15' => esc_html__( 'Delay 1.5 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay16' => esc_html__( 'Delay 1.6 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay17' => esc_html__( 'Delay 1.7 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay18' => esc_html__( 'Delay 1.8 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay19' => esc_html__( 'Delay 1.9 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay20' => esc_html__( 'Delay 2 Seconds', 'slide-addon-for-elementer' ),
		                ],
		                'condition' => [
		                    'shape_four[url]!' => '',
		                ]
		            ]
		        );
		        $repeater->add_control( 'hr_shape_four',
					[
						'type' => \Elementor\Controls_Manager::DIVIDER,
						'condition' => [
		                    'shape_four[url]!' => '',
		                ]
					]
				);
				$repeater->add_control( 'shape_five', 
		        	[
		                'label' => esc_html__( 'Shape Five', 'slide-addon-for-elementer' ),
		                'type' => \Elementor\Controls_Manager::MEDIA
		            ]
		        );
		        $repeater->add_control( 'index_shape_five',
					[
						'label' => esc_html__( 'z-index Shape Five', 'slide-addon-for-elementer' ),
						'type' => \Elementor\Controls_Manager::NUMBER,
						'min' => -1,
						'max' => 1000,
						'step' => 1,
						'selectors' => [
		                    '{{WRAPPER}} {{CURRENT_ITEM}}_shape_five' => 'z-index: {{SIZE}}',
		                ],
		                'condition' => [
		                    'shape_five[url]!' => '',
		                ],
					]
				);
		        $repeater->add_responsive_control( 'shape_five_width',
		            [
		                'label' => esc_html__( 'Width Shape Five', 'slide-addon-for-elementer' ),
		                'type' => \Elementor\Controls_Manager::SLIDER,
		                'size_units' => [ 'px', '%' ],
		                'range' => [
		                    'px' => [
		                        'min' => 0,
		                        'max' => 2000,
		                        'step' => 1,
		                    ],
		                    '%' => [
		                        'min' => 0,
		                        'max' => 100,
		                    ],
		                ],
		                'selectors' => [
		                    '{{WRAPPER}} {{CURRENT_ITEM}}_shape_five' => 'width: {{SIZE}}{{UNIT}}',
		                ],
		                'condition' => [
		                    'shape_five[url]!' => '',
		                ],
		            ]
		        );
		        $repeater->add_responsive_control( 'shape_five_height',
		            [
		                'label' => esc_html__( 'Height Shape Five', 'slide-addon-for-elementer' ),
		                'type' => \Elementor\Controls_Manager::SLIDER,
		                'size_units' => [ 'px', '%' ],
		                'range' => [
		                    'px' => [
		                        'min' => 0,
		                        'max' => 2000,
		                        'step' => 1,
		                    ],
		                    '%' => [
		                        'min' => 0,
		                        'max' => 100,
		                    ],
		                ],
		                'selectors' => [
		                    '{{WRAPPER}} {{CURRENT_ITEM}}_shape_five' => 'height: {{SIZE}}{{UNIT}}',
		                ],
		                'condition' => [
		                    'shape_five[url]!' => '',
		                ],
		            ]
		        );
		        $repeater->add_responsive_control( 'shape_five_offset_x',
		            [
		                'label' => esc_html__( 'Offset X Shape Five', 'slide-addon-for-elementer' ),
		                'type' => \Elementor\Controls_Manager::SLIDER,
		                'size_units' => [ 'px', '%' ],
		                'range' => [
		                    'px' => [
		                        'min' => -2000,
		                        'max' => 2000,
		                        'step' => 1,
		                    ],
		                    '%' => [
		                        'min' => -200,
		                        'max' => 200,
		                    ],
		                ],	                
		                'default' => [
							'unit' => 'px',
							'size' => 0,
						],
		                'selectors' => [
		                    '{{WRAPPER}} {{CURRENT_ITEM}}_shape_five' => 'left: {{SIZE}}{{UNIT}}',
		                ],
		                'condition' => [
		                    'shape_five[url]!' => '',
		                ],
		            ]
		        );
		        $repeater->add_responsive_control( 'shape_five_offset_y',
		            [
		                'label' => esc_html__( 'Offset Y Shape Five', 'slide-addon-for-elementer' ),
		                'type' => \Elementor\Controls_Manager::SLIDER,
		                'size_units' => [ 'px', '%' ],
		                'range' => [
		                    'px' => [
		                        'min' => -2000,
		                        'max' => 2000,
		                        'step' => 1,
		                    ],
		                    '%' => [
		                        'min' => -200,
		                        'max' => 200,
		                    ],
		                ],	                
		                'default' => [
							'unit' => 'px',
							'size' => 0,
						],
		                'selectors' => [
		                    '{{WRAPPER}} {{CURRENT_ITEM}}_shape_five' => 'top: {{SIZE}}{{UNIT}}',
		                ],
		                'condition' => [
		                    'shape_five[url]!' => '',
		                ],
		            ]
		        );
		        $repeater->add_control( 'shape_five_animation',
		            [
		                'label' => esc_html__( 'Shape Five Animation', 'slide-addon-for-elementer' ),
		                'type' => \Elementor\Controls_Manager::SELECT,
		                'default' => '',
		                'options' => [
		                	'' => esc_html__( 'None', 'slide-addon-for-elementer' ),
		                	'vivify fadeInUp_tf' => esc_html__( 'Fade In Up', 'slide-addon-for-elementer' ),
		                	'vivify fadeInDown_tf' => esc_html__( 'Fade In Down', 'slide-addon-for-elementer' ),
		                	'vivify fadeInLeft_tf' => esc_html__( 'Fade In Left', 'slide-addon-for-elementer' ),
		                	'vivify fadeInRight_tf' => esc_html__( 'Fade In Right', 'slide-addon-for-elementer' ),
		                	'fromTop' => esc_html__( 'From Top', 'slide-addon-for-elementer' ),
		                    'fromBottom' => esc_html__( 'From Bottom', 'slide-addon-for-elementer' ),
		                    'fromLeft' => esc_html__( 'From Left', 'slide-addon-for-elementer' ),
		                    'fromRight' => esc_html__( 'From Right', 'slide-addon-for-elementer' ),
		                    'vivify pulsate' => esc_html__( 'Pulsate', 'slide-addon-for-elementer' ),
		                    'vivify ball' => esc_html__( 'Ball', 'slide-addon-for-elementer' ),
		                    'vivify pullUp' => esc_html__( 'Pull Up', 'slide-addon-for-elementer' ),
		                    'vivify pullDown' => esc_html__( 'Pull Down', 'slide-addon-for-elementer' ),
		                    'vivify pullLeft' => esc_html__( 'Pull Left', 'slide-addon-for-elementer' ),
		                    'vivify pullRight' => esc_html__( 'Pull Right', 'slide-addon-for-elementer' ),
		                    'vivify jumpInLeft' => esc_html__( 'Jump In Left', 'slide-addon-for-elementer' ),
		                    'vivify jumpInRight' => esc_html__( 'Jump In Right', 'slide-addon-for-elementer' ),
		                    'vivify rollInLeft' => esc_html__( 'Roll In Left', 'slide-addon-for-elementer' ),
		                    'vivify rollInRight' => esc_html__( 'Roll In Right', 'slide-addon-for-elementer' ),
		                    'vivify rollInTop' => esc_html__( 'Roll In Top', 'slide-addon-for-elementer' ),
		                    'vivify rollInBottom' => esc_html__( 'Roll In Bottom', 'slide-addon-for-elementer' ),
		                    'vivify popIn' => esc_html__( 'Pop In', 'slide-addon-for-elementer' ),
		                    'vivify popInLeft' => esc_html__( 'Pop In Left', 'slide-addon-for-elementer' ),
		                    'vivify popInRight' => esc_html__( 'Pop In Right', 'slide-addon-for-elementer' ),
		                    'vivify popInTop' => esc_html__( 'Pop In Top', 'slide-addon-for-elementer' ),
		                    'vivify popInBottom' => esc_html__( 'Pop In Bottom', 'slide-addon-for-elementer' ),
		                    'vivify swoopInLeft' => esc_html__( 'Swoop In Left', 'slide-addon-for-elementer' ),
		                    'vivify swoopInRight' => esc_html__( 'Swoop In Right', 'slide-addon-for-elementer' ),
		                    'vivify swoopInTop' => esc_html__( 'Swoop In Top', 'slide-addon-for-elementer' ),
		                    'vivify swoopInBottom' => esc_html__( 'Swoop In Bottom', 'slide-addon-for-elementer' ),
		                    'vivify flip' => esc_html__( 'Flip', 'slide-addon-for-elementer' ),
		                    'vivify spin' => esc_html__( 'Spin', 'slide-addon-for-elementer' ),
		                ],
		                'condition' => [
		                    'shape_five[url]!' => '',
		                ]
		            ]
		        );
		        $repeater->add_control( 'shape_five_delay',
		            [
		                'label' => esc_html__( 'Shape Five Delay', 'slide-addon-for-elementer' ),
		                'type' => \Elementor\Controls_Manager::SELECT,
		                'default' => '',
		                'options' => [
		                	'' => esc_html__( 'None', 'slide-addon-for-elementer' ),
		                	'captionDelay1' => esc_html__( 'Delay 0.1 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay2' => esc_html__( 'Delay 0.2 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay3' => esc_html__( 'Delay 0.3 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay4' => esc_html__( 'Delay 0.4 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay5' => esc_html__( 'Delay 0.5 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay6' => esc_html__( 'Delay 0.6 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay7' => esc_html__( 'Delay 0.7 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay8' => esc_html__( 'Delay 0.8 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay9' => esc_html__( 'Delay 0.9 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay10' => esc_html__( 'Delay 1 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay11' => esc_html__( 'Delay 1.1 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay12' => esc_html__( 'Delay 1.2 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay13' => esc_html__( 'Delay 1.3 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay14' => esc_html__( 'Delay 1.4 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay15' => esc_html__( 'Delay 1.5 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay16' => esc_html__( 'Delay 1.6 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay17' => esc_html__( 'Delay 1.7 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay18' => esc_html__( 'Delay 1.8 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay19' => esc_html__( 'Delay 1.9 Seconds', 'slide-addon-for-elementer' ),
		                	'captionDelay20' => esc_html__( 'Delay 2 Seconds', 'slide-addon-for-elementer' ),
		                ],
		                'condition' => [
		                    'shape_five[url]!' => '',
		                ]
		            ]
		        );
	        $this->add_control('flexslider_list',
	            [
	                'label'  => esc_html__('Images Slide','slide-addon-for-elementer'),
	                'type'   => \Elementor\Controls_Manager::REPEATER,
	                'fields' => $repeater->get_controls(),
	                'default' => [
	                    [ '' => '' ],
	                    [ '' => '' ]
	                ],
	                'title_field' => '{{{ title_text }}}',
	            ]
	        );
	        $this->end_controls_section();
        /* End Flex Slide Option*/

        /* Start Arrow Setting*/
			$this->start_controls_section('section_directionnav',
	            [
	                'label'         => esc_html__('Arrow Setting','slide-addon-for-elementer'),
	            ]
	        );
	        $this->add_control( 'directionnav',
	            [
	                'label'         => esc_html__( 'Arrow', 'slide-addon-for-elementer' ),
	                'type'          => \Elementor\Controls_Manager::SWITCHER,
	                'label_on'      => esc_html__( 'On', 'slide-addon-for-elementer' ),
	                'label_off'     => esc_html__( 'Off', 'slide-addon-for-elementer' ),
	                'return_value'  => 'true',
	                'default'       => 'true',
	            ]
	        );
	        $this->add_responsive_control( 'w_size_directionnav',
				[
					'label' => esc_html__( 'Width', 'slide-addon-for-elementer' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ 'px' ],
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 200,
							'step' => 1,
						]
					],
					'default' => [
						'unit' => 'px',
						'size' => 70,
					],
					'selectors' => [
						'{{WRAPPER}} .flexslider .flex-direction-nav a' => 'width: {{SIZE}}{{UNIT}};',
					],
					'condition' => [
	                    'directionnav' => 'true',
	                ]
				]
			);
			$this->add_responsive_control( 'h_size_directionnav',
				[
					'label' => esc_html__( 'Height', 'slide-addon-for-elementer' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ 'px' ],
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 200,
							'step' => 1,
						]
					],
					'default' => [
						'unit' => 'px',
						'size' => 70,
					],
					'selectors' => [
						'{{WRAPPER}} .flexslider .flex-direction-nav a' => 'height: {{SIZE}}{{UNIT}};line-height: {{SIZE}}{{UNIT}};',
					],
					'condition' => [
	                    'directionnav' => 'true',
	                ]
				]
			);
			$this->add_control( 'heading_directionnav_button_icon',
				[
					'label' => esc_html__( 'Icon', 'slide-addon-for-elementer' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'condition' => [
	                    'directionnav' => 'true',
	                ]
				]
			);
	    	$this->add_control( 'prev_icon', [
	                'label' => esc_html__( 'Icon Previous', 'slide-addon-for-elementer' ),
	                'type' => \Elementor\Controls_Manager::ICON,
	                'default' => 'fa fa-angle-left', 
	                'condition' => [
	                    'directionnav' => 'true',
	                ]
	            ]
	        );
	    	$this->add_control( 'next_icon', [
	                'label' => esc_html__( 'Icon Next', 'slide-addon-for-elementer' ),
	                'type' => \Elementor\Controls_Manager::ICON,
	                'default' => 'fa fa-angle-right', 
	                'condition' => [
	                    'directionnav' => 'true',
	                ]
	            ]
	        );
	        $this->add_responsive_control( 'fontsize_directionnav',
				[
					'label' => esc_html__( 'Icon Size', 'slide-addon-for-elementer' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ 'px' ],
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 100,
							'step' => 1,
						]
					],
					'default' => [
						'unit' => 'px',
						'size' => 40,
					],
					'selectors' => [
						'{{WRAPPER}} .flexslider .flex-direction-nav a,{{WRAPPER}} .flexslider .flex-direction-nav i' => 'font-size: {{SIZE}}{{UNIT}};',
					],
					'condition' => [
	                    'directionnav' => 'true',
	                ]
				]
			);
			$this->add_control( 'heading_directionnav_position_horizontal',
				[
					'label' => esc_html__( 'Horizontal Orientation', 'slide-addon-for-elementer' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'condition' => [
	                    'directionnav' => 'true',
	                ]
				]
			);
			$this->add_responsive_control( 'directionnav_horizontal_position_prev',
				[
					'label' => esc_html__( 'Offset Previous', 'slide-addon-for-elementer' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ 'px', '%' ],
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 2000,
							'step' => 1,
						],
						'%' => [
							'min' => 0,
							'max' => 100,
						],
					],
					'default' => [
						'unit' => 'px',
						'size' => 0,
					],
					'selectors' => [
						'{{WRAPPER}} .flexslider .flex-direction-nav a.flex-prev' => 'left: {{SIZE}}{{UNIT}};',
					],
					'condition' => [
	                    'directionnav' => 'true',
	                ]
				]
			);
			$this->add_responsive_control( 'directionnav_horizontal_position_next',
				[
					'label' => esc_html__( 'Offset Next', 'slide-addon-for-elementer' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ 'px', '%' ],
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 2000,
							'step' => 1,
						],
						'%' => [
							'min' => 0,
							'max' => 100,
						],
					],
					'default' => [
						'unit' => 'px',
						'size' => 0,
					],
					'selectors' => [
						'{{WRAPPER}} .flexslider .flex-direction-nav a.flex-next' => 'right: {{SIZE}}{{UNIT}};',
					],
					'condition' => [
	                    'directionnav' => 'true',
	                ]
				]
			);
			$this->add_control( 'heading_directionnav_position_vertical',
				[
					'label' => esc_html__( 'Vertical Orientation', 'slide-addon-for-elementer' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'condition' => [
	                    'directionnav' => 'true',
	                ]
				]
			);
			$this->add_responsive_control( 'directionnav_vertical_position',
				[
					'label' => esc_html__( 'Offset', 'slide-addon-for-elementer' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ 'px', '%' ],
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 1000,
							'step' => 1,
						],
						'%' => [
							'min' => 0,
							'max' => 100,
						],
					],
					'default' => [
						'unit' => '%',
						'size' => 50,
					],
					'selectors' => [
						'{{WRAPPER}} .flexslider .flex-direction-nav a.flex-prev' => 'top: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}} .flexslider .flex-direction-nav a.flex-next' => 'top: {{SIZE}}{{UNIT}};',
					],
					'condition' => [
	                    'directionnav' => 'true',
	                ]
				]
			);
			$this->start_controls_tabs( 'directionnav_tabs',
				[
					'condition' => [
	                    'directionnav' => 'true',
	                ]
				] );
				$this->start_controls_tab( 'directionnav_normal_tab',
					[
						'label' => esc_html__( 'Normal', 'slide-addon-for-elementer' ),						
					]
					);
					$this->add_control( 'directionnav_color',
			            [
			                'label' => esc_html__( 'Color', 'slide-addon-for-elementer' ),
			                'type' => \Elementor\Controls_Manager::COLOR,
			                'default' => '#ffffff',
			                'selectors' => [
								'{{WRAPPER}} .flexslider .flex-direction-nav a' => 'color: {{VALUE}}',
							],
							'condition' => [
			                    'directionnav' => 'true',
			                ]
			            ]
			        );
			        $this->add_control( 'directionnav_bg_color',
			            [
			                'label' => esc_html__( 'Background Color', 'slide-addon-for-elementer' ),
			                'type' => \Elementor\Controls_Manager::COLOR,
			                'default' => '#0080f0',
			                'selectors' => [
								'{{WRAPPER}} .flexslider .flex-direction-nav a' => 'background-color: {{VALUE}};',
							],
							'condition' => [
			                    'directionnav' => 'true',
			                ]
			            ]
			        );			        
			        $this->add_group_control( \Elementor\Group_Control_Border::get_type(),
						[
							'name' => 'directionnav_border',
							'label' => esc_html__( 'Border', 'slide-addon-for-elementer' ),
							'selector' => '{{WRAPPER}} .flexslider .flex-direction-nav a',
							'condition' => [
			                    'directionnav' => 'true',
			                ]
						]
					);
					$this->add_responsive_control( 'directionnav_border_radius_prev',
			            [
			                'label' => esc_html__( 'Border Radius Previous', 'slide-addon-for-elementer' ),
			                'type' => \Elementor\Controls_Manager::DIMENSIONS,
			                'size_units' => [ 'px', '%', 'em' ],
			                'selectors' => [
			                    '{{WRAPPER}} .flexslider .flex-direction-nav a.flex-prev' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			                ],
			                'condition' => [
			                    'directionnav' => 'true',
			                ]
			            ]
			        );
			        $this->add_responsive_control( 'directionnav_border_radius_next',
			            [
			                'label' => esc_html__( 'Border Radius Next', 'slide-addon-for-elementer' ),
			                'type' => \Elementor\Controls_Manager::DIMENSIONS,
			                'size_units' => [ 'px', '%', 'em' ],
			                'selectors' => [
			                    '{{WRAPPER}} .flexslider .flex-direction-nav a.flex-next' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			                ],
			                'condition' => [
			                    'directionnav' => 'true',
			                ]
			            ]
			        );
		        $this->end_controls_tab();
		        $this->start_controls_tab( 'directionnav_hover_tab',
					[
						'label' => esc_html__( 'Hover', 'slide-addon-for-elementer' ),
					]
					);
		        	$this->add_control( 'directionnav_color_hover',
			            [
			                'label' => esc_html__( 'Color', 'slide-addon-for-elementer' ),
			                'type' => \Elementor\Controls_Manager::COLOR,
			                'default' => '#ffffff',
			                'selectors' => [
								'{{WRAPPER}} .flexslider .flex-direction-nav a:hover' => 'color: {{VALUE}}',
							],
							'condition' => [
			                    'directionnav' => 'true',
			                ]
			            ]
			        );
			        $this->add_control( 'directionnav_hover_bg_color',
			            [
			                'label' => esc_html__( 'Background Color', 'slide-addon-for-elementer' ),
			                'type' => \Elementor\Controls_Manager::COLOR,
			                'default' => '#222222',
			                'selectors' => [
								'{{WRAPPER}} .flexslider .flex-direction-nav a:hover' => 'background-color: {{VALUE}};',
							],
							'condition' => [
			                    'directionnav' => 'true',
			                ]
			            ]
			        );
			        $this->add_control( 'directionnav_hover_border_color',
			            [
			                'label' => esc_html__( 'Border Color', 'slide-addon-for-elementer' ),
			                'type' => \Elementor\Controls_Manager::COLOR,
			                'default' => '',
			                'selectors' => [
								'{{WRAPPER}} .flexslider .flex-direction-nav a:hover' => 'border-color: {{VALUE}};',
							],
							'condition' => [
			                    'directionnav' => 'true',
			                ]
			            ]
			        );
		       	$this->end_controls_tab();
	        $this->end_controls_tabs();
			$this->end_controls_section();
        /* End Arrow Setting*/

        /* Start Bullets Setting*/
			$this->start_controls_section('section_controlnav',
	            [
	                'label'         => esc_html__('Bullets Setting','slide-addon-for-elementer'),
	            ]
	        );
	        $this->add_control( 'controlnav',
	            [
	                'label'         => esc_html__( 'Bullets', 'slide-addon-for-elementer' ),
	                'type'          => \Elementor\Controls_Manager::SWITCHER,
	                'label_on'      => esc_html__( 'On', 'slide-addon-for-elementer' ),
	                'label_off'     => esc_html__( 'Off', 'slide-addon-for-elementer' ),
	                'return_value'  => 'true',
	                'default'       => 'true',
	                'description'	=> 'Just show when you have two slide',
	            ]
	        );
	    	$this->add_responsive_control( 'w_size_controlnav',
				[
					'label' => esc_html__( 'Width', 'slide-addon-for-elementer' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ 'px' ],
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 100,
							'step' => 1,
						]
					],
					'default' => [
						'unit' => 'px',
						'size' => 20,
					],
					'selectors' => [
						'{{WRAPPER}} .flexslider .flex-control-nav li a' => 'width: {{SIZE}}{{UNIT}};',
					],
					'condition' => [
	                    'controlnav' => 'true',
	                ]
				]
			); 
			$this->add_responsive_control( 'h_size_controlnav',
				[
					'label' => esc_html__( 'Height', 'slide-addon-for-elementer' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ 'px' ],
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 100,
							'step' => 1,
						]
					],
					'default' => [
						'unit' => 'px',
						'size' => 20,
					],
					'selectors' => [
						'{{WRAPPER}} .flexslider .flex-control-nav li a' => 'height: {{SIZE}}{{UNIT}};line-height: {{SIZE}}{{UNIT}};',
					],
					'condition' => [
	                    'controlnav' => 'true',
	                ]
				]
			);
			$this->add_control( 'heading_controlnav_position_horizontal',
				[
					'label' => esc_html__( 'Horizontal Orientation', 'slide-addon-for-elementer' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'condition' => [
	                    'controlnav' => 'true',
	                ]
				]
			);  
			$this->add_responsive_control( 'controlnav_horizontal_position',
				[
					'label' => esc_html__( 'Offset', 'slide-addon-for-elementer' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ 'px', '%' ],
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 2000,
							'step' => 1,
						],
						'%' => [
							'min' => 0,
							'max' => 100,
						],
					],
					'default' => [
						'unit' => '%',
						'size' => 50,
					],
					'selectors' => [
						'{{WRAPPER}} .flexslider .flex-control-nav' => 'left: {{SIZE}}{{UNIT}};',
					],
					'condition' => [
	                    'controlnav' => 'true',
	                ]
				]
			);
			$this->add_control( 'heading_controlnav_position_vertical',
				[
					'label' => esc_html__( 'Vertical Orientation', 'slide-addon-for-elementer' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'condition' => [
	                    'controlnav' => 'true',
	                ]
				]
			);
			$this->add_responsive_control( 'controlnav_vertical_position',
				[
					'label' => esc_html__( 'Offset', 'slide-addon-for-elementer' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ 'px', '%' ],
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 1000,
							'step' => 1,
						],
						'%' => [
							'min' => 0,
							'max' => 100,
						],
					],
					'default' => [
						'unit' => 'px',
						'size' => 27,
					],
					'selectors' => [
						'{{WRAPPER}} .flexslider .flex-control-nav' => 'bottom: {{SIZE}}{{UNIT}};',
					],
					'condition' => [
	                    'controlnav' => 'true',
	                ]
				]
			);
			$this->start_controls_tabs( 'controlnav_tabs',
				[
					'condition' => [
	                    'controlnav' => 'true',
	                ]
				] );
				$this->start_controls_tab( 'controlnav_normal_tab',
					[
						'label' => esc_html__( 'Normal', 'slide-addon-for-elementer' ),						
					]
					);
					$this->add_control( 'controlnav_bg_color',
			            [
			                'label' => esc_html__( 'Background Color', 'slide-addon-for-elementer' ),
			                'type' => \Elementor\Controls_Manager::COLOR,
			                'default' => '#ffffff',
			                'selectors' => [
								'{{WRAPPER}} .flexslider .flex-control-nav li a' => 'background-color: {{VALUE}}',
							],
							'condition' => [
			                    'controlnav' => 'true',
			                ]
			            ]
			        );			         
			        $this->add_group_control( \Elementor\Group_Control_Border::get_type(),
						[
							'name' => 'controlnav_border',
							'label' => esc_html__( 'Border', 'slide-addon-for-elementer' ),
							'selector' => '{{WRAPPER}} .flexslider .flex-control-nav li a',
							'condition' => [
			                    'controlnav' => 'true',
			                ]
						]
					);
					$this->add_responsive_control( 'controlnav_border_radius',
			            [
			                'label' => esc_html__( 'Border Radius', 'slide-addon-for-elementer' ),
			                'type' => \Elementor\Controls_Manager::DIMENSIONS,
			                'size_units' => [ 'px', '%', 'em' ],
			                'selectors' => [
			                    '{{WRAPPER}} .flexslider .flex-control-nav li a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			                ],
			                'condition' => [
			                    'controlnav' => 'true',
			                ]
			            ]
			        );
			    $this->end_controls_tab();
		        $this->start_controls_tab( 'controlnav_hover_tab',
					[
						'label' => esc_html__( 'Hover', 'slide-addon-for-elementer' ),
					]
					); 
		        	$this->add_control( 'controlnav_hover_bg_color',
			            [
			                'label' => esc_html__( 'Background Color', 'slide-addon-for-elementer' ),
			                'type' => \Elementor\Controls_Manager::COLOR,
			                'default' => '#0080f0',
			                'selectors' => [
								'{{WRAPPER}} .flexslider .flex-control-nav li a:hover, {{WRAPPER}} .flexslider .flex-control-nav li a.flex-active' => 'background-color: {{VALUE}}',
							],
							'condition' => [
			                    'controlnav' => 'true',
			                ]
			            ]
			        ); 
		        	$this->add_control( 'controlnav_hover_border_color',
			            [
			                'label' => esc_html__( 'Border Color', 'slide-addon-for-elementer' ),
			                'type' => \Elementor\Controls_Manager::COLOR,
			                'default' => '',
			                'selectors' => [
								'{{WRAPPER}} .flexslider .flex-control-nav li a:hover, {{WRAPPER}} .flexslider .flex-control-nav li a.flex-active' => 'border-color: {{VALUE}};',
							],
							'condition' => [
			                    'controlnav' => 'true',
			                ]
			            ]
			        );
				$this->end_controls_tab();
	        $this->end_controls_tabs();  
			$this->end_controls_section();
        /* End Bullets Setting*/

        /* Start Button Setting */
	        $this->start_controls_section('section_button_setting',
	            [
	                'label' => esc_html__('Buttons','slide-addon-for-elementer'),
	            ]
	        );
	        $repeater = new \Elementor\Repeater();
	        $repeater->start_controls_tabs( 'button_tabs' );
	        	$repeater->start_controls_tab( 'button_content_tab',
		            [
		                'label' => esc_html__( 'Content', 'slide-addon-for-elementer' ),
		            ]
		        	);	        		
			        $repeater->add_control( 'btn_title', 
			        	[
			                'label' => esc_html__( 'Button Title', 'slide-addon-for-elementer' ),
			                'type' => \Elementor\Controls_Manager::TEXT,
			                'label_block' => true,
			                'default' => 'Services here'
			            ]
			        );
			        $repeater->add_control( 'btn_icon', 
			        	[
			                'label' => esc_html__( 'Icon', 'slide-addon-for-elementer' ),
			                'type' => \Elementor\Controls_Manager::ICONS,
			            ]
			        );
			        $repeater->add_control( 'svg_width',
						[
							'label' => esc_html__( 'Width Icon SVG', 'slide-addon-for-elementer' ),
							'type' => \Elementor\Controls_Manager::SLIDER,
							'size_units' => [ 'px', 'em' ],
							'range' => [
								'px' => [
									'min' => 0,
									'max' => 100,
									'step' => 1,
								],
								'em' => [
									'min' => 0,
									'max' => 100,
									'step' => 0.5,
								],
							],
							'default' => [
								'unit' => 'px',
								'size' => 20,
							],
							'selectors' => [
								'{{WRAPPER}} .button-group a.button-one svg' => 'width: {{SIZE}}{{UNIT}};',
							],
							'condition' => [
								'btn_icon[value]!' => '',
							],
						]
					);
			        $repeater->add_control( 'icon_button_align',
						[
							'label' => esc_html__( 'Icon Position', 'slide-addon-for-elementer' ),
							'type' => \Elementor\Controls_Manager::SELECT,
							'default' => 'btn-icon-left',
							'options' => [
								'btn-icon-left' => esc_html__( 'Before', 'slide-addon-for-elementer' ),
								'btn-icon-right' => esc_html__( 'After', 'slide-addon-for-elementer' ),
							],
							'condition' => [
								'btn_icon[value]!' => '',
							],
						]
					);
			        $repeater->add_control( 'icon_indent_left',
						[
							'label' => esc_html__( 'Icon Spacing Left', 'slide-addon-for-elementer' ),
							'type' => \Elementor\Controls_Manager::SLIDER,
							'range' => [
								'px' => [
									'max' => 50,
								],
							],
							'default' => [
								'size' => 5,
							],
							'selectors' => [
								'{{WRAPPER}} {{CURRENT_ITEM}} .btn-icon-left' => 'margin-right: {{SIZE}}{{UNIT}};',
							],
							'condition' => [
								'btn_icon[value]!' => '',
								'icon_button_align[value]' => 'btn-icon-left',
							],
						]
					);
					$repeater->add_control( 'icon_indent_right',
						[
							'label' => esc_html__( 'Icon Spacing Right', 'slide-addon-for-elementer' ),
							'type' => \Elementor\Controls_Manager::SLIDER,
							'range' => [
								'px' => [
									'max' => 50,
								],
							],
							'default' => [
								'size' => 5,
							],
							'selectors' => [
								'{{WRAPPER}} {{CURRENT_ITEM}} .btn-icon-right' => 'margin-left: {{SIZE}}{{UNIT}};',
							],
							'condition' => [
								'btn_icon[value]!' => '',
								'icon_button_align[value]' => 'btn-icon-right',
							],
						]
					);
			        $repeater->add_control( 'btn_url', 
			        	[
			                'label' => esc_html__( 'Button URL', 'slide-addon-for-elementer' ),
			                'type' => \Elementor\Controls_Manager::URL,
			                'default' => [
			                    'url' => '#'
			                ]
			            ]
			        );
			        $repeater->add_control( 'btn_animation',
			            [
			                'label' => esc_html__( 'Animation', 'slide-addon-for-elementer' ),
			                'type' => \Elementor\Controls_Manager::SELECT,
			                'default' => 'fromTop',
			                'options' => [
			                	'' => esc_html__( 'None', 'slide-addon-for-elementer' ),
			                	'vivify fadeInUp_tf' => esc_html__( 'Fade In Up', 'slide-addon-for-elementer' ),
			                	'vivify fadeInDown_tf' => esc_html__( 'Fade In Down', 'slide-addon-for-elementer' ),
			                	'vivify fadeInLeft_tf' => esc_html__( 'Fade In Left', 'slide-addon-for-elementer' ),
			                	'vivify fadeInRight_tf' => esc_html__( 'Fade In Right', 'slide-addon-for-elementer' ),
			                	'fromTop' => esc_html__( 'From Top', 'slide-addon-for-elementer' ),
			                    'fromBottom' => esc_html__( 'From Bottom', 'slide-addon-for-elementer' ),
			                    'fromLeft' => esc_html__( 'From Left', 'slide-addon-for-elementer' ),
			                    'fromRight' => esc_html__( 'From Right', 'slide-addon-for-elementer' ),
			                    'vivify pulsate' => esc_html__( 'Pulsate', 'slide-addon-for-elementer' ),
			                    'vivify ball' => esc_html__( 'Ball', 'slide-addon-for-elementer' ),
			                    'vivify pullUp' => esc_html__( 'Pull Up', 'slide-addon-for-elementer' ),
			                    'vivify pullDown' => esc_html__( 'Pull Down', 'slide-addon-for-elementer' ),
			                    'vivify pullLeft' => esc_html__( 'Pull Left', 'slide-addon-for-elementer' ),
			                    'vivify pullRight' => esc_html__( 'Pull Right', 'slide-addon-for-elementer' ),
			                    'vivify jumpInLeft' => esc_html__( 'Jump In Left', 'slide-addon-for-elementer' ),
			                    'vivify jumpInRight' => esc_html__( 'Jump In Right', 'slide-addon-for-elementer' ),
			                    'vivify rollInLeft' => esc_html__( 'Roll In Left', 'slide-addon-for-elementer' ),
			                    'vivify rollInRight' => esc_html__( 'Roll In Right', 'slide-addon-for-elementer' ),
			                    'vivify rollInTop' => esc_html__( 'Roll In Top', 'slide-addon-for-elementer' ),
			                    'vivify rollInBottom' => esc_html__( 'Roll In Bottom', 'slide-addon-for-elementer' ),
			                    'vivify popIn' => esc_html__( 'Pop In', 'slide-addon-for-elementer' ),
			                    'vivify popInLeft' => esc_html__( 'Pop In Left', 'slide-addon-for-elementer' ),
			                    'vivify popInRight' => esc_html__( 'Pop In Right', 'slide-addon-for-elementer' ),
			                    'vivify popInTop' => esc_html__( 'Pop In Top', 'slide-addon-for-elementer' ),
			                    'vivify popInBottom' => esc_html__( 'Pop In Bottom', 'slide-addon-for-elementer' ),
			                    'vivify swoopInLeft' => esc_html__( 'Swoop In Left', 'slide-addon-for-elementer' ),
			                    'vivify swoopInRight' => esc_html__( 'Swoop In Right', 'slide-addon-for-elementer' ),
			                    'vivify swoopInTop' => esc_html__( 'Swoop In Top', 'slide-addon-for-elementer' ),
			                    'vivify swoopInBottom' => esc_html__( 'Swoop In Bottom', 'slide-addon-for-elementer' ),
			                    'vivify flip' => esc_html__( 'Flip', 'slide-addon-for-elementer' ),
			                    'vivify spin' => esc_html__( 'Spin', 'slide-addon-for-elementer' ),
			                ],
			                'condition' => [
			                    'btn_title[value]!' => '',
			                ]
			            ]
			        );
			        $repeater->add_control( 'btn_delay',
			            [
			                'label' => esc_html__( 'Delay', 'slide-addon-for-elementer' ),
			                'type' => \Elementor\Controls_Manager::SELECT,
			                'default' => '',
			                'options' => [
			                	'' => esc_html__( 'None', 'slide-addon-for-elementer' ),
			                	'captionDelay1' => esc_html__( 'Delay 0.1 Seconds', 'slide-addon-for-elementer' ),
			                	'captionDelay2' => esc_html__( 'Delay 0.2 Seconds', 'slide-addon-for-elementer' ),
			                	'captionDelay3' => esc_html__( 'Delay 0.3 Seconds', 'slide-addon-for-elementer' ),
			                	'captionDelay4' => esc_html__( 'Delay 0.4 Seconds', 'slide-addon-for-elementer' ),
			                	'captionDelay5' => esc_html__( 'Delay 0.5 Seconds', 'slide-addon-for-elementer' ),
			                	'captionDelay6' => esc_html__( 'Delay 0.6 Seconds', 'slide-addon-for-elementer' ),
			                	'captionDelay7' => esc_html__( 'Delay 0.7 Seconds', 'slide-addon-for-elementer' ),
			                	'captionDelay8' => esc_html__( 'Delay 0.8 Seconds', 'slide-addon-for-elementer' ),
			                	'captionDelay9' => esc_html__( 'Delay 0.9 Seconds', 'slide-addon-for-elementer' ),
			                	'captionDelay10' => esc_html__( 'Delay 1 Seconds', 'slide-addon-for-elementer' ),
			                	'captionDelay11' => esc_html__( 'Delay 1.1 Seconds', 'slide-addon-for-elementer' ),
			                	'captionDelay12' => esc_html__( 'Delay 1.2 Seconds', 'slide-addon-for-elementer' ),
			                	'captionDelay13' => esc_html__( 'Delay 1.3 Seconds', 'slide-addon-for-elementer' ),
			                	'captionDelay14' => esc_html__( 'Delay 1.4 Seconds', 'slide-addon-for-elementer' ),
			                	'captionDelay15' => esc_html__( 'Delay 1.5 Seconds', 'slide-addon-for-elementer' ),
			                	'captionDelay16' => esc_html__( 'Delay 1.6 Seconds', 'slide-addon-for-elementer' ),
			                	'captionDelay17' => esc_html__( 'Delay 1.7 Seconds', 'slide-addon-for-elementer' ),
			                	'captionDelay18' => esc_html__( 'Delay 1.8 Seconds', 'slide-addon-for-elementer' ),
			                	'captionDelay19' => esc_html__( 'Delay 1.9 Seconds', 'slide-addon-for-elementer' ),
			                	'captionDelay20' => esc_html__( 'Delay 2 Seconds', 'slide-addon-for-elementer' ),
			                ],
			                'condition' => [
			                    'btn_title[value]!' => '',
			                ]
			            ]
			        );
	        	$repeater->end_controls_tab();

	        	$repeater->start_controls_tab( 'button_style_tab',
		            [
		                'label' => esc_html__( 'Style', 'slide-addon-for-elementer' ),
		            ]
		        	);
			        $repeater->add_control( 'hr_btn_divider',
			            [
			                'type' => \Elementor\Controls_Manager::DIVIDER,
			            ]
			        );
			        $repeater->add_group_control(
			            \Elementor\Group_Control_Typography::get_type(),
			            [
			                'name' => 'btn_typography',
			                'label' => esc_html__( 'Typography', 'slide-addon-for-elementer' ),
			                'selector' => '{{WRAPPER}} {{CURRENT_ITEM}}',
			            ]
			        );
			        $repeater->add_responsive_control( 'btn_padding',
			            [
			                'label' => esc_html__( 'Padding', 'slide-addon-for-elementer' ),
			                'type' => \Elementor\Controls_Manager::DIMENSIONS,
			                'size_units' => [ 'px', '%', 'em' ],
			                'selectors' => [
			                    '{{WRAPPER}} {{CURRENT_ITEM}}' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			                ],
			            ]
			        );
			        $repeater->add_responsive_control( 'btn_border_radius',
			            [
			                'label' => esc_html__( 'Border Radius', 'slide-addon-for-elementer' ),
			                'type' => \Elementor\Controls_Manager::DIMENSIONS,
			                'size_units' => [ 'px', '%', 'em' ],
			                'selectors' => [
			                    '{{WRAPPER}} {{CURRENT_ITEM}}' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			                ],
			            ]
			        );
			        $repeater->add_responsive_control( 'btn_margin',
						[
							'label' => esc_html__( 'Margin', 'slide-addon-for-elementer' ),
							'type' => \Elementor\Controls_Manager::DIMENSIONS,
							'size_units' => [ 'px', 'em', '%' ],
							'selectors' => [
								'{{WRAPPER}} {{CURRENT_ITEM}}' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
							],
							'separator' => 'before',
						]
					);
			    $repeater->end_controls_tab();

		        $repeater->start_controls_tab( 'style_normal_btn',
		            [
		                'label' => esc_html__( 'Normal', 'slide-addon-for-elementer' ),
		            ]
		        	);
		        	$repeater->add_control( 'font_color', 
		        		[
			                'label' => esc_html__( 'Color', 'slide-addon-for-elementer' ),
			                'type' => \Elementor\Controls_Manager::COLOR,
			                'default' => '#ffffff',
			                'selectors' => [
			                    '{{WRAPPER}} {{CURRENT_ITEM}}, {{WRAPPER}} {{CURRENT_ITEM}} svg' => 'color: {{VALUE}}; fill: {{VALUE}};',
			                ],

			            ]
			        );
			        $repeater->add_control( 'bg_color',
			            [
			                'label' => esc_html__( 'Background Color', 'slide-addon-for-elementer' ),
			                'type' => \Elementor\Controls_Manager::COLOR,
			                'default' => '#0080f0',
			                'selectors' => [
			                    '{{WRAPPER}} {{CURRENT_ITEM}}' => 'background: {{VALUE}}',
			                ],
			            ]
			        );
			        $repeater->add_control( 'border_color',
			            [
			                'label' => esc_html__( 'Border Color', 'slide-addon-for-elementer' ),
			                'type' => \Elementor\Controls_Manager::COLOR,
			                'default' => '#0080f0',
			                'selectors' => [
			                    '{{WRAPPER}} {{CURRENT_ITEM}}' => 'border-color: {{VALUE}}',
			                ],
			            ]
			        );
			    $repeater->end_controls_tab();
			    
			    $repeater->start_controls_tab( 'style_hover_btn',
		            [
		                'label' => esc_html__( 'Hover', 'slide-addon-for-elementer' ),
		            ]
		        	);
			        $repeater->add_control( 'hover_font_color', 
			        	[
			                'label' => esc_html__( 'Font Color', 'slide-addon-for-elementer' ),
			                'type' => \Elementor\Controls_Manager::COLOR,
			                'default' => '#ffffff',
			                'selectors' => array(
			                    '{{WRAPPER}} {{CURRENT_ITEM}}:hover, {{WRAPPER}} {{CURRENT_ITEM}}:hover svg' => 'color: {{VALUE}}; fill: {{VALUE}};',
			                )
			            ]
			        );
			        $repeater->add_control( 'hover_bg_color', 
			            [
			                'label' => esc_html__( 'Background Color', 'slide-addon-for-elementer' ),
			                'type' => \Elementor\Controls_Manager::COLOR,
			                'default' => '#222222',
			                'selectors' => array(
			                    '{{WRAPPER}} {{CURRENT_ITEM}}:hover' => 'background: {{VALUE}}',
			                )
			            ]
			        );
			        $repeater->add_control( 'hover_border_color', 
			        	[
			                'label' => esc_html__( 'Border Color', 'slide-addon-for-elementer' ),
			                'type' => \Elementor\Controls_Manager::COLOR,
			                'default' => '#222222',
			                'selectors' => array(
			                    '{{WRAPPER}} {{CURRENT_ITEM}}:hover' => 'border-color: {{VALUE}}',
			                )
			            ]
			        );
		        $repeater->end_controls_tab();
		        
			$repeater->end_controls_tabs();
	        $this->add_control('buttons',
	            [
	                'label'  => esc_html__('Create Buttons','slide-addon-for-elementer'),
	                'type'   => \Elementor\Controls_Manager::REPEATER,
	                'fields' => $repeater->get_controls(),
	                'title_field' => '{{{ btn_title }}}',
	            ]
	        );
	        $this->end_controls_section();
        /* End Button Setting */ 

        /* Start Tab Color Style */
	        $this->start_controls_section( 'section_text_style_color',
	            [
	                'label' => esc_html__( 'Color', 'slide-addon-for-elementer' ),
	                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
	            ]
	        );
	        $this->add_control( 'subtitle_color',
	            [
	                'label' => esc_html__( 'Color Subtitle', 'slide-addon-for-elementer' ),
	                'type' => \Elementor\Controls_Manager::COLOR,
	                'default' => '#ffffff',
	                'selectors' => [
						'{{WRAPPER}} .flex_caption .sub-title' => 'color: {{VALUE}}',
					],
	            ]
	        );
	        $this->add_control( 'title_color',
	            [
	                'label' => esc_html__( 'Color Title', 'slide-addon-for-elementer' ),
	                'type' => \Elementor\Controls_Manager::COLOR,
	                'default' => '#ffffff',
	                'selectors' => [
						'{{WRAPPER}} .flex_caption .title' => 'color: {{VALUE}}',
					],
	            ]
	        );	        
	        $this->add_control( 'desc_color',
	            [
	                'label' => esc_html__( 'Color Desc', 'slide-addon-for-elementer' ),
	                'type' => \Elementor\Controls_Manager::COLOR,
	                'default' => '#ffffff',
	                'selectors' => [
						'{{WRAPPER}} .flex_caption .desc' => 'color: {{VALUE}}',
					],
	            ]
	        );
	        $this->end_controls_section();    
	    /* End Tab Color Style */

	    /* Start Tab Typography Style */
	        $this->start_controls_section( 'section_text_style_typography',
	            [
	                'label' => esc_html__( 'Typography', 'slide-addon-for-elementer' ),
	                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
	            ]
	        );
	        $this->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				[
					'name' => 'subtitle_typography',
					'label' => esc_html__( 'Typography Subtitle', 'slide-addon-for-elementer' ),			
					'selector' => '{{WRAPPER}} .flex_caption .sub-title',
				]
			);
	        $this->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				[
					'name' => 'title_typography',
					'label' => esc_html__( 'Typography Title', 'slide-addon-for-elementer' ),
					'selector' => '{{WRAPPER}} .flex_caption .title',
				]
			);			
			$this->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				[
					'name' => 'desc_typography',
					'label' => esc_html__( 'Typography Desc', 'slide-addon-for-elementer' ),
					'selector' => '{{WRAPPER}} .flex_caption .desc',
				]
			);
	        $this->end_controls_section();    
	    /* End Tab Typography Style */

	    /* Start Tab Spacing Style */
	        $this->start_controls_section( 'section_text_style_spacing',
	            [
	                'label' => esc_html__( 'Spacing', 'slide-addon-for-elementer' ),
	                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
	            ]
	        );
	        $this->add_control( 'spacing_subtitle',
				[
					'label' => esc_html__( 'Spacing Subtitle', 'slide-addon-for-elementer' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ 'px', '%' ],
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 200,
							'step' => 1,
						],
						'%' => [
							'min' => 0,
							'max' => 100,
						],
					],
					'default' => [
						'unit' => 'px',
						'size' => 20,
					],
					'selectors' => [
						'{{WRAPPER}} .flex_caption .sub-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					],
				]
			);			
	        $this->add_control( 'spacing_title',
				[
					'label' => esc_html__( 'Spacing Title', 'slide-addon-for-elementer' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ 'px', '%' ],
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 200,
							'step' => 1,
						],
						'%' => [
							'min' => 0,
							'max' => 100,
						],
					],
					'default' => [
						'unit' => 'px',
						'size' => 20,
					],
					'selectors' => [
						'{{WRAPPER}} .flex_caption .title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					],
				]
			);			
	        $this->add_control( 'spacing_desc',
				[
					'label' => esc_html__( 'Spacing Desc', 'slide-addon-for-elementer' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ 'px', '%' ],
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 200,
							'step' => 1,
						],
						'%' => [
							'min' => 0,
							'max' => 100,
						],
					],
					'default' => [
						'unit' => 'px',
						'size' => 20,
					],
					'selectors' => [
						'{{WRAPPER}} .flex_caption .desc' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					],
				]
			);
	        $this->end_controls_section();    
	    /* End Tab Spacing Style */ 
	}

	protected function render($instance = []) {
		$settings = $this->get_settings_for_display();
		$title_html = $subtitle_html = $desc_html = $btn_html = $wrap_btn_html = $icon_btn_html = $class = $margin_top = $class_btn = $overlay_html = $content_into_grid_container = '';
		
		if ($settings['buttons']) {
			foreach ( $settings['buttons'] as $key => $value ) {
				if( $key < 3 ) {
					if ( $value['icon_button_align'] == 'btn-icon-left' ) {
						$btn_html .= sprintf('<li class="'.$value['btn_delay'].' '.$value['btn_animation'].'">
										<a href="'.$value['btn_url']['url'].'" class="button-one elementor-repeater-item-'.$value['_id'].'"><span class="btn-icon-left">%s</span> '.$value['btn_title'].'</a>
									</li>', \Elementor\Addon_Elementor_Icon_manager::render_icon( $value['btn_icon'], [ 'aria-hidden' => 'true' ] ));
					}else {
						$btn_html .= sprintf('<li class="'.$value['btn_delay'].' '.$value['btn_animation'].'">
										<a href="'.$value['btn_url']['url'].'" class="button-one elementor-repeater-item-'.$value['_id'].'">'.$value['btn_title'].' <span class="btn-icon-right">%s</span></a>
									</li>', \Elementor\Addon_Elementor_Icon_manager::render_icon( $value['btn_icon'], [ 'aria-hidden' => 'true' ] ));
					}			
					
				}
			}
			$wrap_btn_html = '<ul class="button-group"> '.$btn_html.'</ul>';			
		}

		?>
		<div class="flexslider <?php echo esc_attr($class); ?> " data-height="<?php echo esc_attr($settings['height_slider']['size']); ?>" data-animation_images="<?php echo esc_attr($settings['animation_images']); ?>" data-autoplay="<?php echo esc_attr($settings['slideshow_autoplay']); ?>" data-infinite_loop="<?php echo esc_attr($settings['infinite_loop']); ?>" data-slideshowspeed="<?php echo esc_attr($settings['slideshowspeed']['size']); ?>" data-animationspeed="<?php echo esc_attr($settings['animationspeed']['size']); ?>" data-directionnav="<?php echo esc_attr($settings['directionnav']); ?>" data-controlnav="<?php echo esc_attr($settings['controlnav']); ?>" data-prevtext="<?php echo esc_attr($settings['prev_icon']); ?>" data-nexttext="<?php echo esc_attr($settings['next_icon']); ?>">
			<ul class="slides">
				<?php foreach ( $settings['flexslider_list'] as $value ) {
					if ($value['subtitle_text'] != '') {
						$subtitle_html = '<h3 class="sub-title '.$value['subtitle_delay'].' '.$value['subtitle_animation'].'">'.$value['subtitle_text'].'</h3>';
					}
					if ($value['title_text'] != '') {				
						$title_html = '<h1 class="title '.$value['title_delay'].' '.$value['title_animation'].'">'.$value['title_text'].'</h1>';
					}
					if ($value['desc_text'] != '') {
						$desc_html = '<p class="desc '.$value['desc_delay'].' '.$value['desc_animation'].'">'.$value['desc_text'].'</p>';
					}

					if ( $value['color_overlay'] != '' ) {
						$overlay_html = '<div class="overlay" style="background:'.$value['color_overlay'].'"></div>';
					}

					$shape_html = '';
					if ( $value['shape_one']['url'] != '' ) {
						$shape_html .= '<img src="'.$value['shape_one']['url'].'" alt="shape" class="bg_shape elementor-repeater-item-'.$value['_id'].'_shape_one '.$value['shape_one_animation'].' '.$value['shape_one_delay'].'">';
					}
					if ( $value['shape_two']['url'] != '' ) {
						$shape_html .= '<img src="'.$value['shape_two']['url'].'" alt="shape" class="bg_shape elementor-repeater-item-'.$value['_id'].'_shape_two '.$value['shape_two_animation'].' '.$value['shape_two_delay'].'">';
					}	
					if ( $value['shape_three']['url'] != '' ) {
						$shape_html .= '<img src="'.$value['shape_three']['url'].'" alt="shape" class="bg_shape elementor-repeater-item-'.$value['_id'].'_shape_three '.$value['shape_three_animation'].' '.$value['shape_three_delay'].'">';
					}
					if ( $value['shape_four']['url'] != '' ) {
						$shape_html .= '<img src="'.$value['shape_four']['url'].'" alt="shape" class="bg_shape elementor-repeater-item-'.$value['_id'].'_shape_four '.$value['shape_four_animation'].' '.$value['shape_four_delay'].'">';
					}
					if ( $value['shape_five']['url'] != '' ) {
						$shape_html .= '<img src="'.$value['shape_five']['url'].'" alt="shape" class="bg_shape elementor-repeater-item-'.$value['_id'].'_shape_five '.$value['shape_five_animation'].' '.$value['shape_five_delay'].'">';
					}

					if ( $settings['content_into_grid'] == 'yes' ) {
						$content_into_grid_container .= " container ";
					}

					$bg_images_size = '';
					if ( $settings['bg_images_size'] != '' ) {
						$bg_images_size = 'background-size: '.$settings['bg_images_size'].';';
					}

					$bg_images_position = '';
					if ( $settings['bg_images_position'] != '' ) {
						$bg_images_position = 'background-position: '.$settings['bg_images_position'].';';
					}	

					$align_content_style = '';
					if ( $value['align_content'] != '' ) {
						$align_content_style .= 'style="text-align: '.$value['align_content'].';"';
					}				

					echo sprintf(
							'<li class="item-slide">
								<div class="bgimg %s" style="background-image:url(%s); %s %s">
								</div>
								%s
								%s
								<div class="flex_caption %s" %s>
	                        		%s
	                        		%s
	                        		%s
	                        		%s
			                    </div>	                    
			                    
							</li>',
							$settings['kenburns_effect_images'],
							$value['flexslider_image']['url'],
							$bg_images_size,
							$bg_images_position,
							$overlay_html,	
							$shape_html,
							$content_into_grid_container,
							$align_content_style,			
							$subtitle_html,
							$title_html,
							$desc_html,
							$wrap_btn_html					
							
					);					
				} ?>
			</ul> 
		</div>
		<?php
	}

}