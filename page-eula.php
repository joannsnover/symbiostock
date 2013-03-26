<?php
/*
Template Name: EULA
*/
/**
 * The template for displaying End User License Agreement
 *
 * @package symbiostock
 * @since symbiostock 1.0
 */
get_header(); ?>
		<div class="home row-fluid">
    
            <div id="primary" class="content-area span12">
            
                <div id="content" class="site-content EULA" role="main">
    
                    <?php while ( have_posts() ) : the_post(); ?>
    
                        <?php get_template_part( 'content', 'page' ); ?>
    
                        <?php comments_template( '', true ); ?>
    
                    <?php endwhile; // end of the loop. ?>
    
                </div><!-- #content .site-content -->
            
            
          
            <?php dynamic_sidebar( 'Featured Posts (Below Content)' ) ?>
         
    		</div><!-- #primary .content-area -->
		
        </div>        
        
    	
<?php get_footer(); ?>