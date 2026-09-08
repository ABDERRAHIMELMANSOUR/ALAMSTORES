<?php
define('CONSTRAU_URL', __DIR__ . '/data/theme-src');

// Real values recovered from the site: brand colour sampled from the site logo,
// fonts from the theme's own declared defaults.
$MODS = array(
    'primary_color'  => '#7d0e7c',
    'general_color'  => '#666',
    'general_font_size'   => '15px',
    'general_line_height' => '25px',
    'general_letter_space'=> '0px',
    'global_boxed_container_width' => '1170',
    'global_boxed_offset' => '20',
    'primary_font'   => '{"font":"Lato","regularweight":"100,200,300,400,500,600,700,800,900","category":"serif"}',
    'second_font'    => '{"font":"Rajdhani","regularweight":"100,200,300,400,500,600,700,800,900","category":"serif"}',
);

function get_theme_mod($key, $default = false) {
    global $MODS;
    return array_key_exists($key, $MODS) ? $MODS[$key] : $default;
}
function apply_filters($tag, $value) { return $value; }
function constrau_hex2rgb($hex) {
    $hex = str_replace('#', '', $hex);
    if (strlen($hex) == 3) {
        $r = hexdec(substr($hex,0,1).substr($hex,0,1));
        $g = hexdec(substr($hex,1,1).substr($hex,1,1));
        $b = hexdec(substr($hex,2,1).substr($hex,2,1));
    } else {
        $r = hexdec(substr($hex,0,2));
        $g = hexdec(substr($hex,2,2));
        $b = hexdec(substr($hex,4,2));
    }
    return array($r,$g,$b);
}

function constrau_default_primary_font() {
    return json_encode(array('font'=>'Lato','regularweight'=>'100,200,300,400,500,600,700,800,900','category'=>'serif'));
}
function constrau_default_second_font() {
    return json_encode(array('font'=>'Rajdhani','regularweight'=>'100,200,300,400,500,600,700,800,900','category'=>'serif'));
}

$layout_woo    = include CONSTRAU_URL . '/customize/settings/layout-woo.php';
$layout_global = include CONSTRAU_URL . '/customize/settings/layout-global.php';
$general       = include CONSTRAU_URL . '/customize/settings/general.php';
echo $layout_woo . $layout_global . $general;
