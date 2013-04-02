<?php
//echo '<p>';
//echo($_SERVER["PHP_SELF"]).'<br>';
//echo($_SERVER["argc"]).'<br>';
//echo($_SERVER["REQUEST_METHOD"]).'<br>';
//echo($_SERVER["SCRIPT_NAME"]).'<br>';
//echo($_SERVER["REQUEST_URI"]).'<br>';
//echo '</p>';
/**
 * @package wp_mmg
 * @author Sébastien Gillard
 */
#########################################################################################################
# DEFAULT OPTIONS																						#
#########################################################################################################
// Default Options
$default_wp_mmg_options = array(
    'login' => 'sebastien.gillard',
    'theme' => WP_PLUGIN_URL . '/wp-mobileme-gallery/themes/mobileme/mobileme.css',
    'update_database' => true,
    'update_all' => false,
    'min_update_frequecy' => 21600,
    'last_update' => array(),
    'copyright' => true,
    'mmg_link' => true,
    'gallery_limit' => 0,
    'gallery_col' => 3,
    'gallery_thumb' => true,
    'gallery_title' => true,
    'gallery_info' => true,
    'gallery_description' => true,
    'gallery_movies' => true,
    'gallery_photos_thumb' => 'square',
    'gallery_movies_thumb' => 'small',
    'gallery_movies_size' => 'medium',
    'gallery_deactivated_albums' => array(),
    'gallery_deactivated_movies' => array(),
    'album_limit' => 0,
    'album_col' => 4,
    'album_thumb' => true,
    'album_title' => true,
    'album_info' => true,
    'album_description' => true,
    'album_movies' => true,
    'album_photos_thumb' => 'small',
    'album_movies_thumb' => 'medium',
    'photo_title' => false,
    'photo_description' => false,
    'photo_photos_thumb' => 'medium',
    'photo_photos_width' => 'auto',
    'video_title' => false,
    'video_description' => false,
    'video_info' => false,
    'video_thumb' => true,
    'video_dl_link' => true,
    'video_movies_size' => 'medium',
    'widget_limit' => 12,
    'widget_thumb_size' => 40,
    'version' => 0.3,
);

$format_photos = array('square', 'small', 'medium', 'web', 'large');
$format_movies = array('mobile', 'small', 'medium', 'large');

/* List the content of a folder
 * @param string $path
 * @return array
 */

function showContent($path) {

    if ($handle = opendir($path)) {
        $response = array();
        $i = 0;
        while (false !== ($dir = readdir($handle))) {
            if ($dir != "." && $dir != "..") {
                $dName = $dir;
                $dir = $path . '/' . $dir;
                // If folder
                if (is_dir($dir)) {
                    // Navigate through this folder
                    if ($handle2 = opendir($dir)) {
                        $css_file = array();
                        while (false !== ($file = readdir($handle2))) {
                            if ($file != "." && $file != "..") {
                                $file = $dir . '/' . $file;
                                if (is_file($file) && strripos($file, '.css')) {
                                    $css_file[]['file_dir'] = $file;
                                }
                            }
                        }
                        closedir($handle2);
                    }
                    // Check if there is at least one CSS file and not more than one
//                    echo $css_file[0]['file_dir'].'<br>';
                    if (count($css_file) == 1) {
                        $response[$i]['file_dir'] = str_replace(WP_PLUGIN_DIR, WP_PLUGIN_URL, $css_file[0]['file_dir']);
                        $response[$i]['file_name'] = $dName;
                        $i++;
                    }
                }
            }
        }
        closedir($handle);
    }
    if (count($response) == 0) {
        return array(array('file_name' => __('There is no theme installed', 'wp_mmg')));
    } else {
        return $response;
    }
}

/* List the content of a folder
 * @param string $path
 * @return array
 */

function valid_login($login) {
    $mmg = new WP_mmg();
    if (!$mmg->valid_login($login))
        echo '<div class="error"><p><strong>' . __('The entered login is not valid', 'wp_mmg') . '</strong></p></div>';
}

