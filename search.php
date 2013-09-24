<?php
/**
 * The template for displaying Search Results pages.
 *
 * @package symbiostock
 * @since symbiostock 1.0
 */
//we shift gears depending on if image or blog search or network search
$symbiostock_network_search = get_query_var( 'symbiostock_network_search' );
$symbiostock_post_type = get_query_var( 'post_type' );
if($symbiostock_post_type == 'image' 
&& $symbiostock_network_search == true){
    //now open up a local search
    $local_results = new network_manager();
    
    $local_results->local_search();
    
    $local_results->display_xml_results();
    
}
else{
    
    if(get_query_var( 'post_type' ) == 'image'){
        
        $symbiostock_image_search = true;
        $search_span = 12;
        
        } else {
        $symbiostock_image_search = false;    
        $search_span = 8;    
            }
    
    get_header(); ?>
            
            
            <div class="row">
            
                    <section id="primary" class="content-area">
                    <div id="content" class="site-content search_page col-md-<?php echo $search_span; ?>" role="main">
                    <?php                            
                            
        echo '<h1 class="results_for">Results for <em>"' . ucwords(str_replace('-', ' ', get_query_var('s'))) . '"</em></h1>';
                            
        if($symbiostock_image_search == true){
        
        get_template_part('modal-search');        
    
        $sscount = new results_counter();
    
        //now open up a local search
        $local_results = new network_manager();
        
        $local_results->local_search();
        
        $local_results->display_results(false);        
        
        ?> <div class="clearfix">&nbsp;</div> <?php
        
        //now do network search
        $network_results = new network_manager();
        
        $network_results->network_search_all_similar();
        
        $use_network = get_option('symbiostock_use_network');
        
        if($use_network == 'true'){
                
                ?> <div class="clearfix">&nbsp;</div> <?php 
                
                get_template_part('see-directory');
            }
            
            } else {
            ?>
                    <?php if ( have_posts() ) : ?>
        
                        <header class="page-header">
                            <h1 class="page-title"><?php printf( __( 'Search Results for: %s', 'symbiostock' ), '<span>' . get_search_query() . '</span>' ); ?></h1>
                        </header><!-- .page-header -->
        
                        <?php symbiostock_content_nav( 'nav-above' ); ?>
        
                        <?php /* Start the Loop */ ?>
                        <?php while ( have_posts() ) : the_post(); ?>
        
                            <?php get_template_part( 'content', 'search' ); ?>
        
                        <?php endwhile; ?>
        
                        <?php symbiostock_content_nav( 'nav-below' ); ?>
        
                    <?php else : ?>
        
                        <?php get_template_part( 'no-results', 'search' ); ?>
        
                    <?php endif; } ?>
        
                    </div><!-- #content .site-content -->
                </section><!-- #primary .content-area -->
               
            <?php if($symbiostock_image_search == false){ ?>
                <div class="col-md-4">
                <?php get_sidebar(); ?>
                </div>
            <?php } ?>
            </div> 
            
    <?php get_footer(); 
}?>