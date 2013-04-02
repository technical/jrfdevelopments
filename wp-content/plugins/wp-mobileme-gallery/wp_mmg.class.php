<?php

/**
 * @package wp_mmg
 * @author Sébastien Gillard
 */
class WP_mmg {

    public $login = '';
    public $id = 0;
    public $col;
    public $limit;
    public $display = array();
    public $format = array();
    public $options = array();
    protected $json_feed;
    protected $gallery_json_feed = 'http://gallery.me.com/<--login-->/?webdav-method=truthget&feedfmt=json';
    protected $album_json_feed = 'http://gallery.me.com/<--login-->/<--id-->/?webdav-method=truthget&feedfmt=json';
    protected $data = array();
    protected $type = 'gallery';
    protected $movieSizes = array('large' => 'Large', 'medium' => 'Medium', 'small' => 'Small', 'mobile' => 'Mobile');

    /**
     * Load Data
     * @param string $json_feed url of the json feed
     * @param int $id id of the album or 0 for the gallery
     * @return array 
     */
    public function load_data($json_feed = '', $id = '') {
        // If no json feed or id is specified, load the gallery json feed
        if (empty($json_feed))
            $json_feed = $this->json_feed;
        if (empty($id))
            $id = $this->id;

        // Use local database ? Login is the user id ?
        if ($this->options['update_database'] && $this->login == $this->options['login']) {
            // Chek if an update is needed
            if ($this->needUpdate($json_feed, $id)) {
                $this->update_data($json_feed, $id);
            }
            return $this->data[$id];
        }
        // else, database is not used, so get directly the result
        else {
            $feed = $this->get_feed($json_feed . '&depth=1');
            $data = $this->process_feed($feed, $id);
            return $data;
        }
    }

    /**
     * Prepare Json Feed
     * @param string $json_feed
     * @param int $id
     * @param string $login
     * @return string $json
     */
    protected function prepare_json_feed($json_feed, $id = '', $login = '') {
        // If no login or id is specified, load the gallery json feed
        if (empty($login))
            $login = $this->login;
        if (empty($id))
            $id = $this->id;
        $json_feed = str_replace('<--login-->', $login, $json_feed);
        $json_feed = str_replace('<--id-->', $id, $json_feed);
        return $json_feed;
    }

    /**
     * Get json feed and decode it in an array
     * @param array $json_feed
     * @return array $json
     */
    protected function get_feed($json_feed) {
        $curl = curl_init($json_feed);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_HEADER, false);
        $feed = curl_exec($curl);
        $error = curl_errno($curl);
        curl_close($curl);

