<?php
// does user-cart functions and produces entire cart interface
// we are attempting to make what I call a meta-cart. Its simply an 
// array which can easily be transferred between sites, and is stored
// in user meta instead of a database sophisticated table 
// also creates product tables/purchasing interface
// we strive to keep this class as absolutely simple as possible
//
// edited jas 2013-12-13 add class thumbnail to minipic to get styling of other thumbs on site
class symbiostock_cart

{
    public $user = array( );
        
    public $product_info = array( );
    
    public $option_count = 0;
    
    public $cart = array( );
    
    public $size_key = array();
    
    //cordinates number with size
    public $number_size = array( );
    
    //constructor
    function __construct( $symbiostock_post_meta = array( ) )
    {
        global $ss_sizenames;        
        
        $this->size_key = $ss_sizenames;
                        
        define("symbiostock_remove_cap", true);
        if(function_exists('ipn_user_id')){
        $this->user = get_userdata(ipn_user_id());
        
        } else {
            
        $this->user = wp_get_current_user();
            
            }
       
       
        if(!empty($symbiostock_post_meta)){
            //populate item info if this is an item page, or post meta function
            //has supplied info for another purpose
              $this->product_info = $symbiostock_post_meta;
        }
        
        $this->option_count = 0;
        
       
        //get or set up our cart
        $current_cart = get_user_meta( $this->user->ID, 'symbiostock_cart', true );
                        
        if ( !empty( $current_cart ) ) {
            //for testing, uncomment next line
            //update_user_meta($this->user, 'symbiostock_cart', '');
            
            $this->cart = maybe_unserialize( get_user_meta( $this->user->ID, 'symbiostock_cart', true ) );
            
        } else {
            //if we don't have a cart, initiate one
            $this->cart_template();
            update_user_meta( $this->user->ID, 'symbiostock_cart', serialize( $this->cart ) );            
        }
        //var_dump($this->product_info);                                
    }


    public function list_raster_sizes( $size_info, $extension )
    {
        
        $this->product_info['symbiostock_collection'][0] > 1 ? $size_info['is_collection'] = $this->product_info['symbiostock_collection'][0] : $size_info['is_collection'] = false;
        
        $size_info = apply_filters('symbiostock_size_info', $size_info);
        
        $sizes = array(
             'bloggee',
            'small',
            'medium',
            'large' 
        );
        
        $data = '';
            
        
        foreach ( $sizes as $size ) {
            
            $price = $this->calc_discount($this->product_info[ 'price_' . $size ][ 0 ], $this->product_info['discount_percent'][0]);
            
            $option = $this->product_option( $extension, $size );
            
            //check our availability and proceed accordingly
            if(isset($this->product_info['symbiostock_' . $size . '_available'][0]) 
            && $this->product_info['symbiostock_' . $size . '_available'][0] == 'no'){ continue; }    
                    
            if(isset($this->product_info['symbiostock_' . $size . '_available'][0]) 
            && $this->product_info['symbiostock_' . $size . '_available'][0] == 'no_select'){ 
                $available_class = 'not_available'; } else {$available_class = ''; }            
                
            $row = '<tr class="' . $available_class . ' ' . $option[ 'in_cart' ][ '1' ] . '"><td>' . $option[ 'option' ] . '</td><td>' . $extension . '</td><td>' . $size_info[ $size ][ 'dpi' ] . '<br />' . $size_info[ $size ][ 'pixels' ] . '</td><td>' . $price['compare']  . '</td></tr>
            ';
            $data .= $row;
        }
        
        $data = apply_filters('list_raster_sizes', $data);
        
        return $data;
    }
    
    
    public function list_eps( )
    {
        //check our availability and proceed accordingly
        if(isset($this->product_info['symbiostock_' . 'vector' . '_available'][0]) 
        && $this->product_info['symbiostock_' . 'vector' . '_available'][0] == 'no'){ return; }    
        //check our select / no-select status        
        if(isset($this->product_info['symbiostock_' . 'vector' . '_available'][0]) 
        && $this->product_info['symbiostock_'  . 'vector' .  '_available'][0] == 'no_select'){ 
            $available_class = 'not_available'; } else {$available_class = ''; }    
                    
        $price = $this->calc_discount($this->product_info[ 'price_vector' ][ 0 ], $this->product_info['discount_percent'][0]);
        
        $option = $this->product_option( 'eps', 'vector' );
        
        $row = '<tr class="'. $available_class . ' ' . $option[ 'in_cart' ][ '1' ] . '"><td>' . $option[ 'option' ] . '</td><td>eps</td><td>' . __('Filesize', 'symbiostock' ) . ': ' . $this->product_info[ 'size_eps' ][ 0 ] . '</td><td>' . $price['compare'] . '</td></tr>
        ';
        
        $row = apply_filters('list_eps', $row);
        
        return $row;
        
    }
    
