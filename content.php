<?php @require('../../../wp-config.php'); 
setcookie("an_displayed", "set", time() + (60*60*24) ,'/');
?><?php
	
	$post_id = get_option('announce_id');
	$post = get_post($post_id);
	
	
?>
			
<div id="announcement">
<h3><?= $post->post_title; ?></h3>

<?= $post->post_content; ?>
<br/><br/>		
<div style="float:right;font-color:#cccccc;font-size:9px">Powered by <a href="http://www.sajithmr.me/wordpress-announcement-plugin/">Wordpress Announcement</a> Plugin</div>
</div>