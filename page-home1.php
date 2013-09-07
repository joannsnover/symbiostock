<?php
/*
Template Name: HOME Page CTAs
*/
/**
 * The template for displaying home landing page.
 *
 * @package symbiostock
 * @since symbiostock 1.0
 */
get_header(); ?>
        <div class="home col-md-">
    
            <div id="primary" class="content-area col-md-12">
            
            <?php dynamic_sidebar( 'Home Page (Above Content)' ) ?>
            
                <div id="content" class="site-content home-content col-md-" role="main">
    
                    <?php while ( have_posts() ) : the_post(); ?>
                    <div class="span6">
                        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                            <header class="entry-header">
                                <h1 class="entry-title"><?php the_title(); ?></h1>
                            </header><!-- .entry-header -->
                            <div class="entry-content">
                                <?php the_content(); ?>
                               
                                <?php edit_post_link( __( 'Edit', 'symbiostock' ), '<span class="edit-link">', '</span>' ); ?>
                            </div><!-- .entry-content -->
                        </article><!-- #post-<?php the_ID(); ?> -->
                    </div>
                    <div class="col-md-6">
                    
                    <?php dynamic_sidebar( 'Home Page (Beside Content)' ) ?>
                    
                    </div>                
    
                    <?php endwhile; // end of the loop. ?>
    
                </div><!-- #content .site-content -->
            
            
          
            <?php dynamic_sidebar( 'Home Page (Below Content)' ) ?>
            
            <div class="call-to-actions col-md-">
            
            <?php dynamic_sidebar( 'Home Page Bottom Row 1/3' ) ?>
            <?php dynamic_sidebar( 'Home Page Bottom Row 2/3' ) ?>
            <?php dynamic_sidebar( 'Home Page Bottom Row 3/3' ) ?>
            
            </div>
         
            </div><!-- #primary .content-area -->
        
        </div>        
        
        
<?php get_footer(); ?>