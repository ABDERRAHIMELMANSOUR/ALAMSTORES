<?php

class Slide_Widget extends \Elementor\Widget_Base {

	public function get_name() {
        return 'vegas-slider';
    }
    
    public function get_title() {
        return esc_html__( 'TF Simple Slider', 'slide-addon-for-elementer' );
    }

    public function get_icon() {
        return 'eicon-slider-push';
    }
    
    public function get_categories() {
        return [ 'themesflat_addons' ];
    }

    public function get_style_depends() {
		return ['font-awesome','vegas','slide-animate','slide-addon-for-elementor-style'];
	}

	public function get_script_depends() {
		return ['jquery-easing','typed','vegas','slide-addon-for-elementor-script'];
	}

	protected function register_controls() {
		/* Start Slide Setting*/
			$this->start_controls_section('section_slider_hero',
	            [
	                'label'         => esc_html__('General','slide-addon-for-elementer'),
	            ]
	        );
	        $this->add_control(
	            'vegas_slideshow_height',
	            [
	                'label' => esc_html__( 'Height', 'slide-addon-for-elementer' ),
	                'type' => \Elementor\Controls_Manager::SELECT,
	                'default' => 'custom-height',
	                'options' => [
	                    'full-height' => esc_html__( 'Full Height', 'slide-addon-for-elementer' ),
	                    'custom-height' => esc_html__( 'Custom Height', 'slide-addon-for-elementer' ),
	                ],
	            ]
	        );	
	        $this->add_control('custom_height',
	            [
	                'label'   => esc_html__( 'Custom Height', 'slide-addon-for-elementer' ),
	                'type'    => \Elementor\Controls_Manager::TEXT,
	                'default' => '900',
	                'condition' =>[
	                    'vegas_slideshow_height' => 'custom-height',
	                ],
	            ]
	        ); 	        
	        $this->add_control( 'effect',
	            [
	                'label' => esc_html__( 'Effects', 'slide-addon-for-elementer' ),
	                'type' => \Elementor\Controls_Manager::SELECT,
	                'default' => 'slideRight',
	                'options' => [
	                    'fade' => esc_html__( 'Fade', 'slide-addon-for-elementer' ),
	                    'fade2' => esc_html__( 'Fade2', 'slide-addon-for-elementer' ),
	                    'slideLeft' => esc_html__( 'Slide Left', 'slide-addon-for-elementer' ),
	                    'slideLeft2' => esc_html__( 'Slide Left2', 'slide-addon-for-elementer' ),
	                    'slideRight' => esc_html__( 'Slide Right', 'slide-addon-for-elementer' ),
	                    'slideRight2' => esc_html__( 'Slide Right2', 'slide-addon-for-elementer' ),
	                    'slideUp' => esc_html__( 'Slide Up', 'slide-addon-for-elementer' ),
	                    'slideDown' => esc_html__( 'Slide Down', 'slide-addon-for-elementer' ),
	                    'slideDown2' => esc_html__( 'Slide Down2', 'slide-addon-for-elementer' ),
	                    'zoomIn' => esc_html__( 'Zoom In', 'slide-addon-for-elementer' ),
	                    'zoomIn2' => esc_html__( 'Zoom In2', 'slide-addon-for-elementer' ),
	                    'zoomOut' => esc_html__( 'Zoom Out', 'slide-addon-for-elementer' ),
	                    'zoomOut2' => esc_html__( 'Zoom Out2', 'slide-addon-for-elementer' ),
	                    'swirlLeft' => esc_html__( 'Swirl Left', 'slide-addon-for-elementer' ),
	                    'swirlLeft2' => esc_html__( 'Swirl Left2', 'slide-addon-for-elementer' ),
	                    'swirlRight' => esc_html__( 'Swirl Right', 'slide-addon-for-elementer' ),
	                    'swirlRight2' => esc_html__( 'Swirl Right2', 'slide-addon-for-elementer' ),
	                    'burn' => esc_html__( 'Burn', 'slide-addon-for-elementer' ),
	                    'burn2' => esc_html__( 'Burn2', 'slide-addon-for-elementer' ),
	                    'blur' => esc_html__( 'Blur', 'slide-addon-for-elementer' ),
	                    'blur2' => esc_html__( 'Blur2', 'slide-addon-for-elementer' ),
	                    'flash' => esc_html__( 'Flash', 'slide-addon-for-elementer' ),
	                    'flash2' => esc_html__( 'Flash2', 'slide-addon-for-elementer' )
	                ],
	            ]
	        ); 
	        $this->add_control( 'delay',
	            [
	                'label'   => esc_html__( 'Duration (ms)', 'slide-addon-for-elementer' ),
	                'type'    => \Elementor\Controls_Manager::TEXT,
	                'default' => '3000'
	            ]
	        );
	        $this->add_control( 'pattern_overlay',
	            [
	                'label' => esc_html__( 'Pattern Overlay', 'slide-addon-for-elementer' ),
	                'type' => \Elementor\Controls_Manager::SELECT,
	                'default' => 'style-1',
	                'options' => [
	                    ''          => esc_html__( 'No Parttern', 'slide-addon-for-elementer' ),
	                    'style-1'   => esc_html__( 'Style 1', 'slide-addon-for-elementer' ),
	                    'style-2'   => esc_html__( 'Style 2', 'slide-addon-for-elementer' ),
	                    'style-3'   => esc_html__( 'Style 3', 'slide-addon-for-elementer' ),
	                    'style-4'   => esc_html__( 'Style 4', 'slide-addon-for-elementer' ),
	                    'style-5'   => esc_html__( 'Style 5', 'slide-addon-for-elementer' ),
	                    'style-6'   => esc_html__( 'Style 6', 'slide-addon-for-elementer' ),
	                    'style-7'   => esc_html__( 'Style 7', 'slide-addon-for-elementer' ),
	                    'style-8'   => esc_html__( 'Style 8', 'slide-addon-for-elementer' ),
	                    'style-9'   => esc_html__( 'Style 9', 'slide-addon-for-elementer' ),
	                ],
	            ]
	        );
	        $this->add_control( 'color_overlay',
	            [
	                'label'     => esc_html__( 'Color Overlay', 'slide-addon-for-elementer' ),
	                'type'      => \Elementor\Controls_Manager::COLOR,
	                'default'=>'',
	            ]
	        );
	        $this->add_control( 'content_top',
	            [
	                'label'   => esc_html__( 'Content: Top Margin', 'slide-addon-for-elementer' ),
	                'type'    => \Elementor\Controls_Manager::TEXT,
	                'default' => '',
	                'placeholder' => esc_html__( '50', 'slide-addon-for-elementer' ),
	                'description' => esc_html__('Ex: 50. In case you want to set a spacing above the content area.', 'slide-addon-for-elementer')                    
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
			$repeater->add_control( 'vegas_slideshow_image',
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
	        $this->add_control('vegas_slideshow_list',
	            [
	                'label'  => esc_html__('Image Slides','slide-addon-for-elementer'),
	                'type'   => \Elementor\Controls_Manager::REPEATER,
	                'fields' => $repeater->get_controls(),
	                'default' => [
	                    [ 'vegas_slideshow_text'   => 'Text', ]
	                ]
	            ]
	        );	
	        $this->end_controls_section();
        /* End Slide Setting*/

        /* Start Scroll Anchor Content*/
        	$this->start_controls_section('section_slider_scroll_anchor',
	            [
	                'label'         => esc_html__('Scroll Anchor','slide-addon-for-elementer'),
	            ]
	        );
	        $this->add_control( 'arrow_anchor',
	            [
	                'label'         => esc_html__( 'Arrow Anchor', 'slide-addon-for-elementer' ),
	                'type'          => \Elementor\Controls_Manager::SWITCHER,
	                'label_on'      => esc_html__( 'On', 'slide-addon-for-elementer' ),
	                'label_off'     => esc_html__( 'Off', 'slide-addon-for-elementer' ),
	                'return_value'  => 'yes',
	                'default'       => 'no',
	            ]
	        );
	        $this->add_control( 'arrow_style',
	            [
	                'label' => esc_html__( 'Arrow Style', 'slide-addon-for-elementer' ),
	                'type' => \Elementor\Controls_Manager::SELECT,
	                'default' => 'default',
	                'options' => [
	                    'default'   => esc_html__( 'Default', 'slide-addon-for-elementer' ),
	                    'outline-circle'   => esc_html__( 'Outline Circle', 'slide-addon-for-elementer' ),
	                    'outline-square'   => esc_html__( 'Outline Square', 'slide-addon-for-elementer' ),
	                    'custom'   => esc_html__( 'Custom', 'slide-addon-for-elementer' ),
	                ],
	                'condition' => [
	                    'arrow_anchor[value]' => 'yes',
	                ]
	            ]
	        );
	        $this->add_control( 'arrow_anchor_width',
				[
					'label' => esc_html__( 'Width', 'slide-addon-for-elementer' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ 'px'],
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 500,
							'step' => 1,
						],
					],
					'default' => [
						'unit' => 'px',
						'size' => 50,
					],
					'selectors' => [
						'{{WRAPPER}} .scroll-target' => 'width: {{SIZE}}{{UNIT}};',
					],
					'condition' => [
	                    'arrow_anchor' => 'yes',
	                    'arrow_style' => 'custom',
	                ]
				]
			);
			$this->add_control( 'arrow_anchor_heigth',
				[
					'label' => esc_html__( 'Heigth', 'slide-addon-for-elementer' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ 'px'],
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 500,
							'step' => 1,
						],
					],
					'default' => [
						'unit' => 'px',
						'size' => 50,
					],
					'selectors' => [
						'{{WRAPPER}} .scroll-target' => 'height: {{SIZE}}{{UNIT}};',
					],
					'condition' => [
	                    'arrow_anchor' => 'yes',
	                    'arrow_style' => 'custom',
	                ]
				]
			);
			$this->add_group_control( \Elementor\Group_Control_Border::get_type(),
				[
					'name' => 'arrow_anchor_border',
					'label' => esc_html__( 'Border', 'slide-addon-for-elementer' ),
					'selector' => '{{WRAPPER}} .scroll-target',
					'condition' => [
	                    'arrow_anchor' => 'yes',
	                    'arrow_style' => 'custom',
	                ]
				]
			);
			$this->add_responsive_control( 'arrow_anchor_border_radius',
	            [
	                'label' => esc_html__( 'Border Radius', 'slide-addon-for-elementer' ),
	                'type' => \Elementor\Controls_Manager::DIMENSIONS,
	                'size_units' => [ 'px', '%', 'em' ],
	                'selectors' => [
	                    '{{WRAPPER}} .scroll-target' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
	                ],
	                'condition' => [
	                    'arrow_anchor' => 'yes',
	                    'arrow_style' => 'custom',
	                ]
	            ]
	        );
	        $this->add_control( 'heading_arrow_anchor_icon',
				[
					'label' => esc_html__( 'Icon', 'slide-addon-for-elementer' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'condition' => [
	                    'arrow_anchor[value]' => 'yes',
	                ]
				]
			);
	        $this->add_control( 'arrow_anchor_icon', 
	        	[
	                'label' => esc_html__( 'Icon', 'slide-addon-for-elementer' ),
	                'type' => \Elementor\Controls_Manager::ICONS,
	                'condition' => [
						'arrow_anchor[value]' => 'yes',
					],
	            ]
	        );
	        $this->add_control( 'arrow_anchor_svg_width',
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
						'{{WRAPPER}} .scroll-target svg' => 'width: {{SIZE}}{{UNIT}};',
					],
					'condition' => [
						'arrow_anchor[value]' => 'yes',
						'arrow_anchor_icon[value]!' => '',
					],
				]
			);
			$this->add_control( 'arrow_anchor_font_size',
				[
					'label' => esc_html__( 'Icon Size', 'slide-addon-for-elementer' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ 'px'],
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 1000,
							'step' => 5,
						],
					],
					'default' => [
						'unit' => 'px',
						'size' => 20,
					],
					'selectors' => [
						'{{WRAPPER}} .scroll-target' => 'font-size: {{SIZE}}{{UNIT}};',
					],
					'condition' => [
	                    'arrow_anchor[value]' => 'yes',
	                ]
				]
			);
			$this->add_control( 'arrow_anchor_color',
	            [
	                'label' => esc_html__( 'Color', 'slide-addon-for-elementer' ),
	                'type' => \Elementor\Controls_Manager::COLOR,
	                'default' => '#ffffff',
	                'selectors' => [
						'{{WRAPPER}} .scroll-target, {{WRAPPER}} .scroll-target, {{WRAPPER}} .scroll-target svg' => 'color: {{VALUE}}; fill: {{VALUE}}',
					],
					'condition' => [
	                    'arrow_anchor[value]' => 'yes',
	                ]
	            ]
	        );
	        $this->add_control( 'arrow_anchor_color_hover',
	            [
	                'label' => esc_html__( 'Color Hover', 'slide-addon-for-elementer' ),
	                'type' => \Elementor\Controls_Manager::COLOR,
	                'default' => '#ffffff',
	                'selectors' => [
						'{{WRAPPER}} .scroll-target:hover, {{WRAPPER}} .scroll-target:hover, {{WRAPPER}} .scroll-target:hover svg' => 'color: {{VALUE}}; border-color: {{VALUE}}; fill: {{VALUE}}',
					],
					'condition' => [
	                    'arrow_anchor[value]' => 'yes',
	                ]
	            ]
	        );	        
	        $this->add_control( 'arrow_anchor_effect',
	            [
	                'label'         => esc_html__( 'Effect', 'slide-addon-for-elementer' ),
	                'type'          => \Elementor\Controls_Manager::SWITCHER,
	                'label_on'      => esc_html__( 'On', 'slide-addon-for-elementer' ),
	                'label_off'     => esc_html__( 'Off', 'slide-addon-for-elementer' ),
	                'return_value'  => 'yes',
	                'default'       => 'no',
	                'condition' => [
	                    'arrow_anchor[value]' => 'yes',
	                ]
	            ]
	        );
	        $this->add_control( 'scroll_id',
	            [
	                'label'   => esc_html__( 'Scroll to Row (ID)', 'slide-addon-for-elementer' ),
	                'type'    => \Elementor\Controls_Manager::TEXT,
	                'default' => '',
	                'condition' => [
	                    'arrow_anchor[value]' => 'yes',
	                ]
	            ]
	        );
        	$this->end_controls_section();
         /* End Scroll Anchor Content*/

        /* Start Slide Content*/
	        $this->start_controls_section('section_content_hero',
	            [
	                'label'         => esc_html__('Slide Content','slide-addon-for-elementer'),
	            ]
	        );
	        $this->add_control( 'animation_heading',
	            [
	                'label' => esc_html__( 'Animation Heading', 'slide-addon-for-elementer' ),
	                'type' => \Elementor\Controls_Manager::SELECT,
	                'default' => 'none',
	                'options' => [
	                	'none' => esc_html__( 'None', 'slide-addon-for-elementer' ),
	                    'type' => esc_html__( 'Typing', 'slide-addon-for-elementer' ),
	                    'scroll' => esc_html__( 'Scrolling', 'slide-addon-for-elementer' ),
	                ],
	            ]
	        );
	        $this->add_control('prefix_title_text',
	        	[
	                'label'   => esc_html__( 'Prefix Title', 'slide-addon-for-elementer' ),
	                'type'    => \Elementor\Controls_Manager::TEXT,
	                'label_block' => true,
	                'default' => '',                
	            ]
	    	);
	    	$this->add_control('suffix_title_text',
	        	[
	                'label'   => esc_html__( 'Suffix Title', 'slide-addon-for-elementer' ),
	                'type'    => \Elementor\Controls_Manager::TEXT,
	                'label_block' => true,
	                'default' => '',                
	            ]
	    	);
	        $repeater = new \Elementor\Repeater();
	        $repeater->add_control('vegas_title_text',
	        	[
	                'label'   => esc_html__( 'Text', 'slide-addon-for-elementer' ),
	                'type'    => \Elementor\Controls_Manager::TEXT,
	                'label_block' => true,
	                'default' => 'Create Any Slider You Can Imagine',                    
	            ]
	    	); 			
	        $this->add_control('vegas_content_list',
	            [
	                'label'  => esc_html__('Title','slide-addon-for-elementer'),
	                'type'   => \Elementor\Controls_Manager::REPEATER,
	                'fields' => array_values($repeater->get_controls()),
	                'default' => [
	                    [ 'vegas_title_text'   => 'Create Any Slider You Can Imagine' ],
	                    [ 'vegas_title_text'   => 'Create Any Slider As Your Way' ],
	                    [ 'vegas_title_text'   => 'Customize Every Part of Your Slider' ],
	                ],	
	                'condition' => [
	                    'animation_heading' => ['type','scroll'],
	                ]                
	            ]
	        );
	        $this->add_control('vegas_title_text',
	        	[
	                'label'   => esc_html__( 'Title', 'slide-addon-for-elementer' ),
	                'type'    => \Elementor\Controls_Manager::TEXTAREA,
	                'default' => 'Create Any Slider You Can Imagine',  
	                'condition' => [
	                    'animation_heading' => 'none',
	                ]                  
	            ]
	    	);
	    	$this->add_control('vegas_sub_title_text',
	        	[
	                'label'   => esc_html__( 'Sub Title', 'slide-addon-for-elementer' ),
	                'type'    => \Elementor\Controls_Manager::TEXTAREA,
	                'default' => '',                   
	            ]
	    	);
	    	$this->add_control( 'sub_title_position',
				[
					'label' => esc_html__( 'Position Sub Title', 'slide-addon-for-elementer' ),
					'type' => \Elementor\Controls_Manager::CHOOSE,
					'default' => 'bottom',
					'options' => [
						'top' => [
							'title' => esc_html__( 'Before Title', 'slide-addon-for-elementer' ),
							'icon' => 'eicon-v-align-top',
						],
						'bottom' => [
							'title' => esc_html__( 'After Title', 'slide-addon-for-elementer' ),
							'icon' => 'eicon-v-align-bottom',
						],
					],
					'toggle' => false,
				]
			);
	        $this->add_control('vegas_desc_text',
	        	[
	            'label'         => esc_html__('Desciption','slide-addon-for-elementer'),
	            'type'        => \Elementor\Controls_Manager::TEXTAREA,
	            'default'     => 'E.Slider Add-ons for Elementor WordPress Page Builder was built for you.</br>Designers, developers, marketers, and entrepreneurs.</br>Create slider as your way – everything about slider is within reach!',
	        ]);
	        $this->end_controls_section();
        /* End Slide Content*/

        /* Start Button Setting */
	        $this->start_controls_section('section_button_setting_simple_slide',
	            [
	                'label'         => esc_html__('Buttons','slide-addon-for-elementer'),
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
			                'label' => esc_html__( 'Text', 'slide-addon-for-elementer' ),
			                'type' => \Elementor\Controls_Manager::TEXT,
			                'label_block' => true,
			                'default' => 'Buy only $10'
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
								'{{WRAPPER}} .vegas-content a.button-one svg' => 'width: {{SIZE}}{{UNIT}};',
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
								'{{WRAPPER}} {{CURRENT_ITEM}} .btn-icon-left' => 'margin-right: {{SIZE}}{{UNIT}};'
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
								'{{WRAPPER}} {{CURRENT_ITEM}} .btn-icon-right' => 'margin-left: {{SIZE}}{{UNIT}};'
							],
							'condition' => [
								'btn_icon[value]!' => '',
								'icon_button_align[value]' => 'btn-icon-right',
							],
						]
					);
				$repeater->end_controls_tab();
				
				$repeater->start_controls_tab( 'button_style_tab',
		            [
		                'label' => esc_html__( 'Style', 'slide-addon-for-elementer' ),
		            ]
		        	);		        
			        $repeater->add_control( 'hr_btn_divider1',
			            [
			                'type' => \Elementor\Controls_Manager::DIVIDER,
			            ]
			        );
			        $repeater->add_group_control(
			            \Elementor\Group_Control_Typography::get_type(),
			            [
			                'name' => 'btn_typography_simple_slide',
			                'label' => esc_html__( 'Typography', 'slide-addon-for-elementer' ),
			                'selector' => '{{WRAPPER}} {{CURRENT_ITEM}}',
			            ]
			        );
			        $repeater->add_control( 'hr_btn_divider2',
			            [
			                'type' => \Elementor\Controls_Manager::DIVIDER,
			            ]
			        );
			        $repeater->add_responsive_control( 'btn_padding_simple_slide',
			            [
			                'label' => esc_html__( 'Padding', 'slide-addon-for-elementer' ),
			                'type' => \Elementor\Controls_Manager::DIMENSIONS,
			                'size_units' => [ 'px', '%', 'em' ],
			                'selectors' => [
			                    '{{WRAPPER}} {{CURRENT_ITEM}}' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			                ],
			            ]
			        );
			        $repeater->add_responsive_control( 'btn_border_radius_simple_slide',
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
	        $this->add_control('create_buttons',
	            [
	                'label'  => esc_html__('Create buttons','slide-addon-for-elementer'),
	                'type'   => \Elementor\Controls_Manager::REPEATER,
	                'fields' => $repeater->get_controls(),
	                'title_field' => '{{{ btn_title }}}',
	            ]
	        );
	        $this->end_controls_section();
        /* End Button Setting */ 

        /* Start Tab Text Style */
	        $this->start_controls_section( 'section_text_style',
	            [
	                'label' => esc_html__( 'General', 'slide-addon-for-elementer' ),
	                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
	            ]
	        );
	        $this->add_responsive_control( 'align',
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
						],
						'justify' => [
							'title' => esc_html__( 'Justified', 'slide-addon-for-elementer' ),
							'icon' => 'eicon-text-align-justify',
						],
					],
					'default' => 'center',
					'selectors' => [
						'{{WRAPPER}} .vegas-content' => 'text-align: {{VALUE}};',
					],
				]
			);
			$this->add_control( 'prefix_color',
	            [
	                'label' => esc_html__( 'Color Prefix', 'slide-addon-for-elementer' ),
	                'type' => \Elementor\Controls_Manager::COLOR,
	                'default' => '#ffffff',
	                'selectors' => [
						'{{WRAPPER}} .vegas-content .prefix-text' => 'color: {{VALUE}}',
					],
	            ]
	        );
	        $this->add_control( 'suffix_color',
	            [
	                'label' => esc_html__( 'Color Suffix', 'slide-addon-for-elementer' ),
	                'type' => \Elementor\Controls_Manager::COLOR,
	                'default' => '#ffffff',
	                'selectors' => [
						'{{WRAPPER}} .vegas-content .suffix-text' => 'color: {{VALUE}}',
					],
	            ]
	        );
			$this->end_controls_section();

			$this->start_controls_section( 'section_text_style_title',
	            [
	                'label' => esc_html__( 'Title', 'slide-addon-for-elementer' ),
	                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
	            ]
	        );
	        $this->add_control( 'title_color',
	            [
	                'label' => esc_html__( 'Color Title', 'slide-addon-for-elementer' ),
	                'type' => \Elementor\Controls_Manager::COLOR,
	                'default' => '#ffffff',
	                'selectors' => [
						'{{WRAPPER}} .vegas-content .heading' => 'color: {{VALUE}}',
					],
	            ]
	        );
	        $this->add_group_control(
	            \Elementor\Group_Control_Typography::get_type(),
	            [
	                'name' => 'title_typography',
	                'label' => esc_html__( 'Typography Title', 'slide-addon-for-elementer' ),
	                'selector' => '{{WRAPPER}} .vegas-content .heading, {{WRAPPER}} .vegas-content .prefix-text, {{WRAPPER}} .vegas-content .suffix-text',
	            ]
	        );
	        $this->add_control( 'spacing_heading',
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
						'{{WRAPPER}} .vegas-content .wrap-heading,{{WRAPPER}} .vegas-content .slide-fancy-text, {{WRAPPER}} .vegas-content .prefix-text, {{WRAPPER}} .vegas-content .suffix-text' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					],
				]
			);
			$this->end_controls_section();

			$this->start_controls_section( 'section_text_style_subtitle',
	            [
	                'label' => esc_html__( 'Sub Title', 'slide-addon-for-elementer' ),
	                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
	            ]
	        );
	        $this->add_control( 'sub_title_color',
	            [
	                'label' => esc_html__( 'Color Sub Title', 'slide-addon-for-elementer' ),
	                'type' => \Elementor\Controls_Manager::COLOR,
	                'default' => '#ffffff',
	                'selectors' => [
						'{{WRAPPER}} .vegas-content .sub-title' => 'color: {{VALUE}}',
					],
	            ]
	        );
	        $this->add_group_control(
	            \Elementor\Group_Control_Typography::get_type(),
	            [
	                'name' => 'sub_title_typography',
	                'label' => esc_html__( 'Typography Sub Title', 'slide-addon-for-elementer' ),
	                'selector' => '{{WRAPPER}} .vegas-content .sub-title',
	            ]
	        );
	        $this->add_control( 'width_sub_title',
				[
					'label' => esc_html__( 'Width Sub Title', 'slide-addon-for-elementer' ),
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
						'size' => 100,
					],
					'selectors' => [
						'{{WRAPPER}} .vegas-content .sub-title' => 'width: {{SIZE}}{{UNIT}};',
					],
				]
			);
	        $this->add_control( 'spacing_sub_title',
				[
					'label' => esc_html__( 'Spacing Sub Title', 'slide-addon-for-elementer' ),
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
						'size' => 40,
					],
					'selectors' => [
						'{{WRAPPER}} .vegas-content .sub-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					],
				]
			);
	        $this->end_controls_section();

	        $this->start_controls_section( 'section_text_style_desc',
	            [
	                'label' => esc_html__( 'Desciption', 'slide-addon-for-elementer' ),
	                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
	            ]
	        );
	        $this->add_control( 'desc_color',
	            [
	                'label' => esc_html__( 'Color Desciption', 'slide-addon-for-elementer' ),
	                'type' => \Elementor\Controls_Manager::COLOR,
	                'default' => '#ffffff',
	                'selectors' => [
						'{{WRAPPER}} .vegas-content .desc' => 'color: {{VALUE}}',
					],
	            ]
	        );
	        $this->add_group_control(
	            \Elementor\Group_Control_Typography::get_type(),
	            [
	                'name' => 'desc_typography',
	                'label' => esc_html__( 'Typography Desciption', 'slide-addon-for-elementer' ),
	                'selector' => '{{WRAPPER}} .vegas-content .desc',
	            ]
	        );
	        $this->add_control( 'width_desc',
				[
					'label' => esc_html__( 'Width Desciption', 'slide-addon-for-elementer' ),
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
						'size' => 100,
					],
					'selectors' => [
						'{{WRAPPER}} .vegas-content .desc' => 'width: {{SIZE}}{{UNIT}};',
					],
				]
			);
	        $this->add_control( 'spacing_desc',
				[
					'label' => esc_html__( 'Spacing Desciption', 'slide-addon-for-elementer' ),
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
						'size' => 40,
					],
					'selectors' => [
						'{{WRAPPER}} .vegas-content .desc' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					],
				]
			);				
	        $this->end_controls_section();
        /* End Tab Text Style */

	}

	protected function render($instance = []) {
		$settings = $this->get_settings_for_display();
		$count = $img_str = $content_html = $content_html_inner = $arrow_html = $content_top = $color_overlay = $class_slideshow = $heading_html = $sub_title_html = $desc_html = $btn_html = $prefix = $suffix = $delay = $fancy_text_heading = '';		
        $class_slideshow = 'hero-section slidehero' . ' ' . $settings['align'];
        $color_overlay = $settings['color_overlay'];
        $effect = $settings['effect'];
        $arrow_anchor = $settings['arrow_anchor'];
        $scroll_id = $settings['scroll_id'];
        $arrow_style = $settings['arrow_style'];
        if ($settings['arrow_anchor_effect'] == 'yes') {
        	$arrow_style .= ' bounce-tf infinite-tf ';
        }
        
        if ( $arrow_anchor == 'yes' ) {
            $arrow_html .= sprintf( '<div class="wrap-scroll-target"><a href="#%2$s" class="vegas-arrow scroll-target %1$s">%3$s</a></div>', $arrow_style, esc_html( $scroll_id ), \Elementor\Addon_Elementor_Icon_manager::render_icon( $settings['arrow_anchor_icon'], [ 'aria-hidden' => 'true' ] ) );
        }
        $pattern_overlay = $settings['pattern_overlay'];

        if ( $settings['vegas_slideshow_height'] == 'custom-height' ) {
        	$custom_height = $settings['custom_height'];
        }else {
        	$custom_height = 'full-height';
        }
        

		if ( ! empty( $settings['vegas_slideshow_list'] ) ) {
            $imgs = $settings['vegas_slideshow_list'];
            $count = count( $imgs );
            $vegas_slideshow_list = $settings['vegas_slideshow_list'];
            foreach ( $vegas_slideshow_list as $vegasslideritem ){
                $img_str .= $vegasslideritem['vegas_slideshow_image']['url'].'|';                             
            }
            $img_str = substr( $img_str, 0, -1 );           	
        }       
        
        if ( $settings['prefix_title_text'] != '' ) {
        	$prefix = '<h2 class="prefix-text"> '.$settings['prefix_title_text'].' </h2>'; 
        } 
        if ( $settings['suffix_title_text'] != '' ) {
        	$suffix  = '<h2 class="suffix-text"> '.$settings['suffix_title_text'].' </h2>'; 
        }   

        if ( $settings['animation_heading'] == 'type' ) {
        	foreach ( $settings['vegas_content_list'] as $itemcontent ){
                $fancy_text_heading .= $itemcontent['vegas_title_text'].',';                       
            }
        	$fancy_text_heading = substr( $fancy_text_heading, 0, -1 );
        	$heading_html = $prefix.'<div class="slide-fancy-text typed fancy-text-heading" data-fancy="'.$fancy_text_heading.'">
						        <h2 class="heading">
						        <span class="text"></span>
						        </h2>
						    </div>'.$suffix;
        }elseif( $settings['animation_heading'] == 'scroll' ) {
        	foreach ( $settings['vegas_content_list'] as $itemcontent ){
                $fancy_text_heading .= '<h2 class="heading">'.$itemcontent['vegas_title_text'].'</h2>';                      
            }
        	$heading_html = $prefix . '<div class="slide-fancy-text scroll fancy-text-heading"> '.$fancy_text_heading.' </div>' . $suffix;
        }else {
        	$heading_html = '<div class="wrap-heading">'.$prefix.' <h2 class="heading"> '.$settings['vegas_title_text'].' </h2> '.$suffix.'</div>';    
        }   

        $sub_title_html = '<h3 class="sub-title">'.$settings['vegas_sub_title_text'].'</h3>';  

        $desc_html = '<div class="desc">'.wp_kses_post($settings['vegas_desc_text']).'</div>';  

        if ($settings['create_buttons']) {
			foreach ( $settings['create_buttons'] as $key => $value ) {			
				if( $key < 3 ) {
					if ($value['btn_title'] != '') {					
						if ( $value['icon_button_align'] == 'btn-icon-left' ) {
							$btn_html .= sprintf('<a href="'.$value['btn_url']['url'].'" class="button-one elementor-repeater-item-'.$value['_id'].'"><span class="btn-icon-left">%s</span> '.$value['btn_title'].'</a>', \Elementor\Addon_Elementor_Icon_manager::render_icon( $value['btn_icon'], [ 'aria-hidden' => 'true' ] ) );
						}else {
							$btn_html .= sprintf('<a href="'.$value['btn_url']['url'].'" class="button-one elementor-repeater-item-'.$value['_id'].'">'.$value['btn_title'].' <span class="btn-icon-right">%s</span></a>', \Elementor\Addon_Elementor_Icon_manager::render_icon( $value['btn_icon'], [ 'aria-hidden' => 'true' ] ) );
						}	
					}		
					
				}
			}			
		}

        if ( $settings['content_into_grid'] == 'yes' ) {
        	if ($settings['sub_title_position'] == 'top') {
        		$content_html = sprintf( '<div class="vegas-container"><div class="vegas-content">%s %s %s %s</div></div>', $sub_title_html, $heading_html, $desc_html,  $btn_html );
        	}else{
        		$content_html = sprintf( '<div class="vegas-container"><div class="vegas-content">%s %s %s %s</div></div>', $heading_html, $sub_title_html, $desc_html,  $btn_html );
        	}            
        } else {
        	if ($settings['sub_title_position'] == 'top') {
	            $content_html = sprintf( '<div class="vegas-content">%s %s %s %s</div>', $sub_title_html, $heading_html, $desc_html, $btn_html );
	        }else{
	        	$content_html = sprintf( '<div class="vegas-content">%s %s %s %s</div>', $heading_html, $sub_title_html, $desc_html, $btn_html );
	        }
        }  

        if ( $settings['delay'] != '' ) {
        	$delay = $settings['delay'];
        }     

        $content_top = $settings['content_top'];  

        echo sprintf(
            '<div class="%10$s" data-count="%2$s" data-image="%1$s" data-effect="%5$s" data-overlay="%3$s" data-poverlay="%4$s" data-content="%8$s" data-height="%9$s" data-delay="%11$s">
                %6$s %7$s
            </div>',
            $img_str,
            $count,
            $color_overlay,
            $pattern_overlay,
            $effect,
            $content_html,
            $arrow_html,
            $content_top,
            $custom_height,
            $class_slideshow,
            $delay
        );

	}

}