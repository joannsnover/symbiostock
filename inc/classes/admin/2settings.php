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
//symbiostock_paypal_live_or_sandbox
if(isset($_POST['symbiostock_paypal_live_or_sandbox'])){ 
update_option('symbiostock_paypal_live_or_sandbox', $_POST['symbiostock_paypal_live_or_sandbox']); 
}
$symbiostock_paypal_live_or_sandbox = get_option('symbiostock_paypal_live_or_sandbox');
$symbiostock_paypal_live_or_sandbox == 'sandbox' || !isset($symbiostock_paypal_live_or_sandbox)  ? $symbiostock_paypal_sandbox = 'checked="checked"' : $symbiostock_paypal_sandbox = '';
$symbiostock_paypal_live_or_sandbox == 'live' ? $symbiostock_paypal_live = 'checked="checked"' : $symbiostock_paypal_live = '';
//symbiostock_paypal_live_or_sandbox
if(isset($_POST['symbiostock_credit_links'])){ 
update_option('symbiostock_credit_links', $_POST['symbiostock_credit_links']); 
}
$symbiostock_credit_links = get_option('symbiostock_credit_links');
$symbiostock_credit_links == 'product_page' || !isset($symbiostock_credit_links)  ? $symbiostock_credit_links_product_page = 'checked="checked"' : $symbiostock_credit_links_product_page = '';
$symbiostock_credit_links == 'footer' ? $symbiostock_credit_links_footer = 'checked="checked"' : $symbiostock_credit_links_footer = '';
$symbiostock_credit_links == 'no_use' ? $symbiostock_credit_links_no_use = 'checked="checked"' : $symbiostock_credit_links_no_use = '';
//watermark
$watermark = symbiostock_CLASSDIR . '/image-processor/symbiostock-watermark.png';
$logo = get_option('symbiostock_logo_link', symbiostock_LOGO  );
if(empty($logo)){
	update_option('symbiostock_logo_link',  symbiostock_LOGO);
	}
global $current_user;
      get_currentuserinfo();
