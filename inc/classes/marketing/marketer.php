<hr />
<br />
<?php
//marketer 1 or 0 1
if(isset($_POST['symbiostock_marketer'])){ 
    update_option( 'use_symbiostock_marketer', $_POST[ 'symbiostock_marketer' ] );
}
$symbiostock_marketer = get_option('use_symbiostock_marketer');
$symbiostock_marketer == '1' || !isset($symbiostock_marketer)  ? $symbiostock_marketer_1 = 'selected="selected"' : $symbiostock_marketer_1 = '';
$symbiostock_marketer == '0' ? $symbiostock_marketer_0 = 'selected="selected"' : $symbiostock_marketer_0 = '';
//model release Yes / No / N/A
?>
<select id="symbiostock_marketer"  name="symbiostock_marketer">
    <option <?php echo $symbiostock_marketer_1; ?> value="1"><?php _e( 'Marketer ON', 'symbiostock') ?></option>
    <option <?php echo $symbiostock_marketer_0; ?> value="0"><?php _e( 'Marketer OFF', 'symbiostock') ?></option>
</select> 

<?php echo sshelp('marketing_services', __('Important: About Marketing Services', 'symbiostock')); ?>
<br />
<br />

<?php
if( use_symbiostock_marketer( ) || $_POST['symbiostock_marketer'] == 1 ):

?>

<h2><?php _e( 'Marketer Options', 'symbiostock') ?></h2>

<?php

if($_POST['generate_marketer_number'] == 1 && $_POST['marketer_are_sure'] == 1){

    $marketer_user_number = mt_rand(10000000, 999999999);
    
    update_option('marketer_user_number', $marketer_user_number);
    ?><h2><?php _e( 'Marketer user number regenerated. Please update your marketer account.', 'symbiostock') ?></h2><?php
    
}

?>
<br />

<div class="postbox">
    <div class="inside">
    
<label for="generate_marketer_number"><input id="generate_marketer_number" type="checkbox" name="generate_marketer_number" value="1" /> <?php _e( 'Generate marketer user number.', 'symbiostock') ?></label>

<label for="marketer_are_sure"><input id="marketer_are_sure" type="checkbox" name="marketer_are_sure" value="1" /> <?php _e( 'Are you sure?', 'symbiostock') ?></label>
<br />
<br />

<p class="description"><?php _e( 'This "user number" acts as a key between you and marketer. <strong>Do not share it with anyone besides marketer</strong>.<br />
 It provides access to small unwatermarked preview images as well for their system\'s fingerprinting and preview generating.</p>', 'symbiostock') ?>

<hr />
        <h2>
        <label for="marketer_user_number" class=""><?php _e( 'Marketer user number:', 'symbiostock') ?> 
            <input style="text-align: center;" onClick="this.select();" id="marketer_user_number" name="marketer_user_number" value="<?php echo 'ss-' . get_option('marketer_user_number', __('Please generate #...', 'symbiostock')); ?>" type="text" />
        </label>        
        </h2>
        <br />
        
<hr />
    <ul>
    <li><?php $marketing_link = get_bloginfo('url') . '?ss-' . get_option('marketer_user_number', '88888888') . '=xml&type=all'; ?></li>
    <li><?php _e( 'Marketing services base link:', 'symbiostock') ?><a target="_blank" title="<?php _e( 'Marketing link', 'symbiostock') ?>" href="<?php echo $marketing_link  ?>"><?php echo $marketing_link  ?></a></li>
    <li><?php _e( 'Fetched from given date: ', 'symbiostock') ?> <strong><?php echo $marketing_link = get_bloginfo('url') . '?ss-' . get_option('marketer_user_number', '88888888') . '=xml&date=DD-MM-YYYY'; ?></strong></li>
    </ul>
    
            
    </div>
</div>
<hr />
<?php
endif;
?>