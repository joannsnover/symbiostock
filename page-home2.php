<?php
/*
Template Name: HOME Page Simple
*/
/**
 * The template for displaying home landing page.
 *
 * @package symbiostock
 * @since symbiostock 1.0
 */
get_header(); ?>
		<div class="home row-fluid">
    
            <div id="primary" class="content-area span12">     
            
                <div id="content" class="site-content home-content" role="main">
    
                    <?php while ( have_posts() ) : the_post(); ?>
    				
                        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                            <div class="entry-content">
                                <?php the_content(); ?>
                                <?php edit_post_link( __( 'Edit', 'symbiostock' ), '<span class="edit-link">', '</span>' ); ?>
                            </div><!-- .entry-content -->
                        </article><!-- #post-<?php the_ID(); ?> -->
                         
    
                    <?php endwhile; // end of the loop. ?>
    
                </div><!-- #content .site-content -->            
          
            <?php dynamic_sidebar( 'Home Page (Below Content)' ) ?>
            
            <div class="call-to-actions row-fluid">
            
            <?php dynamic_sidebar( 'Home Page Bottom Row 1/3' ) ?>
            <?php dynamic_sidebar( 'Home Page Bottom Row 2/3' ) ?>
            <?php dynamic_sidebar( 'Home Page Bottom Row 3/3' ) ?>
            
            </div>
         
    		</div><!-- #primary .content-area -->
		
        </div>        
        
    	
<?php get_footer(); ?>