    public function list_zip( )
    {
        //check our availability and proceed accordingly
        if(isset($this->product_info['symbiostock_' . 'zip' . '_available'][0]) 
        && $this->product_info['symbiostock_' . 'zip' . '_available'][0] == 'no'){ return; }    
        
        //check our select / no-select status        
        if(isset($this->product_info['symbiostock_' . 'zip' . '_available'][0]) 
        && $this->product_info['symbiostock_'  . 'zip' .  '_available'][0] == 'no_select'){ 
            $available_class = 'not_available'; } else {$available_class = ''; }                
                
        $price = $this->calc_discount($this->product_info[ 'price_zip' ][ 0 ], $this->product_info['discount_percent'][0]);
            
        $option = $this->product_option( 'zip', 'zip' );
        
        $row = '<tr class="'. $available_class . ' ' . $option[ 'in_cart' ][ '1' ] . '"><td>' . $option[ 'option' ] . __('Supporting Files', 'symbiostock') . '</td><td>'.__('ZIP', 'symbiostock').'</td><td>'.__('Filesize', 'symbiostock').': ' . $this->product_info[ 'size_zip' ][ 0 ] . '</td><td>' . $price['compare'] . '</td></tr>
        ';
        
        $row = apply_filters('list_eps', $row);
        
        return $row;
    }
    
    //we create a function for the option buttons due to the fact that there maybe other related output like invisible form elements
    public function product_option( $extension, $name )
    {
        $product_id = $this->product_info[ 'id' ];
        $data_price = $this->product_info['price_' . $name];
        $size = $this->option_count++;
        
        $this->number_size[ $size ] = $name;
        
        $field_id = $product_id . '_' . $extension . '_' . $size . '_' . $name;
        
        $in_cart = $this->in_cart( $field_id );
        
        if(is_user_logged_in()){
            
            $input_attrs ='name="product"';
            
            } else {
            
            $input_attrs = 'name="product_not_logged_in" data-toggle="modal" data-target="#symbiostock_member_modal"';
                
            }
        
        //check our availability options    
        if(isset($this->product_info['symbiostock_' . $name . '_available'][0]) && $this->product_info['symbiostock_' . $name . '_available'][0] == 'no_select'){ $state="disabled"; } else { $state = '';}
        
        $option = '<label class="radio" for="' . $field_id . '"><input data-price="'.$data_price[0].'" ' . $in_cart[ 0 ] . 'type="radio" value="' . $field_id . '" id="' . $field_id . '" ' . $input_attrs  . ' ' . $state . ' />' . $this->size_key[$name] . '</label>';
       
        
        //add on other elements (invisible) this way...
        $option .= '';
        
        return array(
             'option' => $option,
            'in_cart' => $in_cart 
        );
    }
    
