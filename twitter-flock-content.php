<?php
/*
Plugin Name: Twitter Flock
Plugin URI: http://www.workshop.rs
Description: Displaying tweets from multiple accounts with caching and different color scheme for every account.
Version: 1.0
Author: Ivan Lazarevic
Author URI: http://www.workshop.rs
*/

/*  Copyright 2010  Ivan Lazarevic  (email : devet.sest@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/* options page */
$options_page = get_option('siteurl') . '/wp-admin/admin.php?page=twitter-flock/options.php';

/* Adds our admin options under "Options" */
function tf_options_page() {
	add_options_page('Twitter Flock', 'Twitter Flock', 10, 'twitter-flock/options.php');
}

function tf_head(){
	 
	$path =  get_bloginfo('wpurl')."/wp-content/plugins/twitter-flock/";

	$script = "
		<link rel=\"stylesheet\" href=\"".$path."css/twitter-flock.css.php\" type=\"text/css\" media=\"screen\" charset=\"utf-8\"/>
		<script type=\"text/javascript\" src=\"".$path."scripts/jquery-1.3.2.js\"></script>
	";

	echo $script;
}

add_action('wp_head','tf_head');
add_action('admin_menu','tf_options_page');

?>