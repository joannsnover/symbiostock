<!-- Modal -->
<div id="symbiostock_display_preview" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="search_selection_title">Modal header</h3>
         <p>by <span id="search_selection_author">Someone</span><p>
    </div>
    <div class="modal-body"><div class="modal-preview symbiostock_loader"><img onload="$(this).fadeIn();" id="search_selection_symbiostock_preview" alt="Image Preview" src="<?php echo symbiostock_IMGDIR . '/loading-large.gif' ?>" /></div>
        <table class="table">
            <thead>
                <tr>
                    <th> Size </th>
                    <th> File Info </th>
                    <th> Price </th>
                </tr>
            </thead>
            <tr>
                <td>Bloggee</td>
                <td id="search_selection_bloggee">-</td>
                <td id="search_selection_price_bloggee">-</td>
            </tr>
            <tr>
                <td>Small</td>
                <td id="search_selection_small">-</td>
                <td id="search_selection_price_small">-</td>
            </tr>
            <tr>
                <td>Medium</td>
                <td id="search_selection_medium">-</td>
                <td id="search_selection_price_medium">-</td>
            </tr>
            <tr>
                <td>large</td>
                <td id="search_selection_large">-</td>
                <td id="search_selection_price_large">-</td>
            </tr>
            <tr class="info"><td><strong>Filetypes</strong></td><td id="search_selection_extensions">-</td><td>&nbsp;</td></tr>
        </table>
        <em>Go to image page for more details...</em>
    </div>
    <div class="modal-footer">
    	<a <?php echo SSREF; ?> data-toggle="tooltip" class="modal_control" id="search_selection_permalink" title="Go to image page..." href="#"><i class="icon-share-alt"> &nbsp;</i></a>
        <a class="modal_control" aria-hidden="true" data-dismiss="modal" title="Close window" href="#"><i class="icon-remove"> &nbsp;</i></a>
    </div>
</div>