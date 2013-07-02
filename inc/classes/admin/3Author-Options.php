<?php
//updates all images on site with current values if needed
//in case user updates ALL posts, we up the time limit so it doesn't crash
set_time_limit ( 0 );
function symbiostock_update_all_images( )
{
	ini_set( "memory_limit", "1024M" );
    
    $meta_values = array(
         'symbiostock_exclusive' => 'exclusive',
        'symbiostock_live' => 'live',
        'price_bloggee' => 'price_bloggee',
        'price_small' => 'price_small',
        'price_medium' => 'price_medium',
        'price_large' => 'price_large',
        'price_vector' => 'price_vector',
        'price_zip' => 'price_zip',
        'symbiostock_discount' => 'discount_percent',
        
        'symbiostock_bloggee_available' => 'symbiostock_bloggee_available',
        'symbiostock_small_available' => 'symbiostock_small_available',
        'symbiostock_medium_available' => 'symbiostock_medium_available',
        'symbiostock_large_available' => 'symbiostock_large_available',
        'symbiostock_vector_available' => 'symbiostock_vector_available',
        'symbiostock_zip_available' => 'symbiostock_zip_available',
        
        'symbiostock_referral_label_1' => 'symbiostock_referral_label_1',
        'symbiostock_referral_label_2' => 'symbiostock_referral_label_2',
        'symbiostock_referral_label_3' => 'symbiostock_referral_label_3',
        'symbiostock_referral_label_4' => 'symbiostock_referral_label_4',
        'symbiostock_referral_label_5' => 'symbiostock_referral_label_5',
        
        'symbiostock_referral_link_1' => 'symbiostock_referral_link_1',
        'symbiostock_referral_link_2' => 'symbiostock_referral_link_2',
        'symbiostock_referral_link_3' => 'symbiostock_referral_link_3',
        'symbiostock_referral_link_4' => 'symbiostock_referral_link_4',
        'symbiostock_referral_link_5' => 'symbiostock_referral_link_5' 
    );
    
    $args       = array(
         'post_type' => 'image',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'caller_get_posts' => 1,
		'fields' => 'ids'
    );
    $all_images = null;
    $all_images = new WP_Query( $args );
	
	$count = 0;
	$total_count = 0;
	
    if ( $all_images->have_posts() ) {
        while ( $all_images->have_posts() ):
            $all_images->the_post();
            
            $id = get_the_ID();
            
            if ( isset( $_POST[ 'symbiostock_update_images' ] ) ) {
				
				$image_post = array();
				$image_post['ID'] = $id;
				$image_post['comment_status'] = $_POST['symbiostock_comments'];
				
				wp_update_post( $image_post );
				
                $edit = get_post_meta($id, 'locked', 'not_locked');
		
				if($edit == 'not_locked'){
					
					$size_info = symbiostock_change_image_sizes($id, $_POST['symbiostock_bloggee_size'], $_POST['symbiostock_small_size'], $_POST['symbiostock_medium_size']);
					
					update_post_meta($id, 'size_info', $size_info );
					
					foreach($meta_values as $key => $meta_value){
						
						$option = get_option($key, '');
						
						//echo $meta_value . ': ' . $option . '<br />';
										
						if(!empty($option)){
							
							update_post_meta($id, $meta_value, $option);
							
						}
						
					}
					
			}      
           
        }
		$count++;
		$total_count++;
		if($count == 100){
			
			$subject = 'Image Process Update: ' . $total_count . ' Completed.';
			$message = 'Image process update: ' . $total_count . ' image pages updated on ' . get_bloginfo('wpurl');
			
			wp_mail(get_option('admin_email'), $subject, $message);
			
			$count = 0;
			
			}	
        //update post
        endwhile;
		
		$complete = 'Operation complete!' . $total_count . ' images updated.';
		
		wp_mail(get_option('admin_email'), $complete, $complete);
    }
}
	
