<?php
$location = $options_page; // Form Action URI
$ch = '';
?>
<div id="twitter-flock">
	<h1>Twitter Flock</h1>

	
	<form method="post" action="options.php">

		<?php wp_nonce_field('update-options'); ?>

		<div class="formrow first">
			<label for="usernames">Twitter Username(s):</label>
			<input type="text" id="usernames" name="tf_usernames" class="tf_input" value="<?php echo get_option('tf_usernames'); ?>" />
			<span class="desc">List of Twitter accounts separated with ",". e.g. <i>kopipejst,savinglists,96rs</i></span>
		</div>

		<div class="formrow">		
			<label for="colors">Font Color(s):</label>
			<input type="text" id="colors" name="tf_colors" class="tf_input" value="<?php echo get_option('tf_colors'); ?>" />
			<span class="desc">List of font colors for every account separated with ",". e.g. <i>#CCCCCC,black,#9900CC</i></span>			
		</div>
		
		<div class="formrow">
			<label for="bgcolors">Background Color(s):</label>
			<input type="text" id="bgcolors" name="tf_bgcolors" class="tf_input" value="<?php echo get_option('tf_bgcolors'); ?>" />
			<span class="desc">List of background colors for every account separated with ",". e.g. <i>grey,#FFFFFF,#000000</i></span>					
		</div>
		
		<div class="formrow">		
			<label for="hashes">Hashes:</label>
			<input type="text" id="hashes" name="tf_hashes" class="tf_input" value="<?php echo get_option('tf_hashes'); ?>" />
			<span class="desc">List of hashes that post which you want to be displayed contains separated with ",". e.g. <i>#ws,#in</i></span>								
		</div>

		<div class="formrow">		
			<label for="number">Nubmer of Posts:</label>
			<input type="text" id="number" name="tf_number" class="tf_input" value="<?php echo get_option('tf_number'); ?>" />
			<span class="desc">Total number of posts for all usernames ( 15 by default )</span>								
		</div>	
		
		<div class="formrow">		
			<label>Show User's Photo</label>
			<?php if(get_option('tf_photos') != ""){ $ch = "checked=''"; } else { $ch = ''; }?>
			<input type="checkbox" id="photos" name="tf_photos" <?php echo $ch ?> />
			<span class="desc">This option will show user photo with every message.</span>					
		</div>
		
		<div class="formrow">		
			<label>Link @screennames</label>
			<?php if(get_option('tf_screennames') != ""){ $ch = "checked=''"; } else { $ch = ''; }?>
			<input type="checkbox" id="screennames" name="tf_screennames" <?php echo $ch ?> />
			<span class="desc">Create links from screennames in messages.</span>				
		</div>
		
		<div class="formrow">		
			<label>Link http://</label>
			<?php if(get_option('tf_https') != ""){ $ch = "checked=''"; } else { $ch = ''; }?>
			<input type="checkbox" id="https" name="tf_https" <?php echo $ch ?> />
			<span class="desc">Create links from http, https, file and ftp in messages.</span>				
		</div>
		
		<div class="formrow">		
			<label>Link #hashes</label>
			<?php if(get_option('tf_chashes') != ""){ $ch = "checked=''"; } else { $ch = ''; }?>
			<input type="checkbox" id="chashes" name="tf_chashes" <?php echo $ch ?> />
			<span class="desc">Create links from hashes in messages.</span>				
		</div>
		
		<div class="formrow">		
			<label>Remove #hashes</label>
			<?php if(get_option('tf_removehashes') != ""){ $ch = "checked=''"; } else { $ch = ''; }?>
			<input type="checkbox" id="removehashes" name="tf_removehashes" <?php echo $ch ?> />
			<span class="desc">Remove hashes from list in messages.</span>				
		</div>
		
		<div class="formrow">		
			<label>Show Tabs</label>
			<?php if(get_option('tf_tabs') != ""){ $ch = "checked=''"; } else { $ch = ''; }?>
			<input type="checkbox" id="tabs" name="tf_tabs" <?php echo $ch ?> />
			<span class="desc">Show tabs for filtering users posts.</span>				
		</div>		
				
		<div class="formrow last">		
			<label>Use Cache</label>
			<?php if(get_option('tf_cache') != ""){ $ch = "checked=''"; } else { $ch = ''; }?>
			<input type="checkbox" id="cache" name="tf_cache" <?php echo $ch ?> />
			<span class="desc">Using cache will improve your page load. Data will be saved in cache every minute.</span>				
		</div>


		<input type="hidden" name="action" value="update" />
        <input type="hidden" name="page_options" value="tf_usernames,tf_colors,tf_bgcolors,tf_hashes,tf_number,tf_photos,tf_screennames,tf_https,tf_chashes,tf_removehashes,tf_tabs,tf_cache" />
		
		<input type="submit" class="button-primary" value="Save Settings" />
	
	</form>
</div>

<style>
	#twitter-flock { font-size: 16px; line-height: 20px; }
	#twitter-flock h1 { font-family: Georgia; font-style: italic; font-size: 24px; line-height: 35px; font-weight: normal; }
	#twitter-flock label { width: 180px; float: left; color: #000; }
	#twitter-flock .tf_input { float: left; width: 300px; }  
	#twitter-flock .desc{ clear: left; display: block; font-size: 12px; color: #666; } 
	#twitter-flock .formrow { padding: 7px; clear: both; border-bottom: 1px solid #e5e5e5; border-top: 2px solid #fff; }
	#twitter-flock .first { border-top: none; } 
	#twitter-flock .last { border-bottom: none; }  
	.button-primary { width: 100px; margin-top: 15px; height: 25px; }       
</style>



