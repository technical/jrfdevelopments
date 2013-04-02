<?php
function wp_mmg_addbuttons() {
   // Don't bother doing this stuff if the current user lacks permissions
   if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') )
     return;
 
   // Add only in Rich Editor mode
   if ( get_user_option('rich_editing') == 'true') {
     add_filter("mce_external_plugins", "add_wp_mmg_tinymce_plugin");
     add_filter('mce_buttons', 'register_wp_mmg_button');
   }
}
 
function register_wp_mmg_button($buttons) {
   array_push($buttons, "separator", "wp_mmg");
   return $buttons;
}
 
// Load the TinyMCE plugin : editor_plugin.js (wp2.5)
function add_wp_mmg_tinymce_plugin($plugin_array) {
   $plugin_array['wp_mmg'] = plugins_url('editor_plugin.js', __FILE__);
   return $plugin_array;
}
 
// init process for button control
add_action('init', 'wp_mmg_addbuttons');
?>