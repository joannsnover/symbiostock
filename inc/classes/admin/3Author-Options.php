<?php
//updates all images on site with current values if needed

//in case user updates ALL posts, we up the time limit so it doesn't crash
set_time_limit ( 120);

function symbiostock_update_all_images(){
	
	$meta_values = array(
		'symbiostock_exclusive'          => 'exclusive',
		'symbiostock_live'               => 'live',
		'price_bloggee'                  => 'price_bloggee',	
		'price_small'                    => 'price_small',
		'price_medium'                   => 'price_medium',
		'price_large'                    => 'price_large',
		'price_vector'                   => 'price_vector',
		'price_zip'                      => 'price_zip',
		'symbiostock_discount'           => 'discount_percent',
		
		'symbiostock_bloggee_available'  => 'symbiostock_bloggee_available',
		'symbiostock_small_available'    => 'symbiostock_small_available',
		'symbiostock_medium_available'   => 'symbiostock_medium_available',
		'symbiostock_large_available'    => 'symbiostock_large_available',
		'symbiostock_vector_available'   => 'symbiostock_vector_available',
		'symbiostock_zip_available'      => 'symbiostock_zip_available',
		
		 'symbiostock_referral_label_1'  => 'symbiostock_referral_label_1',
		 'symbiostock_referral_label_2'  => 'symbiostock_referral_label_2',
		 'symbiostock_referral_label_3'  => 'symbiostock_referral_label_3',	
		 'symbiostock_referral_label_4'  => 'symbiostock_referral_label_4',	
		 'symbiostock_referral_label_5'  => 'symbiostock_referral_label_5',
				
		 'symbiostock_referral_link_1'   => 'symbiostock_referral_link_1',
		 'symbiostock_referral_link_2'   => 'symbiostock_referral_link_2',
		 'symbiostock_referral_link_3'   => 'symbiostock_referral_link_3',
		 'symbiostock_referral_link_4'   => 'symbiostock_referral_link_4',
		 'symbiostock_referral_link_5'   => 'symbiostock_referral_link_5',		
	);
	
	$args=array(
	  'post_type' => 'image',
	  'post_status' => 'publish',
	  'posts_per_page' => -1,
	  'caller_get_posts'=> 1
	);
	$all_images = null;
	$all_images = new WP_Query($args);
	if( $all_images->have_posts() ) {
	  while ($all_images->have_posts()) : $all_images->the_post();
		
		$id =  get_the_ID();
		
		$edit = get_post_meta($id, 'locked', 'not_locked');
		
		if($edit == 'not_locked'){
			
			foreach($meta_values as $key => $meta_value){
				
				$option = get_option($key, '');
				
				//echo $meta_value . ': ' . $option . '<br />';
								
				if(!empty($option)){
					
					update_post_meta($id, $meta_value, $option);
					
					}
				
				}
			
			}
		
		//update post
	  endwhile;
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
if(isset($_POST['symbiostock_update_images'])){
	
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
    
        <th scope="row">Live</th>
        <td><select id="symbiostock_live"  name="symbiostock_live">
                <option <?php echo $live; ?> value="live">Live</option>
                <option <?php echo $not_live; ?> value="not_live">Not Live</option>
            </select></td>
    </tr>
    <tr>
        <th colspan=2> <strong>&raquo; Pricing and Options</strong><br />
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
        <th colspan=2> <strong>&raquo; Default Size Settings</strong> *<strong>Note</strong> - Size settings cannot be changed on image edit page yet. Choose carefully. <em>Large</em> size is original upload size. </th>
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
<?php
//if image update occured, notify user 
if(isset($symbiostock_edited_all_images)){echo $symbiostock_edited_all_images;} 
?>
