<?php
//simply a function that gives our network manager class useable results
//this converts our simple xml object to a basic array, more easy to work with
function sx_array($obj){
	$arr = (array)$obj;
	if(empty($arr)){
		$arr = "";
	} else {
		foreach($arr as $key=>$value){
			if(!is_scalar($value)){
				$arr[$key] = sx_array($value);
			}
		}
	}
	
	return $arr;
}
function symbiostock_interpret_results( $symbiostock_xml_results )
{    
	//testing, uncomment next lines
	//header( "Content-Type: text/plain" ); 
	//echo $symbiostock_xml_results;
	
    $results = new SimpleXmlElement( $symbiostock_xml_results );
     
	$results = sx_array($results);
	    
    return $results;
    
}
//this lists the hidden inputs, which contains info for modal windows
function symbiostock_list_attr_inputs($count, $image){
	
	$meta_values = array(
		'price_bloggee',
		'price_small',
		'price_medium',
		'price_large',
		'price_vector',
		'price_zip',
		'discount_percent',
		'exclusive',
		'symbiostock_preview',
		'symbiostock_transparency',
		'size_eps',
		'size_zip',
		'extensions',
		'title',
		'permalink', 
		'author',
	);
	foreach($meta_values as $value){
		$id_key = 'n' . $count . '_' . $image['id'] . '_' .$value;
		
		//this huge if statement should be worked over to something better and more comprehensive
		//I tried a switch statement but got nothing better
		
		if ($value == 'extensions' && !empty($image['extensions']['ext'])){
			
			if(is_array($image['extensions']['ext'])){
				$info = implode(', ', $image['extensions']['ext']);
			}
			} elseif(isset($image[$value]) && is_array($image[$value])){
			
			$info = implode(', ', $image[$value]);
			
			} elseif (isset($image[$value]) && is_string($image[$value])) {
				
			$info = $image[$value];
				
			} else {$info = '';}
		?>
       
		<input type="hidden" id="<?php echo $id_key; ?>" name="<?php echo $id_key; ?>" value="<?php echo $info; ?>" />
		<?php
				
		}
	
			if(is_array($image['size_info'])){
			
			foreach($image['size_info'] as $size => $size_info){
				$id_key = 'n' . $count . '_' . $image['id'] . '_' . $size;
				$size_attr = $size_info['width'] . '×' . $size_info['height'] . 'px';
				
				?>                
                <input type="hidden" id="<?php echo $id_key; ?>" name="<?php echo $id_key; ?>" value="<?php echo $size_attr; ?>" />
                <?php
				
			}
		}
	}
//this function generates the controls and buttons that show under each search result\
//it seemed appropriate to have a separate function for it in case things got more elaborate
function symbiostock_hover_controls($count, $id, $permalink){
	
	$id = 'n' . $count . '_' . $id;
	
	?><br />
    <span class="sscntrl">
        <span>
            <a class="modal_activate" id="<?php echo $id . '_details'; ?>" title="Preview" data-toggle="modal" href="#symbiostock_display_preview" ><i class="icon-zoom-in">&nbsp;</i></a>
            <a id="<?php echo $id . '_goto'; ?>" title="Go to royalty free image." href="<?php echo  $permalink; ?>"><i class="icon-share-alt">&nbsp;</i></a>
        </span>   
        <?php
        //set up for our adminstration commands as well
				
       get_currentuserinfo(); 
	   global $user_level; 
	   
	   if ($user_level > 8) {
        ?> | <span>
            <a id="<?php echo $id . '_feature'; ?>" title="Feature This" href="#"><i class="icon-star">&nbsp;</i></a>
            <a id="<?php echo $id . '_block'; ?>" title="Block Result" href="#"><i class="icon-ban-circle">&nbsp;</i></a>
        </span>  
    </span> 
    <?php
}
	}
function symbiostock_display_pagination($pagination, $results, $position, $size){
	
	?><div class="symbiostock_pagination pagination-<?php echo $position; ?> pagination <?php echo $size; ?>"><div class="pagination">
    
    <ul>
	<?php
	//display pagination
	if(!empty($pagination)){
		
		foreach($pagination as $page_result){
			
			echo '<li>' . $page_result . '</li>';
			
			}		
		
		}
	?>
    <li class="disabled">
    <span><?php echo 'Results: ' . $results; ?></span>
    </li>
    
    </ul></div></div> <?php
	}
	
