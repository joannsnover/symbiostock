<?php
$site_limit = 5;
global $current_user;
      get_currentuserinfo();
	  
//this makes feed aggregating possible - 
include_once(ABSPATH.WPINC.'/rss.php');
settings_fields( 'symbiostock_settings_group' ); 
$site_count = 1;
$alerts = array();
while ( $site_count <= 5 ) {
    
	if(isset($_POST[ 'symbiostock_network_site_' . $site_count ])){
    	$symbiostock_network_site = $_POST[ 'symbiostock_network_site_' . $site_count ];
	}
	
    if ( isset( $symbiostock_network_site ) ) {
       
        if ( symbiostock_validate_url( $symbiostock_network_site ) ) {
            
            update_option( 'symbiostock_network_site_' . $site_count, $_POST[ 'symbiostock_network_site_' . $site_count ] );
            
        } elseif ( $symbiostock_network_site == 'http://' || empty( $symbiostock_network_site )) { 
            delete_option( 'symbiostock_network_site_' . $site_count );
            
        } else {
			delete_option( 'symbiostock_network_site_' . $site_count );	
					
			$alerts['symbiostock_network_site_' . $site_count] = '<p class="alert">Site' . $site_count . ': You\'ve entered an invalid url. <strong>http://www.somesite.com</strong> is the preferred format.</p>';                       
        }
    }
    $site_count++;
}
//personal network settings
if(isset($_POST['symbiostock_my_network_name'])){ 
	update_option('symbiostock_my_network_name', $_POST['symbiostock_my_network_name']); 
}
if(isset($_POST['symbiostock_my_network_description'])){ 
	update_option('symbiostock_my_network_description', $_POST['symbiostock_my_network_description']); 
}
if(isset($_POST['symbiostock_my_network_avatar'])){ 
	if(empty($_POST['symbiostock_my_network_avatar'])){
		$symbiostock_my_network_avatar = symbiostock_32_DEFAULT;
	} else {
		$symbiostock_my_network_avatar = $_POST['symbiostock_my_network_avatar'];	
	}
	update_option('symbiostock_my_network_avatar', $symbiostock_my_network_avatar); 
}
if(isset($_POST['symbiostock_my_network_logo'])){ 
	if(empty($_POST['symbiostock_my_network_logo'])){
		$symbiostock_my_network_logo = symbiostock_128_DEFAULT;
	} else {
		$symbiostock_my_network_logo = $_POST['symbiostock_my_network_logo'];	
	}
	
	update_option('symbiostock_my_network_logo', $symbiostock_my_network_logo); 
}
if(isset($_POST['symbiostock_my_network_about_page'])){ 
	update_option('symbiostock_my_network_about_page', $_POST['symbiostock_my_network_about_page']); 
}
if(isset($_POST['symbiostock_my_network_announcement'])){ 
	update_option('symbiostock_my_network_announcement', $_POST['symbiostock_my_network_announcement']); 
}
if(isset($_POST['symbiostock_use_network'])){ 
	update_option('symbiostock_use_network', $_POST['symbiostock_use_network']); 
}
$symbiostock_use_network = get_option('symbiostock_use_network', 'false');
if($symbiostock_use_network == 'true'){
$ssnet_yes = 'checked="checked"';
$ssnet_no = '';
?>

<div id="symbiostock_network_container">
    <div id="symbiostock_network_header">
        <div class="symbiostock_frame_container">
            <h3>Symbiostock Network - Associates and Status</h3>
            <p>Integrate Your Site with the Symbiostock Community. The defining feature of Symbiostock, the ability to network and share search results for mutual growth. This aspect of Symbiostock is still under much development. Please tolerate the testing and bugs.</p>
            <p><em><a title="Update your site info" href="#your_site_info">Please make sure your info is up to date...</a></em></p>
        </div>
    </div>
    <div id="symbiostock_network_main">
        <div class="symbiostock_frame_container">
            <table class="widefat form-table symbiostock-settings">
                <thead>
                    <tr>
                        <th colspan="2"> <strong>Connected Sites / Status</strong> - Paste your network site urls into slots below. </th>
                    </tr>
                </thead>
                <?php 
	$site_count =1;
while($site_count <= 5){
	
	?>
                <tr>
                    <th class="symbiostock_network_site_header" scope="row">Site <?php echo $site_count; ?></th>
                    <td><input class="symbiostock_network_site longfield" type="text" name="<?php echo 'symbiostock_network_site_' . $site_count; ?>"  id="<?php echo 'symbiostock_network_site_' . $site_count; ?>" value="<?php echo get_option('symbiostock_network_site_' . $site_count, 'http://' ); ?>" />
                        <?php 
				if(isset($alerts['symbiostock_network_site_' . $site_count])){
					echo $alerts['symbiostock_network_site_' . $site_count];
					} ?></td>
                </tr>
                <?php
	$site_count++;
	}
	?>
                <tfoot>
                    <tr>
                        <td colspan="2"><a class="savelink" title="save_changes" href="#save_changes"><strong>Save Changes</strong></a></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <div id="symbiostock_network_sidebar">
        <div class="symbiostock_frame_container"> <a target="_blank" title="Symbiostock Updates" href="http://www.symbiostock.com"> <img title="Symbiostock Updates" src="<?php echo symbiostock_LOGOSMALL; ?>" /> </a>
            <?php
		symbiostock_feed_display('http://www.symbiostock.com/feed/', 3)	   
	   ?>
            <p>This is where updates, news, and general cool stuff about Symbiostock will go. Yes, there will be shameless advertising as well.</p>
        </div>
    </div>
    <div id="symbiostock_network_updates">
        <div class="symbiostock_frame_container">
            <table id="your_site_info"  class="widefat form-table symbiostock-settings">
                <thead>
                    <tr>
                        <th colspan="2"> <strong>Your Site Info</strong> - How your site appears to others.. </th>
                    </tr>
                </thead>
                <tr>
                    <th scope="row">Site Name</th>
                    <td><input class="longfield" type="text" name="symbiostock_my_network_name"  id="symbiostock_my_network_name" value="<?php echo stripcslashes ( get_option('symbiostock_my_network_name', $current_user->user_firstname . ' ' . $current_user->user_lastname . ' (' . $current_user->display_name  . ')')); ?>" /></td>
                </tr>
                <tr>
                    <th scope="row">Site Announcement</th>
                    <td><textarea class="longfield" name="symbiostock_my_network_announcement"  id="symbiostock_my_network_announcement" ><?php echo stripcslashes ( get_option('symbiostock_my_network_announcement', '')) ?></textarea>
                        <br />
                        Real-time announcement regarding your site. Running a discount? Offering custom work? </td>
                </tr>
                <tr>
                    <th scope="row">Site Description</th>
                    <td><textarea class="longfield" name="symbiostock_my_network_description"  id="symbiostock_my_network_description" ><?php echo stripcslashes ( get_option('symbiostock_my_network_description')) ?> </textarea></td>
                </tr>
                <tr>
                    <th scope="row">Site Avatar</th>
                    <td><input class="longfield" type="text" name="symbiostock_my_network_avatar"  id="symbiostock_my_network_avatar" value="<?php echo stripcslashes ( get_option('symbiostock_my_network_avatar', symbiostock_32_DEFAULT)) ?>" />
                        <br />
                        URL to Avatar you upload in <a title="Media Area" href="/wp-admin/upload.php">Media area</a>. <em>Must be <strong>32 × 32 px</strong> or it will not validate.</em></td>
                </tr>
                <tr>
                    <th scope="row">Site Logo</th>
                    <td><input class="longfield" type="text" name="symbiostock_my_network_logo"  id="symbiostock_my_network_logo" value="<?php echo stripcslashes ( get_option('symbiostock_my_network_logo', symbiostock_128_DEFAULT)) ?>" />
                        <br />
                        URL to Logo you upload in <a title="Media Area" href="/wp-admin/upload.php">Media area</a>. <em>Must be <strong>128 × 128 px</strong> or it will not validate.</em></td>
                </tr>
                <tr>
                    <th scope="row">About Page</th>
                    <td><input class="longfield" type="text" name="symbiostock_my_network_about_page"  id="symbiostock_my_network_about_page" value="<?php echo stripcslashes( get_option('symbiostock_my_network_about_page', '')) ?>" />
                        <br />
                        Make an awesome about page and post the URL here. </td>
                </tr>
                <tfoot>
                    <tr>
                        <td colspan="2"><a class="savelink" title="save_changes" href="#save_changes"><strong>Save Changes</strong></a></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <h2>Integrate Your Site with the Symbiostock Community</h2>
    <p>More relevant stuff will be placed here as the need is discovered</p>
    <div> <span id="save_changes" >&nbsp;</span>
        <?php } else { 
	
	$ssnet_yes = '';
	$ssnet_no = 'checked="checked"';
	?>
        <br />
        <a target="_blank" title="Symbiostock Updates" href="http://www.symbiostock.com"> <img title="Symbiostock Updates" src="<?php echo symbiostock_LOGOSMALL; ?>" /> </a>
        <?php
	//if not using the network, just display the feed
	symbiostock_feed_display('http://www.symbiostock.com/feed/', 3);
	
 } ?>
        <p>Use Symbiostock Network?<br />
            <label for="symbiostock_use_network_1">
                <input id="symbiostock_use_network_1" type="radio" name="symbiostock_use_network" value="true" <?php echo $ssnet_yes; ?> />
                Activate</label>
            <br />
            <label for="symbiostock_use_network_2">
                <input id="symbiostock_use_network_2" type="radio" name="symbiostock_use_network" value="false" <?php echo $ssnet_no; ?> />
                Deactivate</label>
        </p>
    </div>
</div>