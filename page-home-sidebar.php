<?php
/*
Template Name: Home Page - Sidebar
*/
/**
 * The template for displaying home landing page with sidebar.
 *
 * @package symbiostock
 * @since symbiostock 1.0
 */
get_header(); ?>
		<div class="home row-fluid">
    
            <div id="primary" class="content-area span8">
            
                <div id="content" class="site-content" role="main">
    
                    <?php while ( have_posts() ) : the_post(); ?>
                    
                    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                        <header class="entry-header">

                        </header><!-- .entry-header -->
                        <div class="entry-content">
                            <?php the_content(); ?>
                            <?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'symbiostock' ), 'after' => '</div>' ) ); ?>
                            <?php edit_post_link( __( 'Edit', 'symbiostock' ), '<span class="edit-link">', '</span>' ); ?>
                        </div><!-- .entry-content -->
                    </article><!-- #post-<?php the_ID(); ?> -->

    
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