<?php
function gl_run_split_keywords(){
	$args = array(
		 'post_type' => 'image',
		'post_status' => 'publish',
		'posts_per_page' => -1,
		'caller_get_posts' => 1,
		'fields' => 'ids'
	);
	$image_list = null;
	$image_list = new WP_Query( $args );	
	if ( $image_list->have_posts() ) {	
		$image_meta = array( );	
		while ( $image_list->have_posts() ):
			$image_list->the_post(); 
			$id = get_the_ID();		
			$terms = get_the_terms( $id, 'image-tags' );		
			if ( $terms && !is_wp_error( $terms ) ):
				$keywords = array( );
				//first populate the original kewywords
				foreach ( $terms as $term ) {
					array_push($keywords, $term->name);
				}
				//then split them and add the split versions
				foreach ( $terms as $term ) {
					array_push($keywords, preg_split( '/[+\s_-]/',$term->name));				 
				} 			
				$corrected_keywords = array_unique($keywords);
				var_dump($corrected_keywords);				
				$keyword_update[ 'tax_input' ] = array( 'image-tags' => array( $corrected_keywords ) );
				//wp_update_post( $keyword_update );			
			endif;		
		endwhile;	
	} //$image_list->have_posts()
}
function gl_split_keywords(){	
	if(isset($_POST['gl_split_keywords'])){
		gl_run_split_keywords();
		}
	?> 
    <p>The keyword splitter takes compound keywords and splits them into separate ones. Symbiostock uses Wordpress's database, which is not always flexibile. This is a temporary fix until the premium plugin is available, which will use its own specialized database for searches.</p>
	<form method="post" action="">
        <label for="gl_split_keywords">Split keywords?  
        	<input id="gl_split_keywords" name="gl_split_keywords" class="button" type="submit" value="SPLIT" />
        </label>
	</form>
    <hr />
	<?php			
	}
add_action( 'gl_custom_actions_after', 'gl_split_keywords' );
?>



<?php
$networks = new network_manager();
$test = $networks->get_connected_networks( );

include_once(symbiostock_CLASSROOT . 'marketing/extended_network.php');

?>
<span class="description">Use these for marketing sites to aggregate your info.</span>
<ul>
<li>
<?php
	
	$url = get_bloginfo('url');

if(file_exists(ABSPATH . '/symbiostock_keyword_info.csv')){
	echo 'Keyword list:  <a title="Keyword list..." href="' . $url . '/symbiostock_keyword_info.csv">' . $url . '/symbiostock_keyword_info.csv</a>';
	}
?>
</li>
<li>
<?php
if(file_exists(ABSPATH . '/symbiostock_image_info.csv')){
	echo 'Image list: <a title="Image list..." href="' . $url . '/symbiostock_image_info.csv">' . $url . '/symbiostock_image_info.csv</a>';
	}
?>
</li>
</ul>

<?php
//include_once(symbiostock_CLASSROOT . 'marketing/network_hub.php');
include_once(symbiostock_CLASSROOT . 'marketing/marketer.php');
?>