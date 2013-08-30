<?php
/**
 * @package symbiostock
 * @since symbiostock 1.0
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<h1 class="entry-title"><?php the_title(); ?></h1>
		<div class="entry-meta">
			<?php symbiostock_posted_on(); ?>
		</div><!-- .entry-meta -->
	</header><!-- .entry-header -->
	<div class="entry-content row-fluid muted">
    	
		<?php 
		
		$image_id = get_post_meta($post->ID, 'symbiostock_image_page');
		
		$attachment_id = get_post_meta( $image_id[0], 'symbiostock_minipic_id' );
		
		$image_attributes = wp_get_attachment_image_src( $attachment_id[0] ); // returns an array       
        
		$permalink = get_permalink($image_id[0]);
		
		?><div>
        <small><?php 
		
		echo '<a class="alignright" title="Original stock image" href="'.$permalink.'"><img class="img-rounded img-polaroid" src="' . $image_attributes[ 0 ] . '" width="' . $image_attributes[ 1 ] . '" height="' . $image_attributes[ 2 ] . '" /></a>' . '';
        
		symbiostock_dublin_core(false);
		
		?> 
        </small>       
        </div>
        <hr />
		<div class="Dataset">
         
		<?php  		       		
		the_content(); 
		?>
        
        </div>
		<?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'symbiostock' ), 'after' => '</div>' ) ); ?>
	</div><!-- .entry-content -->
	<footer class="entry-meta">
        
		<?php
			/* translators: used between list items, there is a space after the comma */
			$category_list = get_the_category_list( __( ', ', 'symbiostock' ) );
			/* translators: used between list items, there is a space after the comma */
			$tag_list = get_the_tag_list( '', __( ', ', 'symbiostock' ) );
			if ( ! symbiostock_categorized_blog() ) {
				// This blog only has 1 category so we just need to worry about tags in the meta text
				if ( '' != $tag_list ) {
					$meta_text = __( 'This entry was tagged %2$s. Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', 'symbiostock' );
				} else {
					$meta_text = __( 'Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', 'symbiostock' );
				}
			} else {
				// But this blog has loads of categories so we should probably display them here
				if ( '' != $tag_list ) {
					$meta_text = __( 'This entry was posted in %1$s and tagged %2$s. Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', 'symbiostock' );
				} else {
					$meta_text = __( 'This entry was posted in %1$s. Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', 'symbiostock' );
				}
			} // end check for categories on this blog
			printf(
				$meta_text,
				$category_list,
				$tag_list,
				get_permalink(),
				the_title_attribute( 'echo=0' )
			);
		?>
		<?php edit_post_link( __( 'Edit', 'symbiostock' ), '<span class="edit-link">', '</span>' ); ?>
	</footer><!-- .entry-meta -->
</article><!-- #post-<?php the_ID(); ?> -->