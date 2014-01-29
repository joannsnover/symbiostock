<!-- Modal -->

<?php global $ss_sizenames ?>

<div id="symbiostock_display_preview" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 id="search_selection_title">Modal Header</h3>
                 <p><?php _e('By', 'symbiostock') ?> <span id="search_selection_author"><?php _e('Someone', 'symbiostock') ?></span><p>
            </div>
            <div class="modal-body"><div class="modal-preview symbiostock_loader"><img class="img-responsive" onload="$(this).fadeIn();" id="search_selection_symbiostock_preview" alt="<?php _e('Image Preview', 'symbiostock') ?>" src="<?php echo symbiostock_IMGDIR . '/loading-large.gif' ?>" /></div>
                <table class="table">
                    <thead>
                        <tr>
                            <th> <?php _e('Size', 'symbiostock') ?> </th>
                            <th> <?php _e('File Info', 'symbiostock') ?> </th>
                            <th> <?php _e('Price', 'symbiostock') ?> </th>
                        </tr>
                    </thead>
                    <tr>
                        <td><?php echo $ss_sizenames['bloggee'] ?></td>
                        <td id="search_selection_bloggee">-</td>
                        <td id="search_selection_price_bloggee">-</td>
                    </tr>
                    <tr>
                        <td><?php echo $ss_sizenames['small'] ?></td>
                        <td id="search_selection_small">-</td>
                        <td id="search_selection_price_small">-</td>
                    </tr>
                    <tr>
                        <td><?php echo $ss_sizenames['medium'] ?></td>
                        <td id="search_selection_medium">-</td>
                        <td id="search_selection_price_medium">-</td>
                    </tr>
                    <tr>
                        <td><?php echo $ss_sizenames['large'] ?></td>
                        <td id="search_selection_large">-</td>
                        <td id="search_selection_price_large">-</td>
                    </tr>
                    <tr class="info"><td><strong><?php _e('Filetypes', 'symbiostock') ?></strong></td><td id="search_selection_extensions">-</td><td>&nbsp;</td></tr>
                </table>
                <em><?php _e('Go to image page for more details...', 'symbiostock') ?></em>
            </div>
            <div class="modal-footer">
                <a data-toggle="tooltip" class="modal_control ssref" id="search_selection_permalink" title="<?php _e('Go to image page for more details...', 'symbiostock') ?>" href="#"><i class="icon-share-alt"> &nbsp;</i></a>
                <a class="modal_control" aria-hidden="true" data-dismiss="modal" title="<?php _e('Close Window', 'symbiostock') ?>" href="#"><i class="icon-remove"> &nbsp;</i></a>
            </div>
        </div>
    </div>
</div>