        if ($error == 0) {
            $feed = json_decode($feed, TRUE);
            return $feed;
        } else {
            echo '<div class="error"><p><strong>' . $e->getMessage() . '</strong></p></div>';
            return false;
        }
    }

    /**
     * Check if a login is valid or not
     * @param array $login
     * @return bool
     */
    public function valid_login($login = '') {
        $json_feed = $this->prepare_json_feed($this->gallery_json_feed, '', $login);
        $feed = $this->get_feed($json_feed . '&depth=0');
        if ($feed)
            return true;
        else
            return false;
    }

    /**
     * Check if update needed
     * @param array $feed
     * @return bool
     */
    protected function needUpdate($json_feed, $id) {
        // If no data, need an update
        if (empty($this->data)) {
            return true;
        }
        // Look for the last update
        if (is_array($this->data[$id]))
            $last_update = $this->options['last_update'][$id];
        else {
            return true;
        }
        // Check the interval until last update and the next panified update time
        $min_update_frequency = $this->options[min_update_frequecy];
        $next_update = $last_update + $min_update_frequency;

        // If update frequency is over
        if (time() > $next_update) {
            // Get feed to check update
            $feed = $this->get_feed($json_feed . '&depth=0');
            // Check the json_feed last update
            // If it is a gallery
            if ($id == 0 && (strtotime($feed['data']['updated']) > $last_update)) {
                return true;
            }
            // Else if it is an album
            elseif (strtotime($feed['records'][0]['updated']) > $last_update) {
                return true;
            }
            else
                return false;
        }
        // Else update frequency is not over
        else
            return false;
    }

    /**
     * Process items from json
     * To save space in the database, it is nescessary to save just the necessary info
     * @param obj $feed
     * @return array
     */
    protected function process_feed($feed, $id=0) {

        // Get only the items in a table
        $feed = $feed['records'];

        // Order the content
        // Read the table, item after item and process the info wanted

        if ($id != 0 && !stristr($feed[0]['type'], "Movie")) {
            unset($feed[0]);
        }
        $i = 0;
        foreach ($feed as $item) {
            // Do not take the locked items
            if (!isset($item['isLocked'])) {
                // TYPE of the element
                $response[$i]['type'] = $item['type'];
                // SRC of the Photo
                if (isset($item['squareDerivativeUrl'])) {
                    $response[$i]['src_square'] = $item['squareDerivativeUrl'];
                }
                else
                    $response[$i]['src_square'] = $item['keyImagePath'] . '.' . $item['keyImageFileExtension'] . '?derivative=square&source=web.' . $item['keyImageFileExtension'] . '&type=square';
                // TITLE
                $response[$i]['title'] = $item['title'];

                // Depending on the type of the element
                // ---------------------------
                // If ALBUM
                if (stristr($item['type'], "Album")) {
                    // ID of the element
                    $id = preg_replace('#(.+)/([0-9]+)$#', '$2', $item['url']);
                    if ($id !== $item['url'])
                        $response[$i]['id'] = $id;
                    // DESCRIPTION of the Album
                    $response[$i]['description'] = $item['content'];
                    // INFO of the Album (number of photos and movies
                    if ($item['numMovies'] != 0) {
                        $response[$i]['info'] = $item['numPhotos'] . ' ' . __("Photos", 'wp_mmg') . ' & ' . $item['numMovies'] . ' ' . __("Movies", 'wp_mmg');
                    }
                    else
                        $response[$i]['info'] = $item['numPhotos'] . ' ' . __("Photos", 'wp_mmg');
                }
                // ---------------------------
                // If PHOTO
                elseif (stristr($item['type'], "Photo")) {
                    // ID of the element
                    $id = preg_replace('#(.+)/(.+)$#', '$2', $item['url']);
                    if ($id !== $item['url'])
                        $response[$i]['id'] = $id;
                    // DESCRIPTION of the Photo
                    $response[$i]['description'] = $item['content'];
                }
                // ---------------------------
                // If MOVIE
                elseif (stristr($item['type'], "Movie")) {
                    // ID of the element
                    $id = preg_replace('#(.+)/([0-9]+)$#', '$2', $item['url']);
                    $response[$i]['id'] = preg_replace('#(.+)/([0-9]+)$#', '$2', $item['url']);
                    // DESCRIPTION of the Movie
                    $response[$i]['description'] = $item['description'];
                    // MOVIE url and info

                    foreach ($this->movieSizes as $sizemin => $sizemaj) {
                        if (isset($item[$sizemin . 'MovieUrl']) && !empty($item[$sizemin . 'MovieUrl'])) {
                            //URL
                            $response[$i]['movie'][$sizemin]['url'] = $item[$sizemin . 'MovieUrl'];
                            // INFO
                            $response[$i]['info'] = date('H:i:s', $item['videoDuration' . $sizemaj]);
                            // HEIGHT
                            $response[$i]['movie'][$sizemin]['height'] = $item['videoHeight' . $sizemaj];
                            // WIDTH
                            $response[$i]['movie'][$sizemin]['width'] = $item['videoWidth' . $sizemaj];
                        }
                    }
                }
                // ---------------------------
                // If VIDEO
                elseif (stristr($item['type'], "Video")) {
                    // ID of the element
                    $response[$i]['id'] = preg_replace('#(.+)/([0-9]+)$#', '$2', $item['url']);
                    // DESCRIPTION of the Video
                    $response[$i]['description'] = $item['content'];
                    // INFO of the Video (duration)
                    $response[$i]['info'] = date('H:i:s', $item['videoDuration']);
                    // URL of the Video
                    $response[$i]['movie']['url'] = $item['videoUrl'];
                    // HEIGHT of the Video
                    $response[$i]['movie']['height'] = $item['videoHeight'];
                    // WIDTH of the Video
                    $response[$i]['movie']['width'] = $item['videoWidth'];
                }
                // EXIF info of the photo
                if (isset($item['exifDerivativeUrl']))
                    $response[$i]['exif'] = $item['exifDerivativeUrl'];
                // ORDER of the element
                $response[$i]['order'] = $item['sortOrder'];
                // DATE of the element
                $response[$i]['date'] = strtotime($item['photoDate']);
                // LAST UPDATE of the element
                $response[$i]['updated'] = strtotime($item['updated']);
                // If the album has no pictures it should not be taken into account
                if (stristr($item['type'], "Album") && $item['numPhotos'] == 0) {
                    unset($response[$i]);
                }
                $i++;
            }
        }
        if ($id != 0 && count($response) > 1) {
            $response = $this->order($response, "asc_order");
        } elseif (count($response) > 1) {
            $response = $this->order($response, "desc_order");
        }
        return $response;
    }

    /**
     * Update saved data
     * @param obj $json_feed
     * @param int $id
     */
    protected function update_data($json_feed = '', $id = '') {
        // If the option is set, it updates the gallery and all albums
        if ($this->options[update_all]) {
            // If the update was needed, the gallery must be updated
            // Get, process and save the gallery
            $feed_gallery = $this->get_feed($this->prepare_json_feed($this->gallery_json_feed) . '&depth=1');
            $data_gallery = $this->process_feed($feed_gallery, 0);
            $this->data[0] = $data_gallery;
            // Navigate through all albums of the Gallery
            // All albums may not need to be updated (save times and ressources to check before)
            // Get, process and save the albums
            foreach ($data_gallery as $item) {
                $id = $item[id];
                $json_feed = $this->prepare_json_feed($this->album_json_feed, $id);
                if ($this->needUpdate($json_feed, $id)) {
                    $feed_album = $this->get_feed($json_feed . '&depth=1');
                    $data_album = $this->process_feed($feed_album, $id);
                    $this->data[$id] = $data_album;
                }
            }
            $sucess = true;
        }
        // If a feed specified, it updates just this feed
        // Get feed to check update
        elseif (!empty($json_feed) AND isset($id)) {
            $feed = $this->get_feed($json_feed . '&depth=1');
            $data = $this->process_feed($feed, $id);
            $this->data[$id] = $data;
            $sucess = true;
        } else {
            $sucess = false;
        }
        // Save the update time and the data in the database
        if ($sucess) {
            $this->options['last_update'][$id] = time();
            // Save modified options and data
            update_option('wp_mmg_options', $this->options);
            update_option('wp_mmg_data', $this->data);
        }
    }

    /**
     * Order Array of data
     * Enable to order by the definied gallery order, date or shuffle.
     * @param array $data
     * @param string $param order | date | shuffle
     * @return array
     */
    protected function order($data, $param) {
        if (!function_exists('cmp_order_asc')) {

            function cmp_order_asc($a, $b) {
                if ($a['order'] == $b['order'])
                    return 0;
                return ($a['order'] > $b['order']) ? -1 : 1;
            }

        }
        if (!function_exists('cmp_order_desc')) {

            function cmp_order_desc($a, $b) {
                if ($a['order'] == $b['order'])
                    return 0;
                return ($a['order'] < $b['order']) ? -1 : 1;
            }

        }
        if (!function_exists('cmp_date')) {

            function cmp_date($a, $b) {
                if ($a["date"] == $b["date"]) {
                    return 0;
                }
                return ($a["date"] > $b["date"]) ? -1 : 1;
            }

        }
        switch ($param) {
            case "asc_order":
                usort($data, "cmp_order_asc");
                break;
            case "desc_order":
                usort($data, "cmp_order_desc");
                break;
            case "date":
                usort($data, "cmp_date");
                break;
            case "shuffle":
                srand(time());
                shuffle($data);
                break;
            default:
                usort($data, "cmp_order");
                break;
        }
        return $data;
    }

    /**
     * Change the size of the Derivative Images
     * @param string $src
     * $param string $size square | small | medium | web | large
     * @return string
     */
    protected function src_image_size($src, $size) {
        // The derivative link saved by default in database is the square link
        $src = str_replace('square', $size, $src);
        return $src;
    }

    /**
     * Change the size of the movie if it does not exists
     * @param array $size
     * @return string 
     */
    protected function changeMovieSize($size, $movies) {
        //How many size of movies do we have ?
        $nb_movies = sizeof($movies);
        $keys_movies = array_keys($movies);

        $new_size = '';

        switch ($size) {
            case 'large' :
                $new_size = $keys_movies[0];
                break;
            case 'medium' :
                if ($nb_movies > 2)
                    $new_size = $keys_movies[1];
                else
                    $new_size = $keys_movies[0];
                break;
            case 'small' :
                $keys_movies = array_reverse($keys_movies);
                $new_size = $keys_movies[0];
                break;
            case 'mobile' :
                $keys_movies = array_reverse($keys_movies);
                $new_size = $keys_movies[0];
                break;
            default :
                $new_size = $keys_movies[0];
                break;
        }
        return $new_size;
    }

    /**
     * Create a gallery or an album
     * @param array $data
     * @return string HTML code of the gallery or the album
     */
    protected function create($data) {

        if (count($data) == 0) {
            $mmp_gallery = '<p>' . __('No items.', 'wp_mmg') . '</p>';
        } else {
            // If limit set, limit number of items
            if ($this->limit != 0)
                $data = array_slice($data, 0, $this->limit);
            // Loop through each feed item and display each item as a hyperlink.
            $mmp_gallery .= '<div id="wp_mmg" class="' . $this->type . '" rel="' . $this->col . '">';
            foreach ($data as $item) {

                if (!in_array($item[id], $this->options[gallery_deactivated_albums]) OR !in_array($item[id], $this->options[gallery_deactivated_movies])) {

                    if (($this->display['photos'] && $item['type'] == 'Photo') OR ($this->display['photos'] && stristr($item['type'], 'Album')) OR ($this->display['movies'] && ($item['type'] == 'Movie' OR $item['type'] == 'Video'))) {
                        $mmp_gallery .= '<div class="element">';
                        if ($this->display['thumb']) {
                            $mmp_gallery .= '<div class="mask">';
                            if ($item['type'] == 'Movie') {
                                if (empty($item['movie'][$this->format['movies_size']]['url']))
                                    $movieSize = $this->changeMovieSize($this->format['movies_size'], $item['movie']);
                                else
                                    $movieSize = $this->format['movies_size'];
                                $mmp_gallery .= '<a href="' . plugins_url('wp_mmg_video_ajax.php', __FILE__) . '?href=' . $item['movie'][$movieSize]['url'] . '&height=' . intval($item['movie'][$movieSize]['height']) . '&width=' . intval($item['movie'][$movieSize]['width']) . '" title="' . $item['title'] . '" class="wp_mmg_lightbox-movie"><img src="' . $this->src_image_size($item['src_square'], $this->format['movies_thumb']) . '" alt="thumbnail_' . $item['title'] . '" /></a>';
                            } elseif ($item['type'] == 'Video') {
                                $mmp_gallery .= '<a href="' . plugins_url('wp_mmg_video_ajax.php', __FILE__) . '?href=' . $item['movie']['url'] . '&height=' . intval($item['movie']['height']) . '&width=' . $item['movie']['width'] . '" title="' . $item['title'] . '" class="wp_mmg_lightbox-movie" rel="' . $this->id . '"><img src="' . $this->src_image_size($item['src_square'], "medium") . '" alt="thumbnail_' . $item['title'] . '" /></a>';
                            } elseif ($this->type == 'gallery') {
                                if($_SERVER["argc"] == 0) {
                                $url_arg = "?";
                                }
                                else $url_arg = $_SERVER["REQUEST_URI"]."&";
                                $mmp_gallery .= '<a href="' . $url_arg . 'type=album&id=' . $item['id'] . '&login=' . $this->login . '" title="' . __('Posted on', 'wp_mmg') . ' ' . date('j F Y', $item['updated']) . '"><img src="' . $this->src_image_size($item['src_square'], "square") . '" alt="thumbnail_' . $item['title'] . '" /></a>';
                            } elseif ($this->type == 'album') {
                                $mmp_gallery .= '<a href="' . $this->src_image_size($item['src_square'], "web") . '" title="' . $item['title'] . '" class="wp_mmg_lightbox" rel="' . $this->id . '"><img src="' . $this->src_image_size($item['src_square'], "medium") . '" alt="thumbnail_' . $item['title'] . '" /></a>';
                            }
                            $mmp_gallery .= '</div>'; // .mask
                        }
                        $mmp_gallery .= '<div class="infos">';
                        // Display Title
                        if ($item['title'] && $this->display['title'])
                            if ($item['type'] == 'Movie') {
                                $mmp_gallery .= '<div class="title"><a href="' . plugins_url('wp_mmg_video_ajax.php', __FILE__) . '?href=' . $item['movie'][$movieSize]['url'] . '&height=' . intval($item['movie'][$movieSize]['height']) . '&width=' . $item['movie'][$movieSize]['width'] . '" title="' . $item['title'] . '" class="wp_mmg_lightbox-movie">' . $item['title'] . '</a></div>';
                            } elseif ($item['type'] == 'Video') {
                                $mmp_gallery .= '<div class="title">' . $item['title'] . '</div>';
                            } elseif ($this->type == 'gallery') {
                                $mmp_gallery .= '<div class="title"><a href="?type=album&id=' . $item['id'] . '&login=' . $this->login . '" title="P' . __('Posted on', 'wp_mmg') . ' ' . date('j F Y', $item['updated']) . '">' . $item['title'] . '</a></div>';
                            } elseif ($this->type == 'album') {
                                $mmp_gallery .= '<div class="title">' . $item['title'] . '</div>';
                            }
                        // Display Description
                        if ($item['description'] && $this->display['description'])
                            $mmp_gallery .= '<div class="sub-title">' . $item['description'] . '</div>';
                        // Display Info
                        if ($item['info'] && $this->display['info'])
                            $mmp_gallery .= '<div class="sub-title">' . $item['info'] . '</div>';
                        $mmp_gallery .= '</div>'; // .info
                        $mmp_gallery .= '</div>'; // .element
                    }
                }
            }
            $mmp_gallery .= '</div>'; // #wp_mmg
        }
        return $mmp_gallery;
    }

}

