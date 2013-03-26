<?php 
include_once(symbiostock_CLASSROOT . '/paypal.php');
function launch_pad_update_carousel(){
	
	//this function updates or deletes carousel options based on what was submitted.
	
	$option_count = 0;
	while ($option_count <= 9){
		if(isset($_POST['edit_values']) && !empty($_POST['launch_pad_slide_image_' . $option_count])){
			
			update_option( 'launch_pad_slide_image_' . $option_count, $_POST['launch_pad_slide_image_' . $option_count] );
			
		}  elseif (isset($_POST['edit_values']) && !isset($_POST['launch_pad_slide_image_' . $option_count]))
		
		{ 
		
		delete_option( 'launch_pad_slide_image_' . $option_count, $_POST['launch_pad_slide_image_' . $option_count] ); }
		
		if( isset($_POST['edit_values']) && !empty($_POST['launch_pad_slide_description_' . $option_count])){
			
			update_option( 'launch_pad_slide_description_' . $option_count, $_POST['launch_pad_slide_description_' . $option_count] );
			
		} elseif (isset($_POST['edit_values']) && !isset($_POST['launch_pad_slide_description_' . $option_count]))
		
		{
		
		delete_option( 'launch_pad_slide_description_' . $option_count, $_POST['launch_pad_slide_description_' . $option_count] ); }
		
	$option_count++;
		
	}
	
}
launch_pad_update_carousel()
?>
<p>Settings for front page Carousel.</p>
<p>This is featured stock images slider which is still in development.</p>
<div class="launch_pad_slider">
    <table width="20%" class="widefat wp-list-table" cellspacing="0" summary="Input links to images and the descriptions for them. They will show up on the front page as you've designed them.">
        <thead>
            <tr>
                <th>Slide Image Link</th>
                <th>Slide Short Description</th>
            </tr>
        </thead>
        <?php
$slide_count = 0;
$blank_count = 0;
while($slide_count <= 9){
	
	$launch_pad_slide_image =  get_option('launch_pad_slide_image_' . $slide_count);
	$launch_pad_slide_description =  get_option('launch_pad_slide_description_' . $slide_count);
	
	$alt = ( $slide_count % 2 ) ? '' : 'alternate';
	
		if(!empty($launch_pad_slide_image) || $blank_count < 2){
			
			?>
				<tr class="slider-data <?php echo $alt; ?>">
					<td><label for="<?php echo 'launch_pad_slide_image_' . $slide_count; ?>">link:
							<input id="<?php echo 'launch_pad_slide_image_' . $slide_count; ?>" value="<?php echo $launch_pad_slide_image; ?>" type="text" name="<?php echo 'launch_pad_slide_image_' . $slide_count; ?>" />
						</label></td>
					<td><label for="<?php echo 'launch_pad_slide_description_' . $slide_count; ?>">short desc:
							<input id="<?php echo 'launch_pad_slide_description_' . $slide_count; ?>" value="<?php echo $launch_pad_slide_description; ?>" type="text" name="<?php echo 'launch_pad_slide_description_' . $slide_count; ?>" />
							<a id="remove" href="#"> - </a></label></td>
				</tr>
				<?php
				
		$blank_count++;		
		
		}
		
		$slide_count++;
}
?>
        <tfoot>
            <tr>
                <td>&nbsp;<input type="hidden" name="edit_values" value="" /></td>
                <td><a id="add_row" href="#">+</a></td>
            </tr>
        </tfoot>
    </table>
    <p>Upload slide images to <a title="Media Uploader" href = "/wp-admin/media-new.php">media uploader</a> then paste links into list below. Add description if desired. (Max 10)</p>
</div>
