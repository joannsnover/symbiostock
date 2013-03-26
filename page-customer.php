<?php
/*
Template Name: Customer
*/
/**
 * This page encompasses the cart and general customer area
 *
 * @package symbiostock
 * @since symbiostock 1.0
 */
get_header(); ?>
		<div class="home row-fluid">
    
            <div id="primary" class="content-area span12">
            
                <div id="content" class="site-content" role="main">
    
                    <?php while ( have_posts() ) : the_post(); ?>
    
                        <?php get_template_part( 'content-customer', 'page' ); ?>
                    
    
                    <?php endwhile; // end of the loop. ?>
    
                </div><!-- #content .site-content -->
           
                   
    		</div><!-- #primary .content-area -->
		
        </div>        
        
    	
<?php get_footer(); ?>