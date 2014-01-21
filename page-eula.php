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
        <div class="home row type-license">
    
            <div id="primary" class="content-area col-md-12">
            
                <div id="content" class="site-content EULA" role="main">
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
         
            </div><!-- #primary .content-area -->
        
        </div>        
        
        
<?php get_footer(); ?>