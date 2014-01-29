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
        <div class="row">
    
            <div id="primary" class="panel-default content-area col-md-6">
                <div id="content" class="site-content" role="main">
                <?php
                //add support for YOAST SEO
                if ( function_exists('yoast_breadcrumb') ) {
                yoast_breadcrumb('<span class="text-info" id="breadcrumbs">','</span><hr />');
                } ?>                     
                
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
                
                <?php endwhile; // end of the loop. ?>

                    <?php
                    $networks = new network_manager();
                    $networks->list_all_networks();
                    ?>

                
                    <div class="clearfix"><br /></div>
                    <hr />
                    <div class="alert alert-info ">
                        
                        <span class="alignleft"><?php echo symbiostock_directory_link($text = '', false, false) ?></span>
                        
                        <h3><?php _e('Extended Network Directory', 'symbiostock') ?></h3>                   
                        
                        <p class="text-info"><strong><a title="<?php _e('Local Symbiostock author directory', 'symbiostock') ?>" href="<?php echo symbiostock_directory_link($text = '', true) ?>" ></a></strong></p>
                        <div class="clearfix"><br /></div> 
                        
                    </div>                    
                    
                </div><!-- #content .site-content -->
            </div><!-- #primary .content-area -->
                
            <div class="col-md-6">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <h2 class="panel-title">
                            <a target="_blank" title="<?php _e('Symbiostock Community Activity', 'symbiostock') ?>" href="http://www.symbiostock.org">
                            <?php _e('Symbiostock Community News and Activity', 'symbiostock') ?>
                            </a>
                            <?php 
                            echo ss_twitter_link();
                            echo ss_facebook_link();
                            ?>
                        </h2>
                    </div>
                    <div class="panel-body">
                    <?php 
                        symbiostock_community_activity();
                    ?>           
                    </div>
                </div>
            </div>
        </div>        
<?php get_footer(); ?>