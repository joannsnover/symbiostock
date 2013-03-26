<?php
// does user-cart functions and produces entire cart interface
// we are attempting to make what I call a meta-cart. Its simply an 
// array which can easily be transferred between sites, and is stored
// in user meta instead of a database sophisticated table 
// also creates product tables/purchasing interface
// we strive to keep this class as absolutely simple as possible
class symbiostock_cart
{
    private $user = array( );
    	
    public $product_info = array( );
    
    public $option_count = 0;
    
    public $cart = array( );
    
    //cordinates number with size
    public $number_size = array( );
    
    //constructor
    function __construct( $symbiostock_post_meta = array( ) )
    {
		
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
            
            $this->cart = unserialize( get_user_meta( $this->user->ID, 'symbiostock_cart', true ) );
            
        } else {
            //if we don't have a cart, initiate one
            $this->cart_template();
            update_user_meta( $this->user->ID, 'symbiostock_cart', serialize( $this->cart ) );
            
        }
        //var_dump($this->product_info);								
    }
    
    private function list_raster_sizes( $size_info, $extension )
    {
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
        
        return $data;
    }
    
    
    private function list_eps( )
    {
		$price = $this->calc_discount($this->product_info[ 'price_vector' ][ 0 ], $this->product_info['discount_percent'][0]);
		
        $option = $this->product_option( 'eps', 'vector' );
        
        $row = '<tr class="' . $option[ 'in_cart' ][ '1' ] . '"><td>' . $option[ 'option' ] . '</td><td>eps</td><td>Filesize: ' . $this->product_info[ 'size_eps' ][ 0 ] . '</td><td>' . $price['compare'] . '</td></tr>
		';
        
        return $row;
        
    }
    
    private function list_zip( )
    {
		$price = $this->calc_discount($this->product_info[ 'price_zip' ][ 0 ], $this->product_info['discount_percent'][0]);
			
        $option = $this->product_option( 'zip', 'zip' );
        
        $row = '<tr class="' . $option[ 'in_cart' ][ '1' ] . '"><td>' . $option[ 'option' ] . 'Supporting Files</td><td>ZIP</td><td>Filesize: ' . $this->product_info[ 'size_zip' ][ 0 ] . '</td><td>' . $price['compare'] . '</td></tr>
		';
        
        return $row;
    }
    
    //we create a function for the option buttons due to the fact that there maybe other related output like invisible form elements
    private function product_option( $extension, $name )
    {
        $product_id = $this->product_info[ 'id' ];
        
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
		
        $option = '<label class="radio" for="' . $field_id . '"><input ' . $in_cart[ 0 ] . 'type="radio" value="' . $field_id . '" id="' . $field_id . '" ' . $input_attrs  . ' ' . $state . ' />' . ucwords( $name ) . '</label>';
       
	    
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
?>
        <form id="symbiostock_product_form" class="symbiostock_product" action="" method="post">
        <table id="symbiostock_product_table" class="table table-striped table-hover">
        <thead>
            <tr>
                <th>
                Size
                </th>
                <th>
                Type
                </th>                
                <th>
                File Info
                </th>
                <th>
                Price <?php 
						
				$discount = $this->product_info['discount_percent'][0];
				$discount == 0?  $savings = '' : $savings = '<em>' . $discount . '% off</em>';
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
                <td class="text-right" colspan="4"><strong><em><?php echo symbiostock_eula('End User License Agreement'); ?></em></strong></td>
             </tr>
            </tbody>
                <tfoot>
                    <tr>           
                    
                    <td class="gotocart text-right" colspan="4"><?php
		
		//get cart link and amount
		$cart_value = '(' . $this->get_cart_value() . ')';			
		echo symbiostock_customer_area( 'Customer / Licensing Area ' . $cart_value);
		
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
		
		?><p>Please <a title="Please log in..." data-toggle="modal" data-target="#symbiostock_member_modal" class="login_register" href="#"><strong><i class="icon-key"> </i> log in</strong></a> to view your cart.</p><?php 
		
		return;
		
		}
		
		$cart_items = $this->cart['products'];
				
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
            <table class="table cart">
        <thead>
            <tr>
            	<th>Preview</th>
                <th>Option</th>
                <th>Price</th>
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
								
			$minipic = '<a title="" href="' . get_permalink( $product ) . '"><img width="' . $sizes['thumb']['width'] . '" height="' . $sizes['thumb']['height'] . '" alt="img ' . $product . '" src="' . $product_info['symbiostock_minipic'][0] . '" /></a>';
			
			$size_info = unserialize($product_info['size_info'][0]);
			
			$size_name = $info['size_name'];
			
			$option = '<strong>' .$product . '</strong><br /><br /><strong>' . $info['type'] . ', ' . ucwords($info['size_name']) . '</strong><br />' . $size_info[$size_name]['pixels'] . '<br />' . $size_info[$size_name]['dpi'] ;
			
			//make the row	?>										
			<tr>
            <input type="hidden" value="Image Number <?php echo $product; ?>" name="item_name_<?php echo $product_count; ?>" />
            <input type="hidden" value="Size" name="on0_<?php echo $product_count; ?>" />
            <input type="hidden" value="<?php echo $size_name; ?>" name="os0_<?php echo $product_count; ?>" />
            <input type="hidden" value="<?php echo $price['final_price']; ?>" name="amount_<?php echo $product_count; ?>" />
            <input type="hidden" value="<?php echo $product; ?>" name="item_number_<?php echo $product_count; ?>" /> 
            <input name="return" type="hidden" value="<?php echo symbiostock_customer_area_link(); ?>" />           
            <?php 
			echo '<td>' . $minipic . '</td><td>' . $option  . '</td><td class="price">' . $price['compare'] . '</td><td><a id="remove_' . $product . '" class="remove_from_cart" href="#"><i class="icon-remove-sign">&nbsp;</i></a></td>'; ?> 
			</tr>
            <?php
		}
		
		?>
        
             <tr class="info">
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td class="text-right" colspan="2"><strong><em><?php echo symbiostock_eula('End User License Agreement'); ?></em></strong></td>
             </tr>
        
            </tbody>
            <tfoot>
                <tr>
                <td colspan="2">
                <a onclick="javascript:window.open('https://www.paypal.com/cgi-bin/webscr?cmd=xpt/Marketing/popup/OLCWhatIsPayPal-outside','olcwhatispaypal','toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=400, height=350');" href="#"><img border="0" alt="Solution Graphics" src="https://www.paypal.com/en_US/i/bnr/horizontal_solution_PPeCheck.gif"></a>
                </td>
              
                <td ><span class="total">Total: <?php echo $this->get_cart_value(); ?></span>
                <button class="btn btn-large" type="submit"><i class="icon-shopping-cart"> Get Licenses</i></button></td>
                <td>&nbsp;</td>
                </tr>         
              	
            </tfoot>
        </table>
        
    </form>
        
        <?php
		
    }
	
	public function display_customer_purchase(){
		
		$cart_items = $this->cart['products'];
				
		$email = $this->cart['cart_email'];
		$product_string = '';
	
        $product_string .='
<table class="table cart">
	<thead>
		<tr>
			<th>Preview</th>
			<th>Option</th>
			<th>Price</th>               
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
							
		$minipic = '<a title="" href="' . get_permalink( $product ) . '"><img width="' . $sizes['thumb']['width'] . '" height="' . $sizes['thumb']['height'] . '" alt="img ' . $product . '" src="' . $product_info['symbiostock_minipic'][0] . '" /></a>';
		
		$size_info = unserialize($product_info['size_info'][0]);
		
		$size_name = $info['size_name'];
		
		$option = '<strong>' .$product . '</strong><br /><br /><strong>' . $info['type'] . ', ' . ucwords($info['size_name']) . '</strong><br />' . $size_info[$size_name]['pixels'] . '<br />' . $size_info[$size_name]['dpi'] ;
		
									
		$product_string .='<tr>'.        
		 '<td>' . $minipic . '</td><td>' . $option  . '</td><td class="price">' . $price['compare'] . '</td>' 
		.'</tr>';
	  
	}
	
		$product_string .='
		 <tr class="info">        
			<td class="text-right" colspan="3"><strong><em>' . symbiostock_eula('End User License Agreement') . '</em></strong></td>
		 </tr>        	
	</tbody>
	<tfoot>
		<tr>
		<td colspan="2">               
		</td>              
		<td ><span class="total">Total:' . $this->get_cart_value() . '</span></td>
		</tr>  
	</tfoot>
