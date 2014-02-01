<?php
/**
 * Symbiostock's Analytics System. Tracks general visits and network referrals.
 * 
 * This class tracks 4 page types: Image Views, Keyword Views, Keyword History, Category Views, and Human Searches.
 * It also tracks referring URLs as well as Symbiostock-specific referrals (from the networks).
 * It tracks general history back 60 days.
 * 
 * To call analytics, you must add the proper variables to the URL:
 * 
 * ss_analytics=<analytic type> (history|referrals|image_views|term_views)
 * If getting traffic history ss_analytics=history, then you must specify the date range.
 * 
 * Example General History: 
 * "?ss_analytics=history&ss_sdt=2014-01-28_21:05:37&ss_edt=2014-01-30_00:00:00" (mysql datetimes)
 * 
 * Example, Keywords History:
 * ?ss_analytics=keyword_history&ss_sdt=2014-01-27_06:48:45&ss_edt=2014-01-31_06:50:14
 * 
 * (ss_sdt means Start Date, ss_edt means End Date).
 * 
 * @link http://dev.mysql.com/doc/refman/4.1/en/datetime.html
 * 
 * @category   Symbiostock Analytics
 * @package    Symbiostock
 * @author     Leo Blanchette <leo@symbiostock.com>
 * @copyright  Symbiostock
 */

class ss_analytics{
	
	/**
	 * Whether or not to execute analytics log for given page. 1 = yes, 0 = no
	 * @var bool 
	 */
	public $execute;
		
	/**
	 * Client IP
	 * @var string
	 */
	public $client_ip;

	/**
	 * Type of page: 1=image, 2=keyword, 3=category, 4=search
	 * @var int
	 */
	public $type;
	
	/**
	 * ID of Image or Term
	 * @var int
	 */
	public $id;
	
	/**
	 * User-entered search terms, when they are entering searches while browsing a Symbiostock site.
	 * @var string
	 */
	public $search_term;
		
	/**
	 * If this is a symbiostock site referral, we log it.
	 * @var string
	 */
	public $referrer;
	
	/**
	 * The URL of the general referrer, Symbiostock site or not.
	 * @var string url of referrer
	 */
	public $referring_url;
	
	/**
	 * When the site delivers search results, this variable distinguishes between local, network, and promoted results. 
	 * @var int 1: local 2: network 3: promoted
	 */
	public $search_result_type;
	
	/**
	 * For local or network results, we list the website its coming from. This is used in keyword tracking.
	 * @var string the website results came from.
	 */
	public $results_website;
	
	/**
	 * Set up initial class properties for analytics
	 */		
	
	/**
	 * For tracking both keywords and tags as one.
	 * @var string
	 */
	public $keyword_or_tag;
	
	
	public function __construct($action, $site = '', $result_type = '' ){
		
		global $wpdb;
		
		global $analytic_run_once;

		$public_analytics = get_option('symbiostock_public_analytics', 1);
		
		if($action == 1){
			
			header('Content-type: text/plain');
			
					
			if($public_analytics == 0){
				echo 0;
				die;				
			}			
			
			
			switch($_GET['ss_analytics']){
				
				case 'history':
					
					$this->history();
					
				break;
				
				case 'keyword_history':
						
					$this->keyword_history();
						
				break;
				
				case 'referrals':
					
					$this->referrals();
					
				break;
				
				case 'image_views':
					
					$this->image_views();
					
				break;
				
				case 'term_views':
					
					$this->term_views();
					
				break;
				
			}			
			die;
			
		}
		
		
		
		$theme_data = wp_get_theme( 'symbiostock' );
		
		$last_version = get_option('ss_analytics_last_version', 0);
						
		if($last_version < $theme_data->version){
			$installer = 1;
		} else {
			$installer = 0;
		}

		if($installer){
			$ss_analytic_tables = array(
					
				//ANALYTICS HISTORY		
				"CREATE TABLE IF NOT EXISTS `".$wpdb->prefix."ss_analytics_history` (
				`time` datetime NOT NULL,
				`ip` varchar(128) NOT NULL,
				`type` int(11) NOT NULL,
				`id` int(11) NOT NULL,
				`ss_referrer` varchar(2000) NOT NULL,
				`r_url` varchar(2000) NOT NULL,
				`search_term` varchar(128) NOT NULL
				) ENGINE=InnoDB DEFAULT CHARSET=latin1;",

				//ANALYTICS KEYWORD PERFORMANCE
				"CREATE TABLE IF NOT EXISTS `".$wpdb->prefix."ss_analytics_keyword_performance` (
				`time` datetime NOT NULL,
				`site` varchar(2000) NOT NULL,
				`keyword` varchar(100) NOT NULL,
				`result_type` int(11) NOT NULL
				) ENGINE=InnoDB DEFAULT CHARSET=latin1;",					
					
				//IMAGE ANALYTICS		
				"CREATE TABLE IF NOT EXISTS `".$wpdb->prefix."ss_analytics_image` (
				  `id` int(11) NOT NULL,
				  `lastview` datetime NOT NULL,
				  `views` int(11) NOT NULL,
				   PRIMARY KEY (`id`),
                   UNIQUE KEY `id` (`id`)
				) ENGINE=InnoDB DEFAULT CHARSET=latin1;",
				
