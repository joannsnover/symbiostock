<?php
/**
 * The template for displaying image category pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package symbiostock
 * @since symbiostock 1.0
 */
$sscount = new results_counter();
//now open up a local search
$local_results = new network_manager();
$local_results->local_search();
get_header(); ?>
	<div class="row-fluid">
		<section id="primary" class="content-area">
			<div id="content" class="site-content span12" role="main">
			
			<?php
			//add support for YOAST SEO
			 if ( function_exists('yoast_breadcrumb') ) {
            yoast_breadcrumb('<span class="text-info" id="breadcrumbs">','</span>');
            } ?>
	
            
			<?php
						
			$term = get_term_by( 'slug', get_query_var( 'image-type' ),  'image-type' );		
			$title = $term->name;
					
			
			echo '<h1 class="results_for">Images in category: <strong>"' . ucwords($title)  . '"</strong>  '.symbiostock_feed('rss_url','icon', 'image-type').'</h1>';
			
			$local_results->display_results(false);
			
			?>                    
			</div><!-- #content .site-content -->
		</section><!-- #primary .content-area -->
	
	</div>
    <div class="clearfix">&nbsp;</div>
    
<?php 
get_template_part('modal-search');
?>   
    
<?php get_footer(); ?>