    public function display_product_table( )
    {    
        //if product is not for sale, abort 
        if($this->product_info['live'][0] == 'not_live'){return;}
        $curr = $this->currency();
        ?>
        <form id="symbiostock_product_form" class="symbiostock_product" action="" method="post">        
            
        <?php if($this->product_info['exclusive'][0] == 'exclusive'): ?>
        <div class="alert alert-success text-center">
            <strong>
            <i class="icon-star"> </i> 
            <?php echo __('EXCLUSIVE IMAGE', 'symbiostock'); ?> <i class="icon-star"> </i> <br /><small><?php _e( 'Found only on ', 'symbiostock') ?> <strong><?php bloginfo('url'); ?></strong></small>
            </strong>
        </div>
        <?php endif; ?>        
        
        <table id="symbiostock_product_table" class="table-striped table table-condensed table-responsive table-hover">
        <thead>
            <tr>
                <th>
                <?php _e( 'Size', 'symbiostock') ?>
                </th>
                <th>
                <?php _e( 'Type', 'symbiostock') ?>
                </th>                
                <th>
                <?php _e( 'File Info', 'symbiostock') ?>
                </th>
                <th>
                <?php _e( 'Price', 'symbiostock') ?> <br /><span class="symbiostock_currency">( <?php echo $curr[0]; ?> )</span><?php 
                        
                $discount = $this->product_info['discount_percent'][0];
                $discount == 0?  $savings = '' : $savings = '<em>' . $discount . __('% off', 'symbiostock') . '</em>';
                echo $savings;
                 ?>
                </th>
            </tr>
        </thead>
        <tbody>
        
        <?php
        
        $extensions = array_reverse( unserialize( $this->product_info[ 'extensions' ][ 0 ] ) );
        
        $size_info = unserialize( $this->product_info[ 'size_info' ][ 0 ] );
        
        $rows = array( );
        foreach ( $extensions as $extension ) {
    
            switch ( $extension ) {
                
                case 'png':
                    
                    $rows[ 0 ] = $this->list_raster_sizes( $size_info, $extension );
                    
                    $png = true;
                    
                    break;
                
                case 'jpg':
                                                                                                        
                        if ( !isset($png) || $png == false ) {
                            $rows[ 1 ] = $this->list_raster_sizes( $size_info, $extension );
                    
                    }
                    break;
                
                case 'eps':
                    
                    $rows[ 2 ] = $this->list_eps();
                    
                    break;
                
                case 'zip':
                    
                    $rows[ 3 ] = $this->list_zip();
                    
                    break;
                    
            }
            
        }
        
        echo isset($rows[ 0 ])?  $rows[ 0 ] : '';
        echo isset($rows[ 1 ])?  $rows[ 1 ] : '';
        echo isset($rows[ 2 ])?  $rows[ 2 ] : '';
        echo isset($rows[ 3 ])?  $rows[ 3 ] : '';
        
?>    
             <tr class="info text-right">
            <?php               
            $license_option = array('license_html' => '<td class="text-right" colspan="4"><strong><em>' . symbiostock_eula(__('End User License Agreement', 'symbiostock')) . '</em></strong></td>');
            
            if(isset($this->user) && !empty($this->user)){
                
                $license_option['user'] = $this->user;
                $license_option['product_info'] = $this->product_info;
                $license_option['option_count'] = $this->option_count;
                $license_option['cart'] = $this->cart;
                
                }            
                
            $license_option = apply_filters('ss_license_options', $license_option); 
            
            echo $license_option['license_html'];
            ?>
             </tr>
            </tbody>
                <tfoot>
                    <tr>           
                    
                    <td class="gotocart text-right" colspan="4"><?php
        
        //get cart link and amount
        $cart_value = '(' . $this->get_cart_value() . ')';  

        $dl_label = get_option('symbiostock_download_button_name', 'CHECKOUT');
        
        echo symbiostock_customer_area( $dl_label . ' ' . $cart_value, true);
        
?></td>
                    </tr>
                                    
                </tfoot>
            </table>
            
            </form>  <?php
 
    }
    public function empty_cart(){
        
        $this->cart_template( );
        update_user_meta( $this->user->ID, 'symbiostock_cart', serialize( $this->cart ) );
        
        }
    //checks to see if item is in cart. If so, return array for info
    public function in_cart( $field_id )
    {
        
        
        $cart = $this->cart[ 'products' ];
                
        $info = explode( "_", $field_id );
        
        if ( array_key_exists( $info[ 0 ], $cart ) 
        && in_array( $info[ 1 ], $cart[ $info[ 0 ] ] ) 
        && in_array( $info[ 2 ], $cart[ $info[ 0 ] ] ) 
        && in_array( $info[ 3 ], $cart[ $info[ 0 ] ] ) ) {
            return array(
                 'checked="checked"',
                'in_cart' 
            );
            
        }
        
    }
    