// List content of the theme folder
$theme_items = showContent(WP_PLUGIN_DIR . '/wp-mobileme-gallery/themes');

// Add options if they do not exist
add_option('wp_mmg_options', $value = $default_wp_mmg_options);
add_option('wp_mmg_data', $value = array());



#########################################################################################################
# "Options" Form Processing 
#########################################################################################################
// If submit th eform
// Update the Options
// and get the options
if (isset($_POST['submit-options'])) {
    $wp_mmg_options = array();
    $wp_mmg_options['login'] = trim($_POST['login']);
    $wp_mmg_options['login'] = str_replace("@me.com", "", $wp_mmg_options['login']);
    $wp_mmg_options['login'] = str_replace("@mac.com", "", $wp_mmg_options['login']);
    if (empty($wp_mmg_options['login'])) {
        echo '<div class="error"><p><strong>' . __('You must enter a login to configure this plugin', 'wp_mmg') . '</strong></p></div>';
    }
    valid_login($wp_mmg_options['login']);
    $wp_mmg_options['theme'] = trim($_POST['theme']);
    if (isset($_POST['copyright']))
        $wp_mmg_options['copyright'] = true;
    else
        $wp_mmg_options['copyright'] = false;
    if (isset($_POST['mmg_link']))
        $wp_mmg_options['mmg_link'] = true;
    else
        $wp_mmg_options['mmg_link'] = false;
    $wp_mmg_options['min_update_frequecy'] = trim($_POST['min_update_frequecy']) * 3600;
    if (isset($_POST['update_database']))
        $wp_mmg_options['update_database'] = true;
    else
        $wp_mmg_options['update_database'] = false;
    if (isset($_POST['update_all']))
        $wp_mmg_options['update_all'] = true;
    else
        $wp_mmg_options['update_all'] = false;
    $wp_mmg_options['gallery_limit'] = trim($_POST['gallery_limit']);
    $wp_mmg_options['gallery_col'] = trim($_POST['gallery_col']);
    if (isset($_POST['gallery_thumb']))
        $wp_mmg_options['gallery_thumb'] = true;
    else
        $wp_mmg_options['gallery_thumb'] = false;
    if (isset($_POST['gallery_title']))
        $wp_mmg_options['gallery_title'] = true;
    else
        $wp_mmg_options['gallery_title'] = false;
    if (isset($_POST['gallery_info']))
        $wp_mmg_options['gallery_info'] = true;
    else
        $wp_mmg_options['gallery_info'] = false;
    if (isset($_POST['gallery_description']))
        $wp_mmg_options['gallery_description'] = true;
    else
        $wp_mmg_options['gallery_description'] = false;
    if (isset($_POST['gallery_movies']))
        $wp_mmg_options['gallery_movies'] = true;
    else
        $wp_mmg_options['gallery_movies'] = false;
    $wp_mmg_options['gallery_photos_thumb'] = trim($_POST['gallery_photos_thumb']);
    $wp_mmg_options['gallery_movies_thumb'] = trim($_POST['gallery_movies_thumb']);
    $wp_mmg_options['gallery_movies_size'] = trim($_POST['gallery_movies_size']);

    $wp_mmg = new WP_mmg_Tinymce($wp_mmg_options, $wp_mmg_data);
    $data = $wp_mmg->return_gallery();
    if ($_POST['gallery_activated_albums']) {
        $gallery_activated_albums = array_keys($_POST['gallery_activated_albums']);
    } else
        $gallery_activated_albums = array();
    foreach ($data as $item) {
        if (!in_array($item[id], $gallery_activated_albums))
            $wp_mmg_options['gallery_deactivated_albums'][] = $item[id];
    }
    if ($_POST['gallery_activated_movies']) {
        $gallery_activated_movies = array_keys($_POST['gallery_activated_movies']);
    } else
        $gallery_activated_movies = array();
    foreach ($data as $item) {
        if (!in_array($item[id], $gallery_activated_movies))
            $wp_mmg_options['gallery_deactivated_movies'][] = $item[id];
    }

    $wp_mmg_options['album_limit'] = trim($_POST['album_limit']);
    $wp_mmg_options['album_col'] = trim($_POST['album_col']);
    if (isset($_POST['album_thumb']))
        $wp_mmg_options['album_thumb'] = true;
    else
        $wp_mmg_options['album_thumb'] = false;
    if (isset($_POST['album_title']))
        $wp_mmg_options['album_title'] = true;
    else
        $wp_mmg_options['album_title'] = false;
    if (isset($_POST['album_info']))
        $wp_mmg_options['album_info'] = true;
    else
        $wp_mmg_options['album_info'] = false;
    if (isset($_POST['album_description']))
        $wp_mmg_options['album_description'] = true;
    else
        $wp_mmg_options['album_description'] = false;
    if (isset($_POST['album_movies']))
        $wp_mmg_options['album_movies'] = true;
    else
        $wp_mmg_options['album_movies'] = false;
    $wp_mmg_options['album_photos_thumb'] = trim($_POST['album_photos_thumb']);
    $wp_mmg_options['album_movies_thumb'] = trim($_POST['album_movies_thumb']);
    if (isset($_POST['photo_title']))
        $wp_mmg_options['photo_title'] = true;
    else
        $wp_mmg_options['photo_title'] = false;
    if (isset($_POST['photo_description']))
        $wp_mmg_options['photo_description'] = true;
    else
        $wp_mmg_options['photo_description'] = false;
    $wp_mmg_options['photo_photos_thumb'] = trim($_POST['photo_photos_thumb']);
    $wp_mmg_options['photo_photos_width'] = trim($_POST['photo_photos_width']);
    $wp_mmg_options['video_movies_size'] = trim($_POST['video_movies_size']);
    if (isset($_POST['video_thumb']))
        $wp_mmg_options['video_thumb'] = true;
    else
        $wp_mmg_options['video_thumb'] = false;
    if (isset($_POST['video_title']))
        $wp_mmg_options['video_title'] = true;
    else
        $wp_mmg_options['video_title'] = false;
    if (isset($_POST['video_info']))
        $wp_mmg_options['video_info'] = true;
    else
        $wp_mmg_options['video_info'] = false;
    if (isset($_POST['video_description']))
        $wp_mmg_options['video_description'] = true;
    else
        $wp_mmg_options['video_description'] = false;
    if (isset($_POST['video_dl_link']))
        $wp_mmg_options['video_dl_link'] = true;
    else
        $wp_mmg_options['video_dl_link'] = false;
//	$query = check_options($wp_mmg_options, $default_wp_mmg_options);
    $update_wp_mmg_queries = array();
    $update_wp_mmg_queries[] = update_option('wp_mmg_options', $wp_mmg_options);
    echo '<div class="updated"><p><strong>' . __('Options saved', 'wp_mmg') . '</strong></p></div>';
    $wp_mmg_options = get_option('wp_mmg_options');
}
// If Default Options clicked
// resetting the options
// and get the options
elseif (isset($_POST['submit-default'])) {
    $update_wp_mmg_queries = array();
    $update_wp_mmg_queries[] = delete_option('wp_mmg_options');
    $update_wp_mmg_queries[] = add_option('wp_mmg_options', $value = $default_wp_mmg_options);
    echo '<div class="updated"><p><strong>' . __('Default Options restored', 'wp_mmg') . '</strong></p></div>';
    $wp_mmg_options = get_option('wp_mmg_options');
}
// If just displaying the option page
// get the options
else {
    $wp_mmg_options = $default_wp_mmg_options;
    $wp_mmg_options = get_option('wp_mmg_options');
}
#########################################################################################################
# DISPLAY																								#
#########################################################################################################
?>