function symbiostock_build_html_results($results, $network_search, $site_count = 0){
	//site_count variable is simply the site's position on the page/loop. 
	//if this is an incoming request, we alter the page_count according to $_POST['symbiostock_site_order'] so that our 
	//friend's page handle's it properly.
	if(isset($_POST['symbiostock_site_order'])){
		
		$site_count = $_POST['symbiostock_site_order'];
		
		}
	
	if($network_search == true){ 
	
	$network_info = $results['network_info'];
	
	//validate incoming network attributes
	//about page
	if(isset($network_info['symbiostock_my_about_page']) && !empty($network_info['symbiostock_my_about_page'])){
		$symbiostock_my_about_page = $network_info['symbiostock_my_about_page'];
	} else {
		$symbiostock_my_about_page = $network_info['url'];	
	}
	
	//avatar
	if(isset($network_info['symbiostock_my_network_avatar']) && !empty($network_info['symbiostock_my_network_avatar'])){
		$symbiostock_my_network_avatar = $network_info['symbiostock_my_network_avatar'];
	} else {
		$symbiostock_my_network_avatar = symbiostock_32_DEFAULT;	
	}	
	//logo
	if(isset($network_info['symbiostock_my_network_logo']) && !empty($network_info['symbiostock_my_network_logo'])){
		$symbiostock_my_network_logo = $network_info['symbiostock_my_network_logo'];
	} else {
		$symbiostock_my_network_logo = symbiostock_128_DEFAULT;	
	}	
	
	//name
	if(isset($network_info['symbiostock_my_network_name']) && !empty($network_info['symbiostock_my_network_name'])){
		$symbiostock_my_network_name = $network_info['symbiostock_my_network_name'];
	} else {
		$symbiostock_my_network_name =  $network_info['url'];	
	}
		
		
	?><div id="network_site_<?php echo $site_count; ?>" class="network_results row-fluid">
    	<div class="span12 well well-small network_results_header"><img class="img-rounded" alt="<?php echo $network_info['symbiostock_my_network_name']; ?>" src="<?php echo $network_info['symbiostock_my_network_avatar']; ?>" />
        <span class="network_name text-info"><?php echo $network_info['symbiostock_my_network_name']; ?> network results ( <?php echo $network_info['url']; ?> )</span>
        </div>
        <div class="span2">
        <a target="_blank" title="<?php echo $network_info['symbiostock_my_network_name']; ?>" href="<?php echo $symbiostock_my_about_page; ?>">
        <img class="img-rounded network_logo" alt="<?php echo $network_info['symbiostock_my_network_name'] . ' Logo'; ?>" src="<?php echo $network_info['symbiostock_my_network_logo']; ?>" />
        </a>
        </div>
        <div class="network_results_container">
    <?php	
	}
	
	//check and set pagination results	
	if(is_array($results['pagination']) && array_key_exists('page', $results['pagination']) && !empty($results['pagination']['page'])){
						
		$pagination = $results['pagination']['page'];	
				
		$paginate = true;
	} else {
		
		$pagination = array();	
		$paginate = false;
		}	
	
	?><div class="results_info">
    <?php 
	if($paginate == true && $network_search == false){
		symbiostock_display_pagination($pagination, $results['total_results'], 'right', 'pagination-small');
	} elseif ($network_search == false){
		
		echo '<span>Results: ' . $results['total_results'] . '</span>';
		}
	?>
    </div>
	<?php	
	
	
	$invis_tag = array(	
	);
	
	$total_results =  $results['total_results'];
	
	//check and set image results
	if(is_array($results) && array_key_exists('image', $results) && !empty($results['image'])){
						
		$image_results = $results['image'];	
		
	} else {
		
		return '<p>No results found.</p>';
		
		}
	
	
	//if our array was created with only one item, we have to modify it so it can be processed properly	
	//we see if the array depth is altered from what we usually expect
	if(!isset($image_results[0])){
		//if it is, then we shift the array down one level
		$tmp = array();
		$tmp[0]=$image_results;
		
		$image_results = $tmp;
		}
	
	//check and set network info results
		
	foreach($image_results as $image){
		global $sscount;
		
		if(!is_object($sscount)){
			//if this is an incoming request, we have to set up counts to mirror the results on that page, using incoming vars
			$sscount = new results_counter();
			
			$sscount->count = $_POST['symbiostock_start_count'];
					
		} 		
		
		?> <input type="hidden" id="network_site_<?php echo $site_count; ?>_start_count" value="<?php echo $sscount->count; ?>" /> <?php	
			
		
		
		$count = $sscount->plus_1();
	
		
		
		?>
        
        <div id="n<?php echo $count; ?>_<?php echo $image['id'] ?>_image" class="search-result">
            <a class="search_result_preview" title="<?php echo $image['title'] ?>" href="<?php echo $image['permalink']  ?>">
              <img class="search_minipic" src="<?php echo $image['symbiostock_minipic']  ?>" />
            </a>
            <?php symbiostock_list_attr_inputs($count, $image); ?>
            <?php symbiostock_hover_controls($count, $image['id'], $image['permalink']); ?>
        </div>
        
        <?php
		
		}
	?> </div> <?php
	if($network_search == true){
		
		$position = 'right';
		
		$size = 'small';
		
		//correct the output of our pagination so the user doesn't get led to xml results	
		//extracts all $_GET vars attached to href, everything after "?" and before "'", 
					
		$remove_vars = array(
		'symbiostock_network_search',
		'symbiostock_network_info',
		);
	
	$corrected_pagination = array();
		
	foreach($pagination as $href_link){
		
		$href_link = str_replace('&hellip;', '...', $href_link);	
						
		$a = new SimpleXMLElement( $href_link );
		$link = $a['href'];
					
		
		if(isset($link) && !empty($link)){
			
		$user_link = remove_query_arg( $remove_vars, $link);				
	
		//a little confusion going on here. Watch for bugs.
		//$user_link = $link;		
		$edited_link = str_replace($link, $user_link, html_entity_decode($href_link));
		
		$edited_link = str_replace("href", "data-networklink='" . htmlentities($link) . "' href", $edited_link);
											
		} else { $edited_link = $href_link;}
		
		array_push($corrected_pagination, $edited_link);
		
		unset($a);
		}
				
		$pagination = $corrected_pagination;	
				
	} else {
		
	$position = 'centered';
	
	$size = 'large';	
		}
	
	if($paginate == true){
		symbiostock_display_pagination($pagination, $results['total_results'], $position, 'pagination-' . $size);
	}
	
	if($network_search == true){ echo '</div>'; }
}
?>