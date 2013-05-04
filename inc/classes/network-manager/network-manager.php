<?php
//this class is responsible for performing network functions, getting network search results, and so forth.
include_once( 'communicator.php' );
include_once( 'interpreter.php' );
//this is for making a unique set of ID's for all search results, incrementing, regardless of network
class results_counter
{
    
    public $count = 0;
    
    public $network_site_count = 0;
    
	public $network_info = array();
	
	public $images_meta = array();
	
	public $netdir = symbiostock_NETDIR; //network directory
	
    public function plus_1( )
    {        
        $this->count = $this->count + 1;
        
        return $this->count;
    }
    
}
class network_manager
{
    
    private $xml_results = '';
    
    public function __construct( )
    {
        
    }
    
	//get current networks your site is connected with
	public function get_connected_networks( )
	{
		
		$count = 0;
		
		$current_networks = array( );
		
		while ( $count <= 9 ) {
									
			$network = get_option( 'symbiostock_network_site_' . $count );
			
			if ( isset( $network ) && !empty( $network ) ) {
				
				 $current_networks['symbiostock_network_site_' . $count] = maybe_unserialize($network);
				
			} elseif ( empty( $network ) ) {
				
				delete_option( 'symbiostock_network_site_' . $count );
				
			} else {
				
				delete_option( 'symbiostock_network_site_' . $count );				
			}
			
			$count++;
		}
		
		return $current_networks;		
	}
	
	public function update_connected_networks( )
{

    $current_entries = $this->get_connected_networks();
    $last_count = 0;
	
	//start at -1 so count intially increments to 0;
	$count = -1;
	$skipped = false;
	while ( $count++ <= 9 ) {
        
		if(isset($_POST['save_form_info']) && (!isset( $_POST[ 'symbiostock_network_site_' . $count ] ) || empty( $_POST[ 'symbiostock_network_site_' . $count ] ))){
			
			delete_option( 'symbiostock_network_site_' . $count );	
			
			//note that a value was skipped
			$skipped = true;
		}
		
        if ( isset( $_POST[ 'symbiostock_network_site_' . $count ] ) ) {
            
			$success_count = $count;
			
			//accounting for skipped values due to user edits, we simply make an alternate count which reflect "successful" entries.
			if($skipped == true){				
				$success_count--;
				$skipped = false;
			}

            if ( symbiostock_validate_url( $_POST[ 'symbiostock_network_site_' . $success_count ] ) ) {
                
                $address = trim( $_POST[ 'symbiostock_network_site_' . $success_count] );
                
            } //symbiostock_validate_url( $_POST[ 'symbiostock_network_site_' . $count ] )
            elseif (!empty($_POST[ 'symbiostock_network_site_' . $success_count ])) {
                
                echo '<p>' . $_POST[ 'symbiostock_network_site_' . $success_count ] . ' is not a valid URL. Try again.</p>';
                
                continue;
                
            }
            
            $key = symbiostock_website_to_key( $address );
            
            $network_info = array( );
            
            $network_info[ 'key' ]         = trim( $key );
            $network_info[ 'address' ]     = trim( $address );
            $network_info[ 'description' ] = trim( $_POST[ 'symbiostock_network_description_' . $success_count] ) ;
            
            if ( isset( $current_entries[ 'symbiostock_network_site_' . $success_count ] ) ) {
                
                $exists = true;
                
                $network_info[ 'key' ] == $network_info[ 'symbiostock_network_site_' . $success_count ][ 'key' ] ? $exists = true : $exists = false;
                $network_info[ 'address' ] == $network_info[ 'symbiostock_network_site_' . $success_count ][ 'address' ] ? $exists = true : $exists = false;
                
            } //isset( $current_entries[ 'symbiostock_network_site_' . $success_count ] )
            else {
                
                $exists = false;
                
            }
            
            if ( $exists == false ) {
                
                //all conditions are right, so we can update our network associate info;
                update_option( 'symbiostock_network_site_' . $success_count, $network_info );
                
            } //$exists == false
            
			$last_count++;
			
        } //isset( $_POST[ 'symbiostock_network_site_' . $success_count ] )
         
		        
    } 	

}

