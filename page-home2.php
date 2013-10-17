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
        <div class="home row">
    
            <div id="primary" class="content-area col-md-12">     
            
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
          
            
            <?php if(is_dynamic_sidebar( 'Home Page (Below Content)' )): ?>
                <div class="panel panel-default">
                <?php dynamic_sidebar( 'Home Page (Below Content)' ) ?>
                </div>
            <?php endif; ?> 
            
            <?php if(                     
                     is_active_sidebar( 'cta-1' ) ||
                     is_active_sidebar( 'cta-2' ) ||
                     is_active_sidebar( 'cta-3' )
                     ): ?>
            
            <div class="row">
                
                <div class="col-md-4">
                    <div class="panel panel-default">
                    <?php dynamic_sidebar( 'cta-1' ) ?>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="panel panel-default">
                    <?php dynamic_sidebar( 'cta-2' ) ?>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="panel panel-default">
                    <?php dynamic_sidebar( 'cta-3' ) ?>
                    </div>
                </div>
                
            </div>
            
            <?php endif; ?>
         
            </div><!-- #primary .content-area -->
        
        </div>        
        
        
<?php get_footer(); ?>