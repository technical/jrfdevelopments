<?php
/*
  Plugin Name: WP Mobileme Gallery
  Plugin URI: http://www.sebastiengillard.fr/en/webdesigner-en/wp-mobileme-plugin-en/
  Description: Display galleries and photos from a public Mobileme gallery.
  Version: 0.7
  Author: Sébastien Gillard alias Mac
  Author URI: http://www.sebastiengillard.fr
  License: GPL2
 */
/*  Copyright 2010  Sébastien Gillard alias Mac
  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License, version 2, as
  published by the Free Software Foundation.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */
/**
 * @package wp_mmg
 * @author Sébastien Gillard
 */
# Options
$wp_mmg_options = get_option('wp_mmg_options');
$wp_mmg_data = get_option('wp_mmg_data');
$wp_mmg_errors = new WP_Error();

# Add Option Page
add_action('admin_menu', 'wp_mmg_menu');

function wp_mmg_menu() {
    // create new top-level menu
    add_options_page(__('WP Mobileme Gallery', 'wp_mmg'), __('WP Mobileme Gallery', 'wp_mmg'), 'manage_options', 'wp-mobileme-gallery/wp_mmg_options.php');
}

# Load CSS
add_action('wp_print_styles', 'wp_mmg_style');

function wp_mmg_style() {
    global $wp_mmg_options;
    wp_register_style('wp_mmg_gallery_video-js', plugins_url('video-js/video-js.css', __FILE__));
    wp_register_style('wp_mmg_gallery_style', $wp_mmg_options['theme']);
    wp_register_style('fancybox', plugins_url('/fancybox/jquery.fancybox-1.3.4.css', __FILE__));
    wp_register_style('jquery-ui-css', "http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/themes/base/jquery-ui.css");
    wp_enqueue_style('jquery-ui-css');
    wp_enqueue_style('wp_mmg_gallery_video-js');
    wp_enqueue_style('wp_mmg_gallery_style');
    wp_enqueue_style('fancybox');
}

add_action('admin_print_styles', 'wp_mmg_style_admin');

function wp_mmg_style_admin() {
    global $wp_mmg_options;
    wp_register_style('jquery-ui-css', "http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/themes/base/jquery-ui.css");
    wp_enqueue_style('jquery-ui-css');
}


# Load jquery and thickbox
add_action('init', 'wp_mmg_init');

function wp_mmg_init() {
    wp_enqueue_script('jquery');
    wp_enqueue_script('jquery-ui-core');
    wp_enqueue_script('jquery-ui-tabs');
    wp_enqueue_script('wp_mmg',plugins_url('wp_mmg.js', __FILE__));
    wp_enqueue_script('video_js',plugins_url('video-js/video.js', __FILE__));
    wp_enqueue_script('fancybox',plugins_url('/fancybox/jquery.fancybox-1.3.4.pack.js', __FILE__));

}

# Add Widget
require_once 'wp_mmg_widget.php';
add_action('widgets_init', create_function('', 'return register_widget("wp_mmg_widget_config");'));

# Add Shortcode
require_once 'wp_mmg_shortcode.php';
add_shortcode('wp_mmg', 'wp_mmg_shortcode');

# Tinymce
include_once (dirname (__FILE__) . '/tinymce/tinymce.php');

# Languages
if(!load_plugin_textdomain('wp_mmg','/wp-content/languages/'))
    load_plugin_textdomain( 'wp_mmg', false, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );;
?>