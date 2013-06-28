<?php
function use_symbiostock_marketer(){
	
	$use = get_option('use_symbiostock_marketer', false);
	
	if($use == true){
		return true;		
	} else {		
		return false;	
	}
}	

function symbiostock_marketer(){
	
	$marketer_user_key = get_option('marketer_user_number');
	
	$marketer_key = get_query_var( 'ss-' . $marketer_user_key );
	
	if(!use_symbiostock_marketer())
		return false;
	
	
	//add_rewrite_tag('%ss-'.$image_number.'%','([^&]+)');
		
	if(isset($marketer_key) && $marketer_key != false && $marketer_key != 'ss-88888888'){
		ini_set( "memory_limit", "1024M" );
		set_time_limit( 300 );	
				
		//first, see if we are requesting an image
		$image_number = get_query_var( 'image_number' );

		if(isset($image_number) && $marketer_key=='image' && !empty($image_number) && is_numeric($image_number)){
			$file = symbiostock_STOCKDIR . trim($image_number) . '_promo.jpg';
			if(file_exists($file)){				
				$type = 'image/jpeg';
				header('Content-Type:'.$type);
				header('Content-Length: ' . filesize($file));
				readfile($file);
				
				exit;				
				} 
			}
				
		$type = get_query_var( 'type' );
		$date = get_query_var( 'date' );
		$time = get_query_var( 'time' );
		
		//START THE QUERY
		$page = get_query_var( 'page' ) ? get_query_var( 'page' ) : 1;
		
		$args = array(
             'post_type' => 'image',
            'post_status' => 'publish',
			'paged' => $page
        );
		
		//at first we wanted to dump all info, but this might not be smart for people with rediculously large portfolios
		switch($type){
			
			case 'all';
							
			break;					
			
		}
	
		
		if(isset($date) && !empty($date)){
		
		function ss_filter_where( $where = '' ) {
			// posts for March 1 to March 15, 2010
			
			$date=implode('-', array_reverse(explode('-', get_query_var( 'date' ))));
						
			$where .= " AND post_date >= '".$date."'";
			
			return $where;
			
		}		
			add_filter( 'posts_where', 'ss_filter_where' );
		}
		
		$images_meta = array();	
		$results_info = array();	
		$count = 1;
		
		$marketing_query = null;
		$marketing_query= new WP_query($args);
		
		if($marketing_query->have_posts()) :
			
			$results_info['found_posts'] = $marketing_query->found_posts;
			$results_info['max_num_pages'] = $marketing_query->max_num_pages;			
			$results_info['post_count'] = $marketing_query->post_count;
			
			while($marketing_query->have_posts()) : $marketing_query->the_post();
			
				$id = get_the_ID();
            	
				if(!file_exists(symbiostock_STOCKDIR . trim($id) . '_promo.jpg'))
					continue;	
				
				$count++;
					    
				//our key / id
				$image_meta[ 'id' ] = 'ss-' . $marketer_user_key;
				
                //generate image id   
                $image_meta[ 'image_id' ] = $id;
                
                //generate licence type   
                $image_meta[ 'license_type' ] = 'RF';
                
                //generate url of image page                                       
                $image_meta[ 'url' ] = get_permalink();
                
                //generate preview pic location
				
				$preview = add_query_arg( array( 'ss-' . $marketer_user_key => 'image', 'image_number' => $id ), get_bloginfo('url') );
				
                $image_meta[ 'fullimage_url' ] = $preview;
                
                //generate thumbnail pic location
                $image_minipic                 = get_post_meta( $id, 'symbiostock_minipic' );
                $image_meta[ 'thumbnail_url' ] = $image_minipic[ 0 ];
                
                //generate model released
                $model_released = get_post_meta( $id, 'symbiostock_model_released', 'N/A'  );
                if ( empty( $model_released ) || $model_released == false ) {
                    $model_released[ 0 ] = 'N/A';
                } //empty( $model_released ) || $model_released == false
                $image_meta[ 'model_release' ] = $model_released[ 0 ];
                
                //generate property released
                $property_released = get_post_meta( $id, 'symbiostock_property_released', 'N/A' );
                if ( empty( $property_released ) || $property_released == false ) {
                    $property_released[ 0 ] = 'N/A';
                } //empty( $property_released ) || $property_released == false
                $image_meta[ 'property_release' ] = $property_released[ 0 ];                
                
                //generate author name                                
                $image_meta[ 'photographer_full_name' ] = get_the_author();
                
                //generate caption                
                $image_meta[ 'caption' ] = the_title( '', '', false );
                
                //generate description
                $image_meta[ 'description' ] = get_the_content();
                
                //generate size info
                $size_info              = get_post_meta( $id, 'size_info' );
                $size_info              = unserialize( $size_info[ 0 ] );
                $image_meta[ 'width' ]  = $size_info[ 'large' ][ 'width' ];
                $image_meta[ 'height' ] = $size_info[ 'large' ][ 'height' ];
                
				//sanitize info
				$image_meta = array_map('addslashes', $image_meta);
				$image_meta = array_map('trim', $image_meta);
                
                //generate keywords
                $terms = get_the_terms( $id, 'image-tags' );
                
                if ( $terms && !is_wp_error( $terms ) ):
                    $keywords = array( );
                    foreach ( $terms as $term ) {
                        $keywords[ ] = $term->name;
                    } //$terms as $term
                    $collected_keywords = join( ", ", $keywords );
                endif;
                
                if ( isset( $collected_keywords ) ) {
                    
                    $image_meta[ 'keyword' ] = $collected_keywords;
                    
                } //isset( $collected_keywords )
                else {
                    
                    $image_meta[ 'keyword' ] = '';
                    
                }
                
                array_push( $images_meta, $image_meta );
			
			endwhile;
		
		endif;
		//END THE QUERY
		
		if($marketer_key == 'xml'){		
			//create the xml document				
			$xml = new DOMDocument();	
	
			$root = $xml->appendChild( $xml->createElement( "results" ) );
			
			$root->appendChild( 
					$xml->createElement( "pages", $results_info['max_num_pages'] ) );
			
			$root->appendChild( 
				$xml->createElement( "results", $results_info['found_posts'] ) );	
				
			$root->appendChild( 
				$xml->createElement( "per_page", $results_info['post_count'] ) );					
			
			foreach($images_meta as $image_meta){
			
				$imageTag = $root->appendChild( 
					$xml->createElement( "image" ) );
					
					foreach($image_meta as $key => $value){					
					
						if($key == 'description' || $key == 'caption'){						
	
							$imageTag->appendChild( 
								$cdata = $xml->createElement( $key ) );
								
							$cdata->appendChild(
								$xml->createCDATASection( $value ) );	
														
						} else {						
							$imageTag->appendChild( 
								$xml->createElement( $key , ssde($value) ) );						
							}
	
						}
				
				}
			
			$xml->formatOutput = true;
			
			$results = $xml->saveXML();
			
			header( "Content-Type: text/plain" );
			echo $results;
			
		}
		return true;
				
		}
		
	}		
?>