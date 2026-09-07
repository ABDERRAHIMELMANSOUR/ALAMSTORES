<?php
/*
Plugin Name: Simple Slider Addon For Elementor
Description: Simple Slider Addon For Elementor.
Author: Themesflat
Author URI: https://codecanyon.net/user/themesflat
Version: 1.0.3
Text Domain: slide-addon-for-elementer
Domain Path: /languages
License: GNU General Public License v3.0
*/

if (!defined('ABSPATH'))
    exit;

final class Slider_Addon_Elementor {

    const VERSION = '1.0.3';
    const MINIMUM_ELEMENTOR_VERSION = '2.0.0';
    const MINIMUM_PHP_VERSION = '5.2';

    private static $_instance = null;

    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function __construct() {
        add_action( 'init', [ $this, 'i18n' ] );
        add_action( 'plugins_loaded', [ $this, 'init' ] );
        define('URL_PLUGIN_SLIDE_ELEMENTOR', plugins_url('/', __FILE__));
        
        add_action( 'elementor/frontend/after_register_styles', [ $this, 'widget_styles' ] , 100 );
        add_action( 'elementor/frontend/after_register_scripts', [ $this, 'widget_scripts' ], 100 );
    }

    public function i18n() {
        load_plugin_textdomain( 'slide-addon-for-elementer', false, basename( dirname( __FILE__ ) ) . '/languages' );
    }

    public function init() {// Check if Elementor installed and activated        
        if ( ! did_action( 'elementor/loaded' ) ) {
            add_action( 'admin_notices', [ $this, 'tf_admin_notice_missing_main_plugin' ] );
            return;
        }

        // Check for required Elementor version
        if ( ! version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
            add_action( 'admin_notices', [ $this, 'admin_notice_minimum_elementor_version' ] );
            return;
        }

        // Check for required PHP version
        if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
            add_action( 'admin_notices', [ $this, 'admin_notice_minimum_php_version' ] );
            return;
        }
        
        add_action( 'elementor/widgets/widgets_registered', [ $this, 'init_widgets' ] );
        add_action( 'elementor/controls/controls_registered', [ $this, 'init_controls' ] );

        require_once( __DIR__ . '/addon-elementor-icon-manager.php' );

        add_action( 'elementor/elements/categories_registered', function () {
            $elementsManager = \Elementor\Plugin::instance()->elements_manager;
            $elementsManager->add_category(
                'themesflat_addons',
                array(
                  'title' => 'THEMESFLAT ADDONS',
                  'icon'  => 'fonts',
            ));
        });
    }

    public function tf_admin_notice_missing_main_plugin() {
        if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

        $message = sprintf(
            esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'slide-addon-for-elementer' ),
            '<strong>' . esc_html__( 'Simple Slider Addon For Elementor', 'slide-addon-for-elementer' ) . '</strong>',
            '<strong>' . esc_html__( 'Elementor', 'slide-addon-for-elementer' ) . '</strong>'
        );

        printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
    }

    public function admin_notice_minimum_elementor_version() {
        if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );
        $message = sprintf(
            esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'slide-addon-for-elementer' ),
            '<strong>' . esc_html__( 'Simple Slider Addon For Elementor', 'slide-addon-for-elementer' ) . '</strong>',
            '<strong>' . esc_html__( 'Elementor', 'slide-addon-for-elementer' ) . '</strong>',
             self::MINIMUM_ELEMENTOR_VERSION
        );

        printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

    }

    public function admin_notice_minimum_php_version() {

        if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );
        $message = sprintf(
            esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'slide-addon-for-elementer' ),
            '<strong>' . esc_html__( 'Simple Slider Addon For Elementor', 'slide-addon-for-elementer' ) . '</strong>',
            '<strong>' . esc_html__( 'PHP', 'slide-addon-for-elementer' ) . '</strong>',
             self::MINIMUM_PHP_VERSION
        );

        printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

    }

    public function init_widgets() {
        require_once( __DIR__ . '/widgets/widget-simple-slide.php' );
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Slide_Widget() );
        require_once( __DIR__ . '/widgets/widget-flex-slide.php' );
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Flex_Slide_Widget() );
    }

    public function init_controls() {}    

    public function widget_styles() {
        wp_register_style( 'font-awesome', plugins_url( '/assets/css/font-awesome.min.css', __FILE__ ) );
        wp_register_style( 'vegas', plugins_url( '/assets/css/vegas.css', __FILE__ ) );
        wp_register_style( 'flexslider', plugins_url( '/assets/css/flexslider.css', __FILE__ ) );
        wp_register_style( 'slide-animate', plugins_url( '/assets/css/animate.css', __FILE__ ) );
        wp_register_style( 'slide-addon-for-elementor-style', plugins_url( '/assets/css/slide-addon-for-elementor-style.css', __FILE__ ) );
        wp_enqueue_style( 'font-awesome' );
        wp_enqueue_style( 'vegas' );
        wp_enqueue_style( 'flexslider' );
        wp_enqueue_style( 'slide-animate' );
        wp_enqueue_style( 'slide-addon-for-elementor-style' );
    }

    public function widget_scripts() {
        wp_register_script( 'jquery-easing', plugins_url( '/assets/js/jquery.easing.js', __FILE__ ), [ 'jquery' ], false, true );
        wp_register_script( 'vegas', plugins_url( '/assets/js/vegas.js', __FILE__ ), [ 'jquery' ], false, true );
        wp_register_script( 'typed', plugins_url( '/assets/js/typed.js', __FILE__ ), [ 'jquery' ], false, true );
        wp_register_script( 'flexslider', plugins_url( '/assets/js/jquery.flexslider-min.js', __FILE__ ), [ 'jquery' ], false, true );
        wp_register_script( 'slide-addon-for-elementor-script', plugins_url( '/assets/js/slide-addon-for-elementor-script.js', __FILE__ ), [ 'jquery' ], false, true );
        wp_enqueue_script( 'jquery-easing' );
        wp_enqueue_script( 'vegas' );
        wp_enqueue_script( 'typed' );
        wp_enqueue_script( 'flexslider' );
        wp_enqueue_script( 'slide-addon-for-elementor-script' );
    }

}
Slider_Addon_Elementor::instance();