	public function update_csv_info_to_networks($csv_data){
		
		//we count existing networks so we can assign the proper number to this one:

		$networks = $this->get_connected_networks( );
		
		$next = ( count($networks));
		
		if(!empty($csv_data)){
						
		$network_info = array();
		
		$network_info['key'] = symbiostock_website_to_key( $csv_data['symbiostock_site'] );
		$network_info['address'] = $csv_data['symbiostock_site'];
				
		if(isset($csv_data['symbiostock_my_network_description'])){
			
			$description = $csv_data['symbiostock_my_network_description'];
			
			} else {
				
			$description = 	$csv_data['admin_email'];
			
			}
		
		$network_info[ 'description' ] = $description;
		
		update_option( 'symbiostock_network_site_' . $next, $network_info );
				
		}
		
	}
	
	//uses a symseed (by key) to setup a folder with all of its images and supporting files
	public function setup_network_directory($symseed){
		
		$info = $this->csv_to_array($symseed . '.csv');
		
		$dir = symbiostock_NETDIR . $symseed . '/';
		
		if (!file_exists($dir)) {
			mkdir($dir, 0755);
		}

		}
	
	//processes a network csv file and transforms it to active network associate
	public function process_network_file($path){
					
		$required_fields = array(
			'symbiostock_site',
			'admin_email',
			'symbiostock_version',
		);
				
		//convert our info
		$converted = $this->csv_to_array($path, ',');
		$network_associate_info = $converted[0];
				
		//validate
		
		foreach($required_fields as $must_have){
		
		if(!isset($must_have)&&empty($must_have)){
			
			return '<p>Could not create network associate. Missing required info: <strong>' . $must_have . '</strong></p>';
			
			}
		
		}		
		//make our key
		$key =  symbiostock_website_to_key($network_associate_info['symbiostock_site']);
				
		$this->update_csv_info_to_networks( $network_associate_info  );
		
		//make a properly named file via unique network name key

		if (!copy($path, ABSPATH . 'symbiostock_network/' . $key  . '.csv')) {
			echo "failed to copy $file...\n";
		}
		
		if($path != ABSPATH . 'symbiostock_network/' . $key  . '.csv'){
			//delete old file
			unlink($path);			
		}
		
		$this->setup_network_directory($key);
				
	}
	
	//converts a CSV to an array
	public function csv_to_array( $filename = '', $delimiter = ',' )
	{
		if ( !file_exists( $filename ) || !is_readable( $filename ) )
			return FALSE;
		
		$header = NULL;
		$data   = array( );
		
		if ( ( $handle = fopen( $filename, 'r' ) ) !== FALSE ) {
			while ( ( $row = fgetcsv( $handle, 1000000, $delimiter ) ) !== FALSE ) {
				if ( !$header )
					$header = $row;
				else
					$data[ ] = array_combine( $header, $row );
			}
			fclose( $handle );
		}
				
		return $data;
	}	
	