    public function display_customer_cart( )
    {
        if(!is_user_logged_in()) { 
        
        ?><p><a title="<?php _e( 'Please log in...', 'symbiostock') ?>" data-toggle="modal" data-target="#symbiostock_member_modal" class="login_register" href="#"><strong><i class="icon-key"> </i> <?php _e( 'Log in to view your cart...', 'symbiostock') ?></strong></a></p><?php 
        
        return;
        
        }
        $curr = $this->currency();
        
        //allow cart to be modified before processing
        
        $cart = apply_filters('ss_display_cart_mod', $this->cart);
        
        $cart_items = $cart['products'];
                
        $email = $this->cart['cart_email'];
        
        
        $symbiostock_paypal_live_or_sandbox = get_option('symbiostock_paypal_live_or_sandbox');
        
        if($symbiostock_paypal_live_or_sandbox == "live" || empty($symbiostock_paypal_live_or_sandbox) || $symbiostock_paypal_live_or_sandbox == NULL){
            
            $paypal_link = 'https://www.paypal.com/cgi-bin/webscr';    
        
        } elseif ($symbiostock_paypal_live_or_sandbox == 'sandbox'){
            
            $paypal_link = 'https://www.sandbox.paypal.com/cgi-bin/webscr';    
        
        }        
        $paypal_vars = array(    
            'buyer_id' => $this->user->ID,
            'buyer_email' => $this->user->user_email,    
        );
        
        $paypal_vars = htmlspecialchars(serialize($paypal_vars));
        ?>
        
       <form action="<?php echo $paypal_link; ?>" method="post" id="symbiostock_cart">
            <input type="hidden" value="_cart" name="cmd" />
            <input type="hidden" value="1" name="upload" />
            <input type="hidden" value="<?php echo get_option('symbiostock_logo_for_paypal'); ?>" name="image_url" />
            <input type="hidden" value="<?php echo $paypal_vars; ?>" name="custom" />
            <input type="hidden" value="<?php echo get_option('symbiostock_paypal_email', ''); ?>" name="business" />
            <input type="hidden" name="currency_code" value="<?php echo $curr[2]; ?>" />
            <input type="hidden" name="notify_url" value="<?php echo get_bloginfo('wpurl') . '/symbiostock_ipn/'; ?>"  />
            <input type="hidden" name="return"  value="<?php echo symbiostock_customer_area_link() . '?paypal_return_message=1'; ?>" />           
            <table class="table cart">
        <thead>
            <tr>
                <th><?php _e( 'Preview', 'symbiostock') ?></th>
                <th><?php _e( 'Option', 'symbiostock') ?></th>
                <th><?php _e( 'Price', 'symbiostock') ?> - <?php echo $curr[0]; ?></th>
                <th>&nbsp;</th>
            </tr>            
        </thead>
        <tbody>
        
           <?php
        $product_count = 0;
        
        foreach($cart_items as $product => $info){
            
            $product_count++;
            
            //gather relevant data
            
            $product_info = symbiostock_post_meta($product);
            
            $price = $this->calc_discount($info['price'], $product_info['discount_percent'][0]);
                        
            $sizes = (unserialize($product_info['size_info'][0]));
            
            // jas begin add class thumbnail to cart images to get styling of other thumbs        
            $minipic = '<a title="" href="' . get_permalink( $product ) . '"><img class="thumbnail" width="' . $sizes['thumb']['width'] . '" height="' . $sizes['thumb']['height'] . '" alt="img ' . $product . '" src="' . $product_info['symbiostock_minipic'][0] . '" /></a>';
            // jas end
            $size_info = unserialize($product_info['size_info'][0]);
            
            $is_collection = get_post_meta($product, 'symbiostock_collection', 0);    
            
            $is_collection[0] > 1 ? $size_info['is_collection'] = $is_collection[0] : $size_info['is_collection'] = false;
            
            $size_info = apply_filters('display_customer_cart_info', $size_info);
                        
            
            $size_name = $info['size_name'];
            
            $option = '<strong>' .$product . '</strong><br /><br /><strong>' . $info['type'] . ', ' . $this->size_key[$info['size_name']] . '</strong><br />' . $size_info[$size_name]['pixels'] . '<br />' . $size_info[$size_name]['dpi'] ;
                    
            
            //make the row    ?>                                        
            <tr>
            <input type="hidden" value="<?php _e( 'Image Number', 'symbiostock') ?> <?php echo $product; ?>" name="item_name_<?php echo $product_count; ?>" />
            <input type="hidden" value="<?php _e( 'Size', 'symbiostock') ?>" name="on0_<?php echo $product_count; ?>" />
            <input type="hidden" value="<?php echo $size_name; ?>" name="os0_<?php echo $product_count; ?>" />
            <input type="hidden" value="<?php echo $price['final_price']; ?>" name="amount_<?php echo $product_count; ?>" />
            <input type="hidden" value="<?php echo $product; ?>" name="item_number_<?php echo $product_count; ?>" /> 
           
            <?php 
            
            //add extra features via plugin if necessary
            $plus_features = apply_filters('ss_plus_features', array($product, $this->cart) );
            
            //if plugin is not utilizing this filter or returns wrong datatype, set it back to empty string.          
            is_array($plus_features) ? $plus_features = '' : '';
            
            echo '<td>' . $minipic . '</td><td>' . $option  . '</td><td class="price">' . $curr[1] . $price['compare'] . $plus_features.  '</td><td><a id="remove_' . $product . '" class="remove_from_cart" href="#"><i class="icon-remove-sign">&nbsp;</i></a></td>'; ?> 
            </tr>
            <?php
        }
        
        do_action('display_customer_cart', $this->cart);
        
        ?>
        
             <tr class="info">
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td class="text-right" colspan="2"><strong><em><?php echo symbiostock_eula(__('License Agreement & Terms', 'symbiostock')); ?></em></strong>
                
                </td>
             </tr>
        
            </tbody>
            <tfoot>
                <tr>
                <td colspan="2">
                <a onclick="javascript:window.open('https://www.paypal.com/cgi-bin/webscr?cmd=xpt/Marketing/popup/OLCWhatIsPayPal-outside','olcwhatispaypal','toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=400, height=350');" href="#"><img border="0" alt="Solution Graphics" src="https://www.paypal.com/en_US/i/bnr/horizontal_solution_PPeCheck.gif"></a>
                </td>
              
                <td ><span class="total"><?php _e( 'Total', 'symbiostock') ?> <?php echo $this->get_cart_value(); ?></span>
                <div class="alert alert-info">                
                <label class="aligncenter checkbox"><input class="" name="aggree_to_EULA" id="aggree_to_EULA" type="checkbox" /> <?php _e( 'Agree to Terms Above', 'symbiostock') ?></label><br />
                <button disabled id="symbiostock_pay_now" class="aligncenter btn btn-large btn-success" type="submit"><i class="icon-shopping-cart"> <?php _e( 'Pay Now', 'symbiostock') ?> </i></button>
                <span class="clearfix"><br /></span>
                </div>
                </td>
                <td>&nbsp;</td>
                </tr>         
                  
            </tfoot>
        </table>
        
    </form>
        
        <?php
        
    }
    
