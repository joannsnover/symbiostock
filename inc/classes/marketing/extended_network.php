<h2>Symbiostock Network Scanner</h2>

<br />
<label for="symbiostock_scan_network"><input id="symbiostock_scan_network" type="checkbox" name="symbiostock_scan_network" /> Scan network and update directory.</label> 
<br /><br />
<label for="symbiostock_reset_extended_network"><input id="symbiostock_reset_extended_network" type="checkbox" name="symbiostock_reset_extended_network" /> Reset extended network (deletes all listings).</label> 

<p class="Description">This scans your network friends and adds their networks to your directory. 
<br />In the near future it will follow more leads than that, but this is where we start while the network is still small.
<br />This could take a while...be patient and grab a soda or something.</p>
<?php

if(isset($_POST['symbiostock_reset_extended_network'])){

	$reset = new network_manager();
	$reset->network_directory_cleanup( true );


}

if(isset($_POST['symbiostock_scan_network'])){
		
	echo '<div class="postbox">';
	echo '<div class="inside">';
	
	$spider = new network_manager();
	$spider->the_spider();
	
	echo '</div>';
	echo '</div>';
}


?><table class="widefat">
<input type="hidden" name="symbiostock_exclude_site" />
<input type="hidden" name="symbiostock_promote_site" />
<input type="hidden" name="symbiostock_delete_seed" />
<thead>
<tr><th>#</th><th>Local Site Directory Listing</th><th>#images</th><th>Promote</th><th>Exclude</th><th>Delete</th></tr>
</thead>
<?php 

//check our deleted seeds and remove
if(isset($_POST['symbiostock_delete_seed']) && !empty($_POST['symbiostock_delete_seed'])){
	foreach($_POST['symbiostock_delete_seed'] as $delete_site){
			
			if(file_exists(symbiostock_NETDIR . 'seeds/' . $delete_site . '.csv')&&!empty($delete_site)){	
				unlink(symbiostock_NETDIR . 'seeds/' . $delete_site . '.csv');
			} 
		}
}

//set up our promoted sites
$promoted = array();

if(isset($_POST['symbiostock_promote_site'])){
	foreach($_POST['symbiostock_promote_site'] as $promoted_site){	
		if(!empty($_POST['symbiostock_promote_site'])){	
			array_push($promoted, $promoted_site);
		}
		}	
	update_option('symbiostock_promoted_sites', $promoted);
}
$promoted = get_option('symbiostock_promoted_sites', array());

//set up our exclusions
$exclusions = array();

if(isset($_POST['symbiostock_exclude_site'])){
	foreach($_POST['symbiostock_exclude_site'] as $exclude_site){
		if(!empty($_POST['symbiostock_exclude_site'])){			
			array_push($exclusions, $exclude_site);	
		}
		}	
	update_option('symbiostock_exclude_sites', $exclusions);
}

//list directories
$directory = new network_manager();
$list = $directory->get_connected_networks_by_symbiocard( true );

$excluded = get_option('symbiostock_exclude_sites', array());

$count = 1;
foreach($list as $listing){
	$key = symbiostock_website_to_key($listing['symbiostock_site']);	

	?>
    <tr>
    	<td><?php echo $count; ?></td>
       
        <td>        	
       		<a title="<?php echo $listing['symbiostock_display_name'] ?>" href="<?php echo $listing['symbiostock_author_page'] ?>"><?php echo $listing['symbiostock_author_page'] ?></a>
        </td> 
        <td>
        <?php echo $listing['symbiostock_num_images'] ?>
        </td>
        <td>
        	<input <?php if(in_array($key, $promoted)){echo 'checked="checked"';} ?> type="checkbox" name="symbiostock_promote_site[]" value="<?php echo $key ?>" />
        	
        </td>              
        <td>
        	<input <?php if(in_array($key, $excluded)){echo 'checked="checked"';} ?> type="checkbox" name="symbiostock_exclude_site[]" value="<?php echo $key ?>" />
        </td>
        <td>
        	<input type="checkbox" name="symbiostock_delete_seed[]" value="<?php echo $key ?>" />
        </td>
    </tr>
    <?php	
	$count++;
}

?>
<tfoot>
<tr><td colspan="5"></td></tr>
</tfoot>
</table>