	public function generate_network_info(){
		
		$network_info = array(); //our master array
		
		$theme_data = wp_get_theme('symbiostock');
				
		$network_options = array(
			//general settings
			'symbiostock_login_logo_link',
			'symbiostock_logo_link',
			'symbiostock_eula_page',
			'symbiostock_logo_for_paypal',
			'symbiostock_copyright_name',
			'symbiostock_currency',
			//network settings
			'symbiostock_my_network_name',
			'symbiostock_my_network_description',
			'symbiostock_my_network_avatar',
			'symbiostock_my_network_logo',
			'symbiostock_my_network_about_page',
			'symbiostock_my_network_announcement',
			'symbiostock_use_network',
			//default image prices
			'price_bloggee', 
			'price_small',
			'price_medium', 
			'price_large', 
			'price_vector', 
			'price_zip', 
		);
		
		$profile_info = get_option('symbiostock_social_credentials');
				
		$network_info['symbiostock_site'] =  site_url();
		
		$network_info['symbiostock_csv_generated_time'] = date("m.d.y");
		
		$network_info['symbiostock_num_images'] = wp_count_posts( 'image' )->publish;
		    					
		$network_info['symbiostock_version'] = $theme_data->Version;
		
		$network_info['symbiostock_URI'] = $theme_data->get( 'ThemeURI' );
		
		//we encrypt our emails to avoid them getting harvested by internet spammers. 
		$network_info['admin_email'] = symbiostock_email_convert(get_option('admin_email', ''));		
		$network_info['symbiostock_correspondence_email'] =symbiostock_email_convert(get_option('symbiostock_correspondence_email', ''));
		$network_info['symbiostock_paypal_email'] =symbiostock_email_convert(get_option('symbiostock_paypal_email', ''));
				
		foreach($network_options as $option){
			
			$network_info[$option] = get_option($option, '');
			
		}
		
		if($profile_info != false && !empty($profile_info)){
			
			foreach($profile_info as $key => $profile_entry){
				
				$network_info[$key] = $profile_entry; 
				
				}
			}
			
			$this->network_info = $network_info;
			
		}
	
	public function write_network_info(){
		
		$name = ABSPATH . '/symseed.csv';
			
		$fp = fopen($name , 'w');
		
		fputcsv($fp, array_keys( $this->network_info )); 
					
		fputcsv($fp, $this->network_info );
			
		fclose($fp);		
		
		}
	
	//generates a list of images for network hubs and other promo sites that use the info
	public function generate_image_list_info(){
		
			ini_set( "memory_limit", "1024M" );
			set_time_limit ( 0 );
			
			$images_meta = array();
			
			$image_vals = array(
				'id', 
				'image_id', //included
				'url', //included
				'fullimage_url', //included ???
				'thumbnail_url', //included
				'photographer_full_name', //included
				'keyword',//included
				'concepts',
				'category',
				'description', //included
				'caption', //included
				'model_release', //included
				'property_release',//included
				'location',
				'geolocation',
				'license_type', //included
				'collection',
				'color',
				'width', //included
				'height' //included
			);
			
			$args       = array(
				 'post_type' => 'image',
				'post_status' => 'publish',
				'posts_per_page' => -1,
				'caller_get_posts' => 1,				
			);	
						
			$all_images = null;
			$all_images = new WP_Query( $args );
			

			if ( $all_images->have_posts() ) {
					
					$image_meta = array();
					
				while ( $all_images->have_posts() ):
					
					$all_images->the_post();
					
					$id =  get_the_ID();
					
					//generate image id   
					$image_meta['image_id'] = $id;
					
					//generate licence type   
					$image_meta['license_type'] = 'RF';
					
					//generate url of image page   									
					$image_meta['url'] = get_permalink();	
					
					//generate preview pic location
					$preview = get_post_meta($id, 'symbiostock_preview');				
					$image_meta['fullimage_url'] = $preview[0]; 
					
					//generate thumbnail pic location
					$image_minipic = get_post_meta($id, 'symbiostock_minipic'); 				
					$image_meta['thumbnail_url'] = $image_minipic[0]; 	
					
					//generate model released
					$model_released = get_post_meta($id, 'symbiostock_model_released'); 					
					if(empty($model_released) || $model_released == false){
						$model_released[0] = 'N/A';
						}															
					$image_meta['model_release'] = $model_released[0]; 	
					

					//generate property released
					$property_released = get_post_meta($id, 'symbiostock_property_released'); 
					if(empty($property_released) || $property_released == false){
						$property_released[0] = 'N/A';
						}													
					$image_meta['property_release'] = $property_released[0]; 						
					
					
					//generate author name								
					$image_meta['photographer_full_name'] = get_the_author();	
					
					//generate caption				
					$image_meta['caption'] = the_title('', '', false);
					
					//generate description
					$image_meta['description'] = get_the_content();
					 
					//generate size info
					$size_info = get_post_meta($id, 'size_info'); 
					$size_info = unserialize($size_info[0]);
					$image_meta['width'] = $size_info['large']['width'];
					$image_meta['height'] = $size_info['large']['height'];
					
					
					//generate keywords
				    $terms = get_the_terms( $id, 'image-tags' );
					
					if ( $terms && ! is_wp_error( $terms ) ) : 

						$keywords = array();
					
						foreach ( $terms as $term ) {
							$keywords[] = $term->name;
						}
											
						$collected_keywords = join( ", ", $keywords );	
												
					 endif; 
					 
					 if(isset($collected_keywords)){
						 
						 $image_meta['keyword'] = $collected_keywords;
						 
						 } else {
							 
						 $image_meta['keyword'] = '';
							 
					 }
					 
					array_push($images_meta, $image_meta);
		
				endwhile;								
			
			}	

			$this->images_meta = $images_meta;	
		}

