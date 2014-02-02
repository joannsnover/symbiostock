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
                    
            $alerts['symbiostock_network_site_' . $site_count] = '<p class="alert">' . __( 'Site', 'symbiostock') . $site_count . __( ': You\'ve entered an invalid url. <strong>http://www.somesite.com</strong> is the preferred format.</p>', 'symbiostock');                       
        }
    }
    $site_count++;
}
//personal network settings

if(isset($_POST['symbiostock_site_author'])){
    update_option('symbiostock_site_author', $_POST['symbiostock_site_author']);    
    }

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

if(isset($_POST['symbiostock_my_promoted_keywords'])){ 
    $total = explode(',', $_POST['symbiostock_my_promoted_keywords']);
    if(count($total) > 40){
        $no_more_keywords =  '<p>' . __( 'Please choose no more than 20 promoted keywords. Promoted keywords not saved.', 'symbiostock') . '</p>';
        
        } else {
        $keywords = array();    
        foreach($total as $keyword){
            array_push($keywords, trim($keyword));
            }
                
        update_option('symbiostock_my_promoted_keywords', implode(', ', $keywords)); 
        }
}

if(isset($_POST['symbiostock_my_filtered_keywords'])){ 
    $total = explode(',', $_POST['symbiostock_my_filtered_keywords']);
    if(count($total) > 40){
        $no_more_f_keywords =  '<p>' . __( 'Please choose no more than 40 promoted keywords. Promoted keywords not saved.', 'symbiostock') . '</p>';
        
        } else {
        $keywords = array();    
        foreach($total as $keyword){
            array_push($keywords, trim($keyword));
            }
                
        update_option('symbiostock_my_filtered_keywords', implode(', ', $keywords)); 
        }
}

//Rating
if(isset($_POST['symbiostock_filter_level'])){ 
    update_option( 'symbiostock_filter_level', $_POST[ 'symbiostock_filter_level' ] );
}
$rating = get_option('symbiostock_filter_level', '0');
$rating == '0' ? $rating_0 = 'selected="selected"' : $rating_0 = '';
$rating == '1' ? $rating_1 = 'selected="selected"' : $rating_1 = '';
$rating == '2' ? $rating_2 = 'selected="selected"' : $rating_2 = '';
$rating == '3' ? $rating_3 = 'selected="selected"' : $rating_3 = '';

