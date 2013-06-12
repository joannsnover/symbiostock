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
    <option <?php echo $symbiostock_marketer_1; ?> value="1">Marketer ON</option>
    <option <?php echo $symbiostock_marketer_0; ?> value="0">Marketer OFF</option>
</select> 

<?php echo sshelp('marketing_services', 'Important: About Marketing Services'); ?>
<br />
<br />

<?php
if( use_symbiostock_marketer( ) || $_POST['symbiostock_marketer'] == 1 ):
?>

<h2>Marketing Services</h2>

<?php

if($_POST['generate_marketer_number'] == 1 && $_POST['marketer_are_sure'] == 1){

	$marketer_user_number = mt_rand(10000000, 999999999);
	
	update_option('marketer_user_number', $marketer_user_number);
	?><h2>Marketer user number regenerated. Please update your marketer account.</h2><?php
	
}

?>
<br />

<div class="postbox">
    <div class="inside">
    
<label for="generate_marketer_number"><input id="generate_marketer_number" type="checkbox" name="generate_marketer_number" value="1" /> Generate marketer user number.</label>

<label for="marketer_are_sure"><input id="marketer_are_sure" type="checkbox" name="marketer_are_sure" value="1" /> Are you sure?</label>
<br />
<br />

<p class="description">This "user number" acts as a key between you and marketer. <strong>Do not share it with anyone besides marketer</strong>.<br />
 It provides access to small unwatermarked preview images as well for their system's fingerprinting and preview generating.</p>

<hr />
        <h2>
        <label for="marketer_user_number" class="">marketer user number: 
            <input style="text-align: center;" onClick="this.select();" id="marketer_user_number" name="marketer_user_number" value="<?php echo 'ss-' . get_option('marketer_user_number', 'Please generate #...'); ?>" type="text" />
        </label>        
        </h2>
        <br />
        
<hr />
	<ul>
    <li>Go to <a title="Edit images..." href="<?php echo get_bloginfo('url') . '/wp-admin/edit.php?post_type=image'; ?>">Editing area</a>, select "<strong>Make Promo Preview</strong>" dropdown, select images you wish to promote, and "apply" changes.</li>
	<?php $marketing_link = get_bloginfo('url') . '?ss-' . get_option('marketer_user_number', '88888888') . '=xml&type=all'; ?>
    <li>Marketing services base link: <a target="_blank" title="marketing link" href="<?php echo $marketing_link  ?>"><?php echo $marketing_link  ?></a></li>
    <li>Fetched from given date: <strong><?php echo $marketing_link = get_bloginfo('url') . '?ss-' . get_option('marketer_user_number', '88888888') . '=xml&date=DD-MM-YYYY'; ?></strong></li>
    </ul>
    
            
    </div>
</div>
<hr />
<?php
endif;
?>