class WP_mmg_Gallery extends WP_mmg {

    public function __construct($wp_mmg_options, $wp_mmg_data) {
        $this->type = 'gallery';
        // Saved Options
        $this->options = $wp_mmg_options;
        $this->data = $wp_mmg_data;
        $this->login = $wp_mmg_options['login'];
        $this->display['photos'] = true;
        $this->display['movies'] = $wp_mmg_options['gallery_movies'];
        $this->display['thumb'] = $wp_mmg_options['gallery_thumb'];
        $this->display['title'] = $wp_mmg_options['gallery_title'];
        $this->display['info'] = $wp_mmg_options['gallery_info'];
        $this->display['description'] = $wp_mmg_options['gallery_description'];
        $this->format['photos_thumb'] = $wp_mmg_options['gallery_photos_thumb'];
        $this->format['movies_thumb'] = $wp_mmg_options['gallery_movies_thumb'];
        $this->format['movies_size'] = $wp_mmg_options['gallery_movies_size'];
        $this->limit = $wp_mmg_options['gallery_limit'];
        $this->col = $wp_mmg_options['gallery_col'];
        $this->json_feed = $this->prepare_json_feed($this->gallery_json_feed);
    }

    public function __toString() {
        // Load the data
        $this->json_feed = $this->prepare_json_feed($this->gallery_json_feed);
        $data = $this->load_data($this->json_feed, $this->id);

        // Save in a variable if movies must be displayed
        $movies = $this->display['movies'];
        // If specified option to display photos
        if ($this->display['photos']) {
            // Disable movies display
            $this->display['movies'] = false;
            $mmp_gallery = '<h1>' . __('Photos', 'wp_mmg') . '</h1>';
            $mmp_gallery .= $this->create($data);
        }
        // If specified option to display movies
        // Restablish the movie parameter
        $this->display['photos'] = false;
        $this->display['movies'] = $movies;
        if ($this->display['movies']) {
            $mmp_gallery .= '<h1>' . __('Movies', 'wp_mmg') . '</h1>';
            $mmp_gallery .= $this->create($data);
        }
        if ($this->options['copyright'])
            $mmp_gallery .= '<p id="mmg_copyright">' . __('© All rights reserved') . '</p>';
        if ($this->options['mmg_link'])
            $mmp_gallery .= '<a href="http://gallery.me.com/' . $this->login . '"><p id="mmg_link">' . __("View in my Mobileme gallery", "wp_mmg") . '</p></a>';
        return $mmp_gallery;
    }

}