?>
<table class="widefat form-table symbiostock-settings">
	<thead>
        <tr>
            <th colspan="2">
            <strong>Branding and Image</strong> - Paste urls from <a title="Media Uploader" href="<?php echo get_home_url(); ?>/wp-admin/upload.php">media uploader</a> below. 
            <br /><br /><em>Need some templates? (<a title="Photoshop and Jpeg Symbiostock Branding Templates" href="https://github.com/orangeman555/symbiostock/blob/master/Symbiostock%20branding%20templates%20PSD%20and%20JPG.zip?raw=true">PSD and JPG</a> | <a title="AI and EPS Symbiostock Branding Templates" href="https://github.com/orangeman555/symbiostock/blob/master/Symbistock%20branding%20templates%20AI%20and%20EPS.zip?raw=true">AI and EPS</a>)</em>
            </th>
        </tr>
  	</thead>
    <tr>
        <th scope="row">Logo</th>
        <td><input class="symbiostock_settings" type="text" name="symbiostock_logo_link"  id="symbiostock_logo_link" value="<?php echo get_option('symbiostock_logo_link', symbiostock_LOGO  ); ?>" />
            <br />
           Leave empty for default Symbiostock logo. </td>
    </tr>
    
    <tr>
        <th scope="row">Login Page Logo</th>
        <td><input class="symbiostock_settings" type="text" name="symbiostock_login_logo_link"  id="symbiostock_login_logo_link" value="<?php echo get_option('symbiostock_login_logo_link', symbiostock_IMGDIR . '/site-login-logo.png'  ); ?>" />
            <br />
           Must be *exactly* 323 x 67px. Leave empty for default Symbiostock logo </td>
    </tr>  
      
    <tr>
        <th scope="row">Watermark Image</th>
        <td><input class="symbiostock_settings" type="text" name="symbiostock_watermark_link"  id="symbiostock_watermark_link" value="<?php echo get_option('symbiostock_watermark_link', $watermark ); ?>" />
            <br />
            Transparent PNG image (522 x 522px). If not supplied, default watermark (Symbiostock <strong>S</strong>) will be used.</td>
    </tr>
   
   
    <thead>
        <tr>
            <th colspan="2">
            <strong>Email and Payment Process Communication</strong>
            </th>
        </tr>
  	</thead>
    
     <tr>
        <th scope="row">Paypal Email &amp; IPN
        <br /><br />
        <label for="symbiostock_paypal_live">
        	<input type="radio" id="symbiostock_paypal_live" name="symbiostock_paypal_live_or_sandbox" <?php echo $symbiostock_paypal_live; ?> value="live" />
        	<a target="_blank" title="Paypal" href="https://www.paypal.com/">Live</a>
        </label>
        <label for="symbiostock_paypal_sandbox">
        	<input type="radio" id="symbiostock_paypal_sandbox" name="symbiostock_paypal_live_or_sandbox" <?php echo $symbiostock_paypal_sandbox ; ?> value="sandbox" />
			<a target="_blank" title="Paypal Sandbox" href="https://www.sandbox.paypal.com//">Sandbox (testing)</a>
        </label>
        </th>
        <td><label for="symbiostock_paypal_email"><input class="symbiostock_settings" type="text" name="symbiostock_paypal_email"  id="symbiostock_paypal_email" value="<?php echo get_option('symbiostock_paypal_email'); ?>" /> Paypal Email</label>
        <br />
             <label for="symbiostock_ipn"><strong><input id="symbiostock_ipn" class="symbiostock_settings" type="text"  value="<?php echo get_bloginfo( 'stylesheet_directory' ) . '/ipn/paypal_ipn.php'; ?>" /></strong> Paypal IPN</label>
             <br />*Ensure you've set up your paypal email and your <strong><a title="IPN Directions" href="https://www.paypal.com/ipn">paypal IPN</a></strong> properly, or your payment system will not work.
             <br />
            <label for="symbiostock_logo_for_paypal"><input class="symbiostock_settings" type="text" name="symbiostock_logo_for_paypal"  id="symbiostock_logo_for_paypal" value="<?php echo get_option('symbiostock_logo_for_paypal'); ?>" /> Site Paypal Logo</label>
            <br />*Your logo seen on the paypal site when the customer is paying. Exactly <strong>150px by 50px</strong>.
            </td>
    </tr>
     <tr>
        <th scope="row">Correspondence Email</th>
        <td><input class="symbiostock_settings" type="text" name="symbiostock_correspondence_email"  id="symbiostock_correspondence_email" value="<?php echo get_option('symbiostock_correspondence_email', $current_user->user_email); ?>" />
            <br />
            The email that will be used for automated communication and replies.</td>
    </tr>
    
     <tr>
        <th scope="row">Customer Area Greeting</th>
        <td><textarea class="symbiostock_settings" name="symbiostock_customer_area_greeting"  id="symbiostock_customer_area_greeting"><?php echo stripslashes(get_option('symbiostock_customer_area_greeting')); ?></textarea>
            <br />
           
            Welcome your customer, talk about stuff -  <?php echo symbiostock_customer_area( '<em>Top of Customer Area.</em>' ); ?> HTML allowed.</em></td>
    </tr>
    
    <tr>
    <th scope="row">Customer Welcome Email</th>
    <td><textarea class="symbiostock_settings" name="symbiostock_customer_welcome_body"  id="symbiostock_customer_welcome_body"><?php echo stripslashes(get_option('symbiostock_customer_welcome_body')); ?></textarea>
        <br />
        Body of the welcome email sent to the customer. Use <strong>&lt;br /&gt;</strong> for line breaks. </td>
    </tr>
    
    <tr>
        <th scope="row">Customer Thank You Email</th>
        <td><textarea class="symbiostock_settings" name="symbiostock_customer_thank_you"  id="symbiostock_customer_thank_you"><?php echo stripslashes(get_option('symbiostock_customer_thank_you')); ?></textarea>
            <br />
            Email sent to customer at purchase. Thank your customer. Use your name. Be human.</td>
    </tr>
    
    
    <thead>
        <tr>
            <th colspan="2">
            <strong>Licensing</strong>
            </th>
        </tr>
  	</thead>
    
     <tr>
        <th scope="row">See License Link</th>
        
        <?php		
		$eula_page_id = get_option('symbiostock_eula_page'); 
		
		$eula_page =  get_permalink( $eula_page_id );
		
		?>
        <td><input class="symbiostock_settings" type="text" name="symbiostock_eula_page"  id="symbiostock_eula_page" value="<?php echo $eula_page; ?>" />
            <br />
           Page that contains your image licensing. </td>
    </tr>
    
    <thead>
        <tr>
            <th colspan="2">
            <strong>Credit Links</strong>
            </th>
        </tr>
  	</thead>
    <tr>
    <td colspan="2">
    <label for="symbiostock_credit_links_1">
    <input type="radio" id="symbiostock_credit_links_1" name="symbiostock_credit_links" <?php echo $symbiostock_credit_links_product_page; ?> value="product_page" />
    <strong> Product Page (Suggested)</strong></label>
<br />
<label for="symbiostock_credit_links_2">
    <input type="radio" id="symbiostock_credit_links_2" name="symbiostock_credit_links" <?php echo $symbiostock_credit_links_footer ; ?> value="footer" />
    In Footer </label>
<br />
<label for="symbiostock_credit_links_3"><?php include_once(symbiostock_CLASSROOT . '/paypal.php'); ?>
    <input type="radio" id="symbiostock_credit_links_3" name="symbiostock_credit_links" <?php echo $symbiostock_credit_links_no_use ; ?> value="no_use" />
    Do Not Use</label><br /><br />
	Backlinks to <a title="clipartillustration.com" href="http://www.clipartillustration.com/">Leo Blanchette's main selling website</a>, <a title="ClipArtOf.com" href="http://www.clipartof.com/">ClipArtOf.com</a> (Special thanks to Kenny and Jamie), and <a title="Microstockgroup.com" href="http://www.microstockgroup.com/">MicrostockGroup.com</a>, who hosts the Symbiostock knowledge forums.<br />
    This is one of the few ways I profit on this theme, so I thank you for keeping this live.
	
    </td>
    </tr>
</table>