	public function write_image_list_info(){
		
		$name = ABSPATH . '/symbiostock_image_info.csv';

		$fp = fopen($name , 'w');
		
		fputcsv($fp, array_keys( $this->images_meta[0] )); 
		
		foreach($this->images_meta as $vals){					
			
			fputcsv($fp, $vals);
		
		}
		
		fclose($fp);		
		
		}
	
    //local search, responsible for generating local search results, returns xml.
    public function local_search( )
    {
        
        //determine number of results to show -
        
        $results_per_page = 5;
        
        if ( is_search() ) {
            $image_tags = get_query_var( 's' );
            
        } else {
            $image_tags = get_query_var( 'image-tags' );
        }
        
        //if this is a category page, then we set the value
        $category = get_query_var( 'image-type' );
        if ( isset( $category ) ) {
            $category = get_query_var( 'image-type' );
        } else {
            $category = NULL;
        }
        //case by case, we change our search query
        if ( is_tax( 'image-tags' ) ) {
            
            $tax_query = array(
                 array(
                     'taxonomy' => 'image-tags',
                    'field' => 'slug',
                    'terms' => preg_split( '/[+\s_-]/', $image_tags ),
                    'operator' => 'AND' 
                ) 
            );
        }
        
        if ( is_tax( 'image-type' ) ) {
            
            $term    = get_term_by( 'slug', get_query_var( 'image-type' ), 'image-type' );
            $term_ID = $term->term_id;
            
            $children = get_term_children( $term_ID, 'image-type' );
            array_push( $children, $term_ID );
            
            $tax_query = array(
                 array(
                     'taxonomy' => 'image-type',
                    'field' => 'id',
                    'terms' => $children 
                    
                ) 
            );
        }
        if ( is_search() ) {
            $search_terms = preg_split( '/[+\s_-]/', $image_tags );
            
            $tax_query = array(
                 'relation' => 'AND' 
                
            );
            
            foreach ( $search_terms as $search_term ) {
                
                $term_array = array(
                     'taxonomy' => 'image-tags',
                    'field' => 'name',
                    'terms' => trim( $search_term ) 
                );
                
                array_push( $tax_query, $term_array );
            }
            
        }
        //get correct page variable
        
        if ( get_query_var( 'paged' ) )
            $paged = get_query_var( 'paged' );
        
        elseif ( get_query_var( 'page' ) )
            $paged = get_query_var( 'page' );
        
        else
            $paged = 1;
        
        //make offset	
        
        $offset = ( $paged - 1 ) * $results_per_page;
        
        //Write query to display results or archive accordingly
        if ( !is_post_type_archive( 'image' ) ) {
            $local_query = array(
                 'post-type' => 'image',
                'paged' => $paged,
                'tax_query' => $tax_query 
            );
            
        } elseif ( is_search() ) {
            
            $local_query = array(
                 'post_type' => 'image',
                'post_status' => 'publish',
                'tax_query' => $tax_query,
                'paged' => $paged,
				'posts_per_page' =>24
            );
            
        } else {
            $local_query = array(
                 'post_type' => 'image',
                'post_status' => 'publish',
                'caller_get_posts' => 1,
                'paged' => $paged 
            );
            
            
        }
        
        $xml = symbiostock_xml_results( $local_query );
        
        $this->xml_results = $xml;
    }
    
