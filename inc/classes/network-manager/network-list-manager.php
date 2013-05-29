<?php 

if(isset($_POST['symbiostock_cache_days']) && !empty($_POST['symbiostock_cache_days']) && is_numeric($_POST['symbiostock_cache_days']) ){	
	update_option('symbiostock_cache_days', trim($_POST['symbiostock_cache_days']));	
	} 
	
$update_symbiostock_networks = new network_manager;
$update_symbiostock_networks->update_connected_networks();
if(isset($_FILES[ 'symbiostock_csv_file' ]) && !empty($_FILES[ 'symbiostock_csv_file' ])){
	
	$allowedExtension = array( "csv" );
	
	foreach ( $_FILES as $file ) {
		
		if ( $file[ 'tmp_name' ] > '' ) {
			
			if ( !in_array( end( explode( ".", strtolower( $file[ 'name' ] ) ) ), $allowedExtension ) ) {
				
				die( $file[ 'name' ] . ' is an invalid file type!<br/>' );
				
			}
			
		}
		
	}
	$target_path = symbiostock_NETDIR;
	
	$target_path = $target_path . basename( $_FILES[ 'symbiostock_csv_file' ][ 'name' ] );
	if ( move_uploaded_file( $_FILES[ 'symbiostock_csv_file' ][ 'tmp_name' ], $target_path) ) {
		
		echo "The file " . basename( $_FILES[ 'symbiostock_csv_file' ][ 'name' ] ) . " has been uploaded";
		
		$make_network_associate = new network_manager();
		
		$make_network_associate->process_network_file($target_path);
		
	} else {
		
		echo "There was an error trying to upload the file. Check the file and file location before trying again!";
		
	}
	
}

if(isset($_POST['update_symbiocards'])){	
	update_symbiocards();
	}
	
?>

<div class="symbiostock_network">
    <table width="20%" class="widefat wp-list-table" cellspacing="0" summary="">
        <thead>
            <tr class="network_links">
                <th><span id="icon-link-manager" class="icon32"> &nbsp; </span>Linked Sites <br />
                    <span class="little_desc">Network Associates</span> <?php echo sshelp('network_associates', 'Networking'); ?></th>
                <th><span id="icon-edit-pages" class="icon32"> &nbsp; </span>Name <br />
                    <span class="little_desc">(Small desc. or label)</span></th>
            </tr>
        </thead>
        <?php
$network_count = 0;
$blank_count = 0;

$network = new network_manager();
$symbiocards = $network->get_connected_networks_by_symbiocard( );
$csv_image = symbiostock_IMGDIR . '/filetypes/text-csv-32.png'; 				
$action_img = symbiostock_IMGDIR . '/actions/';

$last_symbiocard_update = get_option('symbiocards_last_update', 'Never');

if(empty($symbiocards)){
	?>
	<tr class="network-data">
		<td><label for="<?php echo 'symbiostock_network_site_0' ?>">
				<input class="symbiostock_network_site longfield" id="<?php echo 'symbiostock_network_site_0'; ?>" value="<?php echo $symbiostock_site; ?>" type="text" name="<?php echo 'symbiostock_network_site_' . $network_count; ?>" />
			</label></td>
		<td><label for="<?php echo 'symbiostock_network_description_0'; ?>">
				<input id="<?php echo 'symbiostock_network_description_0'; ?>" value="" type="text" name="<?php echo 'symbiostock_network_description_0'; ?>" />
			</label></td>
	</tr>
	<?php
			
}

