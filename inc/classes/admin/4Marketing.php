<?php
$networks = new network_manager();
$test = $networks->get_connected_networks( );

include_once(symbiostock_CLASSROOT . 'marketing/extended_network.php');

?>
<span class="description">Use these for marketing sites to aggregate your info.</span>
<ul>
<li>
<?php
	
	$url = get_bloginfo('url');

if(file_exists(ABSPATH . '/symbiostock_keyword_info.csv')){
	echo 'Keyword list:  <a title="Keyword list..." href="' . $url . '/symbiostock_keyword_info.csv">' . $url . '/symbiostock_keyword_info.csv</a>';
	}
?>
</li>
<li>
<?php
if(file_exists(ABSPATH . '/symbiostock_image_info.csv')){
	echo 'Image list: <a title="Image list..." href="' . $url . '/symbiostock_image_info.csv">' . $url . '/symbiostock_image_info.csv</a>';
	}
?>
</li>
</ul>

<?php
//include_once(symbiostock_CLASSROOT . 'marketing/network_hub.php');
include_once(symbiostock_CLASSROOT . 'marketing/marketer.php');
?>