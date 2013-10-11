<?php
/**
 * The template for displaying Image Results pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
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
//if for human, display page
get_header(); ?>
    <div class=row>
        <section id="primary" class="content-area">
            <div id="content" class="site-content search_page search-results col-md-12" role="main">
            
            <?php

            
            echo '<h1 class="results_for">Results for "' . ucwords(urldecode( get_query_var('image-tags'))) . '"  '.symbiostock_feed('rss_url','icon', 'image-tags').'</h1>';
            
            $local_results->display_results(false);
                
            //now do network search
            $network_results = new network_manager();
            
            $network_results->network_search_all_similar();
            
            $use_network = get_option('symbiostock_use_network');
            ?><div class="clearfix"><br /></div><?php 
            if($use_network == 'true'){
                get_template_part('see-directory');
            }
            ?>
            </div><!-- #content .site-content -->
        </section><!-- #primary .content-area -->
        
    </div>
    
    <div class="clearfix">&nbsp;</div>
<?php 
get_template_part('modal-search');
?>   
   
<?php get_footer();
}
    
?>