class WP_mmg_Album extends WP_mmg {

    public function __construct($wp_mmg_options, $wp_mmg_data) {
        $this->type = 'album';
        // Saved Options
        $this->options = $wp_mmg_options;
        $this->data = $wp_mmg_data;
        $this->login = $wp_mmg_options['login'];
        $this->display['photos'] = true;
        $this->display['movies'] = $wp_mmg_options['album_movies'];
        $this->display['thumb'] = $wp_mmg_options['album_thumb'];
        $this->display['title'] = $wp_mmg_options['album_title'];
        $this->display['info'] = $wp_mmg_options['album_info'];
        $this->display['description'] = $wp_mmg_options['album_description'];
        $this->format['photos_thumb'] = $wp_mmg_options['album_photos_thumb'];
        $this->format['movies_thumb'] = $wp_mmg_options['album_movies_thumb'];
        $this->limit = $wp_mmg_options[album_limit];
        $this->col = $wp_mmg_options[album_col];
        $this->json_feed = $this->prepare_json_feed($this->album_json_feed);
    }

    public function __toString() {
        // Load the data
        $this->json_feed = $this->prepare_json_feed($this->album_json_feed);
        $data = $this->load_data($this->json_feed, $this->id);

        // If the Album display is asked from the gallery display, enables "previous buttons"
        if ($_REQUEST['id'] && $_REQUEST['type']) {
            $mmp_gallery .= '<div class="previous"><a href="' . get_permalink() . '" title="' . __('Previous', 'wp_mmg') . '">' . __('< Previous', 'wp_mmg') . '</a></div>';
        }

        $mmp_gallery .= $this->create($data);

        if ($_REQUEST['id'] && $_REQUEST['type']) {
            $mmp_gallery .= '<div class="previous"><a href="' . get_permalink() . '" title="' . __('Previous', 'wp_mmg') . '">' . __('< Previous', 'wp_mmg') . '</a></div>';
        }
        if ($this->options['copyright'])
            $mmp_gallery .= '<p id="mmg_copyright">' . __('© All rights reserved') . '</p>';
        if ($this->options['mmg_link'])
            $mmp_gallery .= '<a href="http://gallery.me.com/' . $this->login . '"><p id="mmg_link">' . __("View in my Mobileme gallery", "wp_mmg") . '</p></a>';
        return $mmp_gallery;
    }

}