settings_fields( 'symbiostock_settings_group' ); 
//exclusivity
if(isset($_POST['symbiostock_exclusive'])){ 
update_option('symbiostock_exclusive', $_POST['symbiostock_exclusive']); 
}
$exclusive = get_option('symbiostock_exclusive');
$exclusive == 'not_exclusive' || !isset($exclusive)  ? $not_exclusive = 'selected="selected"' : $not_exclusive = '';
$exclusive == 'exclusive' ? $exclusive = 'selected="selected"' : $exclusive = '';
//live or not live
if(isset($_POST['symbiostock_live'])){ 
update_option('symbiostock_live', $_POST['symbiostock_live']); 
}
$live = get_option('symbiostock_live');
$live == 'not_live' ? $not_live = 'selected="selected"' : $not_live = '';
$live == 'live' || !isset($live)   ? $live = 'selected="selected"' : $live = '';
if(isset($_POST['price_bloggee'])){ update_option('price_bloggee', $_POST['price_bloggee']); }
if(isset($_POST['price_small'])){ update_option('price_small', $_POST['price_small']); }
if(isset($_POST['price_medium'])){ update_option('price_medium', $_POST['price_medium']); }
if(isset($_POST['price_large'])){ update_option('price_large', $_POST['price_large']); }
if(isset($_POST['price_vector'])){ update_option('price_vector', $_POST['price_vector']); }
if(isset($_POST['price_zip'])){ update_option('price_zip', $_POST['price_zip']); }
if(isset($_POST['symbiostock_bloggee_available'])){ 
	update_option( 'symbiostock_bloggee_available', $_POST[ 'symbiostock_bloggee_available' ] );
}
if(isset($_POST['symbiostock_small_available'])){ 
	update_option( 'symbiostock_small_available', $_POST[ 'symbiostock_small_available' ] );
}
if(isset($_POST['symbiostock_medium_available'])){ 
	update_option( 'symbiostock_medium_available', $_POST[ 'symbiostock_medium_available' ] );
}
if(isset($_POST['symbiostock_large_available'])){ 
	update_option( 'symbiostock_large_available', $_POST[ 'symbiostock_large_available' ] );
}
if(isset($_POST['symbiostock_vector_available'])){ 
	update_option( 'symbiostock_vector_available', $_POST[ 'symbiostock_vector_available' ] );
}
if(isset($_POST['symbiostock_zip_available'])){ 
	update_option( 'symbiostock_zip_available', $_POST[ 'symbiostock_zip_available' ] );
}
if(isset($_POST['symbiostock_discount'])){ 
	update_option( 'symbiostock_discount', $_POST[ 'symbiostock_discount' ] );
}
if(isset($_POST['symbiostock_bloggee_size'])){ 
	update_option( 'symbiostock_bloggee_size', $_POST[ 'symbiostock_bloggee_size' ] );
}
if(isset($_POST['symbiostock_small_size'])){ 
	update_option( 'symbiostock_small_size', $_POST[ 'symbiostock_small_size' ] );
}
if(isset($_POST['symbiostock_medium_size'])){ 
	update_option( 'symbiostock_medium_size', $_POST[ 'symbiostock_medium_size' ] );
}
//SEO title
if(isset($_POST['symbiostock_title_seo_text'])){ 
	update_option( 'symbiostock_title_seo_text', $_POST[ 'symbiostock_title_seo_text' ] );
}

//Rank
if(isset($_POST['symbiostock_rank'])){ 
	update_option( 'symbiostock_rank', $_POST[ 'symbiostock_rank' ] );
}
$rank = get_option('symbiostock_rank', '2');
$rank == '1' ? $rank_1 = 'selected="selected"' : $rank_1 = '';
$rank == '2' ? $rank_2 = 'selected="selected"' : $rank_2 = '';
$rank == '3' ? $rank_3 = 'selected="selected"' : $rank_3 = '';

//Rating
if(isset($_POST['symbiostock_rating'])){ 
	update_option( 'symbiostock_rating', $_POST[ 'symbiostock_rating' ] );
}
$rating = get_option('symbiostock_rating');
$rating == '1' || !isset($rating)  ? $rating_1 = 'selected="selected"' : $rating_1 = '';
$rating == '2' ? $rating_2 = 'selected="selected"' : $rating_2 = '';
$rating == '3' ? $rating_3 = 'selected="selected"' : $rating_3 = '';