foreach($symbiocards as $symbiocard){	
	
	extract($symbiocard);
	
	$alt = ( $network_count % 2 ) ? '' : 'alternate';
	
		if( $blank_count < 1 || !empty($symbiocard)){
					
		$website_key = symbiostock_website_to_key($symbiostock_address);		

				?>
        <tr class="network-data <?php echo $alt; ?>">
            <td><label for="<?php echo 'symbiostock_network_site_' . $network_count; ?>"> <a class="get_csv" title="Get symbiocard CSV file..." href="<?php echo $symbiostock_site . '/symbiocard.csv'; ?>"><img alt="Network Logo" src="<?php echo $symbiostock_my_network_avatar ?>" /><img alt="Get network associate CSV file..." src="<?php echo $csv_image ?>" /></a>
                    <input class="symbiostock_network_site longfield" id="<?php echo 'symbiostock_network_site_' . $network_count; ?>" value="<?php echo $symbiostock_site; ?>" type="text" name="<?php echo 'symbiostock_network_site_' . $network_count; ?>" />
                </label>
                <br />
                <span id="<?php echo 'symbiostock_info_' . $network_count; ?>" class="network_member_info description centered">                	
                    <a target="_blank" title="Author Page" href="<?php echo $symbiostock_author_page ?>">Author Page</a> &bull;                     
                    <a target="_blank" title="Portfolio" href="<?php echo $symbiostock_portfolio ?>">Portfolio ( <?php echo $symbiostock_num_images; ?> )</a> &bull; 
                    <a target="_blank" title="RSS" href="<?php echo $symbiostock_rss ?>">Image Updates</a> &bull;                    
                    <a target="_blank" title="Contact" href="<?php echo $symbiostock_contact_page ?>">Contact</a> &bull;
                    Version <?php echo $symbiostock_version ?>
                </span><br /><br />
            </td>
            
            <td>
            <div class="symbiostock_remove_network">
                <a id="remove" href="#"><img alt="REMOVE" src="<?php echo $action_img . 'x.png'; ?>" /></a>
            </div>   
            <div id="sort_network_<?php echo $network_count; ?>"  class="symbiostock_action_arrows">
                <a href="#" class="network_up"><img alt="UP" src="<?php echo $action_img . 'up.png'; ?>" /></a>
                <a href="#" class="network_down"><img alt="DOWN" src="<?php echo $action_img . 'down.png'; ?>" /></a>
            </div>
         
            <label for="<?php echo 'symbiostock_network_description_' . $network_count; ?>">
                    <input id="<?php echo 'symbiostock_network_description_' . $network_count; ?>" value="<?php echo $symbiostock_display_name; ?>" type="text" name="<?php echo 'symbiostock_network_description_' . $network_count; ?>" />
            </label>
            
            </td>
        </tr>        
       
        <?php
				
		$blank_count++;		
		
		}
		
		$network_count++;
}
?>
        <tfoot>
            <tr class="get_csv">
                <td><?php
				
				if(file_exists(ABSPATH . '/symbiocard.csv')){
					$csv_image = symbiostock_IMGDIR . '/filetypes/text-csv-32.png'; 
					
					echo '<a href="'.home_url().'/symbiocard.csv"><img alt="Your CSV" src="'.$csv_image.'" />
					</a>';
					
					}
				
				?>
                    <label for="symbiostock_my_network_site">
                        <input onClick="this.select();" class="longfield" id="symbiostock_my_network_site" type="text" value="<?php bloginfo( 'wpurl' );  ?>" readonly="readonly" />
                        <em> - My Site</em></label>
                    &nbsp;
                    <input type="hidden" name="edit_values" value="" /></td>
                <td><a id="add_row" href="#"><img alt="ADD ROW" src="<?php echo $action_img . 'more.png'; ?>" /></a></td>
            </tr>
            <tr>
                <td colspan="2"><input type="file" id="symbiostock_csv_file" name="symbiostock_csv_file" />
                    <label for="html-upload">
                        <input id="html-upload" class="button" type="submit" value="Upload" name="html-upload" />
                        <em> - Add by <strong>.csv</strong> file...</em> <?php echo sshelp('csv_file', '.csv files explained.'); ?></label>
                </td>
            </tr>
            <tr>
            <td>
             <label class="description" for="update_symbiocards"><input id="update_symbiocards" name="update_symbiocards" type="checkbox" /> <strong>Update Symbiocards</strong> <em>Last update: <?php echo $last_symbiocard_update ?></em></label>         
            </td>
            <td>
            
            <label class="description" for="symbiostock_cache_days">
            <strong>
            	<input style="text-align: center;" size="3" type="text" name="symbiostock_cache_days" value="<?php echo get_option('symbiostock_cache_days', 14) ?>" />
            </strong>
            <em> days cache network search</em> 
            </label>                
            </td>
            </tr>
        </tfoot>
    </table>
   
</div>