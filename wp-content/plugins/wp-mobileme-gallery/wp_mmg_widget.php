<?php
/**
 * @package wp_mmg
 * @author Sébastien Gillard
 */
#########################################################################################################
# FONCTIONS																								#
#########################################################################################################
require_once 'wp_mmg.class.php';
include_once(ABSPATH . WPINC . '/feed.php'); // Get RSS Feed(s)
#########################################################################################################
# DEFAULT OPTIONS																						#
#########################################################################################################
// Get saved options
$wp_mmg_options = get_option('wp_mmg_options');
$wp_mmg_data = get_option('wp_mmg_data');

class wp_mmg_widget_config extends WP_Widget {

    /** constructor */
    function wp_mmg_widget_config() {
        parent::WP_Widget(false, $name = 'Mobileme Gallery');
    }

    /** @see WP_Widget::widget */
    function widget($args, $instance) {
        extract($args);
        $title = apply_filters('widget_title', $instance['title']);
        echo $before_widget;
        if ($title)
            echo $before_title . $title . $after_title;
        else
            echo $before_title . __("Latest Photos", 'wp_mmg') . $after_title;
        echo wp_mmg_latest_photos($instance);
        echo $after_widget;
    }

    /** @see WP_Widget::update */
    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['limit'] = strip_tags($new_instance['limit']);
        $instance['thumb_size'] = strip_tags($new_instance['thumb_size']);
        $instance['display'] = $new_instance['display'];
        return $instance;
    }

    /** @see WP_Widget::form */
    function form($instance) {
        $defaults = array('title' => 'My Gallery', 'limit' => 8, 'thumb_size' => 70, 'display' => 'lastest', 'update' => true);
        $instance = wp_parse_args((array) $instance, $defaults);
        $title = esc_attr($instance['title']);
        $limit = esc_attr($instance['limit']);
        $thumb_size = esc_attr($instance['thumb_size']);
        $display = $instance['display'];
        $update = $instance['update'];
        ?>
        <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></label></p>
        <p><label for="<?php echo $this->get_field_id('limit'); ?>"><?php _e('Limit:'); ?> <input class="widefat" id="<?php echo $this->get_field_id('limit'); ?>" name="<?php echo $this->get_field_name('limit'); ?>" type="text" value="<?php echo $limit; ?>" /></label></p>
        <p><label for="<?php echo $this->get_field_id('thumb_size'); ?>"><?php _e('Thumbnail size:'); ?> <input class="widefat" id="<?php echo $this->get_field_id('thumb_size'); ?>" name="<?php echo $this->get_field_name('thumb_size'); ?>" type="text" value="<?php echo $thumb_size; ?>" /></label></p>
        <p>
            <label for="<?php echo $this->get_field_id('display'); ?>">Display:</label>
            <select id="<?php echo $this->get_field_id('display'); ?>" name="<?php echo $this->get_field_name('display'); ?>" class="widefat" style="width:100%;">
                <option <?php if ('random' == $display)
            echo 'selected="selected"'; ?>>random</option>
                <option <?php if ('lastest' == $display)
                echo 'selected="selected"'; ?>>lastest</option>
            </select>
        </p><?php
        }

    }

// class wp_mmg_widget

    function wp_mmg_latest_photos($instance) {
        $mmg_widget = new WP_mmg_Widget($instance, get_option('wp_mmg_options'), get_option('wp_mmg_data'));
        return $mmg_widget;
    }
?>