    //this function loops through all networks and then runs the network search function
    //should only be called on the search or custom taxonomy page
    public function network_search_all_similar( )
    {
        
        $symbiostock_use_network = get_option( 'symbiostock_use_network', 'false' );
        
        if ( $symbiostock_use_network == 'true' ) {
            
            $network_limit = 5;
            $site_count    = 1;
            
            
            
            while ( $site_count <= 5 ) {
                
                $this->network_site_count = $site_count;
                
                $network_site = get_option( 'symbiostock_network_site_' . $site_count );
                
                //different sites might have wordpress installed at different levels like www.mystockphotosite.com/wordpress/
                //so we have to disect our url to get it to function properly...see $query below
                $sub_level = parse_url( get_home_url() );
                
                if ( symbiostock_validate_url( $network_site ) ) {
                    
                    $arr_params = array(
                         'symbiostock_network_search' => true,
                        'symbiostock_network_info' => true,
                        'paged' => 1 
                        //'symbiostock_number_results' => 5 
                    );
                    
                    $query = add_query_arg( $arr_params );
                    
                    //if we don't remove the path from our own url, we will mess up the query going to our friend's site
                    //hard to explain. If you want to see what happens when you don't do what is shown  here,
                    //comment out the line below and uncomment the echo statement below that
                    $query = str_replace( $sub_level[ 'path' ], '', $query );
                    //echo $query;
                    
                    $network_results = $this->get_remote_xml( $network_site . $query );
                    
                    $this->xml_results = $network_results;
                    $this->display_results( true );
                }
                
                $site_count++;
                
            }
        }
    }
    
    //Performs a network search, instigates local_search() on remote site.	  
    public function network_search( $site, $query = '' )
    {
        
        $site = rtrim( $string, '/' );
        
        $xml = $this->xml_results;
        
    }
    
    public function display_results( $network_search )
    {
        
        // $network_search set to true if a network search, false if local.
        
        $xml = $this->xml_results;
        
        $results = symbiostock_interpret_results( $xml );
        
        if ( !isset( $this->network_site_count ) ) {
            $this->network_site_count = '';
        }
        
        symbiostock_build_html_results( $results, $network_search, $this->network_site_count );
        
    }
    
    public function get_remote_xml( $url )
    {
        
        $ch = curl_init();
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
        curl_setopt( $ch, CURLOPT_URL, $url ); // get the url contents
        
        $data = curl_exec( $ch ); // execute curl request
        curl_close( $ch );
        
        if ( simplexml_load_string( $data ) ) {
            
            return $data;
            
        } else {
            
            return 'Results invalid. Cannot display.';
            
        }
        
        
    }
    
    public function network_page_query( $url )
    {
        
        $data = $this->get_remote_xml( $url );
        
        $this->xml_results = $data;
        
        $this->display_results( true );
        
    }
    
    public function display_xml_results( )
    {
        
        header( "Content-Type: text/plain" );
        
        $xml = $this->xml_results;
        
        echo $xml;
        
    }
    
}

function symbiostock_save_network_info(){
		
		$network_info = new network_manager();
		
		$network_info->generate_network_info();
		
		$network_info->write_network_info();
	
	}
function symbiostock_save_image_list_info(){
		
		$network_info = new network_manager();
		
		$network_info->generate_image_list_info();
		
		$network_info->write_image_list_info();
	
	}	
?>