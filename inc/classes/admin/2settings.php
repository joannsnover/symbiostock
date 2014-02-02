<?php
settings_fields( 'symbiostock_settings_group' ); 
if(isset($_POST['symbiostock_logo_link'])){ update_option('symbiostock_logo_link', $_POST['symbiostock_logo_link']); }
    
if(isset($_POST['symbiostock_login_logo_link'])){ update_option('symbiostock_login_logo_link', $_POST['symbiostock_login_logo_link']); }
if(isset($_POST['symbiostock_watermark_link'])){ update_option('symbiostock_watermark_link', $_POST['symbiostock_watermark_link']); }
if(isset($_POST['symbiostock_paypal_email'])){ update_option('symbiostock_paypal_email', $_POST['symbiostock_paypal_email']); }
if(isset($_POST['symbiostock_correspondence_email'])){ update_option('symbiostock_correspondence_email', $_POST['symbiostock_correspondence_email']); }
if(isset($_POST['symbiostock_customer_area_greeting'])){ update_option('symbiostock_customer_area_greeting', $_POST['symbiostock_customer_area_greeting']); }
if(isset($_POST['symbiostock_customer_welcome_body'])){ update_option('symbiostock_customer_welcome_body', $_POST['symbiostock_customer_welcome_body']); }
if(isset($_POST['symbiostock_customer_thank_you'])){ update_option('symbiostock_customer_thank_you', $_POST['symbiostock_customer_thank_you']); }
if(isset($_POST['symbiostock_eula_page'])){ update_option('symbiostock_eula_page', $_POST['symbiostock_eula_page']); }
if(isset($_POST['symbiostock_logo_for_paypal'])){ update_option('symbiostock_logo_for_paypal', $_POST['symbiostock_logo_for_paypal']); }
if(isset($_POST['symbiostock_copyright_name'])){ update_option('symbiostock_copyright_name', $_POST['symbiostock_copyright_name']); }
//symbiostock_paypal_live_or_sandbox
if(isset($_POST['symbiostock_paypal_live_or_sandbox'])){ 
update_option('symbiostock_paypal_live_or_sandbox', $_POST['symbiostock_paypal_live_or_sandbox']); 
}
$symbiostock_paypal_live_or_sandbox = get_option('symbiostock_paypal_live_or_sandbox');
$symbiostock_paypal_live_or_sandbox == 'sandbox' || !isset($symbiostock_paypal_live_or_sandbox)  ? $symbiostock_paypal_sandbox = 'checked="checked"' : $symbiostock_paypal_sandbox = '';
$symbiostock_paypal_live_or_sandbox == 'live' ? $symbiostock_paypal_live = 'checked="checked"' : $symbiostock_paypal_live = '';
//symbiostock_credit_links
if(isset($_POST['symbiostock_credit_links'])){ 
update_option('symbiostock_credit_links', $_POST['symbiostock_credit_links']); 
}
$symbiostock_credit_links = get_option('symbiostock_credit_links');
$symbiostock_credit_links == 'product_page' || !isset($symbiostock_credit_links)  ? $symbiostock_credit_links_product_page = 'checked="checked"' : $symbiostock_credit_links_product_page = '';
$symbiostock_credit_links == 'footer' ? $symbiostock_credit_links_footer = 'checked="checked"' : $symbiostock_credit_links_footer = '';
$symbiostock_credit_links == 'no_use' ? $symbiostock_credit_links_no_use = 'checked="checked"' : $symbiostock_credit_links_no_use = '';
//symbiostock_currency
if(isset($_POST['symbiostock_currency'])){ 
update_option('symbiostock_currency', $_POST['symbiostock_currency']); 
}
$symbiostock_currency = get_option('symbiostock_currency');
$symbiostock_currency == 'USD' || !isset($symbiostock_currency)  ? $symbiostock_currency_USD = 'selected="selected"' : $symbiostock_currency_USD = '';
$symbiostock_currency == 'EUR' ? $symbiostock_currency_EUR = 'selected="selected"' : $symbiostock_currency_EUR = '';
$symbiostock_currency == 'GBP' ? $symbiostock_currency_GBP = 'selected="selected"' : $symbiostock_currency_GBP = '';
$symbiostock_currency == 'CAD' ? $symbiostock_currency_CAD = 'selected="selected"' : $symbiostock_currency_CAD = '';
$symbiostock_currency == 'JPY' ? $symbiostock_currency_JPY = 'selected="selected"' : $symbiostock_currency_JPY = '';
//symbiostock_theme_credit
if(isset($_POST['symbiostock_theme_credit'])){ 
update_option('symbiostock_theme_credit', $_POST['symbiostock_theme_credit']); 
}
$symbiostock_theme_credit = get_option('symbiostock_theme_credit', '');
$symbiostock_theme_credit == 'on' || !isset($symbiostock_theme_credit)  ? $symbiostock_theme_credit_on = 'checked="checked"' : $symbiostock_theme_credit_on = '';
$symbiostock_theme_credit == 'off' ? $symbiostock_theme_credit_off = 'checked="checked"' : $symbiostock_theme_credit_off = '';
//watermark
$watermark = symbiostock_CLASSDIR . '/image-processor/symbiostock-watermark.png';
$logo = get_option('symbiostock_logo_link', symbiostock_LOGO  );
if(empty($logo)){
    update_option('symbiostock_logo_link',  symbiostock_LOGO);
    }