//Allow unrated content
if(isset($_POST['symbiostock_allow_unrated'])){ 
    update_option( 'symbiostock_allow_unrated', $_POST[ 'symbiostock_allow_unrated' ] );
}
$allow_unrated = get_option('symbiostock_allow_unrated', '1');
$allow_unrated == '0' ? $allow_unrated_1 = 'checked' : $allow_unrated_1 = '';
$allow_unrated == '1' ? $allow_unrated_2 = 'checked' : $allow_unrated_2 = '';


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
            <h3><?php _e( 'Symbiostock Network - Associates and Status', 'symbiostock') ?></h3>           
        </div>
    </div>
    <div id="symbiostock_network_main">
        <div class="symbiostock_frame_container">
            
            <?php
            include_once(symbiostock_CLASSROOT . 'network-manager/network-list-manager.php');
            ?>        
            
        </div>
    </div>
    <div id="symbiostock_network_sidebar">
        <div class="symbiostock_frame_container"> <a target="_blank" title="<?php _e( 'Symbiostock Updates', 'symbiostock') ?>" href="http://www.symbiostock.com"> <img title="<?php _e( 'Symbiostock Updates', 'symbiostock') ?>" src="<?php echo symbiostock_LOGOSMALL; ?>" /> </a> <hr />
       
       <p class="description"><a target="_blank" title="<?php _e( 'Community Forums', 'symbiostock') ?>" href="http://www.symbiostock.org/community/">www.symbiostock.org/community/</a>, <?php _e( 'Community Activity', 'symbiostock') ?></p><br />
       <?php        
        symbiostock_feed_display('http://www.symbiostock.org/community/feed.php?mode=news', 10);        
        ?>
        
        </div>
    </div>
    <div id="symbiostock_network_updates">
        <div class="symbiostock_frame_container">
            <table id="your_site_info"  class="widefat symbiostock-settings">
                <thead>
                    <tr>
                        <th colspan="2"> <?php _e( 'Your Site Info- How your site appears to others.', 'symbiostock') ?> </th>
                    </tr>
                </thead>
                <tr>
                    <th scope="row"><?php _e( 'Author Settings', 'symbiostock') ?> <br /> <?php echo sshelp('author_settings',  __( 'Author Settings', 'symbiostock' ) ); ?></th>
                    <td>
                        <strong><a title="<?php _e( 'Author Settings', 'symbiostock') ?>" href="<?php echo get_bloginfo('wpurl'); ?>/wp-admin/profile.php">&raquo; <?php _e( 'Author Settings', 'symbiostock') ?></a></strong>
                        <span class="description"> &mdash; Basic author settings used by wordpress and Symbiostock.</span> 
                        <br /> 
                        <strong><a title="<?php _e( 'Network Bonus Info', 'symbiostock') ?>" href="<?php echo get_bloginfo('wpurl'); ?>/wp-admin/profile.php#extended_network_info">&raquo; <?php _e( 'Network Bonus Info', 'symbiostock') ?></a></strong>
                        <span class="description"> &mdash; <?php _e( 'Special extended info settings used by Symbiostock on author pages and outside network referring pages.', 'symbiostock') ?></span>
                        <br /><label for="symbiostock_site_author"><?php symbiostock_list_admins(); ?> <?php _e( 'Symbiocard Author', 'symbiostock') ?></label>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e( 'Site Name', 'symbiostock') ?></th>
                    <td><input class="longfield" type="text" name="symbiostock_my_network_name"  id="symbiostock_my_network_name" value="<?php echo stripslashes ( get_option('symbiostock_my_network_name', $current_user->user_firstname . ' ' . $current_user->user_lastname . ' (' . $current_user->display_name  . ')')); ?>" /></td>
                </tr>
                
                <tr>
                    <th scope="row"><?php _e( 'Promoted Keywords (choose up to 40)', 'symbiostock') ?>  <br /> <?php echo sshelp('promoted_keywords', 'Promoted Keywords'); ?></th>
                    <td>
                    <?php
                    if(isset($no_more_keywords )){ echo $no_more_keywords; }
                    ?>
                    <textarea class="longfield" name="symbiostock_my_promoted_keywords"  id="symbiostock_my_promoted_keywords" ><?php echo trim( stripslashes ( get_option('symbiostock_my_promoted_keywords', '') ) ) ?></textarea>
                    </td>
                </tr>

                <tr>
                    <th scope="row">                    
                    Content Filtering <?php echo sshelp('rating', __('Rating', 'symbiostock')); ?> <br />
                        <select id="symbiostock_filter_level" name="symbiostock_filter_level">
                               <option <?php echo $rating_0; ?> value="0"> - </option>
                            <option <?php echo $rating_1; ?> value="1"><?php _e( 'Content Filter: GREEN', 'symbiostock') ?></option>
                            <option <?php echo $rating_2; ?> value="2"><?php _e( 'Content Filter: YELLOW', 'symbiostock') ?></option>
                            <option <?php echo $rating_3; ?> value="3"><?php _e( 'Content Filter: RED', 'symbiostock') ?></option>
                        </select>
                        <br />                       
                        <?php _e( 'Allow unrated content?', 'symbiostock') ?><br />
                        <label for="symbiostock_allow_unrated_no">
                        <input <?php echo $allow_unrated_1; ?> id="symbiostock_allow_unrated_no" type="radio" name="symbiostock_allow_unrated" value="0" />
                        <?php _e( 'No', 'symbiostock') ?></label>
                        <label for="symbiostock_allow_unrated_yes">
                        <input <?php echo $allow_unrated_2; ?> id="symbiostock_allow_unrated_yes" type="radio" name="symbiostock_allow_unrated" value="1" />
                        <?php _e( 'Yes', 'symbiostock') ?></label>  
                    </th>
                    
                    <td>
                    <?php
                    if(isset($no_more_f_keywords )){ echo $no_more_f_keywords; }
                    ?>
                    <strong><?php _e( 'Keyword Filtering', 'symbiostock') ?></strong> <?php echo sshelp('filtered_keywords', __('Filtered Keywords', 'symbiostock')); ?><br />
                    <textarea class="longfield" name="symbiostock_my_filtered_keywords"  id="symbiostock_my_filtered_keywords" ><?php echo trim( stripslashes ( get_option('symbiostock_my_filtered_keywords', '') ) ) ?></textarea>
                    </td>
                </tr>    
                
                <tr>
                    <th scope="row"><?php _e( 'Site Announcement', 'symbiostock') ?>  <br /> <?php echo sshelp('site_announcement', __('Announcement', 'symbiostock')); ?></th>
                    <td><textarea class="longfield" name="symbiostock_my_network_announcement"  id="symbiostock_my_network_announcement" ><?php echo stripslashes ( get_option('symbiostock_my_network_announcement', '')) ?></textarea>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e( 'Site Description', 'symbiostock') ?> <br /> <?php echo sshelp('site_description', __('Description', 'symbiostock')); ?></th>
                    <td><textarea class="longfield" name="symbiostock_my_network_description"  id="symbiostock_my_network_description" ><?php echo stripslashes ( get_option('symbiostock_my_network_description')) ?> </textarea></td>
                </tr>
                <tr>
                    <th scope="row"><?php _e( 'Site Avatar', 'symbiostock') ?> <br /> <?php echo sshelp('site_avatar', __('Avatar', 'symbiostock')); ?></th>
                    <td><input class="longfield" type="text" name="symbiostock_my_network_avatar"  id="symbiostock_my_network_avatar" value="<?php echo stripslashes ( get_option('symbiostock_my_network_avatar', symbiostock_32_DEFAULT)) ?>" />
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e( 'Site Logo', 'symbiostock') ?>  <br /> <?php echo sshelp('site_logo', __('Small Logo', 'symbiostock')); ?></th>
                    <td><input class="longfield" type="text" name="symbiostock_my_network_logo"  id="symbiostock_my_network_logo" value="<?php echo stripslashes ( get_option('symbiostock_my_network_logo', symbiostock_128_DEFAULT)) ?>" />
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e( 'About Page', 'symbiostock') ?>  <br /> <?php echo sshelp('about_page', __('About', 'symbiostock')); ?></th>
                    <td><input class="longfield" type="text" name="symbiostock_my_network_about_page"  id="symbiostock_my_network_about_page" value="<?php echo stripslashes( get_option('symbiostock_my_network_about_page', '')) ?>" />
                    </td>
                </tr>
                <tfoot>
                    <tr>
                        <td colspan="2"><a class="savelink" title="save_changes" href="#save_changes"><strong><?php _e( 'Save Changes', 'symbiostock') ?></strong></a></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <h2><?php _e( 'Integrate Your Site with the Symbiostock Community', 'symbiostock') ?></h2>
    <p><?php _e( 'More relevant stuff will be placed here as the need is discovered', 'symbiostock') ?></p>
    <div> <span id="save_changes" >&nbsp;</span>
        <?php } else { 
    
    $ssnet_yes = '';
    $ssnet_no = 'checked="checked"';
    ?>
        <br />
        <a target="_blank" title="<?php _e( 'Symbiostock Updates', 'symbiostock') ?>" href="http://www.symbiostock.com"> <img title="<?php _e( 'Symbiostock Updates', 'symbiostock') ?>" src="<?php echo symbiostock_LOGOSMALL; ?>" /> </a>
        <?php
    //if not using the network, just display the feed
    symbiostock_feed_display('http://www.symbiostock.org/community/feed.php?mode=news', 3);
    
 } ?>
        <p><?php _e( 'Use Symbiostock Network?', 'symbiostock') ?><br />
            <label for="symbiostock_use_network_1">
                <input id="symbiostock_use_network_1" type="radio" name="symbiostock_use_network" value="true" <?php echo $ssnet_yes; ?> />
                <?php _e( 'Activate', 'symbiostock') ?></label>
            <br />
            <label for="symbiostock_use_network_2">
                <input id="symbiostock_use_network_2" type="radio" name="symbiostock_use_network" value="false" <?php echo $ssnet_no; ?> />
                <?php _e( 'Deactivate', 'symbiostock') ?></label>
        </p>
    </div>