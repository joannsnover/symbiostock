<?php
/**
 * The Template for displaying all single posts.
 *
 * @package symbiostock
 * @since symbiostock 1.0
 */
get_header(); 
?>
		
        <div class="row-fluid">
        
            <div id="primary" class="content-area span8">
                <div id="content" class="site-content" role="main">
                <?php while ( have_posts() ) : the_post(); ?>
    
                    <?php symbiostock_content_nav( 'nav-above' ); ?>
    
                    <?php get_template_part( 'content', 'single' ); ?>
    
                    <?php symbiostock_content_nav( 'nav-below' ); ?>
    
                    <?php
                        // If comments are open or we have at least one comment, load up the comment template
                        if ( comments_open() || '0' != get_comments_number() )
                            comments_template( '', true );
                    ?>
    
                <?php endwhile; // end of the loop. ?>
    
                </div><!-- #content .site-content -->
            </div><!-- #primary .content-area -->
			
            <div class="span4">
            
<?php get_sidebar(); ?>
			</div>
		</div>
        
<?php get_footer(); ?>