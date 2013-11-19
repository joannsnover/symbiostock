<?php 
function symbiostock_keyword_update($tag = ''){

        
    if( function_exists('get_terms_meta') && function_exists('update_terms_meta')){   
        
        $term = get_term_by( 'slug', $tag, 'image-tags', ARRAY_A );

        if(is_array($term) && !empty($term)){
        
            $n = get_terms_meta($term['term_id'], 'views');
            
            if(!isset($n[0]) || empty($n[0])){
                
                update_terms_meta($term['term_id'], 'views', 1);
                
            } else {                                              
                $n[0]++;                
                update_terms_meta($term['term_id'], 'views', $n[0]);                                               
            } 
            
            //write log file
            
            if(isset($_GET['r'])){
                $r = str_replace('http://', '', $_GET['r']);
            } else {
                $r = 0;                
            }
            
            if($_SERVER['SERVER_ADDR'] == $_SERVER['REMOTE_ADDR']){
                $type = 1;
            } else {
                $type = 2;
            }
            
            $info = array(
                    $tag, //keyword
                    $type, // 1 = local, 2 = remote
                    $r, // referrer (humans only)
                    $_SERVER['REMOTE_ADDR'], //remote referring IP                    
                    current_time( 'mysql', 1 ), //IMPORTANT gives GMT time
                    );
            
            $name = ABSPATH . '/symbiostock_search_log.csv';
            
            //if its the first of the month, delete file
            if(date('d') == 1){                
                if(file_exists($name)){
                    unlink($name);
                }            
            }
            
            if(file_exists($name)){
                $linecount = 0;
                $handle = fopen($name, "r");
                while(!feof($handle)){
                    $line = fgets($handle);
                    $linecount++;
                }
                if($linecount > 5000){                
                    unlink($name);
                }
            }            
            file_put_contents($name, implode(',', $info).PHP_EOL, FILE_APPEND);            
        }
  
    }

}
