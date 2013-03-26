<?php
/**
 * The template for displaying 404 pages (Not Found).
 *
 * @package symbiostock
 * @since symbiostock 1.0
 */
//we must determine if this search is for a human or remote site
//this MUST be placed before we instantiate our search class
//instantiate counter class for search result ids
$sscount = new results_counter();
$symbiostock_network_info = get_query_var('symbiostock_network_info');
$symbiostock_network_search  = get_query_var('symbiostock_network_search');
//now open up a local search
$local_results = new network_manager();
$local_results->local_search();
//if for remote site, display xml
if(($symbiostock_network_search)==true || ($symbiostock_network_info)==true){
	
	$local_results->display_xml_results();
	
	} else {  
	
	$is_image_taxonomy = get_query_var( 'image-tags' );
	
	get_header();
	
	if ( $is_image_taxonomy ) {
		
	   //if this is a tag
		get_template_part( 'content', 'noresults' );
		
	} else {
		
		get_template_part( 'content', '404' );
		
	}
	
	get_footer();
}
?>