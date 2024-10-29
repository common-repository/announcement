<?php
/*
Plugin Name: annouoncement
Plugin URI: http://www.sajithmr.com/wordpress-announcement-plugin/
Description: You can put announcement in your website. When one visit your website , the announcement will be showed once. 
Version: 1.1
Author: Sajith
Author URI: http://www.sajithmr.com
*/


/*  Copyright 2008  sexyrate (email : mrsajith@gmail.com)

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

// create post object
class wm_mypost {
    var $post_title;
    var $post_content;
    var $post_status;
    var $post_author;    /* author user id (optional) */
    var $post_name;      /* slug (optional) */
    var $post_type;      /* 'page' or 'post' (optional, defaults to 'post') */
    var $comment_status; /* open or closed for commenting (optional) */
}

function announcement_header() {
	
	if( get_option('announce_active') == 1 && ! isset($_COOKIE['an_displayed'])  )
	{	
	
	$plugin_path = get_option('siteurl').'/wp-content/plugins/announcement' ;
	
	
	$style_sheet =  '<link rel="stylesheet" type="text/css" href="'.$plugin_path.'/lightwindow.css" />';
	$javascript =  '<script type="text/javascript" src="'.$plugin_path.'/prototype.js"></script>
			<script type="text/javascript" src="'.$plugin_path.'/effects.js"></script>
			<script type="text/javascript" src="'.$plugin_path.'/lightwindow.js"></script>';
	
	echo $style_sheet.$javascript;
	
	}
	
}

add_action('wp_head', 'announcement_header');
add_action('wp_head', 'insertAnnouncement');


function insertAnnouncement()
{
	
	
	
	if( get_option('announce_active') == 1 && ! isset($_COOKIE['an_displayed'])  )
	{
	
	$plugin_path = get_option('siteurl').'/wp-content/plugins/announcement' ;
	
	
	
$template =  <<< LAST
   
        

	<script type="text/javascript">
	
	window.onload = function() {

			myLightWindow = new lightwindow();
			

			myLightWindow.activateWindow({href: '$plugin_path/content.php', title: '', width:600, class: 'class="lightwindow page-options"' });

			}
			
	</script>		
	
	
LAST;
		
	
	
echo $template;	

	}
	
}


function announcement_install()
{
	// initialize post object
	$wm_mypost = new wm_mypost();
	
	// fill object
	$wm_mypost->post_title = "Announcement";
	$wm_mypost->post_content = "This is a sample";
	$wm_mypost->post_status = 'private';
	$wm_mypost->post_author = 1;
	
	// Optional; uncomment as needed
	$wm_mypost->post_type = 'page';
	// $wm_mypost->comment_status = 'closed';
	
	// feed object to wp_insert_post
	$post_id = wp_insert_post($wm_mypost);
	
	add_option('announce_id', $post_id , "");
	add_option('announce_active', 0,"");
	
	update_option('announce_id', $post_id);
	update_option('announce_active',0);

	
	
}

function announcement_option()
{
	if($_POST['action'] == 'save')
	{
		if ( isset( $_POST['active'] ) )
			update_option('announce_active',1);
		else
			update_option('announce_active',0);
	}
	
	?>
			
			
			
			<div  style="margin-left:30px" >
			<form action="?page=announcement" method="POST">
			
			<input type="hidden" name="action" value="save"/>
			
			<h3>Announcement Options &gt; &gt;</h3>
			
			<hr/>
			
			<font color="Fuchsia" size="4">Your Announcement Plugin is currently </font> <?php if(get_option('announce_active') == 1 ) :?> <font color="Green" size="5"> Active and Running </font>  <?php else: ?> <font color="Red" size="5">Inactive</font> <?php endif; ?>
			<hr/>
			
			 Go to 	<a href="<?= get_option('siteurl') ?>/wp-admin/page.php?action=edit&post=<?= get_option('announce_id'); ?>" >Edit Announcement</a> . Here you and edit and manage your announcement. (No need to publish). <br/>After that check the active checkbox below and save. If you want to stop the announcement, uncheck the checkbox and save. Happy Blogging !!!
			
			<hr/>
			
			<p>Active: <input type="checkbox" <?php if(get_option('announce_active') == 1 ) :?> checked="true" <?php endif; ?>  name="active"/> </p>
			<p><input type="submit" name="save" value="Save"/></p>
			
			<a href="<?= get_option('siteurl') ?>/wp-admin/page.php?action=edit&post=<?= get_option('announce_id'); ?>" >Edit Announcement</a>
			
			
			</form>
			</div>
			<?php
}


function announcement_add_admin()
{
	add_options_page('Announcement', 'Announcement', 7, 'announcement', 'announcement_option');
}

register_activation_hook(__FILE__,"announcement_install");

add_action('admin_menu', 'announcement_add_admin');

function add_suggestion()
{
	global $post;
	if ( $post->ID == get_option('announce_id') ) 
	{
		echo "<font color='#bd5a2e' size='5'> This is the Page for Announcement Plugin. Edit and Save,  No need to publish.</font><br/> ";
		
		
		
		$link = get_option('siteurl').'/wp-admin/options-general.php?page=announcement';
		
		echo '<a href="'.$link.'" >Plugin Settings &gt;&gt;</a><br/><br/>';
	}
}

add_action('edit_page_form', 'add_suggestion');

?>