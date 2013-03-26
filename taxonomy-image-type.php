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
			$title = get_query_var('term');
			if($title){
				$title = ucwords(str_replace('-', ' ', get_query_var('term')));
				
				} else {
					
					$title=' - - - ';
					
					}
			
			echo '<h1 class="results_for">Images in category: <strong>"' . $title  . '"</strong></h1>';
			
			$local_results->display_results(false);
			
			?>                    
			</div><!-- #content .site-content -->
		</section><!-- #primary .content-area -->
	
	</div>
    
<?php 
include_once('modal-search.php');
?>   
    
<?php get_footer(); ?>