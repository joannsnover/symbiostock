<?php
/*
Template Name: Page - Sidebar Right
*/
/**
 * The template for displaying page with sidebar right.
 *
 * @package symbiostock
 * @since symbiostock 1.0
 */
get_header(); ?>
        <div class="home row-fluid">
    
            <div id="primary" class="content-area span8">
            
                <div id="content" class="site-content" role="main">
                <?php
                //add support for YOAST SEO
                if ( function_exists('yoast_breadcrumb') ) {
                yoast_breadcrumb('<span class="text-info" id="breadcrumbs">','</span><hr />');
                } ?>         
                    <?php while ( have_posts() ) : the_post(); ?>
    
                        <?php get_template_part( 'content', 'page' ); ?>
    
                        <?php comments_template( '', true ); ?>
    
                    <?php endwhile; // end of the loop. ?>
    
                </div><!-- #content .site-content -->
            
            
          
            <?php dynamic_sidebar( 'Featured Posts (Below Content)' ) ?>
         
            </div><!-- #primary .content-area -->
            
            <div class="span4">            
                <?php get_sidebar(); ?>
            </div>
        
        </div>        
        
        
<?php get_footer(); ?>