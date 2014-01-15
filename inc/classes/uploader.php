<div id="symbiostock_uploader_container">
    <h1><?php _e( 'Symbiostock Image Uploader', 'symbiostock') ?></h1>
    <form>
        <div id="uploader">
            <p><?php _e( 'You browser doesn\'t have Flash, Silverlight, Gears, BrowserPlus or HTML5 support.', 'symbiostock') ?></p>
        </div>
    </form>
    <div class="info">    
    <p><?php _e( '<em>Want to use <strong>FTP</strong></em>? Here is the uploads folder:', 'symbiostock') ?>  <input size="140" type="text" onClick="this.select();" value="<?php echo WP_CONTENT_DIR . "/plupload/uploads/"; ?>" /></p>
    
    <h3><?php _e( 'Uploading Directions <strong>( .jpg .eps .png .zip )</strong>', 'symbiostock') ?></h3>
    
    <?php _e( 'For best results, upload in batches of less than 40 or 50.', 'symbiostock') ?>
       
        <?php _e( '<strong>Shift+drag</strong> to select groups of files, or <strong>CTRL+click</strong> to select individual files one by one.</p>', 'symbiostock') ?>
    
<?php _e( '    <p><strong>EPS</strong>(vector) files must be accompanied by high-res <strong>JPEG</strong> or <strong>PNG</strong> files with <em>the same name</em>. Example:
    <ul>
        <li><strong>my_stock_art</strong>.eps</li>
        <li><strong>my_stock_art</strong>.jpg        
        </li>
    </ul>
    </p>', 'symbiostock') ?>
    <hr />
    <h4><?php _e( 'When uploading is completed...', 'symbiostock') ?></h4>
    <p><?php _e( '...proceed to', 'symbiostock') ?> <strong><a title="<?php _e( 'Process files', 'symbiostock') ?>" href="<?php echo get_home_url(); ?>/wp-admin/edit.php?post_type=image&page=symbiostock-process-images"><?php _e( 'Process Images', 'symbiostock') ?></a></strong> <?php _e( ' to process them.', 'symbiostock') ?></p>
    
    </div>
</div>
