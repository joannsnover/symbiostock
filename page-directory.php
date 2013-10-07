<?php
/*
Template Name: Network - Directory
*/

/**
 * The template for displaying directory info and statistics. 
 * Similar to the network page, but fare more sites referenced, and not controlled by webmaster.
 *
 * @package symbiostock
 * @since symbiostock 1.0
 */
get_header(); ?>

<?php
    //add support for YOAST SEO
    if ( function_exists('yoast_breadcrumb') ) {
    yoast_breadcrumb('<span class="text-info" id="breadcrumbs">','</span><hr />');
} ?>

<div id="primary" class="content-area span">
    <div class="col-md-6">
        <?php while ( have_posts() ) : the_post(); ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <header class="entry-header">
                <h1 class="entry-title">
                    <?php the_title(); ?>
                </h1>
            </header>
            <!-- .entry-header -->
            <div class="entry-content">
                <?php the_content(); ?>
                <?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'symbiostock' ), 'after' => '</div>' ) ); ?>
                <?php edit_post_link( __( 'Edit', 'symbiostock' ), '<span class="edit-link">', '</span>' ); ?>
            </div>
            <!-- .entry-content --> 
        </article>
        <!-- #post-<?php the_ID(); ?> -->
        
        <?php endwhile; // end of the loop. ?>
    </div>
    <div class="col-md-6 well">
    <a title="Symbiostock, the Stock Photography Network" href="http://www.symbiostock.com/">
        <img alt="the Symbiostock Network" src="<?php echo symbiostock_IMGDIR . '/symbiostock_logo_small.png' ?>" />
        
    </a><br /><br />
    <p><a title="Symbiostock, the Stock Photography Network" href="http://www.symbiostock.com/">Symbiostock</a> is a network of independent photographers and illustrators.<br />Browse our network to find the high quality images you are looking for. <br /><br />The network contributor results below are automatically generated for your convenience.</p>
    
    <h4>Community News / Activity</h4>
    <?php symbiostock_community_activity(); ?>
    
    </div>
    <div class="clearfix"><br /></div>
    <hr />
    <div id="content" class="col-md- site-content network_directory">
        
             <?php
                $networks = new network_manager();
                $networks->list_all_networks(true, true);
                
            ?>       
    </div>
</div>
<?php get_footer(); ?>
