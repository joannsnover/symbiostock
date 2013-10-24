<?php 

//get wordpress - 
require_once( dirname( __FILE__ ) . '/../wp-load.php' );;
// STEP 1: Read POST data
 
// reading posted data from directly from $_POST causes serialization 
// issues with array data in POST
// reading raw POST data from input stream instead. 
$raw_post_data = file_get_contents('php://input');
$raw_post_array = explode('&', $raw_post_data);
$myPost = array();
foreach ($raw_post_array as $keyval) {
  $keyval = explode ('=', $keyval);
  if (count($keyval) == 2)
     $myPost[$keyval[0]] = urldecode($keyval[1]);
}
// read the post from PayPal system and add 'cmd'
$req = 'cmd=_notify-validate';
if(function_exists('get_magic_quotes_gpc')) {
   $get_magic_quotes_exists = true;
} 
foreach ($myPost as $key => $value) {        
   if($get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1) { 
        $value = urlencode(stripslashes($value)); 
   } else {
        $value = urlencode($value);
   }
   $req .= "&$key=$value";
}
 
 
// STEP 2: Post IPN data back to paypal to validate
 
$symbiostock_paypal_live_or_sandbox = get_option('symbiostock_paypal_live_or_sandbox');
$symbiostock_paypal_live_or_sandbox == 'live' || !isset($symbiostock_paypal_live_or_sandbox)  ? $sandbox = '' : $sandbox = 'sandbox.';
 
//sandbox url...uncomment for testing
//$ch = curl_init('https://www.sandbox.paypal.com/cgi-bin/webscr');
$ch = curl_init('https://www.'.$sandbox.'paypal.com/cgi-bin/webscr');
curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));
 
// In wamp like environments that do not come bundled with root authority certificates,
// please download 'cacert.pem' from "http://curl.haxx.se/docs/caextract.html" and set the directory path 
// of the certificate as shown below.
// curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__) . '/cacert.pem');
if( !($res = curl_exec($ch)) ) {
    // error_log("Got " . curl_error($ch) . " when processing IPN data");
    curl_close($ch);
    exit;
}
curl_close($ch);
 
 
// STEP 3: Inspect IPN validation result and act accordingly
 
if (strcmp ($res, "VERIFIED") == 0) {
    // check whether the payment_status is Completed
    // check that txn_id has not been previously processed
    // check that receiver_email is your Primary PayPal email
    // check that payment_amount/payment_currency are correct
    // process payment
 
    // assign posted variables to local variables
    $item_name = $_POST['item_name'];
    $item_number = $_POST['item_number'];
    $payment_status = $_POST['payment_status'];
    $payment_amount = $_POST['mc_gross'];
    $payment_currency = $_POST['mc_currency'];
    $txn_id = $_POST['txn_id'];
    $receiver_email = $_POST['receiver_email'];
    $payer_email = $_POST['payer_email'];
    
    $custom_value = unserialize(stripslashes($_POST['custom']));
    
    $buyer_id = $custom_value['buyer_id'];
    $buyer_site_email =$custom_value['buyer_email'];    
    function ipn_user_id(){
        global $buyer_id;
        return $buyer_id;        
        }
    
    $user_cart = new symbiostock_cart();
            
    $expected_value = $user_cart->get_cart_value();
    
    //if payment matches cart, tranfer images to customer for download
    if($payment_amount == $expected_value ){
                
        //update_user_meta($buyer_id, 'symbiostock_purchased_products', '');        
                
        $user_products = get_user_meta($buyer_id, 'symbiostock_purchased_products'); 
        
        $user_products = unserialize($user_products[0]); //shift the array over
        
        if(empty($user_products) || $user_products==NULL){
        $user_products = array();
        }
        
        $user_cart->cart['products'];
        
        foreach($user_cart->cart['products'] as $number => $product_info){    
                
            $user_products[$number]=$product_info;            
            
            }
        
        
        $user_products = apply_filters('ss_user_cart_before_transfer', $user_products);
        
        //file_put_contents('test.txt', print_r($user_products, true));
        
        update_user_meta($buyer_id, 'symbiostock_purchased_products', serialize($user_products));
        
        do_action('ss_after_purchase_complete', $user_cart);
        
        $purchased_items_to_display = $user_cart->display_customer_purchase();
        
        $symbiostock_thank_you = new symbiostock_mail();
        $symbiostock_thank_you->send_thank_you_email($_POST['payer_email'], $_POST['first_name'],  $purchased_items_to_display );
                    
        //file_put_contents('test2.txt',$purchased_items_to_display);
        
        $user_cart->empty_cart();
        
        }
    
        //uncomment writing functions below for testing
/*        
        $fp = fopen('test.txt', 'w');
        fwrite($fp, "test\n");
        
    
        
        foreach($_POST as $key=>$value){
            
            fwrite($fp, $key . ": " . $value . "\n\n" );
            
            }
        
        fwrite($fp, $custom_value['buyer_']. "\n\n" );
        
        fclose($fp);    */
    } else if (strcmp ($res, "INVALID") == 0) {
        
/*        $fp = fopen('test_failed.txt', 'w');
        fwrite($fp, "test\n");    
        fwrite($fp, "failed" );    
        fclose($fp);    */
}
?>
