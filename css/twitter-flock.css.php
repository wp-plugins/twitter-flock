<?php
header("Content-type: text/css"); 
include("../../../../wp-load.php");
$ftWidth = get_option('ftfeatured-width') != '' ? get_option('ftfeatured-width') : "500";

	$users  	= get_option('tf_usernames');
	$colors 	= get_option('tf_colors');
	$bgcolors 	= get_option('tf_bgcolors');

	$users 		= explode(",",$users);
	$colors 	= explode(",",$colors);
	$bgcolors 	= explode(",",$bgcolors);	
	
	foreach($users as $uk=>$uv){
			$uvs = strtolower($uv);
			echo ".tf_$uvs { color: $colors[$uk]; background-color: $bgcolors[$uk] }";
	}

?>

#twitter-flock { }
#twitter-flock img { float: left; padding: 5px; }
.tf_status { clear: both; padding: 5px; overflow: auto; zoom: 1; margin-bottom: 1px; }
.tf_time { font-style: italic; color: #999 }
#twitter-flock-tabs { overflow: auto; zoom: 1; }
#twitter-flock-tabs a{ border: 0px; } 
#twitter-flock-tabs img { border: 0px; padding-bottom: 0px; margin-right: 1px; }