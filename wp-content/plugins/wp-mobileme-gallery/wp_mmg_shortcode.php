<?php
/**
 * @package wp_mmg
 * @author Sébastien Gillard
 */
function wp_mmg_shortcode($atts, $content = null) {

    #########################################################################################################
    # DEFAULT OPTIONS																						#
    #########################################################################################################
    // Get saved options
    $wp_mmg_options = get_option('wp_mmg_options');
    $wp_mmg_data = get_option('wp_mmg_data');

    #########################################################################################################
    # FONCTIONS																								#
    #########################################################################################################
    require_once 'wp_mmg.class.php';
    include_once(ABSPATH . WPINC . '/feed.php'); // Get RSS Feed(s)
    #########################################################################################################
    # SCRIPT																								#
    #########################################################################################################

    extract(shortcode_atts(array(
                'type' => 'gallery',
                'login' => '',
                'display' => '',
                'limit' => NULL,
                'id' => '',
                'col' => '',
                'photos_thumb' => '',
                'movies_thumb' => '',
                'movies_size' => '',
                'format' => '',
                'width' => '',
                    ), $atts));

    ####################################
    # Detect and process options
    ####################################

    if ($_REQUEST['type'])
        $type = $_REQUEST['type'];

    if (preg_match('#gallery#', $type)) {
        $mmg_gallery = new WP_mmg_Gallery($wp_mmg_options, $wp_mmg_data);
    } elseif (preg_match('#album#', $type)) {
        $mmg_gallery = new WP_mmg_Album($wp_mmg_options, $wp_mmg_data);
    } elseif (preg_match('#photo#', $type)) {
        $mmg_gallery = new WP_mmg_photo($wp_mmg_options, $wp_mmg_data);
    } elseif (preg_match('#video#', $type)) {
        $mmg_gallery = new WP_mmg_video($wp_mmg_options, $wp_mmg_data);
    }
    if (preg_match('#photos#', $type) && preg_match('#movies#', $type)) {
        $mmg_gallery->display['photos'] = true;
        $mmg_gallery->display['movies'] = true;
    } elseif (preg_match('#photos#', $type) && !preg_match('#movies#', $type)) {
        $mmg_gallery->display['photos'] = true;
        $mmg_gallery->display['movies'] = false;
    } elseif (!preg_match('#photos#', $type) && preg_match('#movies#', $type)) {
        $mmg_gallery->display['photos'] = false;
        $mmg_gallery->display['movies'] = true;
    }

    if ($_REQUEST['id'])
        $mmg_gallery->id = $_REQUEST['id'];
    elseif (!empty($id))
        $mmg_gallery->id = $id;
    
    if ($_REQUEST['login'])
        $mmg_gallery->login = $_REQUEST['login'];
    elseif (!empty($login)) {
        $mmg_gallery->login = $login;
    }

    // Option Display
    $display_options = explode("&", $display);

    // If user chosed other options
    //If wants all
    if ($display_options[0] == 'all') {
        $mmg_gallery->display['thumb'] = true;
        $mmg_gallery->display['title'] = true;
        $mmg_gallery->display['info'] = true;
        $mmg_gallery->display['description'] = true;
    }
    // else if specified some options
    else {
        foreach ($display_options as $option) {
            // if he wants to hide something
            $option = str_replace('amp;', '', $option);
            if (preg_match('#^no-(.+)#', $option))
                $mmg_gallery->display[str_replace('no-', '', $option)] = false;
            // else
            else
                $mmg_gallery->display[$option] = true;
        }
    }

    // Option "Limit"
    if (!empty($limit))
        $mmg_gallery->limit = $limit;
    // Option "Col"
    if (!empty($col))
        $mmg_gallery->col = $col;
    // Option "photos_thumb"
    if (!empty($photos_thumb))
        $mmg_gallery->format['photos_thumb'] = $photos_thumb;
    // Option "movies_thumb"
    if (!empty($movies_thumb))
        $mmg_gallery->format['movies_thumb'] = $movies_thumb;
    // Option "movies_size"
    if (!empty($movies_size))
        $mmg_gallery->format['movies_size'] = $movies_size;
    // Option "format"
    if (!empty($format))
        $mmg_gallery->format['format'] = $format;
    // Option "width"
    if (!empty($width))
        $mmg_gallery->format['width'] = $width;

    ####################################
    # Display elements
    ####################################
    
    return $mmg_gallery;
}

?>