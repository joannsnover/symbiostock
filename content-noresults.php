<?php
/**
 * @package symbiostock
 * @since symbiostock 1.0
 */ 
?>
	<div id="primary" class="content-area row-fluid">
		<div id="content" class="site-content span12" role="main">
			<article id="post-0" class="post error404 not-found">
				<header class="entry-header">
					<h1 class="entry-title"><?php _e( 'No results found for ' . ucwords(urldecode (get_query_var('image-tags'))), 'symbiostock' ); ?></h1>
				</header><!-- .entry-header -->
				<div class="entry-content">
					
                    <div class="hero-unit">
                    <h2>Try browsing the categories. You may find some hidden gems!</h2><hr />
                    <?php
										
					$symbiostock_terms = get_terms( 'image-type', 'orderby=count&hide_empty=0' );
					$count = count($symbiostock_terms); $i=0;
					if ($count > 0) {
						$cape_list = '<p class="my_term-archive">';
						$term_list = '';
						foreach ($symbiostock_terms as $term) {
							$i++;
							$term_list .= '<a href="/image-type/' . $term->slug . '" title="' . sprintf(__('View all images  under %s', 'my_localization_domain'), $term->name) . '">' . $term->name . '</a> (' . $term->count. ')<br />';
							if ($count != $i) $term_list .= ' &middot; '; else $term_list .= '</p>';
						}
						echo $term_list;
					}
					
					?>
					</div>
                    
				</div><!-- .entry-content -->
			</article><!-- #post-0 .post .error404 .not-found -->
		</div><!-- #content .site-content -->
	</div><!-- #primary .content-area -->    