    public function display_customer_purchase(){
        
        $cart = $this->cart;
        
        $cart_items = $this->cart['products'];
        
        //add more rows / products if needed, such as when a collection is available
        $this->cart['products'] = apply_filters('display_customer_purchase', $this->cart['products']);        
                
        $email = $this->cart['cart_email'];
        
        $product_string = '';
    
        $product_string .='
<table class="table cart">
    <thead>
        <tr>
            <th>'.__( 'Preview', 'symbiostock').'</th>
            <th>'.__( 'Option', 'symbiostock').'</th>
            <th>'.__( 'Price', 'symbiostock').'</th>               
        </tr>            
    </thead>
    <tbody>
    ';       
   
    $product_count = 0;
    
    foreach($cart_items as $product => $info){            
        $product_count++;
        
        //gather relevant data
        
        $product_info = symbiostock_post_meta($product);
        
        $price = $this->calc_discount($info['price'], $product_info['discount_percent'][0]);
                    
        $sizes = (unserialize($product_info['size_info'][0]));
        // jas begin add class thumbnail to cart images to get styling of other thumbs
        $minipic = '<a title="" href="' . get_permalink( $product ) . '"><img class="thumbnail" width="' . $sizes['thumb']['width'] . '" height="' . $sizes['thumb']['height'] . '" alt="img ' . $product . '" src="' . $product_info['symbiostock_minipic'][0] . '" /></a>';
        // jas end
        $size_info = unserialize($product_info['size_info'][0]);
        
        $size_name = $info['size_name'];
        
        $option = '<strong>' .$product . '</strong><br /><br /><strong>' . $info['type'] . ', ' . ucwords($info['size_name']) . '</strong><br />' . $size_info[$size_name]['pixels'] . '<br />' . $size_info[$size_name]['dpi'] ;
        
        $xtra = array($product, $cart, '');
        
        //this simply allows a plugin to take an array of info, and formulate a string to append "extra" text to product info.
        $xtra = apply_filters('ss_purchased_appended_info', $xtra);
        if(is_array($xtra))
            $xtra = '';
        
        $product_string .='<tr>'.        
         '<td>' . $minipic . '</td><td>' . $option  . '</td><td class="price">' . $price['compare'] . ' ' . $xtra  .  '</td>' 
        .'</tr>';
      
    }    
        $product_string .='
         <tr class="info">        
            <td class="text-right" colspan="3"><strong><em>' . symbiostock_eula(__('End User License Agreement', 'symbiostock')) . '</em></strong></td>
         </tr>            
    </tbody>
    <tfoot>
        <tr>
        <td colspan="2">               
        </td>              
        <td ><span class="total">'.__( 'Total:', 'symbiostock').'' . $this->get_cart_value() . '</span></td>
        </tr>  
    </tfoot>
</table>';       
  
          return $product_string;
        
        }
    
