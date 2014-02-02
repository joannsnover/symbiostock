<?php
//image custom post type



add_action( 'init', 'symbiostock_image_manager_register' );
function symbiostock_image_manager_register( )
{
    //creating custom post type for image
    
    $labels = array(
         'name'              => __('Symbiostock Images', 'symbiostock'),
        'singular_name'      => __('Image', 'symbiostock'),
        'add_new'            => __('New Image', 'symbiostock'),
        'add_new_item'       => __('Add New Image', 'symbiostock'),
        'edit_item'          => __('Edit Image', 'symbiostock'),
        'new_item'           => __('New Image', 'symbiostock'),
        'all_items'          => __('All Images', 'symbiostock'),
        'view_item'          => __('View Image', 'symbiostock'),
        'search_items'       => __('Search Images', 'symbiostock'),
        'not_found'          => __('No image found', 'symbiostock'),
        'not_found_in_trash' => __('No images found in Trash', 'symbiostock'),
        'parent_item_colon'  => '',
        'menu_name'          => __('Stock Images', 'symbiostock'), 
        'not_found'          => __('No images found', 'symbiostock'),
    );
    
    $args = array(
                
         'labels' => $labels,
        'singular_label'      => __( 'Image', 'symbiostock'),
        'description'         => __( 'Image Listings', 'symbiostock'),
        'menu_position'       => 100,
        'menu_icon'           => symbiostock_IMGDIR . '/symbiostock_icon2.png',
        'public'              => true,
        'capability_type'     => 'post',      
        'has_archive'         => true,
        'exclude_from_search' => false,
        'query_var'           => true,
        'supports'            => array(
                                     'title',
                                    'editor',
                                    'thumbnail',             
                                    'excerpt',
                                    'post-formats',  
                                ),
        'rewrite'             => true,
        
    );
    
    
    register_post_type( 'image', $args );
    
    register_taxonomy( 'image-type', array(
         'image' 
    ), array(
         'hierarchical'       => true,
        'label'               => __('Image Categories', 'symbiostock'),
        'singular_label'      => __('Image Type', 'symbiostock'),
        'rewrite'             => true,
        'exclude_from_search' => false,
        'public'              => true, 
        'slug'                => 'image-type',
        'show_in_nav_menus'   => true,         
        
    ) );
    
    register_taxonomy( 'image-tags', array(
         'image' 
    ), array(
        'public'                  => true,
        'rewrite'                 => true,
        'query_var'               => true,
        'singular_label'          => __('Image Keyword', 'symbiostock'),
        'exclude_from_search'     => false,
        'hierarchical'            => false,
        'labels'                  => $labels,
        'show_ui'                 => true,
        'show_admin_column'       => true,
        
        'slug'                    => 'image-tag',                  
        'labels'                  => array(
            'name'                => _x( 'Image Keywords', 'taxonomy general name', 'symbiostock' ),
            'singular_name'       => _x( 'Keywords', 'taxonomy singular name', 'symbiostock' ),
            'search_items'        =>  __( 'Search Images', 'symbiostock' ),
            'all_items'           => __( 'All Image Keywords', 'symbiostock' ),
            'edit_item'           => __( 'Edit Image Keyword', 'symbiostock' ),
            'update_item'         => __( 'Update Image Keyword', 'symbiostock' ),
            'add_new_item'        => __( 'Add New Image Keyword', 'symbiostock' ),
            'new_item_name'       => __( 'New Keyword Name', 'symbiostock' ),
            'menu_name'           => __( 'Image Keywords', 'symbiostock' ),
            ),
        'rewrite'                => array(
            'slug'               => 'search-images', // This controls the base slug that will display before each term
            'with_front'         => false, 
        ),
    ) );
    
}
add_action( 'admin_init', 'symbiostock_image_directory' );
function symbiostock_image_directory( )
{
    add_meta_box( 'symbiostock-image-meta', __('Symbiostock Image Info', 'symbiostock'), 'symbiostock_image_manager_meta_options', 'image', 'normal', 'high' );
    
}
//creates a list of inputs in the image managing screen to allow availability or not
function symbiostock_size_available($size, $available){
    
    $available == __('yes', 'symbiostock') || !isset($available)  ? $yes = 'selected="selected"' : $yes = '';
    
    $available == __('no', 'symbiostock')  ? $no = 'selected="selected"' : $no = '';
    
    $available == 'no_select' ? $no_select = 'selected="selected"' : $no_select = '';
    
    ?>
<select name="symbiostock_<?php echo $size ?>_available">
    <option <?php echo $yes ?> value="<?php _e('yes', 'symbiostock') ?>"><?php _e('Available', 'symbiostock') ?></option>
    <option <?php echo $no ?> value="<?php _e('no', 'symbiostock') ?>"><?php _e('Not Available', 'symbiostock') ?></option>
    <option <?php echo $no_select ?> value="no_select"><?php _e('No Select', 'symbiostock') ?></option>
</select>
<?php
}    
function symbiostock_image_manager_meta_options( )
{
    global $post;
    
    isset($post->ID)? $image_id = $post->ID : $image_id = $_POST['image_id'];
    
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
        return $post_id;
    
    $custom           = get_post_custom( $image_id );
    $price_bloggee    = $custom[ 'price_bloggee' ][ 0 ];
    $price_small      = $custom[ 'price_small' ][ 0 ];
    $price_medium     = $custom[ 'price_medium' ][ 0 ];
    $price_large      = $custom[ 'price_large' ][ 0 ];
    $price_zip        = $custom[ 'price_zip' ][ 0 ];
    $price_vector     = $custom[ 'price_vector' ][ 0 ];
    $discount_percent = $custom[ 'discount_percent' ][ 0 ];
    $exclusive        = $custom[ 'exclusive' ][ 0 ];
    $locked           = $custom[ 'locked' ][ 0 ];
    $live             = $custom[ 'live' ][ 0 ];
    
    //availability options
    
    $symbiostock_bloggee_available = $custom[ 'symbiostock_bloggee_available' ][ 0 ];
    $symbiostock_small_available   = $custom[ 'symbiostock_small_available' ][ 0 ];
    $symbiostock_medium_available  = $custom[ 'symbiostock_medium_available' ][ 0 ];
    $symbiostock_large_available   = $custom[ 'symbiostock_large_available' ][ 0 ];
    $symbiostock_vector_available  = $custom[ 'symbiostock_vector_available' ][ 0 ];
    $symbiostock_zip_available     = $custom[ 'symbiostock_zip_available' ][ 0 ];
    
    //rank and rating
    $symbiostock_rating     = $custom[ 'symbiostock_rating' ][ 0 ];
    $symbiostock_rank     = $custom[ 'symbiostock_rank' ][ 0 ];    
    
    //size info
    $size_info = get_post_meta($image_id, 'size_info');
    $sizes = maybe_unserialize($size_info[0]);
    
    $sizes['large']['width'] > $sizes['large']['height'] ? $symbiostock_large_size = $sizes['large']['width'] : $symbiostock_large_size = $sizes['large']['height'];
    $sizes['medium']['width'] > $sizes['medium']['height'] ? $symbiostock_medium_size = $sizes['medium']['width'] : $symbiostock_medium_size = $sizes['medium']['height'];
    $sizes['small']['width'] > $sizes['small']['height'] ? $symbiostock_small_size = $sizes['small']['width'] : $symbiostock_small_size = $sizes['small']['height'];
    $sizes['bloggee']['width'] > $sizes['bloggee']['height'] ? $symbiostock_bloggee_size = $sizes['bloggee']['width'] : $symbiostock_bloggee_size = $sizes['bloggee']['height'];
    
    //legal
    $symbiostock_model_release     = $custom[ 'symbiostock_model_released' ][ 0 ];
    $symbiostock_property_release  = $custom[ 'symbiostock_property_released' ][ 0 ];
    
    //referral links
    
    $symbiostock_referral_label_1     = $custom[ 'symbiostock_referral_label_1' ][ 0 ];
    $symbiostock_referral_label_2     = $custom[ 'symbiostock_referral_label_2' ][ 0 ];
    $symbiostock_referral_label_3     = $custom[ 'symbiostock_referral_label_3' ][ 0 ];
    $symbiostock_referral_label_4     = $custom[ 'symbiostock_referral_label_4' ][ 0 ];
    $symbiostock_referral_label_5     = $custom[ 'symbiostock_referral_label_5' ][ 0 ];    
    $symbiostock_referral_link_1     = $custom[ 'symbiostock_referral_link_1' ][ 0 ];
    $symbiostock_referral_link_2     = $custom[ 'symbiostock_referral_link_2' ][ 0 ];
    $symbiostock_referral_link_3     = $custom[ 'symbiostock_referral_link_3' ][ 0 ];
    $symbiostock_referral_link_4     = $custom[ 'symbiostock_referral_link_4' ][ 0 ];
    $symbiostock_referral_link_5     = $custom[ 'symbiostock_referral_link_5' ][ 0 ];
    
    
?>
<div class="image_manager">

<br />

<?php do_action('ss_image_options_single_before', $image_id); ?>

<?php
//Rating
$symbiostock_rating == '1' || !isset($symbiostock_rating)  ? $symbiostock_rating_1 = 'selected="selected"' : $symbiostock_rating_1 = '';
$symbiostock_rating == '2' ? $symbiostock_rating_2 = 'selected="selected"' : $symbiostock_rating_2 = '';
$symbiostock_rating == '3' ? $symbiostock_rating_3 = 'selected="selected"' : $symbiostock_rating_3 = '';
?>
<div>
<label>Rating: </label>
<select id="symbiostock_rating"  name="symbiostock_rating">
    <option <?php echo $symbiostock_rating_1; ?> value="1"><?php _e('GREEN',  'symbiostock') ?></option>
    <option <?php echo $symbiostock_rating_2; ?> value="2"><?php _e('YELLOW', 'symbiostock') ?></option>
    <option <?php echo $symbiostock_rating_3; ?> value="3"><?php _e('RED',    'symbiostock') ?></option>                              
</select>
<?php echo sshelp('rating', __('Rating', 'symbiostock')); ?>
</div>


<?php
//rank
$symbiostock_rank == '1' ? $symbiostock_rank_1 = 'selected="selected"' : $symbiostock_rank_1 = '';
$symbiostock_rank == '2' || !isset($symbiostock_rank) ? $symbiostock_rank_2 = 'selected="selected"' : $symbiostock_rank_2 = '';
$symbiostock_rank == '3' ? $symbiostock_rank_3 = 'selected="selected"' : $symbiostock_rank_3 = '';
?>

<div>
<label>rank: </label>
<select id="symbiostock_rank"  name="symbiostock_rank">
    <option <?php echo $symbiostock_rank_1; ?> value="1">1st</option>
    <option <?php echo $symbiostock_rank_2; ?> value="2">2nd</option>
    <option <?php echo $symbiostock_rank_3; ?> value="3">3rd</option>                
</select>
<?php echo sshelp('rank', __('Rank', 'symbiostock')); ?>
</div>

<br />

<?php
    $locked == 'locked' ? $checked = 'checked="checked"' : $checked = '';
     if(!isset($_POST['image_id']))
         echo '<img class="alignright preview_pic" alt="'.__('Image Preview', 'symbiostock').'" src="' . $custom['symbiostock_preview'][0] . '" />';
?>
<div>
    <label>Bloggee: </label>
    <input type="text" name = "price_bloggee" value="<?php
    echo $price_bloggee;
?>" />
    <?php symbiostock_size_available('bloggee', $symbiostock_bloggee_available) ?>
</div>
<div>
    <label>Small: </label>
    <input type="text" name="price_small" value="<?php
    echo $price_small;
?>" />
    <?php symbiostock_size_available('small', $symbiostock_small_available) ?>
</div>
<div>
    <label>Medium: </label>
    <input type="text" name = "price_medium" value="<?php
    echo $price_medium;
?>" />
    <?php symbiostock_size_available('medium', $symbiostock_medium_available) ?>
</div>
<div>
    <label>Large: </label>
    <input type="text" name = "price_large" value="<?php
    echo $price_large;
?>" />
    <?php symbiostock_size_available('large', $symbiostock_large_available) ?>
</div>
<div>
    <label>Vector: </label>
    <input type="text" name="price_vector" value="<?php
    echo $price_vector;
?>" />
    <?php symbiostock_size_available('vector', $symbiostock_vector_available) ?>
</div>
<div>
    <label>Zip: </label>
    <input type="text" name="price_zip" value="<?php
    echo $price_zip;
?>" />
    <?php symbiostock_size_available('zip', $symbiostock_zip_available) ?>
</div>
<div>
    <label>Discount: </label>
    <input type="text" name="discount_percent" value="<?php
    echo $discount_percent;
?>" />
</div>

<br />

<div>
    
    <label>Size Bloggee: </label>
    <input type="text" name="symbiostock_bloggee_size" value="<?php echo $symbiostock_bloggee_size ?>" />
</div>
<div>
    <label>Size Small: </label>
    <input type="text" name="symbiostock_small_size" value="<?php echo $symbiostock_small_size ?>" />
</div>
<div>
    <label>Size Medium: </label>
    <input type="text" name="symbiostock_medium_size" value="<?php echo $symbiostock_medium_size ?>" />
</div>
<div>
    <label>Size Large: </label>
    <input readonly type="text" name="symbiostock_large_size" value="<?php echo $symbiostock_large_size ?>" />
</div>
<?php
    
    $exclusive == 'not_exclusive' || !isset($exclusive)  ? $non_exclusive = 'selected="selected"' : $non_exclusive = '';
    $exclusive == 'exclusive' ? $exclusive = 'selected="selected"' : $exclusive = '';
    
    ?>
<br />
<div>
    <label>Exclusive: </label>
    <select name="exclusive">
        <option <?php echo $non_exclusive; ?> value="not_exclusive"><?php _e('Not Exclusive', 'symbiostock') ?></option>
        <option <?php echo $exclusive; ?> value="exclusive"><?php _e('Exclusive', 'symbiostock') ?></option>
    </select>
</div>
<?php
    
    $live == 'not_live'  ? $not_live = 'selected="selected"' : $not_live = '';
    $live == 'live' || !isset($live) ? $live = 'selected="selected"' : $live = '';
    
    ?>
<div>
    <label>Live: </label>
    <select name="live">
        <option <?php echo $live; ?> value="live"><?php _e('Live', 'symbiostock') ?></option>
        <option <?php echo $not_live; ?> value="not_live"><?php _e('Not Live', 'symbiostock') ?></option>
    </select>
</div>

<?php
$symbiostock_model_release == __('N/A', 'symbiostock') ? $symbiostock_model_released_na = 'selected="selected"' : $symbiostock_model_released_na = '';
$symbiostock_model_release == __('Yes', 'symbiostock')  || !isset($symbiostock_model_release)  ? $symbiostock_model_released_yes = 'selected="selected"' : $symbiostock_model_released_yes = '';
$symbiostock_model_release == __('No', 'symbiostock')  ? $symbiostock_model_released_no = 'selected="selected"' : $symbiostock_model_released_no = '';

$symbiostock_property_release == __('N/A', 'symbiostock')  ? $symbiostock_property_released_na = 'selected="selected"' : $symbiostock_property_released_na = '';
$symbiostock_property_release == __('Yes', 'symbiostock')  || !isset($symbiostock_property_release)  ? $symbiostock_property_released_yes = 'selected="selected"' : $symbiostock_property_released_yes = '';
$symbiostock_property_release == __('No', 'symbiostock')  ? $symbiostock_property_released_no = 'selected="selected"' : $symbiostock_property_released_no = '';

?>
<div><br /><br />
    <label><?php _e('Model Released:', 'symbiostock') ?> </label>    
        <select id="symbiostock_model_released"  name="symbiostock_model_released">
            <option <?php echo $symbiostock_model_released_yes; ?> value="<?php _e('Yes', 'symbiostock') ?>"><?php _e('Yes', 'symbiostock') ?></option>
            <option <?php echo $symbiostock_model_released_no; ?> value="<?php _e('No', 'symbiostock') ?>"><?php _e('No', 'symbiostock') ?></option>
            <option <?php echo $symbiostock_model_released_na; ?> value="<?php _e('N/A', 'symbiostock') ?>"><?php _e('N/A', 'symbiostock') ?></option>
        </select><br />
    <label><?php _e('Property Released:', 'symbiostock') ?> </label>    
        <select id="symbiostock_property_released"  name="symbiostock_property_released">
            <option <?php echo $symbiostock_property_released_yes; ?> value="<?php _e('Yes', 'symbiostock') ?>"><?php _e('Yes', 'symbiostock') ?></option>
            <option <?php echo $symbiostock_property_released_no; ?> value="<?php _e('No', 'symbiostock') ?>"><?php _e('No', 'symbiostock') ?></option>
            <option <?php echo $symbiostock_property_released_na; ?> value="<?php _e('N/A', 'symbiostock') ?>"><?php _e('N/A', 'symbiostock') ?></option>
    </select>
    <br /><br /><br />        
</div>    
    
<?php
    
    $locked == 'not_locked' || !isset($locked)  ? $not_locked = 'selected="selected"' : $not_locked = '';
    $locked == 'locked' ? $locked = 'selected="selected"' : $locked = '';
    
    ?>
<div>
    <label><?php _e('Values Locked: ', 'symbiostock') ?> </label>
    <select name="locked">
        <option <?php echo $not_locked; ?> value="not_locked"><?php _e('Not Locked', 'symbiostock') ?></option>
        <option <?php echo $locked; ?> value="locked"><?php _e('Locked', 'symbiostock') ?></option>
    </select>
</div>
<br />
<div>
    <label><?php _e('Referral Link', 'symbiostock') ?> #1: </label>
    <input size="50" type="text" name="symbiostock_referral_link_1" value="<?php
    echo $symbiostock_referral_link_1;
?>" />
    <br />
    <label><?php _e('Label:', 'symbiostock') ?> </label>
    <input size="50" type="text" name="symbiostock_referral_label_1" value="<?php
    echo $symbiostock_referral_label_1;
?>" /><br /><br />
</div>
<div>
    <label><?php _e('Referral Link', 'symbiostock') ?> #2: </label>
    <input size="50" type="text" name="symbiostock_referral_link_2" value="<?php
    echo $symbiostock_referral_link_2;
?>" />
    <br />
    <label><?php _e('Label:', 'symbiostock') ?> </label>
    <input size="50" type="text" name="symbiostock_referral_label_2" value="<?php
    echo $symbiostock_referral_label_2;
?>" /><br /><br />
</div>
<div>
    <label><?php _e('Referral Link', 'symbiostock') ?> #3: </label>
    <input size="50" type="text" name="symbiostock_referral_link_3" value="<?php
    echo $symbiostock_referral_link_3;
?>" />
    <br />
    <label><?php _e('Label:', 'symbiostock') ?> </label>
    <input size="50" type="text" name="symbiostock_referral_label_3" value="<?php
    echo $symbiostock_referral_label_3;
?>" /><br /><br />
</div>
<div>
    <label><?php _e('Referral Link', 'symbiostock') ?> #4: </label>
    <input size="50" type="text" name="symbiostock_referral_link_4" value="<?php
    echo $symbiostock_referral_link_4;
?>" />
    <br />
    <label><?php _e('Label:', 'symbiostock') ?> </label>
    <input size="50" type="text" name="symbiostock_referral_label_4" value="<?php
    echo $symbiostock_referral_label_4;
?>" /><br /><br />
</div>
<div>
    <label><?php _e('Referral Link', 'symbiostock') ?> #5: </label>
    <input size="50" type="text" name="symbiostock_referral_link_5" value="<?php
    echo $symbiostock_referral_link_5;
?>" />
    <br />
    <label><?php _e('Label:', 'symbiostock') ?> </label>
    <input size="50" type="text" name="symbiostock_referral_label_5" value="<?php
    echo $symbiostock_referral_label_5;
?>" /><br /><br />

<?php do_action('ss_image_options_single_after', $image_id); ?>
<br class="clear" />
</div>
</div>

<?php
    
}
add_action( 'save_post', 'symbiostock_image_manager_save_options' );
function symbiostock_image_manager_save_options( )
{
    global $post;
    global $post_type;
    
    if($post_type != 'image'){        
        if(!isset($_POST['image_id']))
            return;        
        }        
    
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return $post_id;
        
    } //defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE
    
    
    else {
        
                
        isset($post->ID)? $image_id = $post->ID : $image_id = $_POST['image_id'];
        
        if(isset($_POST[ 'price_bloggee' ] ))
               update_post_meta( $image_id, 'price_bloggee', $_POST[ 'price_bloggee' ] ); 
        
        if(isset($_POST[ 'price_small' ] ))           
            update_post_meta( $image_id, 'price_small', $_POST[ 'price_small' ] ); 
        
        if(isset($_POST[ 'price_medium' ] ))                 
            update_post_meta( $image_id, 'price_medium', $_POST[ 'price_medium' ] );  
        
        if(isset($_POST[ 'price_large' ] ))      
               update_post_meta( $image_id, 'price_large', $_POST[ 'price_large' ] );        
        
        if(isset($_POST[ 'price_vector' ] )) 
               update_post_meta( $image_id, 'price_vector', $_POST[ 'price_vector' ] );        
        
        if(isset($_POST[ 'price_zip' ] )) 
            update_post_meta( $image_id, 'price_zip', $_POST[ 'price_zip' ] );        
       
        if(isset($_POST[ 'discount_percent' ] )) 
            update_post_meta( $image_id, 'discount_percent', $_POST[ 'discount_percent' ] );        
        
        if(isset($_POST[ 'exclusive' ] )) 
            update_post_meta( $image_id, 'exclusive', $_POST[ 'exclusive' ] );        
        
        if(isset($_POST[ 'locked' ] )) 
            update_post_meta( $image_id, 'locked', $_POST[ 'locked' ] );        
        
        if(isset($_POST[ 'live' ] )) 
            update_post_meta( $image_id, 'live', $_POST[ 'live' ] );        
        
        //availability options symbiostock_  $size _available    
        if(isset($_POST[ 'symbiostock_bloggee_available' ] ))     
            update_post_meta( $image_id, 'symbiostock_bloggee_available', $_POST[ 'symbiostock_bloggee_available' ] );    
            
        if(isset($_POST[ 'symbiostock_small_available' ] ))             
            update_post_meta( $image_id, 'symbiostock_small_available', $_POST[ 'symbiostock_small_available' ] );        
        
        if(isset($_POST[ 'symbiostock_medium_available' ] )) 
            update_post_meta( $image_id, 'symbiostock_medium_available', $_POST[ 'symbiostock_medium_available' ] );
            
        if(isset($_POST[ 'symbiostock_large_available' ] ))             
            update_post_meta( $image_id, 'symbiostock_large_available', $_POST[ 'symbiostock_large_available' ] );
            
        if(isset($_POST[ 'symbiostock_vector_available' ] ))             
            update_post_meta( $image_id, 'symbiostock_vector_available', $_POST[ 'symbiostock_vector_available' ] );
            
        if(isset($_POST[ 'symbiostock_zip_available' ] ))         
            update_post_meta( $image_id, 'symbiostock_zip_available', $_POST[ 'symbiostock_zip_available' ] );    
        
        //rank and rating
        if(isset($_POST[ 'symbiostock_rank' ] )) 
            update_post_meta( $image_id, 'symbiostock_rank', $_POST[ 'symbiostock_rank' ] );
        
        if(isset($_POST[ 'symbiostock_rating' ] ))         
            update_post_meta( $image_id, 'symbiostock_rating', $_POST[ 'symbiostock_rating' ] );    
        
        //size info
        
        if(isset($image_id) 
        && isset( $_POST['symbiostock_bloggee_size']) 
        && isset( $_POST['symbiostock_small_size']) 
        && isset( $_POST['symbiostock_medium_size'])){
            $size_info = symbiostock_change_image_sizes($image_id, $_POST['symbiostock_bloggee_size'], $_POST['symbiostock_small_size'], $_POST['symbiostock_medium_size']);
            update_post_meta($image_id, 'size_info', $size_info );
        }
        
        
        //legal
        if(isset($_POST[ 'symbiostock_model_released' ] ))      
            update_post_meta( $image_id, 'symbiostock_model_released', $_POST[ 'symbiostock_model_released' ] );
        
        if(isset($_POST[ 'symbiostock_property_released' ] ))         
            update_post_meta( $image_id, 'symbiostock_property_released', $_POST[ 'symbiostock_property_released' ] );    
        
        //referral links
        if(isset($_POST[ 'symbiostock_referral_label_1' ] ))             
            update_post_meta( $image_id, 'symbiostock_referral_label_1', $_POST[ 'symbiostock_referral_label_1' ] );
        
        if(isset($_POST[ 'symbiostock_referral_label_2' ] )) 
            update_post_meta( $image_id, 'symbiostock_referral_label_2', $_POST[ 'symbiostock_referral_label_2' ] );    
        
        if(isset($_POST[ 'symbiostock_referral_label_3' ] )) 
            update_post_meta( $image_id, 'symbiostock_referral_label_3', $_POST[ 'symbiostock_referral_label_3' ] );    
        
        if(isset($_POST[ 'symbiostock_referral_label_4' ] )) 
            update_post_meta( $image_id, 'symbiostock_referral_label_4', $_POST[ 'symbiostock_referral_label_4' ] );    
        
        if(isset($_POST[ 'symbiostock_referral_label_5' ] )) 
            update_post_meta( $image_id, 'symbiostock_referral_label_5', $_POST[ 'symbiostock_referral_label_5' ] );    
        
        if(isset($_POST[ 'symbiostock_referral_link_1' ] ))         
            update_post_meta( $image_id, 'symbiostock_referral_link_1', $_POST[ 'symbiostock_referral_link_1' ] );
        
        if(isset($_POST[ 'symbiostock_referral_link_2' ] )) 
            update_post_meta( $image_id, 'symbiostock_referral_link_2', $_POST[ 'symbiostock_referral_link_2' ] );
        
        if(isset($_POST[ 'symbiostock_referral_link_3' ] ))         
            update_post_meta( $image_id, 'symbiostock_referral_link_3', $_POST[ 'symbiostock_referral_link_3' ] );
        
        if(isset($_POST[ 'symbiostock_referral_link_4' ] ))         
            update_post_meta( $image_id, 'symbiostock_referral_link_4', $_POST[ 'symbiostock_referral_link_4' ] );    
        
        if(isset($_POST[ 'symbiostock_referral_link_5' ] )) 
            update_post_meta( $image_id, 'symbiostock_referral_link_5', $_POST[ 'symbiostock_referral_link_5' ] );                
        
    }     
    
}
add_filter( 'manage_edit-image_columns', 'symbiostock_image_manager_edit_columns' );
function symbiostock_image_manager_edit_columns( $columns )
{
    $columns = array(
         'cb'           => '<input type="checkbox" />',
        'image_preview' => __('Preview', 'symbiostock'),
        'date'          => __('Uploaded', 'symbiostock'),
        'title'         => __('Image Name', 'symbiostock'),
        'description'   => __('Description', 'symbiostock'),
        'exclusive'     => __('Exclusive', 'symbiostock'),
        'price_bloggee' => __('Bloggee', 'symbiostock'),
        'price_small'   => __('Small', 'symbiostock'),
        'price_medium'  => __('Medium', 'symbiostock'),
        'price_large'   => __('Large', 'symbiostock'),
        'price_vector'  => __('Vector', 'symbiostock'),
        'cat'           => __('Category', 'symbiostock'),
        'tag'           => __('Keywords', 'symbiostock'),
    );
    
    return $columns;
}
add_action( 'manage_image_posts_custom_column', 'symbiostock_image_manager_custom_columns' );
function symbiostock_image_manager_custom_columns( $column )
{
    global $post;
        
    $custom = get_post_custom();
    
    switch ( $column ) {
        
         case 'date':            
                    
                    
            break;
        
          
        case 'image_preview':
            
            echo '<img class="admin_preview_pic" alt="'.__('Image Preview', 'symbiostock').'" src="' . $custom['symbiostock_minipic'][0] . '" />';
            
            break;
        
        case 'description':
            
            the_excerpt();
            
            break;
        
        case 'exclusive':
            
            echo $exclusive = ucwords(str_replace('_', ' ', $custom[ "exclusive" ][ 0 ]));
            
            break;
        
        case 'discount_percent':
            
            echo $discount_percent = $custom[ "discount_percent" ][ 0 ];
            
            break;
        
        case 'price_bloggee':
            
            echo $price_bloggee = $custom[ "price_bloggee" ][ 0 ];
            
            
            break;
        
        case 'price_small':
            
            echo $custom[ "price_small" ][ 0 ];
            
            break;
        
        case 'price_medium':
            
            echo $custom[ "price_medium" ][ 0 ];
            
            break;
        
        case 'price_large':
            
            echo $custom[ "price_large" ][ 0 ];
            
            break;
        
        case 'price_vector':
            
            echo $custom[ "price_vector" ][ 0 ];
            
            break;
        
        case 'cat':
            
            echo get_the_term_list( $image_id, 'image-type', '', ', ', '' );
            
            break;
        
        case 'tag':
            
            echo get_the_term_list( $image_id, 'image-tags', '', ', ', '' );
            
            break;
            
    } //$column    
}
// ---------- upload panel ---------- //
function uploader( )
{
    include_once( 'classes/uploader.php' );
    
}
function symbiostock_processor( )
{
    include_once( 'classes/processor.php' );
    
}
add_action( 'admin_menu', 'register_symbiostock_image_submenu_page' );
function register_symbiostock_image_submenu_page( )
{
    add_submenu_page( 'edit.php?post_type=image', __('Upload Images', 'symbiostock'), __('Upload Images', 'symbiostock'), 'manage_options', 'symbiostock-upload-images', 'uploader' );
    
    add_submenu_page( 'edit.php?post_type=image', __('Process Uploads', 'symbiostock'), __('Process Uploads', 'symbiostock'), 'manage_options', 'symbiostock-process-images', 'symbiostock_processor' );
    
}
//get js for uploader script and css, found under image custom post menu
//http://www.plupload.com/example_queuewidget.php
if(isset($_GET[ 'page' ])){
    
    if ( is_admin() && $_GET[ 'page' ] == 'symbiostock-upload-images' ) {
        
        //get browserplus
        
        wp_register_script( 'symbiostock_browserplus', 'http://bp.yahooapis.com/2.4.21/browserplus-min.js' );
        
        wp_enqueue_script( 'symbiostock_browserplus' );
        
        //get plupload css
        
        wp_register_style( 'symbiostock_plupload_css', WP_CONTENT_URL . '/plupload/js/jquery.plupload.queue/css/jquery.plupload.queue.css', false, '1.0.0' );
        
        wp_enqueue_style( 'symbiostock_plupload_css' );
        
        
        //Load plupload and all it's runtimes and finally the jQuery queue widget
        
        wp_register_script( 'symbiostock_plupload_full_js', WP_CONTENT_URL . '/plupload/js/plupload.full.js', array(
             'jquery' 
        ), '1.0', false );
        
        wp_enqueue_script( 'symbiostock_plupload_full_js' );
        
        wp_register_script( 'symbiostock_plupload_queue', WP_CONTENT_URL . '/plupload/js/jquery.plupload.queue/jquery.plupload.queue.js', array(
             'jquery' 
        ), '1.0', false );
        
        wp_enqueue_script( 'symbiostock_plupload_queue' );
        
        
        //and add the plupload head js with altered paths and such...
        
        function uploader_head_js( )
        {
    ?>
<script type="text/javascript">
    // Convert divs to queue widgets when the DOM is ready
    jQuery(function($) {
        $("#uploader").pluploadQueue({
            // General settings
            runtimes : 'gears,flash,silverlight,browserplus,html5',
            url : '<?php
            echo WP_CONTENT_URL;
    ?>/plupload/uploads/upload.php',
            max_file_size : '1000mb',
            unique_names : false,
            // Specify what files to browse for
            filters : [
                {title : "Types: jpg, png, eps, zip.", extensions : "jpg,png,eps,zip"}
                
            ],
    
            // Flash settings
            flash_swf_url : '<?php
            echo WP_CONTENT_URL;
    ?>/plupload/js/plupload.flash.swf',
    
            // Silverlight settings
            silverlight_xap_url : '<?php
            echo WP_CONTENT_URL;
    ?>/plupload/js/plupload.silverlight.xap'
        });
    
        // Client side form validation
        $('form').submit(function(e) {
            var uploader = $('#uploader').pluploadQueue();
    
            // Files in queue upload them first
            if (uploader.files.length > 0) {
                // When all files are uploaded submit form
                uploader.bind('StateChanged', function() {
                    if (uploader.files.length === (uploader.total.uploaded + uploader.total.failed)) {
                        $('form')[0].submit();
                    }
                });
                    
                uploader.start();
            } else {
                alert('You must queue at least one file.');
            }
    
            return false;
        });
    });
    </script>
<style>
    #wpbody{
        background-image: url(<?php
            echo symbiostock_IMGDIR;
    ?>/bg/subtle_dots.png);    
        
        }
    </style>
<?php
            
        }
        // Add hook for admin <head></head>
        add_action( 'admin_head', 'uploader_head_js' );
        
    } //is_admin() && $_GET[ 'page' ] == 'symbiostock-upload-images'
}

//TEMPORARILY we remove the "new image' functionality until we put it in. For now, new images are created on upload.
function symbiostock_adjust_the_wp_menu() {
  
  //or for custom post type 'myposttype'.
  remove_submenu_page( 'edit.php?post_type=image', 'post-new.php?post_type=image' );  
}
add_action( 'admin_menu', 'symbiostock_adjust_the_wp_menu', 999 );
//add rewrite rules
add_action( 'init', 'symbiostock_rewrite' );
function symbiostock_rewrite( )
{    
    global $wp_rewrite;
    $wp_rewrite->add_permastruct('typename','typename/%year%%postname%/' , true , 1);
    add_rewrite_rule('typename/([0-9]{4})/(.+)/?$','index.php?typename=$matches[2]', 'top');
    $wp_rewrite->flush_rules();
}
?>