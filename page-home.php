
<?php
/*
Template Name: Home Page
*/
/**
 * The template for displaying home landing page.
 *
 * @package symbiostock
 * @since symbiostock 1.0
 */
get_header(); ?>
        <div class="home row">
    
            <div id="primary" class="content-area col-md-12">
            
                <div id="content" class="site-content" role="main">
    
                    <?php while ( have_posts() ) : the_post(); ?>
    
                        <?php get_template_part( 'content', 'page' ); ?>
    
                        <?php comments_template( '', true ); ?>
    
                    <?php endwhile; // end of the loop. ?>
    
                </div><!-- #content .site-content -->
            
            
          
            <?php if(is_dynamic_sidebar( 'Featured Posts (Below Content)' )): ?>
                <div class="panel panel-default">
                <?php dynamic_sidebar( 'Featured Posts (Below Content)' ) ?>
                </div>
            <?php endif; ?> 
         
            </div><!-- #primary .content-area -->
        
        </div>        
        
        
<?php get_footer(); ?>