				//TERM ANALYTICS		
				"CREATE TABLE IF NOT EXISTS `".$wpdb->prefix."ss_analytics_term` (
				  `id` int(11) NOT NULL,
				  `lastview` datetime NOT NULL,
				  `views` int(11) NOT NULL,
				   PRIMARY KEY (`id`),
                   UNIQUE KEY `id` (`id`)					
				) ENGINE=InnoDB DEFAULT CHARSET=latin1;",
				
				//REFERRAL ANALYTICS		
				"CREATE TABLE IF NOT EXISTS `".$wpdb->prefix."ss_analytics_referrals` (
				  `siteid` varchar(50) NOT NULL,
				  `referrals` int(11) NOT NULL,
				  PRIMARY KEY (`siteid`),
				  UNIQUE KEY `siteid` (`siteid`)
				) ENGINE=InnoDB DEFAULT CHARSET=latin1;",
			);
			
			foreach($ss_analytic_tables as $ss_analytic_table){
				$wpdb->query($ss_analytic_table);
				
			}	
			
			update_option('ss_analytics_last_version', $theme_data->version);
		}
		
		
		//set up IP address
		$this->client_ip = $this->get_client_ip();
			
		//if image, carry out image analytic functions
		if ( 'image' == get_post_type() ){
			
			//Since we are on valid page, we can issue an analytic logging
			$this->execute = 1;
			
			$this->type = 1;			
			$this->id = get_the_ID();
		}
			
		//if is keyword taxonomy, set up keyword analytic functions
		if ( is_tax('image-tags') ){
			
			//Since we are on valid page, we can issue an analytic logging
			$this->execute = 1;
			
			$this->type = 2;
			$tag = get_query_var( 'image-tags' );
			
			$obj = get_term_by( 'slug', $tag, 'image-tags', OBJECT);
			
			$this->keyword_or_tag = $obj->name;			
			
			$this->id = $obj->term_id;
		}
			
		//if is category taxonomy, set up category analytic functions
		if ( is_tax('image-type') ){
			
			//Since we are on valid page, we can issue an analytic logging
			$this->execute = 1;			
			
			$this->type = 3;
			$category = get_query_var( 'image-type' );
			
			$obj = get_term_by( 'slug', $category, 'image-type', OBJECT);
						
			$this->id = $obj->term_id;
		}
			
		//if is search, set up search analytic functions
		if ( is_search() ) {
			
			//Since we are on valid page, we can issue an analytic logging
			$this->execute = 1;
			
			$this->type = 3;
			
			$s = get_query_var( 's' );

			$this->keyword_or_tag = $s;
			
			$this->search_term = $s;			
		} else {
			$this->search_term = 'NA';
		}
		
		//get Symbiostock Referrer (if from another site)
		if(isset($_GET['r'])){
			
			if(!symbiostock_validate_url( $_GET['r'] ))
				$this->referrer = 'NULL';	
					
			$this->referrer = $_GET['r'];
		} else {
			$this->referrer = 'NULL';
		}
		
		if(isset($_SERVER['HTTP_REFERER'])){
			$this->referring_url = $_SERVER['HTTP_REFERER'];
		} else {
			$this->referring_url = 'NULL';
		}		

		if ((isset($_SERVER['HTTP_X_MOZ'])) && ($_SERVER['HTTP_X_MOZ'] == 'prefetch')) {
			// This is a prefetch request. stop it.
			$this->execute = 0;	
		}
		
		if($this->execute == 1){
			
			$this->update_referrals();
			
			switch($this->type){
				case 1: #IMAGE
					$this->update_history();
					$this->update_image_analytic();
				
				break;
				
				case 2: #TAG
					$this->update_history();
					$this->update_term_analytic();
				
				break;
				
				case 3: #CATEGORY
					$this->update_history();
					$this->update_term_analytic();				
				break;					
			}
			
			if($action == 2){
				if(empty($site))
					return;
								
				$this->results_website = $site;
				$this->search_result_type = $result_type;
				$this->update_keyword_performance();
			}
			
			$this->delete_expired();
		}
		
	}
	
	/**
	 * Logs a page visit to the analytics history.
	 */
	public function update_history(){
		global $wpdb;		
	
		$results = $wpdb->insert(
				$wpdb->prefix."ss_analytics_history",
				array(
						'time'        => date('Y-m-d H:i:s'),
						'ip'          => mysql_real_escape_string($this->client_ip),
						'type'        => mysql_real_escape_string($this->type),
						'id'          => mysql_real_escape_string($this->id),
						'ss_referrer' => mysql_real_escape_string($this->referrer),
						'r_url'       => mysql_real_escape_string($this->referring_url),
						'search_term' => mysql_real_escape_string($this->search_term)
				),
				array(
						'%s', // time
						'%s', // ip
						'%s', // type
						'%s', // id
						'%s', // ss_referrer
						'%s', // r_url
						'%s', // search_term
				)
		);		
	}
	
	/**
	 * Updates image count
	 */
	function update_image_analytic(){	
		global $wpdb;	
		
		$wpdb->query(
			"INSERT INTO `".$wpdb->prefix."ss_analytics_image`
			(`id`, `lastview`, `views`)
			VALUES
			(".$this->id.", '".date('Y-m-d H:i:s')."', 1)
			ON DUPLICATE KEY UPDATE
			views = views + 1, 
			lastview = '".date('Y-m-d H:i:s')."'"
		);	
	}

	/**
	 * Updates term count
	 */
	function update_term_analytic(){
		global $wpdb;
	
		$wpdb->query(
				"INSERT INTO `".$wpdb->prefix."ss_analytics_term`
			(`id`, `lastview`, `views`)
			VALUES
			(".$this->id.", '".date('Y-m-d H:i:s')."', 1)
			ON DUPLICATE KEY UPDATE
			views = views + 1,
			lastview = '".date('Y-m-d H:i:s')."'"
		);
	}
	
	/**
	 * Updates keyword performance 
	 */
	function update_keyword_performance(){
		global $wpdb;
		
		if(empty($this->keyword_or_tag) || empty($this->keyword_or_tag))
			return;
		
		$wpdb->query(
			"INSERT INTO `".$wpdb->prefix."ss_analytics_keyword_performance`
			(`time`, `site`, `keyword`, `result_type`)
			VALUES
			('".date('Y-m-d H:i:s')."', '".$this->results_website."', '".$this->keyword_or_tag."', ".$this->search_result_type.")"
		);
	}

	/**
	 * Updates referral count
	 */
	function update_referrals(){
		global $wpdb;
	
		$wpdb->query(
			"INSERT INTO `".$wpdb->prefix."ss_analytics_referrals`
			(`siteid`, `referrals`)
			VALUES
			('".mysql_real_escape_string($this->referrer)."', 1)
			ON DUPLICATE KEY UPDATE
			referrals = referrals + 1"
		);
	}
	
	/**
	 * Deletes history over 60 days old
	 */
	public function delete_expired(){
		$expiration = 60;		
		global $wpdb;		
		$wpdb->query("DELETE FROM ".$wpdb->prefix."ss_analytics_history 
				WHERE time < UNIX_TIMESTAMP(DATE_SUB(NOW(), 
				INTERVAL ".$expiration." DAY))");
		$wpdb->query("DELETE FROM ".$wpdb->prefix."ss_analytics_keyword_performance
				WHERE time < UNIX_TIMESTAMP(DATE_SUB(NOW(),
				INTERVAL ".$expiration." DAY))");
		}
	
	
	/**
	 * Fairly reliable function for getting the IP address of visitor.
	 * 
	 * @return string ip address
	 */
	public function get_client_ip() {	     
		$ipaddress = '';
	     if (getenv('HTTP_CLIENT_IP'))
	         $ipaddress = getenv('HTTP_CLIENT_IP');
	     else if(getenv('HTTP_X_FORWARDED_FOR'))
	         $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
	     else if(getenv('HTTP_X_FORWARDED'))
	         $ipaddress = getenv('HTTP_X_FORWARDED');
	     else if(getenv('HTTP_FORWARDED_FOR'))
	         $ipaddress = getenv('HTTP_FORWARDED_FOR');
	     else if(getenv('HTTP_FORWARDED'))
	        $ipaddress = getenv('HTTP_FORWARDED');
	     else if(getenv('REMOTE_ADDR'))
	         $ipaddress = getenv('REMOTE_ADDR');
	     else
	         $ipaddress = 'UNKNOWN';	
	     return $ipaddress; 
	}
	
	public function time_hint(){
		return "Try: \n\n" . '&ss_std=' . 
		str_replace(' ', '_', date("Y-m-d H:i:s",strtotime(date("Y-m-d H:i:s"))-(60*60*24))) . 
		'&ss_edt=' . str_replace(' ', '_', date('Y-m-d H:i:s'))
		."\n\n(Retrieves one day back from present server time)";
	}
	
	//----   ----   ----   Analytics Sharing   ----   ----   ----   ----

	public function generateCsv($data, $delimiter = ',', $enclosure = '"') {
       $handle = fopen('php://temp', 'r+');
       foreach ($data as $line) {
               fputcsv($handle, $line, $delimiter, $enclosure);
       }
       rewind($handle);
       while (!feof($handle)) {
               $contents .= fread($handle, 8192);
       }
       fclose($handle);
       return $contents;
	}
	
	/**
	 * Gets general history logs of visits.
	 */
	public function history(){
		global $wpdb;
		
		#date format: 2010-10-01
		
		if(!isset($_GET['ss_std'])||!isset($_GET['ss_edt'])){
			echo "Request invalid\n";
		}		
				
		$start = mysql_real_escape_string(trim(str_replace('_', ' ', $_GET['ss_sdt'])));
		$end   = mysql_real_escape_string(trim(str_replace('_', ' ', $_GET['ss_edt'])));
		
		$history = $wpdb->get_results( 
			"SELECT *
			FROM `".$wpdb->prefix."ss_analytics_history`
			WHERE time 
			BETWEEN '".$start."' AND '".$end."'
			ORDER BY time DESC", ARRAY_A
		);	
		
		if(empty($history)){
			echo "No results for given parameters\n\n";
			echo $this->time_hint();
			return;
		}
		
		$keys = array(
			'TIME',
			'IP',
			'TYPE(1:image 2:keyword 3:category)',
			'ID',
			'SYMBIOSTOCK REFERRAL',	
			'REFERRING URL',
			'SEARCH TERM (human entered)',				
		);
		
		array_unshift($history, $keys);
		
		echo $this->generateCsv($history);
	}

	/**
	 * Gets history of keyword results in general search (for keyword research)
	 */
	public function keyword_history(){
		global $wpdb;
	
		#date format: 2010-10-01
	
		if(!isset($_GET['ss_sdt'])||!isset($_GET['ss_edt'])){
				echo "Request invalid\n";
		}
	
		$start = mysql_real_escape_string(trim(str_replace('_', ' ', $_GET['ss_sdt'])));
		$end   = mysql_real_escape_string(trim(str_replace('_', ' ', $_GET['ss_edt'])));

		$history = $wpdb->get_results(
			"SELECT *
			FROM `".$wpdb->prefix."ss_analytics_keyword_performance`
			WHERE time
			BETWEEN '".$start."' AND '".$end."'
			ORDER BY time DESC", ARRAY_A
		);
	
			if(empty($history)){
				echo "No results for given parameters\n\n";
				echo $this->time_hint();
				return;
			}
	
		$keys = array(
			'TIME',
			'SITE',
			'KEYWORD',
			'RESULT TYPE (1: Local 2:Network 3:Promoted)',
		);
	
		array_unshift($history, $keys);
	
			echo $this->generateCsv($history);
	}
	/**
	 * Gets the referral count of network sites.
	 */
	public function referrals(){
		
		global $wpdb;
		
		$referrals = $wpdb->get_results( 
			"SELECT *
			FROM `".$wpdb->prefix."ss_analytics_referrals` ORDER BY referrals DESC", ARRAY_A
		);	
		
		if(empty($referrals)){
			echo 'No results';
			return;
		}
		
		$keys = array(
			'SITE',
			'# REFERRALS'
		);
		
		array_unshift($referrals, $keys);
		
		echo $this->generateCsv($referrals);
		
	}
	
	/**
	 * Gets image views.
	 */
	public function image_views(){		
		global $wpdb;			
		$imageviews = $wpdb->get_results( 
			"SELECT *
			FROM `".$wpdb->prefix."ss_analytics_image` ORDER BY views DESC", ARRAY_A
		);	
		
		if(empty($imageviews)){
			echo 'No results';
			return;
		}
		
		$keys = array(
				'IMAGE ID',
				'LAST VIEW',
				'TOTAL VIEWS'
		);
		
		array_unshift($imageviews, $keys);
		
		echo $this->generateCsv($imageviews);
	}
	
	/**
	 * Gets term views.
	 */
	public function term_views(){
		global $wpdb;

		$term_view_list = array();
		
		$termviews = $wpdb->get_results( 
			"SELECT *
			FROM `".$wpdb->prefix."ss_analytics_term` ORDER BY views DESC", ARRAY_A
		);
			
		if(empty($termviews)){
			echo 'No results';
			return;
		}
		
		foreach($termviews as $termview){
			$obj = get_term_by( 'id', $termview['id'], 'image-tags', ARRAY_A);
			if($obj == NULL){
				$obj = get_term_by( 'id', $termview['id'], 'image-type', ARRAY_A);
			}
									
			array_push(
			$term_view_list, 
			array(
				 $obj['taxonomy'] == 'image-type' ? 'keyword' : 'category',
				 $obj['name'], 
				 $obj['slug'], 
				 $termview['lastview'], 
				 $termview['views']
				)
			);
		}
		
		$keys = array(
				'TAXONOMY',
				'NAME',
				'SLUG',
				'LAST VIEW',
				'TOTAL VIEWS'				
		);
		
		array_unshift($term_view_list, $keys);
		
		echo $this->generateCsv($term_view_list);
	}	
	
}

function ss_get_analytics(){
	$analytics = new ss_analytics(0);
}

add_action('wp_head', 'ss_get_analytics');

function ss_share_analytics(){
	
	if(isset($_GET['ss_analytics'])){
		$analytics = new ss_analytics(1);
	}	
	
}

add_action('init', 'ss_share_analytics');

/**
 * Prior function for logging keyword performance. No longer needed.
 * 
 * @deprecated
 * @param string $tag
 */
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