class WP_mmg_photo extends WP_mmg {

    public function __construct($wp_mmg_options, $wp_mmg_data) {
        $this->type = 'photo';
        // Saved Options
        $this->options = $wp_mmg_options;
        $this->data = $wp_mmg_data;
        $this->login = $wp_mmg_options['login'];
//        $this->display['photos'] = true;
//        $this->display['movies'] = false;
        $this->display['thumb'] = false;
        $this->display['title'] = $wp_mmg_options['photo_title'];
        $this->display['info'] = false;
        $this->display['description'] = $wp_mmg_options['photo_description'];
        $this->format['format'] = $wp_mmg_options['photo_photos_thumb'];
        $this->format['width'] = $wp_mmg_options['photo_photos_width'];
        $this->json_feed = $this->prepare_json_feed($this->album_json_feed);
    }

    public function __toString() {

        // Read the id to identify the album and look in the database the infos necessary to display the photo
        $id = explode("/", $this->id);
        $data_album = $this->load_data($this->prepare_json_feed($this->album_json_feed, $id[0]));
        foreach ($data_album as $value) {
            if ($value['id'] == $id[1])
                $item = $value;
        }

        $mmp_gallery .= '<div id="wp_mmg" class="' . $this->type . '" rel="' . $this->col . '">';
        $mmp_gallery .= '<div class="mask">';
        $mmp_gallery .= '<a href="' . $this->src_image_size($item['src_square'], "web") . '" title="' . $item['title'] . '" class="wp_mmg_lightbox" rel="' . $this->id . '"><img src="' . $this->src_image_size($item[src_square], $this->format['format']) . '" alt="thumbnail_' . $item[title] . '" width="' . $this->format['width'] . '" /></a>';
        $mmp_gallery .= '</div>'; // .mask
        $mmp_gallery .= '<div class="title">' . $item[title] . '</div>';
        if ($item['description'] && $this->display['description'])
            $mmp_gallery .= '<div class="sub-title">' . $item['description'] . '</div>';
        if ($this->options['copyright'])
            $mmp_gallery .= '<div class="sub-title">' . __('© All rights reserved') . '</div>';
        $mmp_gallery .= '</div>'; // #wp_mmg
        return $mmp_gallery;
    }

}

