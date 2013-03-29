<?php
//this class is responsible for performing network functions, getting network search results, and so forth.
include_once( 'communicator.php' );
include_once( 'interpreter.php' );
//this is for making a unique set of ID's for all search results, incrementing, regardless of network
class results_counter
{
    
    public $count = 0;
    
    public $network_site_count = 0;
    
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
        wp_reset_query();
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
        if ( is_tax( 'image-tags' ) || is_search() ) {
            $tax_query = array(
                 array(
                     'taxonomy' => 'image-tags',
                    'field' => 'name',
                    'terms' =>  preg_split('/[+\s_-]/', $image_tags ),
                    'operator' => 'AND' 
                ) 
            );
        }
        
        if ( is_tax( 'image-type' ) ) {
            $tax_query = array(
                 array(
                     'taxonomy' => 'image-type',
                    'field' => 'slug',
                    'terms' => explode( ' ', $category ),
                    'operator' => 'AND' 
                ) 
            );
        }
        
		//get correct page variable
		
		if ( get_query_var('paged') )
		
			$paged = get_query_var('paged');
			
		elseif ( get_query_var('page') )
		
			$paged = get_query_var('page');
			
		else
    		$paged = 1;	
			
		//make offset	
        
		$offset = ( $paged - 1 ) * $results_per_page;
        $local_query = array(  
	 		'post-type' => 'image',
            'paged' => $paged,
            'tax_query' => $tax_query,
	
	
        );
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
				$sub_level = parse_url(get_home_url());
				
                if ( symbiostock_validate_url( $network_site ) ) {
                   				   				    
                    $arr_params = array(
                         'symbiostock_network_search' => true,
                        'symbiostock_network_info' => true,
						'paged'=> 1
                        //'symbiostock_number_results' => 5 
                    );                    
					
                    $query = add_query_arg( $arr_params );					
                    
					//if we don't remove the path from our own url, we will mess up the query going to our friend's site
					//hard to explain. If you want to see what happens when you don't do what is shown  here,
					//comment out the line below and uncomment the echo statement below that
					$query = str_replace($sub_level['path'], '', $query);
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
        
        $xml     = $this->xml_results;
        
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
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
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
?>