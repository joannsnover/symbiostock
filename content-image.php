<?php
/**
 * @package symbiostock
 * @since symbiostock 1.0
 */

$strictly_minimal = get_theme_mod( 'strictly_minimal' );
if( $strictly_minimal == '' ) {    
    $strictly_minimal = 1;       
} 


?>
<?php 
//get our post meta
$postid = get_the_ID();
$symbiostock_post_meta = symbiostock_post_meta($postid);
                                    
//testing, uncommment
//var_dump($symbiostock_post_meta);
?>
<?php 
$symbiostock_post_meta['caller_action'] = 'ss_before_image_page';
do_action( 'ss_before_image_page', $symbiostock_post_meta ); 

?> 
<article id="post-<?php the_ID(); ?>" <?php post_class(' row'); ?>>
    <div class="symbiostock-image col-md-7">
        <header class="entry-header">
            <div itemscope itemtype="http://schema.org/CreativeWork" class="hmedia">
                <div class="panel panel-default image_container">
                    <?php 
                    $symbiostock_post_meta['caller_action'] = 'ss_before_img_page_title';
                    do_action( 'ss_before_img_page_title', $symbiostock_post_meta );  
                    ?>
                    <div class="panel-heading">
                        <h1 itemprop="name" class="page-header entry-title panel-title title">
                            <a class="" href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'symbiostock' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark">
                                <?php the_title(); ?>                       
                            </a>
                        </h1>
                    </div>
                    <?php 
                    $symbiostock_post_meta['caller_action'] = 'ss_after_img_page_title';
                    do_action( 'ss_after_img_page_title', $symbiostock_post_meta );  
                    ?>
                    <div class="panel-body" itemtype="http://schema.org/ImageObject">
                        
                        <?php 
                        $symbiostock_post_meta['caller_action'] = 'ss_before_img_page_preview';
                        do_action( 'ss_before_img_page_preview', $symbiostock_post_meta ); 
                        ?>     
                        <div class="item-preview content-box"><a id="stock-image-preview" title="<?php the_title(); ; ?>" rel="enclosure" type="image/jpeg" href="<?php echo $symbiostock_post_meta['symbiostock_preview'][0];  ?>"> <img itemprop="contentURL image" class="photo img-responsive" alt="<?php the_title();  ?>" src="<?php echo $symbiostock_post_meta['symbiostock_preview'][0];  ?>"/> </a></div>
                        <?php 
                        $symbiostock_post_meta['caller_action'] = 'ss_after_img_page_preview';
                        do_action( 'ss_after_img_page_preview', $symbiostock_post_meta ); 
                        ?> 
                       
                        <div itemprop="description caption" class="entry-content fn item-description">
                            
                            <?php 
                            $symbiostock_post_meta['caller_action'] = 'ss_before_img_page_description';
                            do_action( 'ss_before_img_page_description', $symbiostock_post_meta ); 
                            ?> 
                            
                            <?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'symbiostock' ) ); ?>
                           
                            <?php
                            $symbiostock_post_meta['caller_action'] = 'ss_after_img_page_description';
                            do_action( 'ss_after_img_page_description', $symbiostock_post_meta ); 
                            ?> 
                           
                            <?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'symbiostock' ), 'after' => '</div>' ) ); ?>
                          
                        </div>
                    </div>
                    <div class="panel-body bio_box">
                        <?php 
                        $symbiostock_post_meta['caller_action'] = 'ss_before_img_page_author_box';
                        do_action( 'ss_before_img_page_author_box', $symbiostock_post_meta ); 
                        ?> 
                        <?php symbiostock_csv_symbiocard_box($symbiocard_location = '', true, '', true); ?>
                        <?php 
                        $symbiostock_post_meta['caller_action'] = 'ss_after_img_page_author_box';
                        do_action( 'ss_after_img_page_author_box', $symbiostock_post_meta ); 
                        ?> 
                        
                        <span class="date updated text-muted"><em>Image updated&mdash;<?php echo get_the_date(); ?></em></span>                    
                        <!-- .entry-content -->
                        <?php 
                        $symbiostock_post_meta['caller_action'] = 'ss_bottom_img_page_preview_well';
                        do_action( 'ss_bottom_img_page_preview_well' );  
                        ?>
                    </div> 
                </div>
                
                <?php 
                if($strictly_minimal == 1):
                ?>
                
                <div class="panel panel-default" id="keywords-listing">
                    <div class="panel-heading"><h4 class="panel-title"><i class="icon-tags"> </i> Keywords</h4></div>
                    <div itemprop="keywords"  class="panel-body">
                        <?php 
                        $symbiostock_post_meta['caller_action'] = 'ss_before_img_page_keywords';
                        do_action( 'ss_before_img_page_keywords', $symbiostock_post_meta );  
                        ?>
                        
                        <?php echo get_the_term_list( $post->ID, 'image-tags', '', ', ', '' ); ?> 
                    </div>
                    
                        <?php           
                    $symbiostock_categories = get_the_term_list( $post->ID, 'image-type', '', ' | ', '' );            
                    if($symbiostock_categories){ ?>
                    <div class="panel-heading"><h4 class="panel-title"><i class="icon-sitemap"> </i> Image Categories</h4></div>
                    <div class="panel-body">
                        <?php 
                        $symbiostock_post_meta['caller_action'] = 'ss_before_img_page_categories';
                        do_action( 'ss_before_img_page_categories', $symbiostock_post_meta );  
                        ?>                                        
                        <?php echo  $symbiostock_categories; ?>
                     
                    </div><?php
                    }
                ?>                    
                </div>                

                <?php
                if(!function_exists('ss_is_collection') || !ss_is_collection( $symbiostock_post_meta )):
                                        
                    if(is_active_sidebar( 'image-page-bottom' )):                
                
                    ?>                    
                    <div class="panel panel-default">                
                    <?php    
                    //get bottom sidebar
                    dynamic_sidebar( 'image-page-bottom' );                
                    ?>
                    </div>
                    <?php endif; 
                    
                endif;
                ?>
                
                <?php 
                endif; //strictly minimal
                ?>                
               
                              
            </div>
        </header>
        <!-- .entry-header --> 
        
    </div>
    <div class="col-md-5">    
        <?php
        $symbiostock_post_meta['caller_action'] = 'ss_before_img_page_product_table';
        do_action( 'ss_before_img_page_product_table', $symbiostock_post_meta ); 
        //set up the buying options from cart class
        $cart_options = new symbiostock_cart($symbiostock_post_meta);

        ?><div class="panel panel-default">
        <div class="panel-heading"><span class="panel-title">Image #<?php echo $postid  ?></span></div>
        <?php 
        $cart_options->display_product_table();
        ?></div><?php 
        
        $symbiostock_post_meta['caller_action'] = 'ss_after_img_page_product_table';
        do_action( 'ss_after_img_page_product_table', $symbiostock_post_meta ); 
        
        
        if($strictly_minimal == 1):
        
        
        
        //get sidebar
        $symbiostock_post_meta['caller_action'] = 'ss_before_img_page_sidebar';
        do_action( 'ss_before_img_page_sidebar', $symbiostock_post_meta ); 
        
        
        if(!function_exists('ss_is_collection') || !ss_is_collection( $symbiostock_post_meta )):
        
            if(is_active_sidebar( 'image-page-side' )): 
            ?><div class="panel panel-default"><?php
                          
            dynamic_sidebar( 'image-page-side' );
            
            ?></div><?php 
            endif;
        
        endif;
        
        $symbiostock_post_meta['caller_action'] = 'ss_after_img_page_sidebar';
        do_action( 'ss_after_img_page_sidebar', $symbiostock_post_meta); 
        
        
        $cart_options->display_referral_links();
        symbiostock_credit_links('product_page');
        ?>
        <footer class="entry-meta">
            <?php if ( 'post' == get_post_type() ) : // Hide category and tag text for pages on Search ?>
            <?php
                /* translators: used between list items, there is a space after the comma */
                $categories_list = get_the_category_list( __( ', ', 'symbiostock' ) );
                if ( $categories_list && symbiostock_categorized_blog() ) :
            ?>
            <span class="cat-links"> <?php printf( __( 'Posted in %1$s', 'symbiostock' ), $categories_list ); ?> </span>
            <?php endif; // End if categories ?>
            <?php
                /* translators: used between list items, there is a space after the comma */
                $tags_list = get_the_tag_list( '', __( ', ', 'symbiostock' ) );
                if ( $tags_list ) :
            ?>
            <span class="sep"> | </span> <span class="tags-links"> <?php printf( __( 'Tagged %1$s', 'symbiostock' ), $tags_list ); ?> </span>
            <?php endif; // End if $tags_list ?>
            <?php endif; // End if 'post' == get_post_type() ?>
            <?php if ( ! post_password_required() && ( comments_open() || '0' != get_comments_number() ) ) : ?>
            <span class="sep"> | </span> <span class="comments-link">
            <?php comments_popup_link( __( 'Leave a comment', 'symbiostock' ), __( '1 Comment', 'symbiostock' ), __( '% Comments', 'symbiostock' ) ); ?>
            </span>
            <?php endif; ?>
            <?php edit_post_link( __( 'Edit', 'symbiostock' ), '<span class="sep"> | </span><span class="edit-link">', '</span>' ); ?> 
        </footer>
        <!-- .entry-meta --> 
        
        <?php 
        endif; //strictly minimal 
        ?>
        
    </div>
    
</article>
<!-- #post-<?php the_ID(); ?> -->
<?php 

if($strictly_minimal == 1):

$symbiostock_post_meta['caller_action'] = 'ss_before_img_page_bottom_widget';
do_action( 'ss_before_img_page_bottom_widget', $symbiostock_post_meta ); 

if(!function_exists('ss_is_collection') || !ss_is_collection( $symbiostock_post_meta )):

    if(is_active_sidebar( 'image-page-bottom-fullwidth' )): 
    ?>
    <div class="col-md-12">
        <div class="panel panel-default ss-widget-12"><?php
        
        dynamic_sidebar( 'image-page-bottom-fullwidth' );
        
        ?>
        </div>
    </div>
    <?php
    endif; 

endif;

endif; //strictly minimal

$symbiostock_post_meta['caller_action'] = 'ss_after_image_page';
do_action( 'ss_after_image_page', $symbiostock_post_meta ); ?>  