class WP_mmg_video extends WP_mmg {

    public function __construct($wp_mmg_options, $wp_mmg_data) {
        $this->type = 'video';
        // Saved Options
        $this->options = $wp_mmg_options;
        $this->data = $wp_mmg_data;
        $this->login = $wp_mmg_options['login'];
//        $this->display['photos'] = false;
//        $this->display['movies'] = true;
        $this->display['thumb'] = $wp_mmg_options['video_thumb'];
        $this->display['title'] = $wp_mmg_options['video_title'];
        $this->display['info'] = $wp_mmg_options['video_info'];
        $this->display['description'] = $wp_mmg_options['video_description'];
        $this->display['dl_link'] = $wp_mmg_options['video_dl_link'];
        $this->format['movies_size'] = $wp_mmg_options['video_movies_size'];
        $this->json_feed = $this->prepare_json_feed($this->album_json_feed);
    }

    public function __toString() {

        // Read the id to identify the album and look in the database the infos necessary to display the video
        if (strstr($id, '/')) {
            $id = explode("/", $this->id);
            $data_album = $this->load_data($this->prepare_json_feed($this->album_json_feed, $id[0]));
            foreach ($data_album as $value) {
                if ($value['id'] == $id[1])
                    $item = $value;
            }
        }
        // If it is a movie
        else {
            $this->json_feed = $this->prepare_json_feed($this->album_json_feed);
            $data = $this->load_data($this->json_feed);
            $item = $data[0];
        }

        if ($this->display['title'])
            $mmp_gallery .= '<h2>' . $item['title'] . '</h2>';
        // Begin VideoJS
        $mmp_gallery .= '<div class="video-js-box">';
        if ($this->display['thumb']) {
            if ($item['type'] == 'Movie') {
                $mmp_gallery .= '<video class="video-js" width="' . $item['movie'][$this->format['movies_size']]['width'] . '" height="' . $item['movie'][$this->format['movies_size']]['height'] . '" controls preload poster="' . $this->src_image_size($item['src_square'], $this->format['movies_size']) . '">';
                $mmp_gallery .= '  <source src="' . $item['movie'][$this->format['movies_size']]['url'] . '" type=\'video/mp4; codecs="avc1.42E01E, mp4a.40.2"\' />';
                $mmp_gallery .= '  <img src="' . $this->src_image_size($item['src_square'], $this->format['movies_size']) . '" width="' . $item['movie'][$this->format['movies_size']]['width'] . '" height="' . $item['movie'][$this->format['movies_size']]['height'] . '" alt="Poster Image"';
            } elseif ($item['type'] == 'Video') {
                $mmp_gallery .= '<video class="video-js" width="' . $item['movie']['width'] . '" height="' . $item['movie']['height'] . '" controls preload poster="' . $this->src_image_size($item['src_square'], "medium") . '">';
                $mmp_gallery .= '  <source src="' . $item['movie']['url'] . '" type=\'video/mp4; codecs="avc1.42E01E, mp4a.40.2"\' />';
                $mmp_gallery .= '  <img src="' . $this->src_image_size($item['src_square'], "medium") . '" width="' . $item['movie']['width'] . '" height="' . $item['movie']['height'] . '" alt="Poster Image" title="No video playback capabilities." />';
            }
            $mmp_gallery .= '</video>';
        }
        if ($this->display['dl_link']) {
            $mmp_gallery .= '<p class="vjs-no-video"><strong>Download Video:</strong>';
            $mmp_gallery .= '  <a href="' . $item['movie'][$this->format['movies_size']]['url'] . '">MP4</a>,';
            $mmp_gallery .= '</p>';
        }
        $mmp_gallery .= '</div>'; // End VideoJS
        if ($this->display['description'] OR $this->display['info']) {
            $mmp_gallery .= '<div id="wp_mmg">';
            if ($this->display['description'])
                $mmp_gallery .= '<p class="title">' . $item['description'] . '</p>';
            if ($this->display['info'])
                $mmp_gallery .= '<p class="sub-title">' . __('Duration: ', 'wp_mmg') . $item['info'] . '</p>';
            $mmp_gallery .= '</div>';
        }
        return $mmp_gallery;
    }

}

