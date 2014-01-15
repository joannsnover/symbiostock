<h2><?php _e( 'Symbiostock Network Scanner', 'symbiostock') ?></h2>
<br />
<label for="symbiostock_scan_network">
    <input id="symbiostock_scan_network" type="checkbox" name="symbiostock_scan_network" />
    <?php _e( 'Scan network and update directory ', 'symbiostock') ?>- </label>
    <label for="symbiostock_info_scan">
    <input id="symbiostock_info_scan" type="checkbox" name="symbiostock_info_scan" />
    <?php _e( 'Fetch from', 'symbiostock') ?><a title="<?php _e( 'network hub of Symbiostock', 'symbiostock') ?> href="http://www.symbiostock.info">symbiostock.info</a></label>
    
<br />
<br />
<label for="symbiostock_reset_extended_network">
    <input id="symbiostock_reset_extended_network" type="checkbox" name="symbiostock_reset_extended_network" />
    <?php _e( 'Reset extended network (deletes all listings).', 'symbiostock') ?></label>
<p class="Description"><?php _e( 'This scans your network friends and adds their networks to your directory. <br />
    In the near future it will follow more leads than that, but this is where we start while the network is still small. <br />
    This could take a while...be patient and grab a soda or something.', 'symbiostock') ?></p>
<?php

if(isset($_POST['symbiostock_reset_extended_network'])){

    $reset = new network_manager();
    $reset->network_directory_cleanup( true );

}

if(isset($_POST['symbiostock_scan_network'])){

    echo '<div class="postbox">';
    echo '<div class="inside">';    
    
    if(isset($_POST['symbiostock_info_scan'])){
        
        $sitelist = symbiostock_info_sitelist();
        $spider = new network_manager();
        
        if(is_array($sitelist))
            $spider->fetch_symbiocards( $sitelist, true );

    } else {                
 
        $spider = new network_manager();
        $spider->the_spider();
    
    }
    echo '</div>';
    echo '</div>';
}

$network_total_images = 0;
?>
<table class="widefat">
    <thead>
        <tr>
            <th>#</th>
            <th><?php _e( 'Local Site Directory Listing', 'symbiostock') ?></th>
            <th>P<?php _e( 'Promoted Keywords', 'symbiostock') ?></th>
            <th><?php _e( '# Images', 'symbiostock') ?></th>
            <th><?php _e( 'Marketing Option', 'symbiostock') ?></th>
            <th><?php _e( 'Promote', 'symbiostock') ?></th>
            <th><?php _e( 'Exclude', 'symbiostock') ?></th>
            <th><?php _e( 'Delete', 'symbiostock') ?></th>
        </tr>
    </thead>
    <?php 

//check our deleted seeds and remove
if(isset($_POST['symbiostock_delete_seed'])){
    foreach($_POST['symbiostock_delete_seed'] as $delete_site){
            if(file_exists(symbiostock_NETDIR . 'seeds/' . $delete_site . '.csv')&&!empty($delete_site)){    
                unlink(symbiostock_NETDIR . 'seeds/' . $delete_site . '.csv');
            } 
        }
}



    
//set up our promoted sites

if(isset($_POST['save_form_info'])){
    
    if(isset($_POST['symbiostock_promote_site'])){
        
        $promoted = array();
        foreach($_POST['symbiostock_promote_site'] as $promoted_site){    
            array_push($promoted, $promoted_site);        
        }    
        update_option('symbiostock_promoted_sites', $promoted);    
    } else {
        update_option('symbiostock_promoted_sites', array());    
        }
    
    //set up our exclusions
        
    if(isset($_POST['symbiostock_exclude_site'])){
        
        $exclusions = array();
        foreach($_POST['symbiostock_exclude_site'] as $exclude_site){
            if(!empty($_POST['symbiostock_exclude_site'])){            
                array_push($exclusions, $exclude_site);    
            }
        }    
        update_option('symbiostock_exclude_sites', $exclusions);    
    } else {
        update_option('symbiostock_exclude_sites', array());
        }
}
$promoted = get_option('symbiostock_promoted_sites', array());
$excluded = get_option('symbiostock_exclude_sites', array());

//list directories
$directory = new network_manager();
$list = $directory->get_connected_networks_by_symbiocard( true );
$directory->get_seeds_by_keyword();


$count = 1;

ksort($list, SORT_REGULAR);

foreach($list as $listing){
    $key = symbiostock_website_to_key($listing['symbiostock_site']);    

    ?>
    <tr>
        <td><?php echo $count; ?></td>
        <td><a title="<?php echo $listing['symbiostock_display_name'] ?>" href="<?php echo $listing['symbiostock_author_page'] ?>"><?php echo $listing['symbiostock_author_page'] ?></a></td>
        <td><?php
        if(isset($listing['symbiostock_my_promoted_keywords']) && !empty($listing['symbiostock_my_promoted_keywords'])){
            
            echo $listing['symbiostock_my_promoted_keywords'];
            
            }
        
        ?></td>
        <td><?php echo $listing['symbiostock_num_images'];
        $network_total_images = $network_total_images+trim($listing['symbiostock_num_images']);
        ?></td>
        
        <td>
        <?php
        if(!isset($listing['use_symbiostock_marketer']) || $listing['use_symbiostock_marketer'] == 0 || empty($listing['use_symbiostock_marketer']) ){            
            echo __('off', 'symbiostock');            
            } elseif ($listing['use_symbiostock_marketer'] == 1){
            echo __('on', 'symbiostock');    
                }
        ?>
        </td>
        
        <td><input <?php if(in_array($key, $promoted)){echo 'checked="checked"';} ?> type="checkbox" name="symbiostock_promote_site[]" value="<?php echo $key ?>" /></td>
        <td><input <?php if(in_array($key, $excluded)){echo 'checked="checked"';} ?> type="checkbox" name="symbiostock_exclude_site[]" value="<?php echo $key ?>" /></td>
        <td><input type="checkbox" name="symbiostock_delete_seed[]" value="<?php echo $key ?>" /></td>
    </tr>
    <?php    
    $count++;
}

?>
    <tfoot>
        <tr>
            <td>#</td>
            <td><em><a title="<?php _e( 'See extended directory...', 'symbiostock') ?>" href="<?php echo symbiostock_directory_link(__('See extended directory...', 'symbiostock'), true, true); ?>">&mdash; <?php _e( 'See extended directory...', 'symbiostock') ?></a></em></td>
            <td>.</td>
            <td><?php echo $network_total_images; ?></td>
            <td>.</td>
            <td>.</td>
            <td>.</td>
            <td>.</td>
        </tr>
    </tfoot>
</table>
<?php
$seeds = get_option('symbiostock_enqueued_seeds', array());

if(!empty($seeds)){
    
    ?>

    <ul>
    <li><p><?php _e( 'Enqueued Seeds ', 'symbiostock') ?> <span class="description"><?php _e( 'Symbiocards discovered through keyword traffic activity, and enqueued for directory. Will be added or updated. ', 'symbiostock') ?></span></p></li>
    
        <?php
        foreach($seeds as $seed){        
            echo '<li><a title="'.$seed.'" href="'.$seed.'">'.$seed.'</a></li>';
            }
       
        }
        ?>
    </ul>