//comments on or off on images
if(isset($_POST['symbiostock_comments'])){ 
	update_option( 'symbiostock_comments', $_POST[ 'symbiostock_comments' ] );
}
$symbiostock_comment_status = get_option('symbiostock_comments');
$symbiostock_comment_status == 'open' || !isset($symbiostock_comment_selected)  ? $symbiostock_comment_selected = 'selected="selected"' : $symbiostock_comment_selected = '';
$symbiostock_comment_status == 'closed' ? $symbiostock_comment_not_selected = 'selected="selected"' : $symbiostock_comment_not_selected = '';
//reflections on or off on minipics
if(isset($_POST['symbiostock_reflections'])){ 
	update_option( 'symbiostock_reflections', $_POST[ 'symbiostock_reflections' ] );
}
$symbiostock_reflections = get_option('symbiostock_reflections');
$symbiostock_reflections == 'on' || !isset($symbiostock_reflections)  ? $symbiostock_reflections_on = 'selected="selected"' : $symbiostock_reflections_on = '';
$symbiostock_reflections == 'off' ? $symbiostock_reflections_off = 'selected="selected"' : $symbiostock_reflections_off = '';
//model release Yes / No / N/A
if(isset($_POST['symbiostock_model_released'])){ 
	update_option( 'symbiostock_model_released', $_POST[ 'symbiostock_model_released' ] );
}
$symbiostock_model_release = get_option('symbiostock_model_released', 'N/A');
$symbiostock_model_release == 'Yes' || !isset($symbiostock_model_release)  ? $symbiostock_model_released_yes = 'selected="selected"' : $symbiostock_model_released_yes = '';
$symbiostock_model_release == 'No' ? $symbiostock_model_released_no = 'selected="selected"' : $symbiostock_model_released_no = '';
$symbiostock_model_release == 'N/A' ? $symbiostock_model_released_na = 'selected="selected"' : $symbiostock_model_released_na = '';
//property release Yes / No / N/A
if(isset($_POST['symbiostock_property_released'])){ 
	update_option( 'symbiostock_property_released', $_POST[ 'symbiostock_property_released' ] );
}
$symbiostock_property_release = get_option('symbiostock_property_released', 'N/A');
$symbiostock_property_release == 'Yes' || !isset($symbiostock_property_released_yes)  ? $symbiostock_property_released_yes = 'selected="selected"' : $symbiostock_property_released_yes = '';
$symbiostock_property_release == 'No' ? $symbiostock_property_released_no = 'selected="selected"' : $symbiostock_property_released_no = '';
$symbiostock_property_release == 'N/A' ? $symbiostock_property_released_na = 'selected="selected"' : $symbiostock_property_released_na = '';
//datasheets Yes / No / 
if(isset($_POST['symbiostock_enable_datasheets'])){ 
	update_option( 'symbiostock_enable_datasheets', $_POST[ 'symbiostock_enable_datasheets' ] );
}
$symbiostock_datasheets = get_option('symbiostock_enable_datasheets');
$symbiostock_datasheets == 'Yes' || !isset($symbiostock_datasheets)  ? $symbiostock_datasheets_yes = 'selected="selected"' : $symbiostock_datasheets_yes = '';
$symbiostock_datasheets == 'No' ? $symbiostock_datasheets_no = 'selected="selected"' : $symbiostock_datasheets_no = '';
$symbiostock_bloggee_available = get_option( 'symbiostock_bloggee_available', 'yes');
$symbiostock_small_available   = get_option( 'symbiostock_small_available', 'yes');
$symbiostock_medium_available  = get_option( 'symbiostock_medium_available', 'yes');
$symbiostock_large_available   = get_option( 'symbiostock_large_available', 'yes');
$symbiostock_vector_available  = get_option( 'symbiostock_vector_available', 'yes');
$symbiostock_zip_available     = get_option( 'symbiostock_zip_available', 'yes');
$symbiostock_medium_size       = get_option('symbiostock_medium_size', 1000);
$symbiostock_small_size        = get_option('symbiostock_small_size', 500);
$symbiostock_bloggee_size       = get_option('symbiostock_bloggee_size', 250);
		