    public function remove_item_from_cart( $item )
    {    
        unset($this->cart[ 'products' ][ $item ]);    
        update_user_meta( $this->user->ID, 'symbiostock_cart', $this->cart );    
    }
    
    public function add_item_to_cart( $selection, $other_additions = array() )
    {
        
        //get price (we get it fresh to avoid user abuse)
        
        $price = get_post_meta($selection[ 0 ], 'price_' . strtolower($selection[ 3 ]));
        $discount = get_post_meta($selection[ 0 ], 'discount_percent');    
        
        $this->cart['date'] = current_time( 'mysql' );
        
        //add item by product id
        $this->cart[ 'products' ][ $selection[ 0 ] ] = array(
            
            //then set its attributes. 
             'type' => $selection[ 1 ], //type
            'size' => $selection[ 2 ],
            'size_name' => $selection[ 3 ], 
            'price' => $price[0],
            'discount' => $discount[0]     
            
        );
        
                
        $this->cart = apply_filters('add_item_to_cart', $this->cart);
                
        update_user_meta( $this->user->ID, 'symbiostock_cart', serialize( $this->cart ) );
        
    }
    
    //calculates discount on given price
    
    public function calc_discount($price, $discount){
            
            $price = str_replace('.', '',  $price);
            
            $discount_value = ($price / 100) * $discount;
            
            $final_price = $price - $discount_value;
            
            $discount_value = number_format($discount_value/100, 2);
            
            $price = number_format($price/100, 2);
            
            $final_price = number_format($final_price/100, 2);
            
            $discount == '0'? $compare = $price : $compare = '<span class="compare_discount">' . $price . '</span><br /><em class="compare_discount">' . $final_price . '</em>';
            
            !isset($savings)?$savings = '':'';
            
            $discount_val = array(
                'discount' => $discount,
                'price' => $price,
                'final_price'=> $final_price,
                'compare' => $compare,
                'savings' => $savings
            
            );
            
            return $discount_val;
        }
    
