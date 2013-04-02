<?php
// look up for the path
require_once( dirname(dirname(__FILE__)) . '/wp_mmg_config.php');
require_once( dirname(dirname(__FILE__)) . '/wp_mmg.class.php');

// check for rights
if (!is_user_logged_in() || !current_user_can('edit_posts'))
    wp_die(__("You are not allowed to be here"));

global $wpdb;

$format_photos = array('square', 'small', 'medium', 'web', 'large');
$format_movies = array('mobile', 'small', 'medium', 'large');

// get the options
$wp_mmg_options = get_option('wp_mmg_options');
$wp_mmg_data = get_option('wp_mmg_data');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>wp_mmg</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <script language="javascript" type="text/javascript" src="<?php echo get_option('siteurl') ?>/wp-includes/js/tinymce/tiny_mce_popup.js"></script>
        <script language="javascript" type="text/javascript" src="<?php echo get_option('siteurl') ?>/wp-includes/js/tinymce/utils/mctabs.js"></script>
        <script language="javascript" type="text/javascript" src="<?php echo get_option('siteurl') ?>/wp-includes/js/tinymce/utils/form_utils.js"></script>

        <link href="wp_mmg_popup.css" rel="stylesheet" type="text/css" media="all" />

        <script src="<?php echo admin_url() ?>/load-scripts.php?c=1&load=jquery"></script>
        <script language="javascript" type="text/javascript">
            function init() {
                tinyMCEPopup.resizeToInnerSize();
            }
	
            function insertwpTubeLink() {
		
                var tagtext;
		
                var options = <?= json_encode($wp_mmg_options) ?>;
                
                var Gallery = document.getElementById('gallery_panel');
                var Album = document.getElementById('album_panel');
                var Photo = document.getElementById('photo_panel');
                var Video = document.getElementById('video_panel');
                var Login = document.getElementById('login_panel');
		
                // Gallery Panel
                if (Gallery.className.indexOf('current') != -1) {
                    var gallery_photos = document.getElementById('gallery_photos').value;
                    var gallery_movies = document.getElementById('gallery_movies').value;
                    var gallery_limit = Math.round(document.getElementById('gallery_limit').value);
                    var gallery_col = Math.round(document.getElementById('gallery_col').value);
                    var gallery_display = new Object();
                    gallery_display.thumb = document.getElementById('gallery_thumb').value;
                    gallery_display.title = document.getElementById('gallery_title').value;
                    gallery_display.info = document.getElementById('gallery_info').value;
                    gallery_display.description = document.getElementById('gallery_description').value;
                    var gallery_format = new Object();
                    gallery_format.photos_thumb = document.getElementById('gallery_photos_thumb').value;
                    gallery_format.movies_thumb = document.getElementById('gallery_movies_thumb').value;
                    gallery_format.movies_size = document.getElementById('gallery_movies_size').value;
                    var type = '';
                    var display = '';
                    var limit = '';
                    var col = '';
                    var photos_thumb = '';
                    var movies_thumb = '';
                    var movies_size = '';
                    var login = document.getElementById('login').value;

                    if (gallery_photos && gallery_movies && !options.gallery_photos)
                        type = 'type="gallery(photos&movies)"';
                    else if (gallery_photos && !gallery_movies && options.gallery_photos)
                        type = 'type="gallery(photos)"';
                    else if (!gallery_photos && gallery_movies  && !options.gallery_photos)
                        type = 'type="gallery(movies)"';
                    if (gallery_limit && gallery_limit != options.gallery_limit)
                        limit = 'limit="' + gallery_limit + '"';
                    if (gallery_col && gallery_col != options.gallery_col)
                        col = 'col="' + gallery_col + '"';
                    if (gallery_display.length != 0 ) {
                        for(var indice in gallery_display)
                        {
                            if(gallery_display[indice] && !options['gallery_'+indice]) {
                                display = display.concat(indice+"&");
                            }
                            else if(!gallery_display[indice] && options['gallery_'+indice]) {
                                display = display.concat("no-"+indice+"&");
                            }
                        }
                        if (display)
                            display = 'display="' + display.slice(0, -1) + '"';
                    }
                    if (gallery_format.photos_thumb && gallery_format.photos_thumb != options.gallery_photos_thumb)
                        photos_thumb = 'photos_thumb="' + gallery_format.photos_thumb + '"';
                    if (gallery_format.movies_thumb && gallery_format.movies_thumb != options.gallery_movies_thumb)
                        movies_thumb = 'movies_thumb="' + gallery_format.movies_thumb + '"';
                    if (gallery_format.movies_size && gallery_format.movies_size != options.gallery_movies_size)
                        movies_size = 'movies_size="' + gallery_format.movies_size + '"';
                    if (login && login != options.login)
                        login = 'login="' + login + '"';
                    else login = '';
                    
                    tagtext = '[wp_mmg ' + type + ' ' + display + ' ' + limit + ' ' + col + ' ' + photos_thumb + ' ' + movies_thumb + ' ' + movies_size + ' ' + login + '/]';

                }
                
                // Album Panel
                if (Album.className.indexOf('current') != -1) {
                    var album_id = document.getElementById('album_id').value;
                    var album_limit = Math.round(document.getElementById('album_limit').value);
                    var album_col = Math.round(document.getElementById('album_col').value);
                    var album_display = new Object();
                    album_display.thumb = document.getElementById('album_thumb').value;
                    album_display.title = document.getElementById('album_title').value;
                    album_display.info = document.getElementById('album_info').value;
                    album_display.description = document.getElementById('album_description').value;
                    var album_format = new Object();
                    album_format.photos_thumb = document.getElementById('album_movies_thumb').value;
                    album_format.movies_thumb = document.getElementById('album_movies_thumb').value;
                    var type = 'type="album"';
                    var id = '';
                    var display = '';
                    var limit = '';
                    var col = '';
                    var photos_thumb = '';
                    var movies_thumb = '';
                    var login = document.getElementById('login').value;
                    
                    if (album_id)
                        id = 'id="' + album_id + '"';
                    
                    if (album_limit && album_limit != options.album_limit)
                        limit = 'limit="' + album_limit + '"';
                    if (album_col && album_col != options.album_col)
                        col = 'col="' + album_col + '"';
                    if (album_display.length != 0 )
                        for(var indice in album_display)
                    {
                        if(album_display[indice] && !options['gallery_'+indice]) {
                            display = display.concat(indice+"&");
                        }
                        else if(!album_display[indice] && options['album_'+indice]) {
                            display = display.concat("no-"+indice+"&");
                        }
                    }
                    if (display)
                        display = 'display="' + display.slice(0, -1) + '"';
                    if (album_format.photos_thumb && album_format.photos_thumb != options.album_photos_thumb)
                        photos_thumb = 'photos_thumb="' + album_format.photos_thumb + '"';
                    if (album_format.movies_thumb && album_format.movies_thumb != options.album_movies_thumb)
                        movies_thumb = 'movies_thumb="' + album_format.movies_thumb + '"';
                    if (login && login != options.login)
                        login = 'login="' + login + '"';
                    else login = '';
                    
                    tagtext = '[wp_mmg ' + type + ' ' + id + ' ' + display + ' ' + limit + ' ' + col + ' ' + photos_thumb + ' ' + movies_thumb + ' ' + login + '/]';
                }
                
                // Photo Panel
                if (Photo.className.indexOf('current') != -1) {
                    var photo_id = document.getElementById('photo_id').value;
                    var photo_format = document.getElementById('photo_photos_thumb').value;
                    var photo_size = document.getElementById('photo_photos_width').value;
                    var photo_display = new Object();
                    photo_display.title = document.getElementById('photo_title').value;
                    photo_display.description = document.getElementById('photo_description').value;
                    var type = 'type="photo"';
                    var id = '';
                    var display = '';
                    var format = '';
                    var size = '';
                    var login = document.getElementById('login').value;
                    
                    if (photo_album_id)
                        id = 'id="' + photo_id + '"';
                    if (photo_format && photo_format != options.photo_format)
                        format = 'format="' + photo_format + '"';
                    if (photo_size && photo_size != options.photo_size)
                        size = 'size="' + photo_size + '"';
                    if (photo_display.length != 0 )
                        for(var indice in photo_display)
                    {
                        if(photo_display[indice] && !options['photo_'+indice]) {
                            display = display.concat(indice+"&");
                        }
                        else if(!photo_display[indice] && options['photo_'+indice]) {
                            display = display.concat("no-"+indice+"&");
                        }
                    }
                    if (display)
                        display = 'display="' + display.slice(0, -1) + '"';
                    if (login && login != options.login)
                        login = 'login="' + login + '"';
                    else login = '';
                    
                    tagtext = '[wp_mmg ' + type + ' ' + id + ' ' + display + ' ' + format + ' ' + size + ' ' + login + '/]';
                }
                // Video Panel
		if (Video.className.indexOf('current') != -1) {
                    var video_id = document.getElementById('video_id').value;
                    var video_movies_size = document.getElementById('video_movies_size').value;
                    var video_display = new Object();
                    video_display.title = document.getElementById('video_title').value;
                    video_display.description = document.getElementById('video_description').value;
                    video_display.thumb = document.getElementById('video_thumb').value;
                    video_display.info = document.getElementById('video_info').value;
                    video_display.dl_link = document.getElementById('video_dl_link').value;
                    var type = 'type="video"';
                    var id = '';
                    var display = '';
                    var format = '';
                    var movies_size = '';
                    var login = document.getElementById('login').value;
                    
                    if (video_id)
                        id = 'id="' + video_id + '"';
                    if (video_movies_size && video_movies_size != options.video_movies_size)
                        movies_size = 'movies_size="' + video_movies_size + '"';
                    if (video_display.length != 0 )
                        for(var indice in video_display)
                    {
                        if(video_display[indice] && !options['video_'+indice]) {
                            display = display.concat(indice+"&");
                        }
                        else if(!video_display[indice] && options['video_'+indice]) {
                            display = display.concat("no-"+indice+"&");
                        }
                    }
                    if (display)
                        display = 'display="' + display.slice(0, -1) + '"';

                    if (login && login != options.login)
                        login = 'login="' + login + '"';
                    else login = '';
                    
                    tagtext = '[wp_mmg ' + type + ' ' + id + ' ' + display + ' ' + format + ' ' + movies_size + ' ' + login + '/]';
                }
                
                // Login Panel
		if (Login.className.indexOf('current') != -1) {
                    //do nothing
                    tagtext = '';
                }
                
                if(window.tinyMCE) {
                    window.tinyMCE.execInstanceCommand('content', 'mceInsertContent', false, tagtext);
                    //Peforms a clean up of the current editor HTML. 
                    //tinyMCEPopup.editor.execCommand('mceCleanup');
                    //Repaints the editor. Sometimes the browser has graphic glitches. 
                    tinyMCEPopup.editor.execCommand('mceRepaint');
                    tinyMCEPopup.close();
                }
		
                return;
            }
            
            jQuery(document).ready(function($) {
                
                // Video Panel
                function update_popup(target, action, login) {
                    $.ajax({
                        url: "<?php echo WP_PLUGIN_URL . '/' . plugin_basename(dirname(__FILE__)) ?>/popup_content.php?action="+action+"&login="+login,
                        beforeSend: function() {
                            $(target).empty().append('<img id="loader" src="ajax-loader.gif" title="loader" alt="loader" width="12px" height="12px" />');
                        },
                        success: function(data){
                            $(target).empty().append(data);
                        },
                        error: function(){
                            $(target).after('<div class="error">an error has occured.</div>');
                        }
                    })
                    return false;
                };
                
                var login = $(this).val();
                update_popup('td#videos', 'videos', login);
                update_popup('td#movies', 'movies', login);
                
                $('#login').focusout(function () {
                    
                    var login = $(this).val();
                    $(this).after('<img id="loader" src="ajax-loader.gif" title="loader" alt="loader" width="12px" height="12px" />').queue(function() {
                        update_popup('#photo_album_id', 'list_albums2', login);
                        update_popup('#album_id', 'list_albums1', login);
                        update_popup('td#videos', 'videos', login);
                        update_popup('td#movies', 'movies', login);
                        $(this).dequeue();
                    }).next('img#loader').fadeOut();
                    return false;
                })
                
                $('td#videos img, td#movies img').live('click', function() {
                    var photo_id = $(this).attr("rel");
                    $("input#video_id").val(photo_id).focus();
                    return false;
                });
                
                // Photo Panel
                $('select#photo_album_id').live('change', function() {
                    var album_id = $(this).children("option:selected").val();
                    var login = $('#login').val();
                    $("#display_album").empty().load("<?php echo WP_PLUGIN_URL . '/' . plugin_basename(dirname(__FILE__)) ?>/popup_content.php?action=create_album&id="+album_id+"&login="+login).fadeIn();
                    return false;
                });
                $('#display_album img').live('click', function() {
                    var photo_id = $(this).attr("rel");
                    $("input#photo_id").val(photo_id).focus();
                    $("#display_album").fadeOut();
                    return false;
                });
                
            });

        </script>


        <base target="_self" />
    </head>
    <body id="link" onload="tinyMCEPopup.executeOnLoad('init();');document.body.style.display='';" style="display: none">
        <!-- <form onsubmit="insertLink();return false;" action="#"> -->
        <form name="wp_mmg" action="#">
            <div class="tabs">
                <ul>
                    <li id="gallery_tab" class="current"><span><a href="javascript:mcTabs.displayTab('gallery_tab','gallery_panel');" onmousedown="return false;"><?php _e("Gallery", 'wp_mmg'); ?></a></span></li>
                    <li id="album_tab"><span><a href="javascript:mcTabs.displayTab('album_tab','album_panel');" onmousedown="return false;"><?php _e("Album", 'wp_mmg'); ?></a></span></li>
                    <li id="photo_tab"><span><a href="javascript:mcTabs.displayTab('photo_tab','photo_panel');" onmousedown="return false;"><?php _e("Photo", 'wp_mmg'); ?></a></span></li>
                    <li id="video_tab"><span><a href="javascript:mcTabs.displayTab('video_tab','video_panel');" onmousedown="return false;"><?php _e("Video", 'wp_mmg'); ?></a></span></li>
                    <li id="login_tab"><span><a href="javascript:mcTabs.displayTab('login_tab','login_panel');" onmousedown="return false;"><?php _e("Login", 'wp_mmg'); ?></a></span></li>
                </ul>
            </div>

            <div class="panel_wrapper">
                <!-- gallery panel -->
                <div id="gallery_panel" class="panel current">
                    <br />
                    <table border="0" cellpadding="4" cellspacing="0">
                        <tr>
                            <th nowrap="nowrap"><?php _e("Type", 'wp_mmg'); ?></th>
                            <td>
                                <input type="checkbox" id="gallery_photos" name="gallery_photos" checked="checked" /><label for="gallery_photos"><?php _e("Display Photos", 'wp_mmg'); ?></label>
                            </td>
                            <td>
                                <input type="checkbox" id="gallery_movies" name="gallery_movies" <?php if ($wp_mmg_options[gallery_movies])
    echo 'checked="checked"' ?> /><label for="gallery_movies"><?php _e("Display movies", 'wp_mmg'); ?></label>
                            </td>
                            <td></td>
                        </tr>
                        <tr>
                            <th nowrap="nowrap"><?php _e("Limit", 'wp_mmg'); ?></th>
                            <td><input type="text" size="5" id="gallery_limit" name="gallery_limit" value="<?php echo $wp_mmg_options[gallery_limit]; ?>" />
                            </td>
                        </tr>
                        <tr>
                            <th nowrap="nowrap"><?php _e("Number of column", 'wp_mmg'); ?></th>
                            <td><input type="text" size="5" id="gallery_col" name="gallery_col" value="<?php echo $wp_mmg_options[gallery_col]; ?>" />
                            </td>
                        </tr>
                        <tr>
                            <th nowrap="nowrap"><?php _e("Display", 'wp_mmg'); ?></th>
                            <td colspan="3">
                                <input type="checkbox" id="gallery_thumb" name="gallery_thumb" <?php if ($wp_mmg_options[gallery_thumb])
    echo 'checked="checked"' ?> /><label for="gallery_thumb"><?php _e("Display thumbnail", 'wp_mmg'); ?></label>
                            </td>
                        </tr>
                        <tr>
                            <th></th>
                            <td>
                                <input type="checkbox" id="gallery_title" name="gallery_title" <?php if ($wp_mmg_options[gallery_title])
    echo 'checked="checked"' ?> /><label for="gallery_title"><?php _e("Display title", 'wp_mmg'); ?></label>
                            </td>
                            <td>
                                <input type="checkbox" id="gallery_info" name="gallery_info" <?php if ($wp_mmg_options[gallery_info])
    echo 'checked="checked"' ?> /><label for="gallery_info"><?php _e("Display info", 'wp_mmg'); ?></label>
                            </td>
                            <td>
                                <input type="checkbox" id="gallery_description" name="gallery_description" <?php if ($wp_mmg_options[gallery_description])
    echo 'checked="checked"' ?> /><label for="gallery_description"><?php _e("Display description", 'wp_mmg'); ?></label>
                            </td>
                        </tr>
                        <tr>
                            <th nowrap="nowrap"><?php _e("Format", 'wp_mmg'); ?></th>
                            <td colspan="2"><?php _e('Size of the photos thumbnails', 'wp_mmg'); ?></td>
                            <td>
                                <select id="gallery_photos_thumb" name="gallery_photos_thumb">
                                    <?php
                                    foreach ($format_photos as $item) {
                                        if ($wp_mmg_options[gallery_photos_thumb] == $item)
                                            $selected = 'selected';
                                        else
                                            $selected='';
                                        echo '<option value="' . $item . '" ' . $selected . '>' . $item . '</option>';
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th></th>
                            <td colspan="2"><?php _e('Size of the movies thumbnails', 'wp_mmg'); ?></td>
                            <td>
                                <select id="gallery_movies_thumb" name="gallery_movies_thumb">
                                    <?php
                                    foreach ($format_photos as $item) {
                                        if ($wp_mmg_options[gallery_movies_thumb] == $item)
                                            $selected = 'selected';
                                        else
                                            $selected='';
                                        echo '<option value="' . $item . '" ' . $selected . '>' . $item . '</option>';
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th></th>
                            <td colspan="2"><?php _e('Size of the linked movies', 'wp_mmg'); ?></td>
                            <td>
                                <select id="gallery_movies_size" name="gallery_movies_size">
                                    <?php
                                    foreach ($format_photos as $item) {
                                        if ($wp_mmg_options[gallery_movies_size] == $item)
                                            $selected = 'selected';
                                        else
                                            $selected='';
                                        echo '<option value="' . $item . '" ' . $selected . '>' . $item . '</option>';
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>
                    </table>
                </div>
                <!-- gallery panel -->

                <!-- album panel -->
                <div id="album_panel" class="panel">
                    <br />
                    <table border="0" cellpadding="4" cellspacing="0">
                        <tr>
                            <th nowrap="nowrap"><label for="album_id"><?php _e("Select an album", 'wp_mmg'); ?></label></th>
                            <td colspan="3"><select id="album_id" name="album_id" style="width: 190px">
                                    <?php
                                    $wp_mmg = new WP_mmg_Tinymce($wp_mmg_options, $wp_mmg_data);
                                    $data = $wp_mmg->return_gallery();
                                    if ($data) {
                                        foreach ($data as $item) {
                                            if($item[type] != "Movie")
                                            echo '<option value="' . $item[id] . '">' . $item[title] . '</option>';
                                        }
                                    }
                                    ?>	
                                </select></td>
                        </tr>

                        <tr>
                            <th nowrap="nowrap"><?php _e("Limit", 'wp_mmg'); ?></th>
                            <td><input type="text" size="5" id="album_limit" name="album_limit" value="<?php echo $wp_mmg_options[album_limit]; ?>" />
                            </td>
                        </tr>
                        <tr>
                            <th nowrap="nowrap"><?php _e("Number of column", 'wp_mmg'); ?></th>
                            <td><input type="text" size="5" id="album_col" name="album_col" value="<?php echo $wp_mmg_options[album_col]; ?>" />
                            </td>
                        </tr>
                        <tr>
                            <th nowrap="nowrap"><?php _e("Display", 'wp_mmg'); ?></th>
                            <td>
                                <input type="checkbox" id="album_movie" name="album_movie" <?php if ($wp_mmg_options[album_movie])
                                        echo 'checked="checked"' ?> /><label for="album_movie"><?php _e("Display movies", 'wp_mmg'); ?></label>
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>
                                <input type="checkbox" id="album_thumb" name="album_thumb" <?php if ($wp_mmg_options[album_thumb])
                                        echo 'checked="checked"' ?> /><label for="album_thumb"><?php _e("Display thumbnail", 'wp_mmg'); ?></label>
                            </td>
                        </tr>
                        <tr>
                            <th></th>
                            <td>
                                <input type="checkbox" id="album_title" name="album_title" <?php if ($wp_mmg_options[album_title])
                                        echo 'checked="checked"' ?> /><label for="album_title"><?php _e("Display title", 'wp_mmg'); ?></label>
                            </td>
                            <td>
                                <input type="checkbox" id="album_info" name="album_info" <?php if ($wp_mmg_options[album_info])
                                        echo 'checked="checked"' ?> /><label for="album_info"><?php _e("Display info", 'wp_mmg'); ?></label>
                            </td>
                            <td>
                                <input type="checkbox" id="album_description" name="album_description" <?php if ($wp_mmg_options[album_description])
                                        echo 'checked="checked"' ?> /><label for="album_description"><?php _e("Display description", 'wp_mmg'); ?></label>
                            </td>
                        </tr>
                        <tr>
                            <th nowrap="nowrap"><?php _e("Format", 'wp_mmg'); ?></th>
                            <td><?php _e('Size of the photos thumbnails', 'wp_mmg'); ?></td>
                            <td>
                                <select id=album_photos_thumb" name="album_photos_thumb">
                                    <?php
                                    foreach ($format_photos as $item) {
                                        if ($wp_mmg_options[album_photos_thumb] == $item)
                                            $selected = 'selected';
                                        else
                                            $selected='';
                                        echo '<option value="' . $item . '" ' . $selected . '>' . $item . '</option>';
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th></th>
                            <td><?php _e('Size of the movies thumbnails', 'wp_mmg'); ?></td>
                            <td>
                                <select id="album_movies_thumb" name="album_movies_thumb">
                                    <?php
                                    foreach ($format_photos as $item) {
                                        if ($wp_mmg_options[album_movies_thumb] == $item)
                                            $selected = 'selected';
                                        else
                                            $selected='';
                                        echo '<option value="' . $item . '" ' . $selected . '>' . $item . '</option>';
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>
                    </table>
                </div>
                <!-- album panel -->

                <!-- photo panel -->
                <div id="photo_panel" class="panel">
                    <br />
                    <table border="0" cellpadding="4" cellspacing="0">
                        <tr>
                            <th nowrap="nowrap"><label for="photo_album_id"><?php _e("Select a gallery", 'wp_mmg'); ?></label></th>
                            <td colspan="2"><select id="photo_album_id" name="photo_album_id" style="width: 190px">
                                    <option><?php _e("-- Choose an album --", 'wp_mmg') ?></option>
                                    <?php
                                    if ($data) {
                                        foreach ($data as $item) {
                                            if($item[type] != "Movie")
                                            echo '<option value="' . $item[id] . '">' . $item[title] . '</option>';
                                        }
                                    }
                                    ?>	
                                </select></td>
                        </tr>
                        <tr>
                            <th nowrap="nowrap"><?php _e("Photo id", 'wp_mmg'); ?></th>
                            <td><input type="text" size="30" id="photo_id" name="photo_id" value="" />
                            </td>
                        </tr>
                        <tr>
                            <th nowrap="nowrap"><?php _e("Format", 'wp_mmg'); ?></th>
                            <td>
                                <select id="photo_photos_thumb" name="photo_photos_thumb">
                                    <?php
                                    foreach ($format_photos as $item) {
                                        if ($wp_mmg_options[photo_photos_thumb] == $item)
                                            $selected = 'selected';
                                        else
                                            $selected='';
                                        echo '<option value="' . $item . '" ' . $selected . '>' . $item . '</option>';
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th nowrap="nowrap"><?php _e("Size", 'wp_mmg'); ?></th>
                            <td><input type="text" size="5" id="photo_photos_width" name="photo_photos_width" value="<?php echo $wp_mmg_options[photo_photos_width]; ?>" />
                            </td>
                        </tr>
                        <tr>
                            <th nowrap="nowrap"><?php _e("Display", 'wp_mmg'); ?></th>
                            <td>
                                <input type="checkbox" id="photo_title" name="photo_title" <?php if ($wp_mmg_options[photo_title])
                                        echo 'checked="checked"' ?> /><label for="photo_title"><?php _e("Display title", 'wp_mmg'); ?></label>
                            </td>
                            <td>
                                <input type="checkbox" id="photo_description" name="photo_description" <?php if ($wp_mmg_options[photo_description])
                                        echo 'checked="checked"' ?> /><label for="photo_description"><?php _e("Display description", 'wp_mmg'); ?></label>
                            </td>
                        </tr>
                    </table>
                    <div id="display_album"></div>
                    <div class="separator""></div>
                </div>
                <!-- photo panel -->

                <!-- movie panel -->
                <div id="video_panel" class="panel">
                    <br />
                    <table border="0" cellpadding="4" cellspacing="0">
                        <tr>
                            <th nowrap="nowrap"><?php _e("Video Id", 'wp_mmg'); ?></th>
                            <td colspan="2"><input type="text" size="30" id="video_id" name="video_id" value="" />
                            </td>
                        </tr>
                        <tr>
                            <th nowrap="nowrap"><?php _e("Size", 'wp_mmg'); ?></th>
                            <td>
                                <select id="video_movies_size" name="video_movies_size">
                                    <?php
                                    foreach ($format_movies as $item) {
                                        if ($wp_mmg_options[video_movies_size] == $item)
                                            $selected = 'selected';
                                        else
                                            $selected='';
                                        echo '<option value="' . $item . '" ' . $selected . '>' . $item . '</option>';
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th nowrap="nowrap"><?php _e("Display", 'wp_mmg'); ?></th>
                            <td>
                                <input type="checkbox" id="video_thumb" name="album_thumb" <?php if ($wp_mmg_options[video_thumb])
                                        echo 'checked="checked"' ?> /><label for="video_thumb"><?php _e("Display thumbnail", 'wp_mmg'); ?></label>
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>
                                <input type="checkbox" id="video_title" name="photo_title" <?php if ($wp_mmg_options[video_title])
                                        echo 'checked="checked"' ?> /><label for="video_title"><?php _e("Display title", 'wp_mmg'); ?></label>
                            </td>
                            <td>
                                <input type="checkbox" id="video_description" name="video_description" <?php if ($wp_mmg_options[video_description])
                                        echo 'checked="checked"' ?> /><label for="video_description"><?php _e("Display description", 'wp_mmg'); ?></label>
                            </td>
                            <td>
                                <input type="checkbox" id="video_info" name="video_info" <?php if ($wp_mmg_options[video_info])
                                        echo 'checked="checked"' ?> /><label for="video_info"><?php _e("Display info", 'wp_mmg'); ?></label>
                            </td>
                            <td>
                                <input type="checkbox" id="video_dl_link" name="video_dl_link" <?php if ($wp_mmg_options[video_dl_link])
                                        echo 'checked="checked"' ?> /><label for="video_dl_link"><?php _e("Download link", 'wp_mmg'); ?></label>
                            </td>
                        </tr>
                        <tr>
                            <th nowrap="nowrap"><?php _e("Video", 'wp_mmg'); ?></th>
                            <td id="videos" colspan="2">
                                <!-- video integration by jquery -->
                            </td>
                        </tr>
                        <tr>
                            <th nowrap="nowrap"><?php _e("Movies", 'wp_mmg'); ?></th>
                            <td id="movies" colspan="2">
                                <!-- movie integration by jquery -->
                            </td>
                        </tr>                    </table>
                    <div class="separator""></div>
                </div>
                <!-- movie panel -->

                <!-- login panel -->
                <div id="login_panel" class="panel">
                    <br />
                    <table border="0" cellpadding="4" cellspacing="0">
                        <tr>
                            <th nowrap="nowrap"><?php _e("Login", 'wp_mmg'); ?></th>
                            <td><input type="text" size="30" id="login" name="login" value="<?php echo $wp_mmg_options[login]; ?>" />
                            </td>
                        </tr>
                    </table>
                    <div id="display_album"></div>
                    <div class="separator""></div>
                </div>
                <!-- login panel -->
            </div>

            <div class="mceActionPanel">
                <div style="float: left">
                    <input type="button" id="cancel" name="cancel" value="<?php _e("Cancel", 'wpTube'); ?>" onclick="tinyMCEPopup.close();" />
                </div>

                <div style="float: right">
                    <input type="submit" id="insert" name="insert" value="<?php _e("Insert", 'wpTube'); ?>" onclick="insertwpTubeLink();" />
                </div>
            </div>
        </form>
    </body>
</html>
<?php ?>
