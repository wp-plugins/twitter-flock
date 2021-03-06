<?php
/*
Plugin Name: Twitter Flock
Plugin URI: http://workshop.rs/2010/02/twitter-flock-for-wordpress-multiple-accounts-tweets-with-style/
Description: Displaying tweets from multiple accounts with caching and different color scheme for every account. With sidebar widget feature.
Version: 1.2.1
Author: Ivan Lazarevic
Author URI: http://workshop.rs
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
		<link rel=\"stylesheet\" href=\"".$path."css/twitter-flock.css.php\" type=\"text/css\" media=\"screen\" charset=\"utf-8\"/>";
	
	if(!get_option('tf_jquery')){	
		$script .=	"<script type=\"text/javascript\" src=\"".$path."scripts/jquery-1.3.2.js\"></script>";
	}

	echo $script;
}


function TwitterFlock(){
	new TwitterFlockStart;
}

class TwitterFlockStart {

	private $users;
	private $no_of_statuses;
	private $hashes;
	private $avatars;

	private $T_URL 	  = "http://api.twitter.com/1/statuses/user_timeline";
	private $T_FORMAT = ".xml";
	private $T_CACHE;

	
	function __construct(){
		
		$force = $_GET['tf_force']; // force source from url
		$this->T_CACHE = dirname(__FILE__) . '/cache/cache.ch';
		
		$users = get_option('tf_usernames');
		$this->users = explode(",",$users);
		
		$this->no_of_statuses = is_numeric(get_option('tf_number')) ? get_option('tf_number') : 15;
		$hashes = get_option('tf_hashes');
		$this->hashes = explode(",",$hashes);

		if($force)
			$data = $this->tf_getData($force);
		else
			$data = $this->tf_getData();
		
		echo "<div id='twitter-flock'>";
		echo "<h2 id='twitter-flock-title'>".get_option('tf_title')."</h2>";
		
			if(get_option('tf_tabs') != "")
				$this->tf_printHeader($data);
			$this->tf_printData($data);
		echo "</div>";
	}
	
	
	/**
	 * Return data to display
	 * Calculate source, Api or Cache
	 * 
	 * @param $force - if we want to force data source, API or CACHE
	 * 
	 */
	private function tf_getData($force = ''){

		if($force == 'CACHE'){
			if(file_exists($this->T_CACHE)){
				$data = unserialize(file_get_contents($this->T_CACHE));
				return $data;
			} else {
				echo "Sorry, we're not able to show data from Twitter";
			}
		}
		
		if($force == 'API'){
			$data = $this->tf_getApiData();
			return $data;
		}
		
		if(file_exists($this->T_CACHE)){
			$cache_time = filemtime($this->T_CACHE);
			if(time()-$cache_time > 60){
				$data = $this->tf_getApiData();
			} else {
				$data = unserialize(file_get_contents($this->T_CACHE));
			}
		} else {
			$data = $this->tf_getApiData();
		}
		
		return $data;
		
	}
	
	/**
	 * 
	 * Create array from Twitter API response
	 * 
	 */	
	public function tf_getApiData(){
		
		foreach ($this->users as $key=>$name){
			
			$url = $this->T_URL.$this->T_FORMAT.'?include_entities=true&include_rts=true&screen_name='.$name;
			//$url = https://api.twitter.com/1/statuses/user_timeline.xml?include_entities=true&include_rts=true&screen_name=kopipejst
			$xml = @simplexml_load_file($url);

			/* if there is error in Twitter response force data from Cache */
			if(!$xml) {
				$data = $this->tf_getData('CACHE');
				return $data;
			}
			
			foreach ($xml as $x){
				
				$tmp['text'] 		= (string)$x->text;
				$tmp['time'] 		= (string)$x->created_at;
				$tmp['timestamp']	= (string)strtotime($x->created_at);
				$tmp['name'] 		= (string)$x->user->name;
				$tmp['username']	= (string)$x->user->screen_name;
				$tmp['avatar']		= (string)$x->user->profile_image_url;
				$sts[] = $tmp;
				
			}
		}
		
		/* sort statuses array by timestamp */
			$tmp = $sts;
			foreach ($tmp as $key=>$row) {
				$text[$key] = $row['timestamp'];
			}
		
			array_multisort($text,SORT_DESC,$tmp);
		
			$sts = $tmp;
		/* end sorting */		
			
		/* put data in file for latter use : cache */	
			$cache = fopen($this->T_CACHE, 'w');
			fwrite($cache, serialize($sts));
			fclose($cache);
		
		return $sts;	
	}
	

	/**
	 * Print Twitter data
	 * 
	 * @param $sts - array of twitter data
	 * 
	 */
	private function tf_printData($sts){
		
		$i = 0;
		echo "<div id='twitter-flock-body'>";

		foreach ($sts as $key=>$val){
			
			if($this->tf_checkHashes($val['text'])) continue;
			
			$screenname = "<a href='http://www.twitter.com/".$val['username']."'>".$val['name'].":</a>";
			$img  		= "<img src='{$val['avatar']}' />";
			$text 		= $this->tf_parseStatus($val['text']);
			$username	= strtolower($val['username']);
			
			if(get_option('tf_photos') == '') $img = '';
			
			echo "<div class='tf_status tf_$username'>$img<b class='tf_name'>$screenname </b><span class='tf_text'>$text </span><br /><span class='tf_time'>{$this->tf_showTime($val['timestamp'])}</span></div>";
			$i++;
			if($i == $this->no_of_statuses) break;
			
		}
		echo "</div>";

	}
		
	private function tf_printHeader($sts){

		foreach($sts as $key=>$val){

			if($this->tf_checkHashes($val['text'])) continue;
			
			$username			= strtolower($val['username']);
			$img  				= "<img src='{$val['avatar']}' class='tf_$username' />";
			if(!$avatars[$username]) $avatars[$username] = $img;
			
		}

		$sts = get_option('tf_usernames');
		$sts = explode(',',$sts);
		
		echo "<div id='twitter-flock-tabs'>";
		$firstImg = get_bloginfo('wpurl')."/wp-content/plugins/twitter-flock/img/all_tweets.png";
		echo "<a href='javascript:void(0)' class='tf_tabs' id='tf_all'><img src='$firstImg' title='all' /></a>";
		foreach ($sts as $key=>$val){
			$name 	= "<a href='javascript:void(0)' class='tf_tabs' id='tf_$val' title='$val' >{$avatars[$val]}</a>";
			echo $name;
		}
		echo "</div>";
		
		echo <<<T

		<script>
		
			$('.tf_tabs').css('opacity','0.4');
			$('.tf_tabs:first').css('opacity','1');
		
			$('.tf_tabs').click( function(){
				$('.tf_tabs').css('opacity','0.4');
				if($(this).attr('id') == 'tf_all')
					$('.tf_status').show();
				else
					$('.tf_status').hide();
				$('.' + $(this).attr('id')).show();
				$(this).css('opacity','1');
			});
		
		</script>
		
T;
		
	}
	
	/**
	 * Create 'human readable' date format from timestamp
	 * 
	 * @param $ts - timestamp
	 * 
	 * TODO fix for 1st, 2nd, 3rd 
	 * 
	 */
	private function tf_showTime($ts){
		$c = time() - $ts;
		$nd = date('g:i A M d\, Y',$ts);
		
		if ($c < 60) return $c.' seconds ago';
		elseif ($c < 3600) return (int)($c/60).' minutes ago';
		elseif ($c < 3600*24) return (int)($c/3600).' hours ago';
		else return $nd;
	}

	
	/**
	 * 
	 * Adding links, screen names, keywords 
	 * Remove unwanted hashes
	 * 
	 * @param $text - Twitter message
	 * 
	 */	
	private function tf_parseStatus($text){
		
		global $escape_hashes;
		// add urls
		if(get_option('tf_https') != '')
			$text = preg_replace('@((https?|ftp|file)://([-\w\.]+)+(:\d+)?(/([\w/_\.-]*(\?\S+)?)?)?)@', '<a href="$1">$1</a>', $text);
		
		// add screen name
		if(get_option('tf_screennames') != '')
			$text = preg_replace('#(@)(([^\s\,]+))#', '$1<a href="http://twitter.com/$2">$2</a>', $text);	
		
		// remove unwanted hashes
		if(get_option('tf_removehashes') != ''){
			if($this->hashes[0] != ''){
				foreach($this->hashes as $val){
					if(!strpos($text,$val)) continue;
					$text = str_replace($val,'',$text);
				}
			}
		}
	
		// add keywords	
		if(get_option('tf_chashes') != '')
			$text = preg_replace('@(#([^\s\,]+))@', '<a href="http://twitter.com/search?q=$1">$1</a>', $text);	
	
		return $text;
		
	}

	
	/**
	 * 
	 * Check does status have some of hashes that we want to display
	 * 
	 * @param $text - Twitter message
	 * 
	 */
	private function tf_checkHashes($text){

		if(get_option('tf_hashes') != ''){
			if($this->hashes[0] != ''){
				foreach($this->hashes as $val){
					if(strpos($text,$val)) return false;
				}
			}
			return true;
		} else {
			return false;
		}
		
	}
}

class TwitterFlock{
  function control(){
    echo 'Configure this widget on <a href="'.get_option('siteurl') . '/wp-admin/admin.php?page=twitter-flock/options.php'.'">options page</a>';
  }
  function widget($args){
    echo $args['before_widget'];
    new TwitterFlockStart();
    echo $args['after_widget'];
  }
  function register(){
    register_sidebar_widget('Twitter Flock', array('TwitterFlock', 'widget'));
    register_widget_control('Twitter Flock', array('TwitterFlock', 'control'));
  }
}


add_action('wp_head','tf_head');
add_action('admin_menu','tf_options_page');
add_action("widgets_init", array('TwitterFlock', 'register'));


?>