    //calculates cart value
    public function get_cart_value(){
                        
        $cart = $this->cart;
        $prices = array();
        
        $cart = apply_filters('ss_mod_cart_value', $cart);
        
        foreach ($cart['products'] as $product){
                        
            array_push(    $prices, array('price'=>trim($product['price']), 'discount' => trim($product['discount'] )));            
            }
    
        $total = 0;
                
        foreach($prices as $price){
                
                
             $price = $this->calc_discount($price['price'], $price['discount']);    
                
             $price = str_replace('.', '',  $price['final_price']);
                
             $total += $price;
            }
        
        $total = number_format($total/100, 2);
                
        return $total;
    
    }
    
    public function currency(){
            
            $symbiostock_currency = get_option('symbiostock_currency');
            !isset($symbiostock_currency)  ? $symbiostock_currency = 'USD' : '';
        
            $symbiostock_currencies = array(
                'USD' => array( __('US Dollars $', 'symbiostock'),       '$', 'USD' ),
                'EUR' => array( __('Euros €', 'symbiostock'),            '€', 'EUR' ),
                'GBP' => array( __('Pounds Sterling £', 'symbiostock'),  '£', 'GBP' ),
                'CAD' => array( __('Canadian Dollars $', 'symbiostock'), '$', 'CAD' ),
                'JPY' => array( __('Japanese Yen ¥', 'symbiostock'),     '¥', 'JPY' ),
            );        
            
            $symbiostock_currency = $symbiostock_currencies[$symbiostock_currency];    
            
            return $symbiostock_currency;        
            
        }
    
    //creates the cart array, which can be manipulated. Beautiful alternative to database based cart system
    public function cart_template( )
    {
        $this->cart = array(            
             'site' => get_site_url(),
            'cart_email' => $this->user->user_email,
            'products' => array( ),
            'date' => current_time( 'mysql' ) 
        );
                
        $this->cart = apply_filters('cart_template', $this->cart);        
    
    }
    public function display_referral_links(){
        
        if(
            isset($this->product_info['symbiostock_referral_link_1'][0]) && !empty($this->product_info['symbiostock_referral_link_1'][0]) ||
            isset($this->product_info['symbiostock_referral_link_2'][0]) && !empty($this->product_info['symbiostock_referral_link_2'][0]) ||
            isset($this->product_info['symbiostock_referral_link_3'][0]) && !empty($this->product_info['symbiostock_referral_link_3'][0]) ||
            isset($this->product_info['symbiostock_referral_link_4'][0]) && !empty($this->product_info['symbiostock_referral_link_4'][0]) ||
            isset($this->product_info['symbiostock_referral_link_5'][0]) && !empty($this->product_info['symbiostock_referral_link_5'][0])        
        ){
            
        $referral_site_count = 1;    
        ?>        
        <div class="well">
               
        <?php
        
        while($referral_site_count <=5){
            
            if(
            isset($this->product_info['symbiostock_referral_link_'.$referral_site_count][0]) && !empty($this->product_info['symbiostock_referral_link_'.$referral_site_count][0])&&
            isset($this->product_info['symbiostock_referral_label_'.$referral_site_count][0]) && !empty($this->product_info['symbiostock_referral_label_'.$referral_site_count][0])            
            ){
                
            echo '<a title="'.stripslashes($this->product_info['symbiostock_referral_label_'.$referral_site_count][0]).'" href="'.$this->product_info['symbiostock_referral_link_'.$referral_site_count][0].'">
            '.$this->product_info['symbiostock_referral_label_'.$referral_site_count][0].'</a><hr />';    
                }
            
            $referral_site_count++;
            }
        
        
        ?>             
        </div>
        <?php
        }
        }
}
?>