class WP_mmg_Widget extends WP_mmg {

    public function __construct($wp_mmg_options_widget, $wp_mmg_options, $wp_mmg_data) {
        $this->type = 'widget';
        $this->options = $wp_mmg_options;
        $this->data = $wp_mmg_data;
        $this->limit = $wp_mmg_options_widget[limit];
        $this->display['size'] = $wp_mmg_options_widget[thumb_size];
        $this->display['order'] = $wp_mmg_options_widget[display];
//        $this->options['update'] = $wp_mmg_options_widget[update];
        $this->json_feed = $this->prepare_json_feed($this->gallery_json_feed);
    }

    public function __toString() {

        $data = array();
        // Load_data function is good but it take ressources because it always check if updates are needed
        // To gain time, we will check if the use database is activated (otherwise there is no data)
        // Then we will check if a update is needed.
        // If not, we will directly load the database
        if ($this->options['update_database'] && !$this->needUpdate($this->json_feed, '0')) {
            $alldata = $this->data;
            unset($alldata[0]);
            foreach ($alldata as $item) {
                $data = array_merge($data, $item);
            }
        } else {
            $gallery = $this->load_data($this->json_feed);
            foreach ($gallery as $item) {
                $id = $item['id'];
                if ($id != 0) {
                    $json_feed = $this->prepare_json_feed($this->album_json_feed, $id);
                    $album = $this->load_data($json_feed, $id);
                    $data = array_merge($data, $album);
                }
            }
        }
        if (count($data) == 0) {
            $mmp_gallery = '<p>' . __('No items.', 'wp_mmg') . '</p>';
        } else {
            if ($this->display[order] == 'lastest') {
                $data = $this->order($data, "date");
            }
            if ($this->display[order] == 'random') {
                $data = $this->order($data, "shuffle");
            }
            // If limit set, limit number of items
            if ($this->limit != 0)
                $data = array_slice($data, 0, $this->limit);
            // Loop through each feed item and display each item as a hyperlink.
            foreach ($data as $item) {
                $mmp_gallery .= '<a href="' . $this->src_image_size($item['src_square'], 'web') . '" title="' . $item['title'] . '" class="wp_mmg_lightbox" rel="' . $item['id'] . '" style="height:' . $this->display['size'] . 'px;"><img src="' . $this->src_image_size($item['src_square'], "square") . '" width="' . $this->display['size'] . '" alt="thumbnail_' . $item['title'] . '" /></a>';
            }
        }
        return $mmp_gallery;
    }

}

