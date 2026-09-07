<?php

if (!defined( 'ABSPATH' )) {
    exit;
}
if (!class_exists( 'Constrau_Customize' )){

class Constrau_Customize {
	
	public function __construct() {
        add_action( 'customize_register', array( $this, 'constrau_customize_register' ) );
    }

    public function constrau_customize_register($wp_customize) {
        
        $this->constrau_init_remove_setting( $wp_customize );
        $this->constrau_init_ova_typography( $wp_customize );
        $this->constrau_init_ova_layout( $wp_customize );
        $this->constrau_init_ova_header( $wp_customize );
        $this->constrau_init_ova_footer( $wp_customize );
        $this->constrau_init_ova_blog( $wp_customize );
        

        if( constrau_is_woo_active() ){
        	$this->constrau_init_ova_woo( $wp_customize );	
        }
   
        do_action( 'constrau_customize_register', $wp_customize );
    }

    public function constrau_init_remove_setting( $wp_customize ){
    	/* Remove Colors &  Header Image Customize */
		$wp_customize->remove_section('colors');
		$wp_customize->remove_section('header_image');
    }

    
    
    /* Typo */
    public function constrau_init_ova_typography($wp_customize){
    	
    	


    		/* Body Pane ******************************/
			$wp_customize->add_section( 'typo_general' , array(
			    'title'      => esc_html__( 'Typography', 'constrau' ),
			    'priority'   => 1,
			    // 'panel' => 'typo_panel',
			) );


				/* General Typo */
				$wp_customize->add_setting( 'general_heading', array(
				  'default' => '',
				  'sanitize_callback' => 'sanitize_text_field' // Get function name 
				  
				) );

				$wp_customize->add_control(
					new Constrau_Customize_Control_Heading( 
					$wp_customize, 
					'general_heading', 
					array(
						'label'          => esc_html__('General Typo','constrau'),
			            'section'        => 'typo_general',
			            'settings'       => 'general_heading',
					) )
				);


				/* General Font */
				$wp_customize->add_setting( 'primary_font',
					array(
						'default' => constrau_default_primary_font(),
						'sanitize_callback' => 'constrau_google_font_sanitization'
					)
				);
				$wp_customize->add_control( new Constrau_Google_Font_Select_Custom_Control( $wp_customize, 'primary_font',
					array(
						'label' => esc_html__( 'Primary Font', 'constrau' ),
						'section' => 'typo_general',
						'input_attrs' => array(
							'font_count' => 'all',
							'orderby' => 'popular',
						),
					)
				) );
				

				/* Font Size */
				$wp_customize->add_setting( 'general_font_size', array(
				  'type' => 'theme_mod', // or 'option'
				  'capability' => 'edit_theme_options',
				  'theme_supports' => '', // Rarely needed.
				  'default' => '15px',
				  'transport' => 'refresh', // or postMessage
				  'sanitize_callback' => 'sanitize_text_field' // Get function name 
				  
				) );
				
				$wp_customize->add_control('general_font_size', array(
					'label' => esc_html__('Font Size','constrau'),
					'description' => esc_html__('Example: 16px, 1.2em','constrau'),
					'section' => 'typo_general',
					'settings' => 'general_font_size',
					'type' 		=>'text'
				));

				/* Line Height */
				$wp_customize->add_setting( 'general_line_height', array(
				  'type' => 'theme_mod', // or 'option'
				  'capability' => 'edit_theme_options',
				  'theme_supports' => '', // Rarely needed.
				  'default' => '25px',
				  'transport' => 'refresh', // or postMessage
				  'sanitize_callback' => 'sanitize_text_field' // Get function name 
				  
				) );
				
				$wp_customize->add_control('general_line_height', array(
					'label' => esc_html__('Line height','constrau'),
					'description' => esc_html__('Example: 23px, 1.6em','constrau'),
					'section' => 'typo_general',
					'settings' => 'general_line_height',
					'type' 		=>'text'
				));


				/* Letter Space */
				$wp_customize->add_setting( 'general_letter_space', array(
				  'type' => 'theme_mod', // or 'option'
				  'capability' => 'edit_theme_options',
				  'theme_supports' => '', // Rarely needed.
				  'default' => '0px',
				  'transport' => 'refresh', // or postMessage
				  'sanitize_callback' => 'sanitize_text_field' // Get function name 
				  
				) );
				
				$wp_customize->add_control('general_letter_space', array(
					'label' => esc_html__('Letter Spacing','constrau'),
					'description' => esc_html__('Example: 0px, 0.5em','constrau'),
					'section' => 'typo_general',
					'settings' => 'general_letter_space',
					'type' 		=>'text'
				));


				$wp_customize->add_setting( 'general_color', array(
				  'type' => 'theme_mod', // or 'option'
				  'capability' => 'edit_theme_options',
				  'theme_supports' => '', // Rarely needed.
				  'default' => '#666',
				  'transport' => 'refresh', // or postMessage
				  'sanitize_callback' => 'sanitize_text_field' // Get function name 
				  
				) );
				$wp_customize->add_control(
					new WP_Customize_Color_Control(
					$wp_customize, 
					'general_color', 
					array(
						'label'          => esc_html__("Content Color",'constrau'),
			            'section'        => 'typo_general',
			            'settings'       => 'general_color',
					) ) 
				);
						

				/* Message */
				$wp_customize->add_setting( 'second_font_message', array(
				  'type' => 'theme_mod', // or 'option'
				  'capability' => 'edit_theme_options',
				  'theme_supports' => '', // Rarely needed.
				  'default' => '',
				  'transport' => 'refresh', // or postMessage
				  'sanitize_callback' => 'sanitize_text_field' // Get function name 
				  
				) );
				$wp_customize->add_control(
					new Constrau_Customize_Control_Heading( 
					$wp_customize, 
					'second_font_message', 
					array(
						'label'          => esc_html__('Second Font','constrau'),
			            'section'        => 'typo_general',
			            'settings'       => 'second_font_message',
					) )
				);

				/* Heading Font */
				$wp_customize->add_setting( 'second_font',
					array(
						'default' => constrau_default_second_font(),
						'sanitize_callback' => 'constrau_google_font_sanitization'
					)
				);
				$wp_customize->add_control( new Constrau_Google_Font_Select_Custom_Control( $wp_customize, 'second_font',
					array(
						'label' => esc_html__( 'Font', 'constrau' ),
						'section' => 'typo_general',
						'input_attrs' => array(
							'font_count' => 'all',
							'orderby' => 'popular',
						),
					)
				) );



				$wp_customize->add_setting( 'color_message', array(
				  'type' => 'theme_mod', // or 'option'
				  'capability' => 'edit_theme_options',
				  'theme_supports' => '', // Rarely needed.
				  'default' => '',
				  'transport' => 'refresh', // or postMessage
				  'sanitize_callback' => 'sanitize_text_field' // Get function name 
				  
				) );

				$wp_customize->add_control(
					new Constrau_Customize_Control_Heading( 
					$wp_customize, 
					'color_message', 
					array(
						'label'          => esc_html__('General Color','constrau'),
			            'section'        => 'typo_general',
			            'settings'       => 'color_message',
					) )
				);


				$wp_customize->add_setting( 'primary_color', array(
				  'type' => 'theme_mod', // or 'option'
				  'capability' => 'edit_theme_options',
				  'theme_supports' => '', // Rarely needed.
				  'default' => '#fed501',
				  'transport' => 'refresh', // or postMessage
				  'sanitize_callback' => 'sanitize_text_field' // Get function name 
				  
				) );
				$wp_customize->add_control(
					new WP_Customize_Color_Control(
					$wp_customize, 
					'primary_color', 
					array(
						'label'          => esc_html__("Primary color",'constrau'),
			            'section'        => 'typo_general',
			            'settings'       => 'primary_color',
					) ) 
				);

				



				/* Custom Font */
				/* Message */
				$wp_customize->add_setting( 'custom_font_message', array(
				  'type' => 'theme_mod', // or 'option'
				  'capability' => 'edit_theme_options',
				  'theme_supports' => '', // Rarely needed.
				  'default' => '',
				  'transport' => 'refresh', // or postMessage
				  'sanitize_callback' => 'sanitize_text_field' // Get function name 
				  
				) );
				$wp_customize->add_control(
					new Constrau_Customize_Control_Heading( 
					$wp_customize, 
					'custom_font_message', 
					array(
						'label'          => esc_html__('Custom Font','constrau'),
			            'section'        => 'typo_general',
			            'settings'       => 'custom_font_message',
					) )
				);


				$wp_customize->add_control(
					new Constrau_Customize_Control_Heading( 
					$wp_customize, 
					'custom_font_message', 
					array(
						'label'          => esc_html__('Custom Font','constrau'),
			            'section'        => 'typo_general',
			            'settings'       => 'custom_font_message',
					) )
				);

				$wp_customize->add_setting( 'ova_custom_font', array(
				  'type' => 'theme_mod', // or 'option'
				  'capability' => 'edit_theme_options',
				  'theme_supports' => '', // Rarely needed.
				  'default' => '',
				  'transport' => 'refresh', // or postMessage
				  'sanitize_callback' => 'sanitize_text_field' // Get function name 
				  
				) );

				$wp_customize->add_control('ova_custom_font', array(
					'label' => esc_html__('Custom Font','constrau'),
					'description' => esc_html__('Step 1: Insert font-face in style.css file: Refer https://www.w3schools.com/cssref/css3_pr_font-face_rule.asp. Step 2: Insert font-family and font-weight like format: 
						["Perpetua", "Regular:Bold:Italic:Light"]. Step 3: Refresh customize page to display new font in dropdown font field.','constrau'),
					'section' => 'typo_general',
					'settings' => 'ova_custom_font',
					'type' =>'textarea'
				));

		
			

    }


    /* Layout */
    public function constrau_init_ova_layout( $wp_customize ){

    	$wp_customize->add_section( 'layout_section' , array(
		    'title'      => esc_html__( 'Layout', 'constrau' ),
		    'priority'   => 2,
		) );


    		$wp_customize->add_setting( 'global_preload', array(
			  'type' => 'theme_mod', // or 'option'
			  'capability' => 'edit_theme_options',
			  'theme_supports' => '', // Rarely needed.
			  'default' => 'yes',
			  'transport' => 'refresh', // or postMessage
			  'sanitize_callback' => 'sanitize_text_field' // Get function name 
			  
			) );
			$wp_customize->add_control('global_preload', array(
				'label' => esc_html__('Preload','constrau'),
				'section' => 'layout_section',
				'settings' => 'global_preload',
				'type' =>'select',
				'choices' => array(
					'yes' => esc_html__('Yes', 'constrau'),
					'no' => esc_html__('No', 'constrau')
				)
			));

			$wp_customize->add_setting( 'global_layout', array(
			  'type' => 'theme_mod', // or 'option'
			  'capability' => 'edit_theme_options',
			  'theme_supports' => '', // Rarely needed.
			  'default' => 'layout_2r',
			  'transport' => 'refresh', // or postMessage
			  'sanitize_callback' => 'sanitize_text_field' // Get function name 
			  
			) );
			$wp_customize->add_control('global_layout', array(
				'label' => esc_html__('Layout','constrau'),
				'section' => 'layout_section',
				'settings' => 'global_layout',
				'type' =>'select',
				'choices' => apply_filters( 'constrau_define_layout', '' )
			));

			$wp_customize->add_setting( 'global_sidebar_width', array(
			  'type' => 'theme_mod', // or 'option'
			  'capability' => 'edit_theme_options',
			  'theme_supports' => '', // Rarely needed.
			  'default' => '320',
			  'transport' => 'refresh', // or postMessage
			  'sanitize_callback' => 'sanitize_text_field' // Get function name 
			  
			) );
			$wp_customize->add_control('global_sidebar_width', array(
				'label' => esc_html__('Sidebar Width (px)','constrau'),
				'section' => 'layout_section',
				'settings' => 'global_sidebar_width',
				'type' =>'number'
			));
			

			$wp_customize->add_setting( 'global_width_content', array(
			  'type' => 'theme_mod', // or 'option'
			  'capability' => 'edit_theme_options',
			  'theme_supports' => '', // Rarely needed.
			  'default' => '1170',
			  'transport' => 'refresh', // or postMessage
			  'sanitize_callback' => 'sanitize_text_field' // Get function name 
			  
			) );
			$wp_customize->add_control('global_width_content', array(
				'label' => esc_html__('Width Content (px)','constrau'),
				'section' => 'layout_section',
				'settings' => 'global_width_content',
				'type' =>'number',
				'default' => '1170'
			));

			$wp_customize->add_setting( 'global_width_site', array(
			  'type' => 'theme_mod', // or 'option'
			  'capability' => 'edit_theme_options',
			  'theme_supports' => '', // Rarely needed.
			  'default' => 'wide',
			  'transport' => 'refresh', // or postMessage
			  'sanitize_callback' => 'sanitize_text_field' // Get function name 
			  
			) );
			$wp_customize->add_control('global_width_site', array(
				'label' => esc_html__('Width Site','constrau'),
				'section' => 'layout_section',
				'settings' => 'global_width_site',
				'type' =>'select',
				'choices' => apply_filters('constrau_define_wide_boxed', '')
			));

			$wp_customize->add_setting( 'global_boxed_container_width', array(
			  'type' => 'theme_mod', // or 'option'
			  'capability' => 'edit_theme_options',
			  'theme_supports' => '', // Rarely needed.
			  'default' => '1170',
			  'transport' => 'refresh', // or postMessage
			  'sanitize_callback' => 'sanitize_text_field' // Get function name 
			  
			) );
			$wp_customize->add_control('global_boxed_container_width', array(
				'label' => esc_html__('Boxed Container Width (px)','constrau'),
				'section' => 'layout_section',
				'settings' => 'global_boxed_container_width',
				'type' =>'number',
				'default' => '1170'
			));
			$wp_customize->add_setting( 'global_boxed_offset', array(
			  'type' => 'theme_mod', // or 'option'
			  'capability' => 'edit_theme_options',
			  'theme_supports' => '', // Rarely needed.
			  'default' => '20',
			  'transport' => 'refresh', // or postMessage
			  'sanitize_callback' => 'sanitize_text_field' // Get function name 
			  
			) );
			$wp_customize->add_control('global_boxed_offset', array(
				'label' => esc_html__('Boxed Offset (px)','constrau'),
				'section' => 'layout_section',
				'settings' => 'global_boxed_offset',
				'type' =>'number',
				'default' => '20'
			));

    }

    /* Header */
    public function constrau_init_ova_header( $wp_customize ){

    	$wp_customize->add_section( 'header_section' , array(
		    'title'      => esc_html__( 'Header', 'constrau' ),
		    'priority'   => 3,
		) );

			$wp_customize->add_setting( 'global_header', array(
			  'type' => 'theme_mod', // or 'option'
			  'capability' => 'edit_theme_options',
			  'theme_supports' => '', // Rarely needed.
			  'default' => 'default',
			  'transport' => 'refresh', // or postMessage
			  'sanitize_callback' => 'sanitize_text_field' // Get function name 
			  
			) );
			$wp_customize->add_control('global_header', array(
				'label' => esc_html__('Header Default','constrau'),
				'description' => esc_html__('This isn\'t effect in Blog' ,'constrau'),
				'section' => 'header_section',
				'settings' => 'global_header',
				'type' =>'select',
				'choices' => apply_filters('constrau_list_header', '')
			));

    }

    /* Footer */
    public function constrau_init_ova_footer( $wp_customize ){

    	$wp_customize->add_section( 'footer_section' , array(
		    'title'      => esc_html__( 'Footer', 'constrau' ),
		    'priority'   => 4,
		) );

			$wp_customize->add_setting( 'global_footer', array(
			  'type' => 'theme_mod', // or 'option'
			  'capability' => 'edit_theme_options',
			  'theme_supports' => '', // Rarely needed.
			  'default' => 'default',
			  'transport' => 'refresh', // or postMessage
			  'sanitize_callback' => 'sanitize_text_field' // Get function name 
			  
			) );
			$wp_customize->add_control('global_footer', array(
				'label' => esc_html__('Footer Default','constrau'),
				'description' => esc_html__('This isn\'t effect in Blog' ,'constrau'),
				'section' => 'footer_section',
				'settings' => 'global_footer',
				'type' =>'select',
				'choices' => apply_filters('constrau_list_footer', '')
			));

    }


    /* Blog */
    public function constrau_init_ova_blog( $wp_customize ){

    	$wp_customize->add_panel( 'blog_panel', array(
		    'title'      => esc_html__( 'Blog', 'constrau' ),
		    'priority' => 5,
		) );

			$wp_customize->add_section( 'blog_section' , array(
			    'title'      => esc_html__( 'Archive', 'constrau' ),
			    'priority'   => 30,
			    'panel' => 'blog_panel',
			) );

				$wp_customize->add_setting( 'blog_template', array(
				  'type' => 'theme_mod', // or 'option'
				  'capability' => 'edit_theme_options',
				  'theme_supports' => '', // Rarely needed.
				  'default' => 'classic_v1',
				  'transport' => 'refresh', // or postMessage
				  'sanitize_callback' => 'sanitize_text_field' // Get function name 
				  
				) );
				$wp_customize->add_control('blog_template', array(
					'label' => esc_html__('Type','constrau'),
					'section' => 'blog_section',
					'settings' => 'blog_template',
					'type' =>'select',
					'choices' => array(
						'classic_v1' => esc_html__('Classic V1', 'constrau'),
						'classic_v2' => esc_html__('Classic V2', 'constrau'),
						'grid_v1' => esc_html__('Grid V1', 'constrau'),
						'grid_v2' => esc_html__('Grid V2', 'constrau'),
					)
				));

				$wp_customize->add_setting( 'blog_layout', array(
				  'type' => 'theme_mod', // or 'option'
				  'capability' => 'edit_theme_options',
				  'theme_supports' => '', // Rarely needed.
				  'default' => 'layout_2r',
				  'transport' => 'refresh', // or postMessage
				  'sanitize_callback' => 'sanitize_text_field' // Get function name 
				  
				) );
				$wp_customize->add_control('blog_layout', array(
					'label' => esc_html__('Layout','constrau'),
					'section' => 'blog_section',
					'settings' => 'blog_layout',
					'type' =>'select',
					'choices' => apply_filters( 'constrau_define_layout', '' )
				));

				$wp_customize->add_setting( 'blog_sidebar_width', array(
				  'type' => 'theme_mod', // or 'option'
				  'capability' => 'edit_theme_options',
				  'theme_supports' => '', // Rarely needed.
				  'default' => '320',
				  'transport' => 'refresh', // or postMessage
				  'sanitize_callback' => 'sanitize_text_field' // Get function name 
				  
				) );
				$wp_customize->add_control('blog_sidebar_width', array(
					'label' => esc_html__('Sidebar Width (px)','constrau'),
					'section' => 'blog_section',
					'settings' => 'blog_sidebar_width',
					'type' =>'number'
				));



				$wp_customize->add_setting( 'show_date_blog', array(
				  'type' => 'theme_mod', // or 'option'
				  'capability' => 'edit_theme_options',
				  'theme_supports' => '', // Rarely needed.
				  'default' => 'no',
				  'transport' => 'refresh', // or postMessage
				  'sanitize_callback' => 'sanitize_text_field' // Get function name 
				  
				) );
				$wp_customize->add_control('show_date_blog', array(
					'label' => esc_html__('Show date blog','constrau'),
					'section' => 'blog_section',
					'settings' => 'show_date_blog',
					'choices' => [
						'yes' => __('Yes', 'constrau'),
						'no' => __('No', 'constrau'),
					],
					'type' =>'select'
				));


				$wp_customize->add_setting( 'num_text_blog_grid', array(
				  'type' => 'theme_mod', // or 'option'
				  'capability' => 'edit_theme_options',
				  'theme_supports' => '', // Rarely needed.
				  'default' => '22',
				  'transport' => 'refresh', // or postMessage
				  'sanitize_callback' => 'sanitize_text_field' // Get function name 
				  
				) );
				$wp_customize->add_control('num_text_blog_grid', array(
					'label' => esc_html__('Number Text Grid','constrau'),
					'section' => 'blog_section',
					'settings' => 'num_text_blog_grid',
					'type' =>'number'
				));
				

				$wp_customize->add_setting( 'blog_header', array(
				  'type' => 'theme_mod', // or 'option'
				  'capability' => 'edit_theme_options',
				  'theme_supports' => '', // Rarely needed.
				  'default' => 'default',
				  'transport' => 'refresh', // or postMessage
				  'sanitize_callback' => 'sanitize_text_field' // Get function name 
				  
				) );
				$wp_customize->add_control('blog_header', array(
					'label' => esc_html__('Header','constrau'),
					'section' => 'blog_section',
					'settings' => 'blog_header',
					'type' =>'select',
					'choices' => apply_filters('constrau_list_header', '')
				));

				$wp_customize->add_setting( 'blog_footer', array(
				  'type' => 'theme_mod', // or 'option'
				  'capability' => 'edit_theme_options',
				  'theme_supports' => '', // Rarely needed.
				  'default' => 'default',
				  'transport' => 'refresh', // or postMessage
				  'sanitize_callback' => 'sanitize_text_field' // Get function name 
				  
				) );
				$wp_customize->add_control('blog_footer', array(
					'label' => esc_html__('Footer','constrau'),
					'section' => 'blog_section',
					'settings' => 'blog_footer',
					'type' =>'select',
					'choices' => apply_filters('constrau_list_footer', '')
				));


			$wp_customize->add_section( 'single_section' , array(
			    'title'      => esc_html__( 'Single', 'constrau' ),
			    'priority'   => 30,
			    'panel' => 'blog_panel',
			) );	

				$wp_customize->add_setting( 'single_layout', array(
				  'type' => 'theme_mod', // or 'option'
				  'capability' => 'edit_theme_options',
				  'theme_supports' => '', // Rarely needed.
				  'default' => 'layout_2r',
				  'transport' => 'refresh', // or postMessage
				  'sanitize_callback' => 'sanitize_text_field' // Get function name 
				  
				) );
				$wp_customize->add_control('single_layout', array(
					'label' => esc_html__('Layout','constrau'),
					'section' => 'single_section',
					'settings' => 'single_layout',
					'type' =>'select',
					'choices' => apply_filters( 'constrau_define_layout', '' )
				));

				$wp_customize->add_setting( 'single_sidebar_width', array(
				  'type' => 'theme_mod', // or 'option'
				  'capability' => 'edit_theme_options',
				  'theme_supports' => '', // Rarely needed.
				  'default' => '320',
				  'transport' => 'refresh', // or postMessage
				  'sanitize_callback' => 'sanitize_text_field' // Get function name 
				  
				) );
				$wp_customize->add_control('single_sidebar_width', array(
					'label' => esc_html__('Sidebar Width (px)','constrau'),
					'section' => 'single_section',
					'settings' => 'single_sidebar_width',
					'type' =>'number'
				));


				

				$wp_customize->add_setting( 'single_header', array(
				  'type' => 'theme_mod', // or 'option'
				  'capability' => 'edit_theme_options',
				  'theme_supports' => '', // Rarely needed.
				  'default' => 'default',
				  'transport' => 'refresh', // or postMessage
				  'sanitize_callback' => 'sanitize_text_field' // Get function name 
				  
				) );
				$wp_customize->add_control('single_header', array(
					'label' => esc_html__('Header','constrau'),
					'section' => 'single_section',
					'settings' => 'single_header',
					'type' =>'select',
					'choices' => apply_filters('constrau_list_header', '')
				));

				$wp_customize->add_setting( 'single_footer', array(
				  'type' => 'theme_mod', // or 'option'
				  'capability' => 'edit_theme_options',
				  'theme_supports' => '', // Rarely needed.
				  'default' => 'default',
				  'transport' => 'refresh', // or postMessage
				  'sanitize_callback' => 'sanitize_text_field' // Get function name 
				  
				) );
				$wp_customize->add_control('single_footer', array(
					'label' => esc_html__('Footer','constrau'),
					'section' => 'single_section',
					'settings' => 'single_footer',
					'type' =>'select',
					'choices' => apply_filters('constrau_list_footer', '')
				));

    }

    public function constrau_init_ova_woo( $wp_customize ){

		$wp_customize->add_setting( 'woo_layout', array(
			'type'              => 'theme_mod', // or 'option'
			'capability'        => 'edit_theme_options',
			'theme_supports'    => '', // Rarely needed.
			'default'           => 'layout_1c',
			'transport'         => 'refresh', // or postMessage
			'sanitize_callback' => 'sanitize_text_field' // Get function name 
				  
		) );
		$wp_customize->add_control('woo_layout', array(
			'label'    => esc_html__('Layout Archive','constrau'),
			'section'  => 'woocommerce_product_catalog',
			'settings' => 'woo_layout',
			'type'     =>'select',
			'choices'  => apply_filters( 'constrau_define_layout', '' )
		));

		$wp_customize->add_setting( 'woo_sidebar_width', array(
			'type'              => 'theme_mod', // or 'option'
			'capability'        => 'edit_theme_options',
			'theme_supports'    => '', // Rarely needed.
			'default'           => '320',
			'transport'         => 'refresh', // or postMessage
			'sanitize_callback' => 'sanitize_text_field' // Get function name 
		  
		) );
		$wp_customize->add_control('woo_sidebar_width', array(
			'label'    => esc_html__('Sidebar Width (px)','constrau'),
			'section'  => 'woocommerce_product_catalog',
			'settings' => 'woo_sidebar_width',
			'type'     =>'number'
		));

		$wp_customize->add_setting( 'header_shop_background_image', array(
			'type'              => 'theme_mod', // or 'option'
			'capability'        => 'edit_theme_options',
			'theme_supports'    => '', // Rarely needed.
			'transport'         => 'refresh', // or postMessage
			'sanitize_callback' => 'sanitize_text_field' // Get function name 
                  
        ) );

        $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'header_shop_background_image', array(
			'label'    => esc_html__('Header Catalog Background Image', 'constrau'),
			'section'  => 'woocommerce_product_catalog',
			'settings' => 'header_shop_background_image',    
        )));

		/* Custom Header_Footer Woo */

		/* Header Woo */

        $wp_customize->add_section( 'woo_header_section' , array(
		    'title'      => esc_html__( 'Header', 'constrau' ),
		    'priority'   => 30,
		    'panel' => 'woocommerce',
		) );

		$wp_customize->add_setting( 'woo_header_archive', array(
			'type'              => 'theme_mod', // or 'option'
			'capability'        => 'edit_theme_options',
			'theme_supports'    => '', // Rarely needed.
			'default'           => 'default',
			'transport'         => 'refresh', // or postMessage
			'sanitize_callback' => 'sanitize_text_field' // Get function name 
		  
		) );
		$wp_customize->add_control('woo_header_archive', array(
			'label'    => esc_html__('Header Archive','constrau'),
			'section'  => 'woo_header_section',
			'settings' => 'woo_header_archive',
			'type'     =>'select',
			'choices'  => apply_filters('constrau_list_header', '')
		));

		$wp_customize->add_setting( 'woo_header_single', array(
			'type'              => 'theme_mod', // or 'option'
			'capability'        => 'edit_theme_options',
			'theme_supports'    => '', // Rarely needed.
			'default'           => 'default',
			'transport'         => 'refresh', // or postMessage
			'sanitize_callback' => 'sanitize_text_field' // Get function name 
		  
		) );
		$wp_customize->add_control('woo_header_single', array(
			'label'    => esc_html__('Header Single','constrau'),
			'section'  => 'woo_header_section',
			'settings' => 'woo_header_single',
			'type'     =>'select',
			'choices'  => apply_filters('constrau_list_header', '')
		));

		/* Footer Woo */ 

        $wp_customize->add_section( 'woo_footer_section' , array(
			'title'    => esc_html__( 'Footer', 'constrau' ),
			'priority' => 30,
			'panel'    => 'woocommerce',
		) );

		$wp_customize->add_setting( 'woo_archive_footer', array(
			'type'              => 'theme_mod', // or 'option'
			'capability'        => 'edit_theme_options',
			'theme_supports'    => '', // Rarely needed.
			'default'           => 'default',
			'transport'         => 'refresh', // or postMessage
			'sanitize_callback' => 'sanitize_text_field' // Get function name 
		  
		) );

		$wp_customize->add_control('woo_archive_footer', array(
			'label'    => esc_html__('Footer Archive','constrau'),
			'section'  => 'woo_footer_section',
			'settings' => 'woo_archive_footer',
			'type'     =>'select',
			'choices'  => apply_filters('constrau_list_footer', '')
		));

		$wp_customize->add_setting( 'woo_single_footer', array(
			'type'              => 'theme_mod', // or 'option'
			'capability'        => 'edit_theme_options',
			'theme_supports'    => '', // Rarely needed.
			'default'           => 'default',
			'transport'         => 'refresh', // or postMessage
			'sanitize_callback' => 'sanitize_text_field' // Get function name 
		  
		) );

		$wp_customize->add_control('woo_single_footer', array(
			'label'    => esc_html__('Footer Single','constrau'),
			'section'  => 'woo_footer_section',
			'settings' => 'woo_single_footer',
			'type'     =>'select',
			'choices'  => apply_filters('constrau_list_footer', '')
		));

    }

	
}

}

new Constrau_Customize();