</table>';       
  
  		return $product_string;
		
		}
    
    public function remove_item_from_cart( $item )
    {	
		unset($this->cart[ 'products' ][ $item ]);	
		update_user_meta( $this->user->ID, 'symbiostock_cart', serialize( $this->cart ) );	
    }
    
    public function add_item_to_cart( $selection )
    {
        
		//get price (we get it fresh to avoid user abuse)
		
		$price = get_post_meta($selection[ 0 ], 'price_' . strtolower($selection[ 3 ]));
		$discount = get_post_meta($selection[ 0 ], 'discount_percent');	
		
		//add item by product id
        $this->cart[ 'products' ][ $selection[ 0 ] ] = array(
            
            //then set its attributes. 
             'type' => $selection[ 1 ], //type
            'size' => $selection[ 2 ],
			'size_name' => $selection[ 3 ], 
			'price' => $price[0],
			'discount' => $discount[0]
			 
            
        );
                
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
		
		foreach ($cart['products'] as $product){
						
			array_push(	$prices, array('price'=>trim($product['price']), 'discount' => trim($product['discount'] )));
			
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
	
    //creates the cart array, which can be manipulated. Beautiful alternative to database based cart system
    private function cart_template( )
    {
        $this->cart = array(
            
             'site' => get_site_url(),
            'cart_email' => $this->user->user_email,
            'products' => array( ) 
            
            
        );
        
        
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
				
			echo '<a title="'.$this->product_info['symbiostock_referral_label_'.$referral_site_count][0].'" href="'.$this->product_info['symbiostock_referral_link_'.$referral_site_count][0].'">
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