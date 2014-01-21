<?php
/*
Template Name: Page - Full Width
*/
/**
 * The template for displaying page full width.
 *
 * @package symbiostock
 * @since symbiostock 1.0
 */
get_header(); ?>
        <div class="home symbiostock_license row">
        
              <div id="primary" class="content-area col-md-12">  
               
                <div id="content" class="site-content" role="main">
                <?php
                //add support for YOAST SEO
                if ( function_exists('yoast_breadcrumb') ) {
                yoast_breadcrumb('<span class="text-info" id="breadcrumbs">','</span><hr />');
                } ?>                     
    
                    <?php while ( have_posts() ) : the_post(); ?>
    
                        <article class="panel" id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                            <header class="entry-header panel-heading">
                                <h1 class="entry-title panel-title"><?php the_title(); ?></h1>
                            </header><!-- .entry-header -->
                            <div class="entry-content panel-body">
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
        </div>        
        
        
<?php get_footer(); ?>