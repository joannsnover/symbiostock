<h2>Symbiostock Network Scanner</h2>
<br />
<label for="symbiostock_scan_network"><input id="symbiostock_scan_network" type="checkbox" name="symbiostock_scan_network" /> Scan network and update directory.</label> 
<br /><br />
<label for="symbiostock_reset_extended_network"><input id="symbiostock_reset_extended_network" type="checkbox" name="symbiostock_reset_extended_network" /> Reset extended network (deletes all listings).</label> 

<p cless="Description">This scans your network friends and adds their networks to your directory. 
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
?>