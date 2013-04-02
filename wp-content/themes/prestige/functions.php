<?php
if ( function_exists('register_sidebar') ) {
   register_sidebar(array(
       'before_widget' => '<div id="%1$s" class="box widget %2$s">',
	   'before_title' => '<div class="wtitle"><h2>',
       'after_title' => '</h2></div><!--/wtitle --><div class="content">',
       'after_widget' => '</div><!--/content --></div><!--/box -->',       
   ));
}

add_action('admin_menu', 'taccess_theme_page');

function taccess_theme_page ()
{
	if ( count($_POST) > 0 && isset($_POST['taccess_settings']) )
	{
		$options = array ( 'feedburner_id', 'advertise_page', 'flickr_group_id' );

		foreach ( $options as $opt )
		{
			delete_option ( 'taccess_'.$opt, $_POST[$opt] );
			add_option ( 'taccess_'.$opt, $_POST[$opt] );
		}
		wp_redirect("themes.php?page=functions.php&saved=true");
		die;
	}
	add_theme_page(__('wpTheme Settings'), __('wpTheme Settings'), 'edit_themes', basename(__FILE__), 'taccess_settings');
}

function taccess_settings ()
{

if ( isset( $_REQUEST['saved'] ) ) echo '<div id="message" class="updated fade"><p><strong>'.__('Options saved.').'</strong></p></div>';

echo <<<TT
<div class="wrap">
	<h2>wp Theme Settings</h2>

<form method="post" action="">
	<table class="form-table">
		<tr valign="top">
			<th scope="row"><label for="feedburner_id">FeedBurner ID</label></th>
			<td><input name="feedburner_id" type="text" id="feedburner_id" value="<?php echo htmlspecialchars(stripslashes(get_option('taccess_feedburner_id'))); ?>" class="regular-text" /></td>
		</tr>
		<tr valign="top">
			<th scope="row"><label for="advertise_page">Advertise Page</label></th>
			<td>
				<?php wp_dropdown_pages("name=advertise_page&show_option_none=".__('- Select -')."&selected=" .get_option('taccess_advertise_page')); ?>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row"><label for="flickr_group_id">Flickr Group ID</label></th>
			<td>
				<input name="flickr_group_id" type="text" id="flickr_group_id" value="<?php echo htmlspecialchars(stripslashes(get_option('taccess_flickr_group_id'))); ?>" class="regular-text" />
			</td>
		</tr>
	</table>
	<p class="submit">
		<input type="submit" name="Submit" class="button-primary" value="Save Changes" />
		<input type="hidden" name="taccess_settings" value="save" style="display:none;" />
	</p>
</form>

</div>
TT;

}

function taccess_popular_posts ()
{
	global $wpp, $wpdb, $post;
	$summoner = 1;

			$table_wpp = $wpdb->prefix . $wpp->table_name;

			if ( $wpp->options_holder[$summoner]['pages'] ) {
				$nopages = '';
			} else {
				$nopages = "AND $wpdb->posts.post_type = 'post'";
			}

			// time range
			switch( $wpp->options_holder[$summoner]['range'] ) {
				case 'all-time':
					$range = "post_date_gmt < '".gmdate("Y-m-d H:i:s")."'";
					break;
				case 'today':
					$range = "$table_wpp.day = '".gmdate("Y-m-d")."'";
					break;
				case 'weekly':
					$range = "$table_wpp.day >= '".gmdate("Y-m-d")."' - INTERVAL 7 DAY";
					break;
				case 'monthly':
					$range = "$table_wpp.day >= '".gmdate("Y-m-d")."' - INTERVAL 30 DAY";
					break;
				case 'yearly':
					$range = "$table_wpp.day >= '".gmdate("Y-m-d")."' - INTERVAL 365 DAY";
					break;
				default:
					$range = "post_date_gmt < '".gmdate("Y-m-d H:i:s")."'";
					break;
			}

			// sorting options
			switch( $wpp->options_holder[$summoner]['sortby'] ) {
				case 1:
					$sortby = 'comment_count';
					break;
				case 2:
					$sortby = 'pageviews';
					break;
				case 3:
					$sortby = 'avg_views';
					break;
				default:
					$sortby = 'comment_count';
					break;
			}


			// dynamic query fields
			$fields = ', ';
			if ( $wpp->options_holder[$summoner]['views'] ) $fields .= "SUM($table_wpp.pageviews) AS 'pageviews' ";
			if ( $wpp->options_holder[$summoner]['comments'] ) {
				if ( $fields != ', ' ) {
					$fields .= ", $wpdb->posts.comment_count AS 'comment_count' ";
				} else {
					$fields .= "$wpdb->posts.comment_count AS 'comment_count' ";
				}
			}
			if ( $sortby == 'avg_views' ) {
				if ( $fields != ', ' ) {
					$fields .= ", (SUM($table_wpp.pageviews)/(IF ( DATEDIFF(CURDATE(), MIN($table_wpp.day)) > 0, DATEDIFF(CURDATE(), MIN($table_wpp.day)), 1) )) AS 'avg_views' ";
				} else {
					$fields .= "(SUM($table_wpp.pageviews)/(IF ( DATEDIFF(CURDATE(), MIN($table_wpp.day)) > 0, DATEDIFF(CURDATE(), MIN($table_wpp.day)), 1) )) AS 'avg_views' ";
				}
			}
			if ( $wpp->options_holder[$summoner]['author'] ) {
				if ( $fields != ', ' ) {
					$fields .= ", (SELECT $wpdb->users.display_name FROM $wpdb->users WHERE $wpdb->users.ID = $wpdb->posts.post_author ) AS 'display_name'";
				} else {
					$fields .= "(SELECT $wpdb->users.display_name FROM $wpdb->users WHERE $wpdb->users.ID = $wpdb->posts.post_author ) AS 'display_name'";
				}
			}
			if ( $wpp->options_holder[$summoner]['date'] ) {
				if ( $fields != ', ' ) {
					$fields .= ", $wpdb->posts.post_date_gmt AS 'date_gmt'";
				} else {
					$fields .= "$wpdb->posts.post_date_gmt AS 'date_gmt'";
				}
			}

			if (strlen($fields) == 2) $fields = '';


			$mostpopular = $wpdb->get_results("SELECT $wpdb->posts.ID, $wpdb->posts.post_title $fields FROM $wpdb->posts LEFT JOIN $table_wpp ON $wpdb->posts.ID = $table_wpp.postid WHERE post_status = 'publish' AND post_password = '' AND $range AND pageviews > 0 $nopages GROUP BY postid ORDER BY $sortby DESC LIMIT " . $wpp->options_holder[$summoner]['limit'] . "");
		return $mostpopular;
}

/**
 * add a default-gravatar to options
 */
if ( !function_exists('fb_addgravatar') ) {
	function fb_addgravatar( $avatar_defaults ) {
		$myavatar = get_bloginfo('template_directory') . '/images/avatar.gif';
		$avatar_defaults[$myavatar] = 'people';
 
		$myavatar2 = get_bloginfo('template_directory') . '/images/myavatar.png';
		$avatar_defaults[$myavatar2] = 'wpengineer.com';
 
		return $avatar_defaults;
	}
 
	add_filter( 'avatar_defaults', 'fb_addgravatar' );
}

?>
