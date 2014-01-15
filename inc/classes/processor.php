<div id="symbiostock_admin">
    <div id="wpbody-content" tabindex="0" aria-label="Main content">
        <h1><?php _e( 'Process Images', 'symbiostock') ?></h1>
        
        <form id="symbiostock_admin_form" action="" method="post" >
        
            <?php 
include_once( 'image-processor/symbiostock_image_processor.php' );
$symbiostock_images = new symbiostock_image_processor();
isset($_POST['action']) && $_POST['action'] == 'delete' ? $symbiostock_images->delete_images() : '';
isset($_POST['action']) && $_POST['action'] == 'delete_all' ? $symbiostock_images->delete_all_files( 'uploads' ) : '';
isset($_POST['action']) && $_POST['action'] == 'process_publish' || isset($_POST['action']) &&  $_POST['action'] == 'process_drafts' ? $symbiostock_images->process() : '';
$symbiostock_images->list_images();
?>                        
            <div class="tablenav bottom">
                        
                <div class="alignleft actions">
                   
                
                   <select name="symbiostock_rating">
                        
                        <option selected="selected" value="0"> - </option>
                        
                        <option value="1"><?php _e( 'GREEN', 'symbiostock') ?></option>
                        
                        <option value="2"><?php _e( 'YELLOW', 'symbiostock') ?></option>
                        
                        <option value="3"><?php _e( 'RED', 'symbiostock') ?></option>  
                        
                    </select>
                    <select name="action">
                    
                        <option selected="selected" value="process_drafts"><?php _e( 'Process to Drafts', 'symbiostock') ?></option>
                        
                        <option value="process_publish"><?php _e( 'Process to Publish', 'symbiostock') ?></option>
                        
                        <option value="delete"><?php _e( 'Delete Selected Files', 'symbiostock') ?></option>
                        
                        <option value="delete_all"><?php _e( 'Delete ALL Files', 'symbiostock') ?></option>
                        
                    </select>
                                 
                     <input type="submit" value="<?php _e( 'Process Images', 'symbiostock') ?>" class="button action" id="doaction" name="">
                     
                     <p>
                     <?php echo sshelp('rating', __('Rating Filter', 'symbiostock')); ?><br />
                     <?php _e( '* NOTE: Images without IPTC data (at least <strong>title</strong> and <strong>keywords</strong>) are always saved as drafts.', 'symbiostock') ?></p>
                     
                </div>
                
            </div>
            
        </form>
        
    </div>
    
</div>