class WP_mmg_Tinymce extends WP_mmg {

    public function __construct($wp_mmg_options, $wp_mmg_data) {
        $this->type = 'tinymce';
        $this->options = $wp_mmg_options;
        $this->data = $wp_mmg_data;
        $this->login = $wp_mmg_options['login'];
        $this->json_feed = $this->prepare_json_feed($this->gallery_json_feed);
    }

    public function return_gallery() {
        $this->json_feed = $this->prepare_json_feed($this->gallery_json_feed);
        $gallery = $this->load_data($this->json_feed);
        return $gallery;
    }

    public function create_album() {

        $this->json_feed = $this->prepare_json_feed($this->album_json_feed, $this->id);
        $data = $this->load_data($this->json_feed, $this->id);

        $mmp_gallery = '<ul>';
        foreach ($data as $item) {
            $mmp_gallery .= '<li>';
            $mmp_gallery .= '<img src="' . $this->src_image_size($item['src_square'], 'square') . '" alt="thumbnail_' . $item['title'] . '" rel="' . $this->id . '/' . $item['id'] . '" />';
            $mmp_gallery .= '</li>';
        }
        $mmp_gallery .= '</ul>';
        return $mmp_gallery;
    }

    public function create_videos() {

        $this->json_feed = $this->prepare_json_feed($this->gallery_json_feed);
        $data = array();
        if ($this->options['update_database'] && !$this->needUpdate($this->json_feed, '0') && $this->login == $this->options['login']) {
            $alldata = $this->data;
            unset($alldata[0]);
            foreach ($alldata as $item) {
                if (is_array($item))
                    $data = array_merge($data, $item);
            }
        } else {
            $gallery = $this->load_data($this->json_feed);
            foreach ($gallery as $item) {
                $id = $item['id'];
                if ($id != 0) {
                    $json_feed = $this->prepare_json_feed($this->album_json_feed, $id);
                    $album = $this->load_data($json_feed, $id);
                    $data = array_merge($data, $album);
                }
            }
        }

        $mmp_gallery = '<ul>';
        $i = 0;
        foreach ($data as $item) {
            if ($item[type] == "Video") {
                $mmp_gallery .= '<li>';
                $mmp_gallery .= '<img src="' . $this->src_image_size($item['src_square'], 'square') . '" alt="thumbnail_' . $item['title'] . '" rel="' . $this->id . '/' . $item['id'] . '" />';
                $mmp_gallery .= '</li>';
                $i++;
            }
        }
        if ($i == 0)
            $mmp_gallery .= '<p>' . __('No films in the gallery', 'wp_mmg') . '</p>';
        $mmp_gallery .= '</ul>';
        return $mmp_gallery;
    }

    public function create_movies() {

        $this->json_feed = $this->prepare_json_feed($this->gallery_json_feed);
        $data = $this->load_data($this->json_feed);
        $i = 0;
        $mmp_gallery = '<ul>';
        foreach ($data as $item) {
            if ($item[type] == "Movie") {
                $mmp_gallery .= '<li>';
                $mmp_gallery .= '<img src="' . $this->src_image_size($item['src_square'], 'square') . '" alt="thumbnail_' . $item['title'] . '" rel="' . $item['id'] . '" />';
                $mmp_gallery .= '</li>';
                $i++;
            }
        }
        if ($i == 0)
            $mmp_gallery .= '<p>' . __('No movies in the gallery', 'wp_mmg') . '</p>';
        $mmp_gallery .= '</ul>';
        return $mmp_gallery;
    }

}

?>