$referral_count = 1;
while($referral_count <=5){
	
if(isset($_POST['symbiostock_referral_link_' . $referral_count])){ 
	update_option('symbiostock_referral_link_' . $referral_count, $_POST['symbiostock_referral_link_' . $referral_count]); 
	update_option('symbiostock_referral_label_' . $referral_count, $_POST['symbiostock_referral_label_' . $referral_count]); 
	}
$referral_count++;	
}
if(isset($_POST['symbiostock_update_images']) || isset($_POST['symbiostock_generate_related_images'])){
	
	symbiostock_update_all_images();	
	$symbiostock_edited_all_images = '<p><em>Site images updated.</em></p>';	
	}
?>
<h1>Author Default Settings and Pricing</h1>
<table class="form-table symbiostock-settings">
    <tr>
        <th colspan=2> <strong>&raquo; Image Status</strong> </th>
    </tr>
    <tr>
        <th scope="row">Exclusive</th>
        <td><select id="symbiostock_exclusive"  name="symbiostock_exclusive">
                <option <?php echo $not_exclusive; ?> value="not_exclusive">Not Exclusive</option>
                <option <?php echo $exclusive; ?> value="exclusive">Exclusive</option>
            </select></td>
    </tr>
    <tr>
        <th scope="row">Live<br /><?php echo sshelp('live', 'Live'); ?></th>
        <td><select id="symbiostock_live"  name="symbiostock_live">
                <option <?php echo $live; ?> value="live">Live</option>
                <option <?php echo $not_live; ?> value="not_live">Not Live</option>
            </select></td>
    </tr>
    
    <!--rank rating-->
    
    <tr>
        <th scope="row">Rank<br /><?php echo sshelp('rank', 'Rank'); ?></th>
        <td>        	
        	<select id="symbiostock_rank"  name="symbiostock_rank">
                <option <?php echo $rank_1; ?> value="1">1st</option>
                <option <?php echo $rank_2; ?> value="2">2nd</option>
                <option <?php echo $rank_3; ?> value="3">3rd</option>                
            </select>
            <br />
            <p class="description">Relative ranking system, putting premium at front of search results, second in the middle, third last.</p>
        </td>
    </tr>
    
    <tr>
        <th scope="row">Rating<br /><?php echo sshelp('rating', 'Rating'); ?></th>
        <td>
        	<select id="symbiostock_rating"  name="symbiostock_rating">
                <option <?php echo $rating_1; ?> value="1">GREEN</option>
                <option <?php echo $rating_2; ?> value="2">YELLOW</option>
                <option <?php echo $rating_3; ?> value="3">RED</option>                              
            </select>
            <p class="description">Nudity filter. See info link for definitions.</p></td>
    </tr>
        
    
  
    <tr>
        <th colspan=2> <strong>&raquo; Pricing and Options <?php echo sshelp('default_pricing', 'Default Pricing'); ?></strong><br />
            *See <strong>Settings</strong> to change type. </th>
    </tr>
    <tr>
        <th scope="row"><strong>Vector</strong></th>
        <td><input type="text" name="price_vector"  id="price_vector" value="<?php echo get_option('price_vector', '20.00'); ?>" />
            <?php symbiostock_size_available('vector', $symbiostock_vector_available) ?></td>
    </tr>
    <tr>
        <th scope="row"><strong>Zip</strong> (packaged alternate files)</th>
        <td><input type="text" name="price_zip"  id="price_zip" value="<?php echo get_option('price_zip', '30.00'); ?>" />
            <?php symbiostock_size_available('zip', $symbiostock_zip_available) ?></td>
    </tr>
    <tr>
        <th scope="row"><strong>Large</strong></th>
        <td><input type="text" name="price_large"  id="price_large" value="<?php echo get_option('price_large', '20.00'); ?>" />
            <?php symbiostock_size_available('large', $symbiostock_large_available) ?></td>
    </tr>
    <tr>
        <th scope="row"><strong>Medium</strong></th>
        <td><input type="text" name="price_medium"  id="price_medium" value="<?php echo get_option('price_medium', '10.00'); ?>" />
            <?php symbiostock_size_available('medium', $symbiostock_medium_available) ?></td>
    </tr>
    <tr>
        <th scope="row"><strong>Small</strong></th>
        <td><input type="text" name="price_small"  id="price_small" value="<?php echo get_option('price_small', '5.00'); ?>" />
            <?php symbiostock_size_available('small', $symbiostock_small_available) ?></td>
    </tr>
    <tr>
        <th scope="row"><strong>Bloggee</strong></th>
        <td><input type="text" name="price_bloggee"  id="price_bloggee" value="<?php echo get_option('price_bloggee', '2.50'); ?>" />
            <?php symbiostock_size_available('bloggee', $symbiostock_bloggee_available) ?></td>
    </tr>
    <tr>
        <th scope="row">Discount %</th>
        <td><input type="text" name="symbiostock_discount"  id="symbiostock_discount" value="<?php echo get_option('symbiostock_discount', '0'); ?>" />
            Enter "<strong>00</strong>" to reset to 0.</td>
    </tr>
    <tr>
        <th colspan=2> <strong>&raquo; Default Size Settings</strong>  
        <br /><?php echo sshelp('default_size_settings', 'Default Size Settings'); ?>
        </th>
    </tr>
    <tr>
        <th scope="row"><strong>Medium</strong></th>
        <td><input type="text" name="symbiostock_medium_size"  id="symbiostock_medium_size" value="<?php echo $symbiostock_medium_size ?>" /></td>
    </tr>
    <tr>
        <th scope="row"><strong>Small</strong></th>
        <td><input type="text" name="symbiostock_small_size"  id="symbiostock_small_size" value="<?php echo $symbiostock_small_size ?>" /></td>
    </tr>
    <tr>
        <th scope="row"><strong>Bloggee</strong></th>
        <td><input type="text" name="symbiostock_bloggee_size"  id="symbiostock_bloggee_size" value="<?php echo $symbiostock_bloggee_size ?>" /></td>
    </tr>
    <tr>
        <th colspan=2> <strong>&raquo; Image SEO</strong> *Entering "<strong><em>-Royalty Free Image</em></strong>" will append that phrase to all image titles. </th>
    </tr>
   
   
    <tr>
        <th scope="row"><strong>Append Text to Title:</strong></th>    
        <td><input type="text" name="symbiostock_title_seo_text"  id="symbiostock_title_seo_text" value="<?php echo get_option('symbiostock_title_seo_text', ''); ?>" /><br /><br /></td>
   
    </tr>   
   
   
    <tr>
    
        <th scope="row"><strong>Image Comments</strong> <br /> Applied during processing. Must manually change afterward.</th>
        <td><select id="symbiostock_comments"  name="symbiostock_comments">
                <option <?php echo $symbiostock_comment_selected; ?> value="open">Allowed</option>
                <option <?php echo $symbiostock_comment_not_selected; ?> value="closed">Disabled</option>
            </select></td>
    </tr>
    <?php if ( extension_loaded( 'imagick' ) ) {  ?>
    <tr>
        <th scope="row"><strong>Thumbnail Reflections</strong> <br /> Attractive for full-area pictures. Isolated pics (on white), not so much. </th>
        <td><select id="symbiostock_reflections"  name="symbiostock_reflections">
                <option <?php echo $symbiostock_reflections_on; ?> value="on">Reflections On</option>
                <option <?php echo $symbiostock_reflections_off; ?> value="off">Reflections Off</option>
            </select></td>
  	</tr>      
    <?php } ?>    
    <tr>    
        <th scope="row"><strong>Model Released?</strong> <br /></th>
        <td><select id="symbiostock_model_released"  name="symbiostock_model_released">
                <option <?php echo $symbiostock_model_released_yes; ?> value="Yes">Yes</option>
                <option <?php echo  $symbiostock_model_released_no; ?> value="No">No</option>
                <option <?php echo  $symbiostock_model_released_na; ?> value="N/A">N/A</option>
            </select></td>
    </tr>
    <tr>    
        <th scope="row"><strong>Property Released?</strong> <br /></th>
        <td><select id="symbiostock_property_released"  name="symbiostock_property_released">
                <option <?php echo $symbiostock_property_released_yes; ?> value="Yes">Yes</option>
                <option <?php echo  $symbiostock_property_released_no; ?> value="No">No</option>
                <option <?php echo  $symbiostock_property_released_na; ?> value="N/A">N/A</option>
            </select></td>
    </tr> 
    <tr>    
        <th scope="row"><strong>Enable Datasheets?</strong> <br /></th>
        <td><select id="symbiostock_enable_datasheets"  name="symbiostock_enable_datasheets">
                <option <?php echo $symbiostock_datasheets_yes; ?> value="Yes">Yes</option>
                <option <?php echo $symbiostock_datasheets_no; ?> value="No">No</option>
            </select><?php echo sshelp('datasheets', 'About Datasheets'); ?></td>
    </tr>         
   
    <tr>
        <th colspan=2> <strong>&raquo; Referral Links</strong> </th>
    </tr>
    <tr>
        <th scope="row"><strong>Referral Link #1:</strong></th>
        <td><input class="longfield" type="text" name="symbiostock_referral_link_1"  id="symbiostock_referral_link_1" value="<?php echo get_option('symbiostock_referral_link_1', ''); ?>" /></td>
    </tr>
    <tr>
        <th scope="row">Label:</th>
        <td><input class="longfield" type="text" name="symbiostock_referral_label_1"  id="symbiostock_referral_label_1" value="<?php echo get_option('symbiostock_referral_label_1', ''); ?>" /></td>
    </tr>
    <tr>
        <th scope="row"><strong>Referral Link #2</strong></th>
        <td><input class="longfield" type="text" name="symbiostock_referral_link_2"  id="symbiostock_referral_link_2" value="<?php echo get_option('symbiostock_referral_link_2', ''); ?>" /></td>
    </tr>
    <tr>
        <th scope="row">Label:</th>
        <td><input class="longfield" type="text" name="symbiostock_referral_label_2"  id="symbiostock_referral_label_2" value="<?php echo get_option('symbiostock_referral_label_2', ''); ?>" /></td>
    </tr>
    <tr>
        <th scope="row"><strong>Referral Link #3</strong></th>
        <td><input class="longfield" type="text" name="symbiostock_referral_link_3"  id="symbiostock_referral_link_3" value="<?php echo get_option('symbiostock_referral_link_3', ''); ?>" /></td>
    </tr>
    <tr>
        <th scope="row">Label:</th>
        <td><input class="longfield" type="text" name="symbiostock_referral_label_3"  id="symbiostock_referral_label_3" value="<?php echo get_option('symbiostock_referral_label_3', ''); ?>" /></td>
    </tr>
    <tr>
        <th scope="row"><strong>Referral Link #4</strong></th>
        <td><input class="longfield" type="text" name="symbiostock_referral_link_4"  id="symbiostock_referral_link_4" value="<?php echo get_option('symbiostock_referral_link_4', ''); ?>" /></td>
    </tr>
    <tr>
        <th scope="row">Label:</th>
        <td><input class="longfield" type="text" name="symbiostock_referral_label_4"  id="symbiostock_referral_label_4" value="<?php echo get_option('symbiostock_referral_label_4', ''); ?>" /></td>
    </tr>
    <tr>
        <th scope="row"><strong>Referral Link #5</strong></th>
        <td><input class="longfield" type="text" name="symbiostock_referral_link_5"  id="symbiostock_referral_link_5" value="<?php echo get_option('symbiostock_referral_link_5', ''); ?>" /></td>
    </tr>
    <tr>
        <th scope="row">Label:</th>
        <td><input class="longfield" type="text" name="symbiostock_referral_label_5"  id="symbiostock_referral_label_5" value="<?php echo get_option('symbiostock_referral_label_5', ''); ?>" /></td>
    </tr>
</table>
<br />
<br />
<label for="symbiostock_update_images">
    <input value="1" id="symbiostock_update_images" type="checkbox" name="symbiostock_update_images" />
    <strong>Update all existing images</strong> with new values? <em>Caution!</em></label>
<br />
<br />   
<?php
//if image update occured, notify user 
if(isset($symbiostock_edited_all_images)){echo $symbiostock_edited_all_images;} 
?>