    <h1><?php _e( 'Author Default Settings and Pricing', 'symbiostock') ?></h1>
<?php
if(isset($_POST['symbiostock_update_images'])){    
    symbiostock_update_all_images();    
    $symbiostock_edited_all_images = '<p><em>'.__('Site images updated.', 'symbiostock') . '</em></p>';    
}    

settings_fields( 'symbiostock_settings_group' ); 

symbiostock_settings_and_pricing();
?>
<label for="symbiostock_save_defaults"><input id="symbiostock_save_defaults" type="checkbox" name="symbiostock_save_defaults" value="1" /> 
<strong><?php _e( 'Save as defaults?', 'symbiostock') ?></strong> <span class="description"><?php _e( '(Will apply to uploads as well).', 'symbiostock') ?></span></label> 
<br /><br />
<label for="symbiostock_update_images">
<input value="1" id="symbiostock_update_images" type="checkbox" name="symbiostock_update_images" />

<?php _e( 'Update ALL existing images with new values? *Caution!*', 'symbiostock') ?></label>
<br />


<?php
//if image update occured, notify user 
if(isset($symbiostock_edited_all_images)){echo $symbiostock_edited_all_images;} 

?>