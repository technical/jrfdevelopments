<?php

// look up for the path
require_once( dirname(dirname(__FILE__)) . '/wp_mmg_config.php');
require_once( dirname(dirname(__FILE__)) . '/wp_mmg.class.php');

// check for rights
if (!is_user_logged_in() || !current_user_can('edit_posts'))
    wp_die(__("You are not allowed to be here"));

global $wpdb;

// get the options
$wp_mmg_options = get_option('wp_mmg_options');
$wp_mmg_data = get_option('wp_mmg_data');

if ($_REQUEST['action'] && $_REQUEST['action'] == 'list_albums1') {
    $wp_mmg = new WP_mmg_Tinymce($wp_mmg_options, $wp_mmg_data);
    if ($_REQUEST['login'] && !empty($_REQUEST['login']))
        $wp_mmg->login = $_REQUEST['login'];
    // Make the list of the select elements
    $data = $wp_mmg->return_gallery();
    $response = array();
    if ($data) {
        foreach ($data as $item) {
            if($item[type] != "Movie")
            $response .= '<option value="' . $item[id] . '">' . $item[title] . '</option>';
        }
    }
    echo $response;
}
if ($_REQUEST['action'] && $_REQUEST['action'] == 'list_albums2') {
    $wp_mmg = new WP_mmg_Tinymce($wp_mmg_options, $wp_mmg_data);
    if ($_REQUEST['login'] && !empty($_REQUEST['login']))
        $wp_mmg->login = $_REQUEST['login'];
    // Make the list of the select elements
    $data = $wp_mmg->return_gallery();
    $response = array();
    $response = '<option>' . __('-- Choose an album --', 'wp_mmg') . '</option>';
    if ($data) {
        foreach ($data as $item) {
            if($item[type] != "Movie")
            $response .= '<option value="' . $item[id] . '">' . $item[title] . '</option>';
        }
    }
    echo $response;
}
if ($_REQUEST['action'] && $_REQUEST['action'] == 'videos') {
    $wp_mmg = new WP_mmg_Tinymce($wp_mmg_options, $wp_mmg_data);
    if ($_REQUEST['login'] && !empty($_REQUEST['login']))
        $wp_mmg->login = $_REQUEST['login'];
    // Create the list of the videos
    $response = $wp_mmg->create_videos();
    echo $response;
}
if ($_REQUEST['action'] && $_REQUEST['action'] == 'movies') {
    $wp_mmg = new WP_mmg_Tinymce($wp_mmg_options, $wp_mmg_data);
    if ($_REQUEST['login'] && !empty($_REQUEST['login']))
        $wp_mmg->login = $_REQUEST['login'];
    // Create the list of the videos
    $response = $wp_mmg->create_movies();
    echo $response;
}

if ($_REQUEST['action'] && $_REQUEST['action'] == 'create_album') {
    // Display Album
    $mmp_gallery = new WP_mmg_Tinymce($wp_mmg_options, $wp_mmg_data, $_REQUEST['login']);
    if ($_REQUEST['id'] && !empty($_REQUEST['id']))
            $mmp_gallery->id = $_REQUEST['id'];
    if ($_REQUEST['login'] && !empty($_REQUEST['login']))
            $mmp_gallery->login = $_REQUEST['login'];
    echo $mmp_gallery->create_album();
}
?>