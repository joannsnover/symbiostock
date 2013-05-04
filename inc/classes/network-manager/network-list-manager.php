<?php 
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
?>

<div class="symbiostock_network">
    <table width="20%" class="widefat wp-list-table" cellspacing="0" summary="">
        <thead>
            <tr class="network_links">
                <th><span id="icon-link-manager" class="icon32"> &nbsp; </span>Linked Sites<br /><span class="little_desc">Network Associates</span> <?php echo sshelp('network_associates', 'Networking'); ?></th>
                <th><span id="icon-edit-pages" class="icon32"> &nbsp; </span>Descriptions <br /><span class="little_desc">FYI</span></th>
            </tr>
        </thead>
        <?php
$network_count = 0;
$blank_count = 0;
while($network_count <= 9){
	
	$symbiostock_network_address =  get_option('symbiostock_network_site_' . $network_count);
	$symbiostock_network_description =  get_option('symbiostock_network_description_' . $network_count);
	
	$alt = ( $network_count % 2 ) ? '' : 'alternate';
	
		if($symbiostock_network_address != false || !empty($symbiostock_network_address) || $blank_count < 1){
			
			?>	
            	<?php 
				
				$path_to_csv = get_option('symbiostock_network_site_' . $network_count ) . 'symseed.csv';
				$csv_image = symbiostock_IMGDIR . '/filetypes/text-csv-32.png'; 
				
				?>
                                
				<tr class="network-data <?php echo $alt; ?>">
					<td><label for="<?php echo 'symbiostock_network_site_' . $network_count; ?>">
                            <a class="get_csv" title="Get symseed CSV file..." href="<?php echo $path_to_csv; ?>"><img alt="Get network associate CSV file..." src="<?php echo $csv_image ?>" /></a>
							<input class="symbiostock_network_site longfield" id="<?php echo 'symbiostock_network_site_' . $network_count; ?>" value="<?php echo $symbiostock_network_address['address']; ?>" type="text" name="<?php echo 'symbiostock_network_site_' . $network_count; ?>" />
						</label></td>
					<td><label for="<?php echo 'symbiostock_network_description_' . $network_count; ?>">
							<input id="<?php echo 'symbiostock_network_description_' . $network_count; ?>" value="<?php echo $symbiostock_network_address['description']; ?>" type="text" name="<?php echo 'symbiostock_network_description_' . $network_count; ?>" />
							<a id="remove" href="#"> - </a></label></td>
				</tr>
				<?php
				
		$blank_count++;		
		
		}
		
		$network_count++;
}
?>
        <tfoot>
            <tr class="get_csv">
                <td>
                <?php
				
				if(file_exists(ABSPATH . '/symseed.csv')){
					$csv_image = symbiostock_IMGDIR . '/filetypes/text-csv-32.png'; 
					
					echo '<a href="'.home_url().'/symseed.csv"><img alt="Your CSV" src="'.$csv_image.'" />
					</a>';
					
					}
				
				?>
                <label for="symbiostock_my_network_site"><input onClick="this.select();" class="longfield" id="symbiostock_my_network_site" type="text" value="<?php bloginfo( 'wpurl' );  ?>" readonly="readonly" /> <em> - My Site</em></label>
                &nbsp;<input type="hidden" name="edit_values" value="" />
                </td>
                <td><a id="add_row" href="#">+</a></td>
            </tr>
            <tr>
                <td>                	
                	<input type="file" id="symbiostock_csv_file" name="symbiostock_csv_file" />
                    <label for="html-upload"><input id="html-upload" class="button" type="submit" value="Upload" name="html-upload" /><em> - Add by <strong>.csv</strong> file...</em> <?php echo sshelp('csv_file', '.csv files explained.'); ?></label>
                </td>
            </tr>
        </tfoot>
    </table>   
</div>
