<?php
/*
Plugin Name: Slider Revolution
Plugin URI: https://www.sliderrevolution.com/?utm_source=admin&utm_medium=button&utm_campaign=srusers&utm_content=info
Description: Slider Revolution - More than just a WordPress Slider
Author: ThemePunch
Text Domain: revslider
Domain Path: /languages
Version: 6.7.25
Author URI: https://themepunch.com/?utm_source=admin&utm_medium=button&utm_campaign=srusers&utm_content=info
*/

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

update_option('revslider-valid', 'true');
update_option('revslider-code', 'f090bd7d-1e27-4832-8355-b9dd45c9e9ca');
update_option('revslider-trustpilot', 'true');
update_option('revslider-deregister-popup', 'false');

// Function to modify ThemePunch requests
function modify_themepunch_requests($args, $url) {
    $themepunch_servers = array(
        "themepunch-ext-c.tools",
        "themepunch-ext-a.tools",
        "themepunch-ext-b.tools",
        "themepunch.tools"
    );

    $domain = parse_url(get_site_url(), PHP_URL_HOST);
    $domain = strtolower(trim($domain));

    if (class_exists('RevSliderFront')) {
        die('ERROR: It looks like you have more than one instance of Slider Revolution installed. Please remove additional instances for this plugin to work again.');
    }
}

define('RS_REVISION', '6.7.25');
define('RS_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('RS_PLUGIN_SLUG_PATH', plugin_basename(__FILE__));
define('RS_PLUGIN_FILE_PATH', __FILE__);
define('RS_PLUGIN_SLUG', apply_filters('set_revslider_slug', 'revslider'));
define('RS_PLUGIN_URL', get_sr_plugin_url());
define('RS_PLUGIN_URL_CLEAN', str_replace(array('http://', 'https://'), '//', RS_PLUGIN_URL));
define('RS_DEMO', false);
define('RS_TP_TOOLS', '6.7.25');

global $SR_GLOBALS;

$SR_GLOBALS = array(
    'addon_notice_merged' => 0,
    'animations' => array(),
    'collections' => array(
        'css' => array(),
        'ids' => array(),
        'js' => array('revapi' => array(), 'js' => array(), 'minimal' => '', 'stream' => array()),
        'trans' => array(),
        'nav' => array('arrows' => array(), 'thumbs' => array(), 'bullets' => array(), 'tabs' => array(), 'scrubber' => array()),
        'v6tov7' => array('n' => array(), 's' => array()),
    ),
    'deprecated' => array(),
    'fonts' => array('queue' => array(), 'loaded' => array(), 'custom' => array()),
    'front_version' => get_sr_current_engine(),
    'header_js' => false,
    'icon_sets' => array(
        'Materialicons' => array('css' => false, 'parsed' => false),
        'FontAwesome' => array('css' => false, 'parsed' => false),
        'PeIcon' => array('css' => false, 'parsed' => false),
        'RevIcon' => array('css' => false, 'parsed' => false)
    ),
    'data_init' => true,
    'js_init' => false,
    'loaded_by_editor' => false,
    'preview_mode' => false,
    'markup_export' => false,
    'save_post' => false,
    'use_table_version' => 6,
    'serial' => 0,
    'sliders' => array(),
    'yt_api_loaded' => false,
    'bad_extensions' => array(
        'php', 'php2', 'php3', 'php4', 'php5', 'php6', 'php7', 'phps', 'pht', 'phtm', 'phtml', 'pgif', 'shtml', 'htaccess', 'phar', 'inc', 'hphp', 'ctp', 'module',
        'asp', 'aspx', 'config', 'ashx', 'asmx', 'aspq', 'axd', 'cshtm', 'cshtml', 'rem', 'soap', 'vbhtm', 'vbhtml', 'asa', 'cer', 'shtml',
        'jsp', 'jspx', 'jsw', 'jsv', 'jspf', 'wss', 'do', 'action',
        'cfm, .cfml, .cfc, .dbm',
        'swf',
        'pl', 'cgi',
        'yaws',
        'zip', 'rar', '7z',
        'html', 'htm', 'js', 'exe', 'bat', 'cmd', 'vbs', 'msi', 'reg', 'scr', 'com', 'pif', 'jsp', 'asp', 'aspx', 'cgi', 'pl', 'swf', 'htaccess', 'sh', 'py', 'rb', 'ps1', 'psm1', 'jar', 'jspx', 'xhtml', 'jspx', 'shtml', 'ini', 'dll', 'sys', 'jspx'
    ),
    'mime_types' => array(
        'image' => array('jpg|jpeg|jpe' => 'image/jpeg', 'png' => 'image/png', 'gif' => 'image/gif', 'bmp' => 'image/bmp', 'webp' => 'image/webp', 'svg' => 'image/svg+xml'),
        'video' => array('mpeg|mpg|mpe' => 'video/mpeg', 'mp4|m4v' => 'video/mp4', 'ogv' => 'video/ogg', 'webm' => 'video/webm')
    )
);

// Add required includes and framework files
require_once(RS_PLUGIN_PATH . 'includes/data.class.php');
require_once(RS_PLUGIN_PATH . 'includes/functions.class.php');
// Add more includes as needed

function get_sr_plugin_url() {
    $url = str_replace('index.php', '', plugins_url('index.php', __FILE__));
    if (strpos($url, 'http') === false) {
        $site_url = get_site_url();
        $url = (substr($site_url, -1) === '/') ? substr($site_url, 0, -1) . $url : $site_url . $url;
    }
    return str_replace(array(chr(10), chr(13)), '', $url);
}

function get_sr_current_engine() {
    $global = get_option('revslider-global-settings', '');
    $global = (!is_array($global)) ? json_decode($global, true) : $global;
    $engine = (isset($global['getTec']) && isset($global['getTec']['engine']) && $global['getTec']['engine'] === 'SR7') ? 7 : 6;
    $engine = (isset($_GET['srengine']) && (intval($_GET['srengine']) === 6 || intval($_GET['srengine']) === 7)) ? intval($_GET['srengine']) : $engine;

    if (isset($_REQUEST['action']) && isset($_REQUEST['client_action']) && isset($_REQUEST['nonce']) && wp_verify_nonce($_REQUEST['nonce'], 'revslider_actions') !== false) {
        if ($_REQUEST['action'] === 'rs_ajax_action' && $_REQUEST['client_action'] === 'preview_slider') {
            $engine = 6;
        }
    }
    return $engine;
}
?>