global $current_user;
      get_currentuserinfo();
?>
<p><?php _e( 'Customize your site\'s appearance here:', 'symbiostock') ?> <a title="<?php _e( 'Customize', 'symbiostock') ?>" href="<?php echo get_home_url() ?>/wp-admin/customize.php"><?php _e( 'Customize Symbiostock', 'symbiostock') ?></a></p>
<table class="widefat symbiostock-settings">
    <thead>
        <tr>
            <th colspan="2"> <strong><?php _e( 'Branding and Image', 'symbiostock') ?></strong> - <?php _e( 'Paste URLS from ', 'symbiostock') ?><a title="<?php _e( 'Media Uploader', 'symbiostock') ?>" href="<?php echo get_home_url(); ?>/wp-admin/upload.php"><?php _e( 'Media uploader', 'symbiostock') ?></a> <?php _e( 'below', 'symbiostock') ?>. <br />
                           
        </tr>
    </thead>
    
    <?php do_action('ss_settings_table_top') ?>
    
    <tr>
        <th scope="row"><?php _e( 'Header Logo', 'symbiostock') ?><br /><?php echo sshelp('header_logo', __( 'Header Logo', 'symbiostock')); ?></th>
        <td><input class="symbiostock_settings" type="text" name="symbiostock_logo_link"  id="symbiostock_logo_link" value="<?php echo get_option('symbiostock_logo_link', symbiostock_LOGO  ); ?>" />
    </td>
    </tr>
    <tr>
        <th scope="row"><?php _e( 'Login Page Logo', 'symbiostock') ?><br /> <?php echo sshelp('login_page_logo', __( 'Login Page Logo', 'symbiostock')); ?></th>
        <td><input class="symbiostock_settings" type="text" name="symbiostock_login_logo_link"  id="symbiostock_login_logo_link" value="<?php echo get_option('symbiostock_login_logo_link', symbiostock_IMGDIR . '/site-login-logo.png'  ); ?>" />
        </td>
    </tr>
    <tr>
        <th scope="row"><?php _e( 'Watermark Image', 'symbiostock') ?><br /> <?php echo sshelp('watermark_image', __( 'Watermark Image', 'symbiostock')); ?></th>
        <td><input class="symbiostock_settings" type="text" name="symbiostock_watermark_link"  id="symbiostock_watermark_link" value="<?php echo get_option('symbiostock_watermark_link', $watermark ); ?>" />
            <br />
        </td>
    </tr>
    <thead>
        <tr>
            <th colspan="2"> <strong><?php _e( 'Email and Payment Process Communication', 'symbiostock') ?></strong></th>
        </tr>
    </thead>
    <tr>
        <th scope="row"><?php _e( 'Paypal Email & IPN ', 'symbiostock') ?><br />
            <br />
            <label for="symbiostock_paypal_live">
                <input type="radio" id="symbiostock_paypal_live" name="symbiostock_paypal_live_or_sandbox" <?php echo $symbiostock_paypal_live; ?> value="live" />
                <a target="_blank" title="<?php _e( 'Paypal', 'symbiostock') ?>" href="https://www.paypal.com/">Live</a> </label>
            <label for="symbiostock_paypal_sandbox">
                <input type="radio" id="symbiostock_paypal_sandbox" name="symbiostock_paypal_live_or_sandbox" <?php echo $symbiostock_paypal_sandbox ; ?> value="sandbox" />
                <a target="_blank" title="<?php _e( 'Paypal Sandbox', 'symbiostock') ?>" href="https://www.sandbox.paypal.com//"><?php _e( 'Sandbox (testing)', 'symbiostock') ?></a> </label>
                <br /><br /><br /> <?php echo sshelp('paypal', __('Paypal help.', 'symbiostock')); ?> 
        </th>
        <td><label for="symbiostock_paypal_email">
                <input class="symbiostock_settings" type="text" name="symbiostock_paypal_email"  id="symbiostock_paypal_email" value="<?php echo get_option('symbiostock_paypal_email'); ?>" />
                <?php _e( 'Paypal Email', 'symbiostock') ?></label>
            <br />
            <br />
            <label for="symbiostock_ipn"><strong>
                <input onClick="this.select();" id="symbiostock_ipn" class="symbiostock_settings" type="text"  value="<?php echo get_bloginfo('url') . '/symbiostock_ipn/'; ?>" />
                </strong><?php _e( 'Paypal IPN', 'symbiostock') ?></label>
            <br />
            <br />
            <label for="symbiostock_logo_for_paypal">
                <input class="symbiostock_settings" type="text" name="symbiostock_logo_for_paypal"  id="symbiostock_logo_for_paypal" value="<?php echo get_option('symbiostock_logo_for_paypal'); ?>" />
                <?php _e( 'Site Paypal Logo', 'symbiostock') ?></label>
            <br />
            <?php _e( '*Your logo seen on the paypal site when the customer is paying. {Exactly 150px by 50px}. ', 'symbiostock') ?>
            
            <!--CURRENCY--> 
            <br />
            <label for="symbiostock_currency">
                <?php
            $symbiostock_currencies = array(
                'USD' => __( 'US Dollars $', 'symbiostock' ),
                'EUR' => __( 'Euros €', 'symbiostock' ),
                'GBP' => __( 'Pounds Sterling £', 'symbiostock' ),
                'CAD' => __( 'Canadian Dollars $', 'symbiostock' ),
                'JPY' => __( 'Japanese Yen ¥', 'symbiostock' ),
            );
            ?>
                <select id="symbiostock_currency" name="symbiostock_currency">
                    <?php
            foreach ($symbiostock_currencies as $currency => $desc){
                    echo 'symbiostock_currency_'.$currency;
                    $symbiostock_currency_selected = ${'symbiostock_currency_'.$currency};                    
                    ?>
                    <option <?php echo $symbiostock_currency_selected; ?> value="<?php echo $currency ?>" ><?php echo $desc; ?></option>
                    <?php    
                }
            ?>
                </select>
                - <?php _e( 'Shop Currency', 'symbiostock') ?> </label>
            
            <!--/CURRENCY--></td>
    </tr>
    <tr>
        <th scope="row"><?php _e( 'Correspondence Email', 'symbiostock') ?> <br /> <?php echo sshelp('email', __('Email', 'symbiostock') ); ?> </th>
        <td><input class="symbiostock_settings" type="text" name="symbiostock_correspondence_email"  id="symbiostock_correspondence_email" value="<?php echo get_option('symbiostock_correspondence_email', $current_user->user_email); ?>" />
        </td>
    </tr>
    <tr>
        <th scope="row"><?php _e( 'Customer Area Greeting', 'symbiostock') ?></th>
        <td><textarea class="symbiostock_settings" name="symbiostock_customer_area_greeting"  id="symbiostock_customer_area_greeting"><?php echo stripslashes(get_option('symbiostock_customer_area_greeting')); ?></textarea>
            <br />
            <?php _e( 'Welcome your customer, talk about stuff', 'symbiostock') ?> - <?php echo symbiostock_customer_area( __('Top of Customer Area.', 'symbiostock') ); ?> <?php _e( 'HTML Allowed', 'symbiostock') ?></td>
    </tr>
    <tr>
        <th scope="row"><?php _e( 'Customer Welcome Email', 'symbiostock') ?> <br /> <?php echo sshelp('email', __( 'Email', 'symbiostock')); ?> </th>
        <td><textarea class="symbiostock_settings" name="symbiostock_customer_welcome_body"  id="symbiostock_customer_welcome_body"><?php echo stripslashes(get_option('symbiostock_customer_welcome_body')); ?></textarea>
        </td>
    </tr>
    <tr>
        <th scope="row"><?php _e( 'Customer Thank You Email', 'symbiostock') ?> <br /> <?php echo sshelp('email', __( 'Email', 'symbiostock') ); ?> </th>
        <td><textarea class="symbiostock_settings" name="symbiostock_customer_thank_you"  id="symbiostock_customer_thank_you"><?php echo stripslashes(get_option('symbiostock_customer_thank_you')); ?></textarea>
        </td>
    </tr>
    
    <?php do_action('ss_settings_table_bottom') ?>
    
    <thead>
        <tr>
            <th colspan="2"> <?php _e( 'Credit Links', 'symbiostock') ?> -- <?php echo sshelp('credit_links', __( 'About Credit Links', 'symbiostock')); ?> </th>
                	        	
        </tr>
    </thead>
    <tr>
        <td colspan="2"><label for="symbiostock_credit_links_1">
                <input type="radio" id="symbiostock_credit_links_1" name="symbiostock_credit_links" <?php echo $symbiostock_credit_links_product_page; ?> value="product_page" />
                <strong><?php _e( 'Product Page (Suggested)', 'symbiostock') ?></strong></label>
            <br />
            <label for="symbiostock_credit_links_2">
                <input type="radio" id="symbiostock_credit_links_2" name="symbiostock_credit_links" <?php echo $symbiostock_credit_links_footer ; ?> value="footer" />
                <?php _e( 'In Footer', 'symbiostock') ?> </label>
            <br />
            <label for="symbiostock_credit_links_3">
                <input type="radio" id="symbiostock_credit_links_3" name="symbiostock_credit_links" <?php echo $symbiostock_credit_links_no_use ; ?> value="no_use" />
                <?php _e( 'Do Not Use', 'symbiostock') ?></label>
            <br />
            <br />
            <?php _e( 'Backlinks to <a title="clipartillustration.com" href="http://www.clipartillustration.com/">Leo Blanchette\'s main selling website</a>, <a title="ClipArtOf.com" href="http://www.clipartof.com/">ClipArtOf.com</a> (Special thanks to Kenny and Jamie), and <a title="Microstockgroup.com" href="http://www.microstockgroup.com/">MicrostockGroup.com</a>, who hosts the Symbiostock knowledge forums.<br />
            This is one of the few ways I profit on this theme, so I thank you for keeping this live.', 'symbiostock') ?>
            
            <br /><br />
            
            <label for="symbiostock_theme_credit_on">
                <input type="radio" id="symbiostock_theme_credit_on" name="symbiostock_theme_credit" <?php echo $symbiostock_theme_credit_on; ?> value="on" />
                <?php _e( 'Wordpress / Theme Credit', 'symbiostock') ?> <strong><?php _e( 'ON', 'symbiostock') ?></strong></label>
            <br />
            <label for="symbiostock_theme_credit_off">
                <input type="radio" id="symbiostock_theme_credit_off" name="symbiostock_theme_credit" <?php echo $symbiostock_theme_credit_off ; ?> value="off" />
                <?php _e( 'Wordpress / Theme Credit', 'symbiostock') ?>  <strong><?php _e( 'OFF', 'symbiostock') ?></strong></label>
            
            </td>
            
           </tr>
    
           
        <tr>
        <th scope="row"><?php _e( 'Footer Copyright Info', 'symbiostock') ?></th>
        <td><label><?php _e( 'Copyright Name', 'symbiostock') ?>: <input type="text" class="symbiostock_settings" name="symbiostock_copyright_name"  id="symbiostock__copyright_name" value="<?php echo stripslashes(get_option('symbiostock_copyright_name', '')); ?>" /></label>          
        </td>
    </tr>
</table>
