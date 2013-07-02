<div id="symbiostock_admin">
    <div id="wpbody-content" tabindex="0" aria-label="Main content">
        <h1>Process Images</h1>
        
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
                    
                        <option selected="selected" value="1">GREEN</option>
                        
                        <option value="2">YELLOW</option>
                        
                        <option value="3">RED</option>  
                        
                    </select>
                    <select name="action">
                    
                        <option selected="selected" value="process_drafts">Process to Drafts</option>
                        
                        <option value="process_publish">Process to Publish</option>
                        
                        <option value="delete">Delete Selected Files</option>
                        
                        <option value="delete_all">Delete ALL Files</option>
                        
                    </select>
                                 
                     <input type="submit" value="Process Images" class="button action" id="doaction" name="">
                     
                     <p>
                     <?php echo sshelp('rating', 'Rating Filter'); ?><br />
                     * NOTE: Images without IPTC data (at least <strong>title</strong> and <strong>keywords</strong>) are always saved as drafts.</p>
                     
                </div>
                
            </div>
            
        </form>
        
    </div>
    
</div>
