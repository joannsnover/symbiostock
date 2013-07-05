<?php
/**
 * @package symbiostock
 * @since symbiostock 1.0
 */
?>
<?php 
//get our post meta
$postid = get_the_ID();
$symbiostock_post_meta = symbiostock_post_meta($postid);
									
//testing, uncommment
//var_dump($symbiostock_post_meta);
?>
<?php do_action( 'ss_before_image_page' ); ?> 
<article id="post-<?php the_ID(); ?>" <?php post_class("row-fluid"); ?>>
    <div class="symbiostock-image span7">
        <header class="entry-header">
            <div itemscope itemtype="http://schema.org/CreativeWork" class="hmedia">
                <div class="well">
                    <?php do_action( 'ss_before_img_page_title' );  ?>
                    <h1 itemprop="name" class="entry-title title"><a class="" href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'symbiostock' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark">
                        <?php the_title(); ?>                       
                    </a>
                    </h1>
                    <?php do_action( 'ss_after_img_page_title' );  ?>
                    <div itemscope itemtype="http://schema.org/ImageObject">
                    	
						<?php do_action( 'ss_before_img_page_preview' ); ?>     
                        <div class="item-preview content-box"><a id="stock-image-preview" title="<?php the_title(); ; ?>" rel="enclosure" type="image/jpeg" href="<?php echo $symbiostock_post_meta['symbiostock_preview'][0];  ?>"> <img itemprop="contentURL image" class="photo" alt="<?php the_title();  ?>" src="<?php echo $symbiostock_post_meta['symbiostock_preview'][0];  ?>"/> </a></div>
                        <?php do_action( 'ss_after_img_page_preview' ); ?> 
                       
                        <div itemprop="description caption" class="entry-content fn item-description">
                            
							<?php do_action( 'ss_before_img_page_description' ); ?> 
							
							<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'symbiostock' ) ); ?>
                           
                            <?php do_action( 'ss_after_img_page_description' ); ?> 
                           
                            <?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'symbiostock' ), 'after' => '</div>' ) ); ?>
                          
                        </div>
                    </div>
                    <?php do_action( 'ss_before_img_page_author_box' ); ?> 
					<?php symbiostock_csv_symbiocard_box($symbiocard_location = '', true, '', true); ?>
                    <?php do_action( 'ss_after_img_page_author_box' ); ?> 
                    <hr />
                    <span class="date updated muted"><em>Image updated&mdash;<?php echo get_the_date(); ?></em></span>					
                    <!-- .entry-content -->
                    <?php do_action( 'ss_bottom_img_page_preview_well' );  ?> 
                </div>
                <div id="keywords-listing">
                	
                    <div itemprop="keywords"  class="well">
                        <?php do_action( 'ss_before_img_page_keywords' );  ?>
                        <h5>Keywords</h5>
                        <?php echo get_the_term_list( $post->ID, 'image-tags', '', ', ', '' ); ?> </div>
                    
                        <?php           
                $symbiostock_categories = get_the_term_list( $post->ID, 'image-type', '', ' | ', '' );            
                if($symbiostock_categories){ ?>
                <div class="well">
        		<?php do_action( 'ss_before_img_page_categories' );  ?>
                <h5>Image Categories</h5>                
                <?php echo  $symbiostock_categories; ?>
                 
                </div><?php
                }
                ?>                    
                </div>
                                
                <?php				
				//get bottom sidebar
				dynamic_sidebar( 'Image Page Bottom' );				
				?>                
            </div>
        </header>
        <!-- .entry-header --> 
        
    </div>
    <div class="span5">    
        <?php
		
		do_action( 'ss_before_img_page_product_table' ); 
		//set up the buying options from cart class
		$cart_options = new symbiostock_cart($symbiostock_post_meta);		
		$cart_options->display_product_table();
		do_action( 'ss_after_img_page_product_table' ); 
		
		//get sidebar
		do_action( 'ss_before_img_page_sidebar' ); 
		dynamic_sidebar( 'Image Page Side' );
		do_action( 'ss_after_img_page_sidebar' ); 
		
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
        
    </div>
    
</article>
<!-- #post-<?php the_ID(); ?> -->
<?php do_action( 'ss_before_img_page_bottom_widget' );  ?>
 
<?php dynamic_sidebar( 'Image Page Bottom Fullwidth' ); ?> 
 
<?php do_action( 'ss_after_image_page' ); ?>  