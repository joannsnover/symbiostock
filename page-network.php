<?php
/*
Template Name: Network - Associated
*/

/**
 * The template for displaying network info and statistics.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package symbiostock
 * @since symbiostock 1.0
 */
get_header(); ?>
        <div class=row>
    
            <div id="primary" class="content-area col-md-6">
                <div id="content" class="site-content" role="main">
                <?php
                //add support for YOAST SEO
                if ( function_exists('yoast_breadcrumb') ) {
                yoast_breadcrumb('<span class="text-info" id="breadcrumbs">','</span><hr />');
                } ?>                     
                
                <?php
                $networks = new network_manager();
                $networks->list_all_networks();
                
                ?>
                    <div class="clearfix"><br /></div>
                    <hr />
                    <div class="alert alert-info ">
                        
                        <span class="alignleft"><?php echo symbiostock_directory_link($text = '', false, false) ?></span>
                        
                        <h3>Extended Network Directory</h3>                   
                        
                        <p class="text-info">See the <strong><a title="Local Symbiostock author directory" href="<?php echo symbiostock_directory_link($text = '', true) ?>" >directory</a> </strong> page for more sites / authors.</p>
                        <div class="clearfix"><br /></div>
                    </div>
                    
                </div><!-- #content .site-content -->
            </div><!-- #primary .content-area -->
                
            <div class="col-md-6">
            <?php while ( have_posts() ) : the_post(); ?>
            
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <header class="entry-header">
                    <h1 class="entry-title"><?php the_title(); ?></h1>
                </header><!-- .entry-header -->
                <div class="entry-content">
                    <?php the_content(); ?>
                    <?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'symbiostock' ), 'after' => '</div>' ) ); ?>
                    <?php edit_post_link( __( 'Edit', 'symbiostock' ), '<span class="edit-link">', '</span>' ); ?>
                </div><!-- .entry-content -->
            </article><!-- #post-<?php the_ID(); ?> -->
            
                <?php comments_template( '', true ); ?>
            
            <?php endwhile; // end of the loop. ?>
            </div>
        </div>        
<?php get_footer(); ?>