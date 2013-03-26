<?php
/*
Template Name: Login Page
*/
/**
 * The template for displaying home landing page.
 *
 * @package symbiostock
 * @since symbiostock 1.0
 */
get_header(); ?>
		<div class="row-fluid">
    
            <div id="primary" class="content-area span12">
                <div id="content" class="site-content" role="main">
    
                    <?php while ( have_posts() ) : the_post(); ?>
					<?php include_once('register_symbiostock.php'); ?>                  
                        
                    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                        <header class="entry-header">
                          
                        </header><!-- .entry-header -->
                    
                        <div class="entry-content">
                            <?php the_content(); ?>
                            <?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'symbiostock' ), 'after' => '</div>' ) ); ?>
                            <?php edit_post_link( __( 'Edit', 'symbiostock' ), '<span class="edit-link">', '</span>' ); ?>
                        </div><!-- .entry-content -->
                    </article><!-- #post-<?php the_ID(); ?> -->
                    
                    <?php endwhile; // end of the loop. ?>
    
                </div><!-- #content .site-content -->
            </div><!-- #primary .content-area -->
        
		</div>        
<?php get_footer(); ?>