<div id="wp_mmg_plugin_admin" class="wrap">
    <h1>WP Mobileme Gallery Options  </h1>

    <form id="wp_mmg_plugin_admin_form" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
        <!--tabs list-->
        <ul>
            <li><a href="#wp_mmg_plugin_admin_form-1"><?php _e('MobileMe information', 'wp_mmg'); ?></a></li>
            <li><a href="#wp_mmg_plugin_admin_form-2"><?php _e('General Options', 'wp_mmg'); ?></a></li>
            <li><a href="#wp_mmg_plugin_admin_form-3"><?php _e('Gallery Options', 'wp_mmg'); ?></a></li>
            <li><a href="#wp_mmg_plugin_admin_form-4"><?php _e('Album Options', 'wp_mmg'); ?></a></li>
            <li><a href="#wp_mmg_plugin_admin_form-5"><?php _e('Photo Options', 'wp_mmg'); ?></a></li>
            <li><a href="#wp_mmg_plugin_admin_form-6"><?php _e('Video Options', 'wp_mmg'); ?></a></li>
        </ul>
        <table class="form-table" id="wp_mmg_plugin_admin_form-1">
            <tr valign="top">
                <th scope="row" colspan="2"><h2><?php _e('MobileMe information', 'wp_mmg'); ?></h2></th>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e('User Login', 'wp_mmg'); ?></th>
                <td><input type="text" name="login" value="<?php echo stripslashes($wp_mmg_options[login]); ?>" size="50" /></td>
            </tr>
        </table>

        <table class="form-table" id="wp_mmg_plugin_admin_form-2">
            <tr valign="top">
                <th scope="row" colspan="2"><h2><?php _e('General Options', 'wp_mmg'); ?></h2></th>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e('Theme', 'wp_mmg'); ?></th>
                <td><select name="theme">
                        <?php foreach ($theme_items as $option) { ?>
                            <option value="<?php echo $option['file_dir'] ?>" <?php if ($wp_mmg_options['theme'] == $option['file_dir'])
                            echo 'selected' ?>><?php echo $option['file_name'] ?></option><?php } ?>
                    </select>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e('Display Copyright', 'wp_mmg'); ?></th>
                <td><input type="checkbox" name="copyright" <?php if ($wp_mmg_options[copyright])
                                    echo 'checked="checked"' ?> /></td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e('Display link to the Mobileme Gallery', 'wp_mmg'); ?></th>
                <td><input type="checkbox" name="mmg_link" <?php if ($wp_mmg_options[mmg_link])
                           echo 'checked="checked"' ?> /></td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e('Use a local database to store the gallery', 'wp_mmg'); ?></th>
                <td><input type="checkbox" name="update_database" <?php if ($wp_mmg_options[update_database])
                           echo 'checked="checked"' ?> /></td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e('Minimum intervalle between 2 updates (in hours)', 'wp_mmg'); ?></label></th>
                <td><input type="text" name="min_update_frequecy" value="<?php echo stripslashes($wp_mmg_options['min_update_frequecy'] / 3600); ?>" size="4" /></td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e('Update the whole database each time an update is necessary', 'wp_mmg'); ?></th>
                <td><input type="checkbox" name="update_all" <?php if ($wp_mmg_options[update_all])
                           echo 'checked="checked"' ?> /></td>
            </tr>
        </table>

        <table class="form-table" id="wp_mmg_plugin_admin_form-3">
            <tr valign="top">
                <th scope="row" colspan="2"><h2><?php _e('Gallery Options', 'wp_mmg'); ?></h2></th>
            </tr>
            <tr valign="top">
                <th scope="row" colspan="2"><h3><?php _e('General', 'wp_mmg'); ?></h3></th>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e('Limit of albums displayed (0 = no limit)', 'wp_mmg'); ?></th>
                <td><input type="text" name="gallery_limit" value="<?php echo stripslashes($wp_mmg_options[gallery_limit]); ?>" size="3" /></td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e('Number of colums', 'wp_mmg'); ?></th>
                <td><input type="text" name="gallery_col" value="<?php echo stripslashes($wp_mmg_options[gallery_col]); ?>" size="3" /></td>
            </tr>
            <tr valign="top">
                <th scope="row" colspan="2"><h3><?php _e('Display', 'wp_mmg'); ?></h3></th>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e('Display thumbnail', 'wp_mmg'); ?></th>
                <td><input type="checkbox" name="gallery_thumb" <?php if ($wp_mmg_options[gallery_thumb])
                           echo 'checked="checked"' ?> /></td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e('Display title', 'wp_mmg'); ?></th>
                <td><input type="checkbox" name="gallery_title" <?php if ($wp_mmg_options[gallery_title])
                           echo 'checked="checked"' ?> /></td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e('Display info (number of images)', 'wp_mmg'); ?></th>
                <td><input type="checkbox" name="gallery_info" <?php if ($wp_mmg_options[gallery_info])
                           echo 'checked="checked"' ?> /></td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e('Display description', 'wp_mmg'); ?></th>
                <td><input type="checkbox" name="gallery_description" <?php if ($wp_mmg_options[gallery_description])
                           echo 'checked="checked"' ?> /></td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e('Display movies', 'wp_mmg'); ?></th>
                <td><input type="checkbox" name="gallery_movies" <?php if ($wp_mmg_options[gallery_movies])
                           echo 'checked="checked"' ?> /></td>
            </tr>
            <tr valign="top">
                <th scope="row" colspan="2"><h3><?php _e('Format', 'wp_mmg'); ?></h3></th>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e('Size of the photos thumbnails', 'wp_mmg'); ?></th>
                <td>
                    <select id="gallery_photos_thumb" name="gallery_photos_thumb">
                        <?php
                        foreach ($format_photos as $item) {
                            if ($wp_mmg_options[gallery_photos_thumb] == $item)
                                $selected = 'selected';
                            else
                                $selected = '';
                            echo '<option value="' . $item . '" ' . $selected . '>' . $item . '</option>';
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e('Size of the movies thumbnails', 'wp_mmg'); ?></th>
                <td>
                    <select id="gallery_movies_thumb" name="gallery_movies_thumb">
                        <?php
                        foreach ($format_photos as $item) {
                            if ($wp_mmg_options[gallery_movies_thumb] == $item)
                                $selected = 'selected';
                            else
                                $selected = '';
                            echo '<option value="' . $item . '" ' . $selected . '>' . $item . '</option>';
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e('Size of the linked movies', 'wp_mmg'); ?></th>
                <td>
                    <select id="gallery_movies_size" name="gallery_movies_size">
                        <?php
                        foreach ($format_movies as $item) {
                            if ($wp_mmg_options[gallery_movies_size] == $item)
                                $selected = 'selected';
                            else
                                $selected = '';
                            echo '<option value="' . $item . '" ' . $selected . '>' . $item . '</option>';
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e("Displayed albums", 'wp_mmg'); ?></th>
                <td colspan="3">
                    <?php
                    $wp_mmg = new WP_mmg_Tinymce($wp_mmg_options, $wp_mmg_data);
                    $data = $wp_mmg->return_gallery();
                    if ($data) {
                        foreach ($data as $item) {
                            if ($item[type] != "Movie") {
                                if (in_array($item[id], $wp_mmg_options[gallery_deactivated_albums]))
                                    $checked = "";
                                else
                                    $checked = "checked";
                                echo '<input id="gallery_activated_albums[' . $item[id] . ']" name="gallery_activated_albums[' . $item[id] . ']" type="checkbox" ' . $checked . '><label for="gallery_activated_albums[' . $item[id] . ']">' . $item[title] . '</label></br>';
                            }
                        }
                    }
                    else
                        echo _e("Your gallery is empty", 'wp_mmg');
                    ?>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e("Displayed movies", 'wp_mmg'); ?></th>
                <td colspan="3">
                    <?php
                    $wp_mmg = new WP_mmg_Tinymce($wp_mmg_options, $wp_mmg_data);
                    $data = $wp_mmg->return_gallery();
                    if ($data) {
                        foreach ($data as $item) {
                            if ($item[type] == "Movie") {
                                if (in_array($item[id], $wp_mmg_options[gallery_deactivated_movies]))
                                    $checked = "";
                                else
                                    $checked = "checked";
                                echo '<input id="gallery_activated_movies[' . $item[id] . ']" name="gallery_activated_movies[' . $item[id] . ']" type="checkbox" ' . $checked . '><label for="gallery_activated_movies[' . $item[id] . ']">' . $item[title] . '</label></br>';
                            }
                        }
                    }
                    else
                        echo _e("Your gallery is empty", 'wp_mmg');
                    ?>
                </td>
            </tr>
        </table>

        <table class="form-table" id="wp_mmg_plugin_admin_form-4">
            <tr valign="top">
                <th scope="row" colspan="2"><h2><?php _e('Album Options', 'wp_mmg'); ?></h2></th>
            </tr>
            <tr valign="top">
                <th scope="row" colspan="2"><h3><?php _e('General', 'wp_mmg'); ?></h3></th>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e('Limit of photos displayed (0 = no limit)', 'wp_mmg'); ?></th>
                <td><input type="text" name="album_limit" value="<?php echo stripslashes($wp_mmg_options[album_limit]); ?>" size="3" /></td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e('Number of colums', 'wp_mmg'); ?></th>
                <td><input type="text" name="album_col" value="<?php echo stripslashes($wp_mmg_options[album_col]); ?>" size="3" /></td>
            </tr>
            <tr valign="top">
                <th scope="row" colspan="2"><h3><?php _e('Display', 'wp_mmg'); ?></h3></th>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e('Display thumbnail', 'wp_mmg'); ?></th>
                <td><input type="checkbox" name="album_thumb" <?php if ($wp_mmg_options[album_thumb])
                        echo 'checked="checked"' ?> /></td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e('Display title', 'wp_mmg'); ?></th>
                <td><input type="checkbox" name="album_title" <?php if ($wp_mmg_options[album_title])
                           echo 'checked="checked"' ?> /></td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e('Display description', 'wp_mmg'); ?></th>
                <td><input type="checkbox" name="album_description" <?php if ($wp_mmg_options[album_description])
                           echo 'checked="checked"' ?> /></td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e('Display movies', 'wp_mmg'); ?></th>
                <td><input type="checkbox" name="album_movies" <?php if ($wp_mmg_options[album_movies])
                           echo 'checked="checked"' ?> /></td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e('Display info (duration of videos)', 'wp_mmg'); ?></th>
                <td><input type="checkbox" name="album_info" <?php if ($wp_mmg_options[album_info])
                           echo 'checked="checked"' ?> /></td>
            </tr>
            <tr valign="top">
                <th scope="row" colspan="2"><h3><?php _e('Format', 'wp_mmg'); ?></h3></th>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e('Size of the photos thumbnails', 'wp_mmg'); ?></th>
                <td>
                    <select id="album_photos_thumb" name="album_photos_thumb">
                        <?php
                        foreach ($format_photos as $item) {
                            if ($wp_mmg_options[album_photos_thumb] == $item)
                                $selected = 'selected';
                            else
                                $selected = '';
                            echo '<option value="' . $item . '" ' . $selected . '>' . $item . '</option>';
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e('Size of the films thumbnails', 'wp_mmg'); ?></th>
                <td>
                    <select id="album_movies_thumb" name="album_movies_thumb">
                        <?php
                        foreach ($format_photos as $item) {
                            if ($wp_mmg_options[album_movies_thumb] == $item)
                                $selected = 'selected';
                            else
                                $selected = '';
                            echo '<option value="' . $item . '" ' . $selected . '>' . $item . '</option>';
                        }
                        ?>
                    </select>
                </td>
            </tr>
        </table>

        <table class="form-table" id="wp_mmg_plugin_admin_form-5">
            <tr valign="top">
                <th scope="row" colspan="2"><h2><?php _e('Photo Options', 'wp_mmg'); ?></h2></th>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e('Size of the pictures', 'wp_mmg'); ?></th>
                <td>
                    <select id="photo_photos_thumb" name="photo_photos_thumb">
                        <?php
                        foreach ($format_photos as $item) {
                            if ($wp_mmg_options[photo_photos_thumb] == $item)
                                $selected = 'selected';
                            else
                                $selected = '';
                            echo '<option value="' . $item . '" ' . $selected . '>' . $item . '</option>';
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e('Size of the pictures (auto, px or %)', 'wp_mmg'); ?></th>
                <td><input type="text" name="photo_photos_width" value="<?php echo stripslashes($wp_mmg_options[photo_photos_width]); ?>" size="6" /></td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e('Display title', 'wp_mmg'); ?></th>
                <td><input type="checkbox" name="photo_title" <?php if ($wp_mmg_options[photo_title])
                            echo 'checked="checked"' ?> /></td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e('Display description', 'wp_mmg'); ?></th>
                <td><input type="checkbox" name="photo_description" <?php if ($wp_mmg_options[photo_description])
                           echo 'checked="checked"' ?> /></td>
            </tr>
        </table>

        <table class="form-table" id="wp_mmg_plugin_admin_form-6">
            <tr valign="top">
                <th scope="row" colspan="2"><h2><?php _e('Video Options', 'wp_mmg'); ?></h2></th>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e('Size of the movies', 'wp_mmg'); ?></th>
                <td>
                    <select id="video_movies_size" name="video_movies_size">
                        <?php
                        foreach ($format_movies as $item) {
                            if ($wp_mmg_options[video_movies_size] == $item)
                                $selected = 'selected';
                            else
                                $selected = '';
                            echo '<option value="' . $item . '" ' . $selected . '>' . $item . '</option>';
                        }
                        ?>
                    </select>
                </td>
            </tr>            <tr valign="top">
                <th scope="row"><?php _e('Display thumbnail', 'wp_mmg'); ?></th>
                <td><input type="checkbox" name="video_thumb" <?php if ($wp_mmg_options[video_thumb])
                            echo 'checked="checked"' ?> /></td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e('Display title', 'wp_mmg'); ?></th>
                <td><input type="checkbox" name="video_title" <?php if ($wp_mmg_options[video_title])
                           echo 'checked="checked"' ?> /></td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e('Display info', 'wp_mmg'); ?></th>
                <td><input type="checkbox" name="video_info" <?php if ($wp_mmg_options[video_info])
                           echo 'checked="checked"' ?> /></td>
            </tr><tr valign="top">
                <th scope="row"><?php _e('Display description', 'wp_mmg'); ?></th>
                <td><input type="checkbox" name="video_description" <?php if ($wp_mmg_options[video_description])
                           echo 'checked="checked"' ?> /></td>
            </tr>
        </tr><tr valign="top">
        <th scope="row"><?php _e('Display download link (when video is not displayed)', 'wp_mmg'); ?></th>
        <td><input type="checkbox" name="video_dl_link" <?php if ($wp_mmg_options[video_dl_link])
                           echo 'checked="checked"' ?> /></td>
    </tr>
</table>

<p class="submit">
    <input type="submit" name="submit-options" class="button-primary" value="<?php _e('Save', 'wp_mmg') ?>" />
    <input type="submit" name="submit-default" class="button-secondary" value="<?php _e('Default', 'wp_mmg') ?>" />
</p>
</form>
</div>
