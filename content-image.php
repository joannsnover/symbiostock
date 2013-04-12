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
<article id="post-<?php the_ID(); ?>" <?php post_class("row-fluid"); ?>>
    <div class="symbiostock-image span7">
        <header class="entry-header">
            <div itemscope itemtype="http://schema.org/ImageObject" class="hmedia">
                <div class="well">
                    <h1 itemprop="name" class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'symbiostock' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark">
                        <?php the_title(); ?>
                        </a></h1>
                    <div class="item-preview content-box"><a  title="<?php the_title(); ; ?>" rel="enclosure" type="image/jpeg" href="<?php echo $symbiostock_post_meta['symbiostock_preview'][0];  ?>"> <img itemprop="contentURL image" class="photo" alt="<?php the_title();  ?>" src="<?php echo $symbiostock_post_meta['symbiostock_preview'][0];  ?>"/> </a></div>
                    <div itemprop="description" class="entry-content fn item-description">
                        <?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'symbiostock' ) ); ?>
                        <?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'symbiostock' ), 'after' => '</div>' ) ); ?>
                        <em>by <span itemprop="author contributor copyrightHolder creator" class="contributor vcard"> <a class="url fn" href="<?php the_author_meta('user_url') ?>">
                        <?php the_author(); ?>
                        </a></span></em> (<a class="url" href="<?php echo get_option('symbiostock_my_network_about_page', get_site_url()); ?>">profile</a>) </div>
                    <!-- .entry-content --> 
                </div>
                <div id="keywords-listing">
                    <div itemprop="keywords"  class="well">
                        <h5>Keywords</h5>
                        <?php echo get_the_term_list( $post->ID, 'image-tags', '', ', ', '' ); ?> </div>
                    
                        <?php           
                $symbiostock_categories = get_the_term_list( $post->ID, 'image-type', '', ' | ', '' );            
                if($symbiostock_categories){ ?>
                <div class="well">
        
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
            <?php if ( 'post' == get_post_type() ) : ?>
            <div class="entry-meta">
                <?php symbiostock_posted_on(); ?>
            </div>
            <!-- .entry-meta -->
            <?php endif; ?>
        </header>
        <!-- .entry-header --> 
        
    </div>
    <div class="span5">
        <?php
		
		//set up the buying options from cart class
		
		$cart_options = new symbiostock_cart($symbiostock_post_meta);
		
		$cart_options->display_product_table();
		
		//get sidebar
		dynamic_sidebar( 'Image Page Side' );
		
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
