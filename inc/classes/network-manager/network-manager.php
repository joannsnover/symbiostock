<?php
//this class is responsible for performing network functions, getting network search results, and so forth.
include_once( 'communicator.php' );
include_once( 'interpreter.php' );
//this is for making a unique set of ID's for all search results, incrementing, regardless of network
class results_counter
{
    
    public $count = 0;
    
    public $messages = '';
    
    public $network_site_count = 0;
    
    public $network_info = array( );
    
    public $images_meta = array( );
    
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
                
                $current_networks[ 'symbiostock_network_site_' . $count ] = maybe_unserialize( $network );
                
            } //isset( $network ) && !empty( $network )
            
            $count++;
        } //$count <= 9
        
        return $current_networks;
    }
    
    public function get_connected_networks_csv( )
        {        
            
            $connected_networks = array();
            
            if ( file_exists( ABSPATH . 'symbiostock_network.csv' ) ) {
                $sites = $this->csv_to_array( ABSPATH . 'symbiostock_network.csv', ',' );
            } //file_exists( ABSPATH . 'symbiostock_network.csv' )
            
            if ( isset($sites[0]['symbiostock_network_site_0'] ) && !empty( $sites[0]['symbiostock_network_site_0'] ) ) {
                        
                        foreach ( $sites[ 0 ] as $site ) {                
                                                            
                          array_push( $connected_networks, $site );
                            
                        } //$sites[ 0 ] as $site
                    } //isset( $sites[ 0 ] ) && !empty( $sites[ 0 ] )    
                            
              return $connected_networks;
            }    
                
    //get current networks your site is connected with by what CSV files you have
    //if seeds is set to "true" it will loop over the seeds folder
    public function get_connected_networks_by_symbiocard( $seeds = false )
    {
        $symbiocards = array( );
        
        if ( file_exists( ABSPATH . 'symbiostock_network.csv' ) && $seeds == false ) {
            $sites = $this->csv_to_array( ABSPATH . 'symbiostock_network.csv', ',' );
        } //file_exists( ABSPATH . 'symbiostock_network.csv' )
                        
        //if user saved sites in any particular order, it would have saved a file to the top level
        //looping through this will maintain the order
        if ( isset($sites[0]['symbiostock_network_site_0'] ) && !empty( $sites[0]['symbiostock_network_site_0'] ) && $seeds == false ) {
            
            foreach ( $sites[ 0 ] as $site ) {
                
                $file = symbiostock_NETDIR . $site;
                
                $symbiocard = $this->csv_to_array( $file );
                
                if ( !empty( $symbiocard ) ) {
                    
                    array_push( $symbiocards, $symbiocard[ 0 ] );
                } //!empty( $symbiocard )
            } //$sites[ 0 ] as $site
        } //isset( $sites[ 0 ] ) && !empty( $sites[ 0 ] )
        else {
            
            $seeds == true ? $dir = 'seeds/' : $dir = '';
            
            if ( $handle = opendir( symbiostock_NETDIR . $dir ) ) {
                $count = 1;
                /* This is the correct way to loop over the directory. */
                
                
                while ( false !== ( $entry = readdir( $handle ) ) ) {
                    
                    if ( $entry != "." && $entry != ".." ) {
                        
                        $filetype = explode( '.', $entry );
                        if ( $filetype[ 1 ] != 'csv' ) {
                            continue;
                        } //$filetype[ 1 ] != 'csv'
                        
                        $symbiocard = $this->csv_to_array( symbiostock_NETDIR . $dir . $entry, ',' );
                        
                        if ( !empty( $symbiocard ) ) {
                            
                            array_push( $symbiocards, $symbiocard[ 0 ] );
                            
                        } //!empty( $symbiocard )
                    } //$entry != "." && $entry != ".."
                } //false !== ( $entry = readdir( $handle ) )
            } //$handle = opendir( symbiostock_NETDIR )
        }
        return $symbiocards;
    }
    
    public function delete_symbiocard( $key )
    {
        $file = symbiostock_NETDIR . $key . '.csv';
        
        if ( file_exists( $file ) ) {
            
            if ( !unlink( $file ) ) {
                echo 'Network symbiocard deleted.';
            } //!unlink( $file )
            else {
                echo '';
            }
        } //file_exists( $file )
    }
    
    //This verifies all files in your directory correspond to your network. If not, they are deleted.
    public function network_directory_cleanup( $directory = false )
    {
        
        $files = array( );
        
        if($directory == false){            
            
            $dir = '';
            
            $networks = $this->get_connected_networks();                
            
            //this list takes precidence over database list
            $networks_public_file = $this->get_connected_networks_csv( );
            
            foreach ( $networks as $network ) {
                
                array_push( $files, $network[ 'key' ] . '.csv' );
           
            $files = array_unique(array_merge($files,$networks_public_file)); 
        } //$networks as $network
        } else {
            
            $dir = 'seeds/';
            
            }
        
        
        if ( $handle = opendir( symbiostock_NETDIR . $dir) ) {
            $count = 1;
            /* This is the correct way to loop over the directory. */
            
            while ( false !== ( $entry = readdir( $handle ) ) ) {
                
                if ( $entry != "." && $entry != ".." ) {
                    
                    $filetype = explode( '.', $entry );
                    if ( $filetype[ 1 ] != 'csv' ) {
                        continue;
                    } //$filetype[ 1 ] != 'csv'
                    
                    if ( !in_array( $entry, $files ) ) {
                        
                        unlink( symbiostock_NETDIR . $dir . $entry );
                        
                    } //!in_array( $entry, $files )
                } //$entry != "." && $entry != ".."
            } //false !== ( $entry = readdir( $handle ) )
            
            closedir( $handle );
        } //$handle = opendir( symbiostock_NETDIR )
        
    }
    
    public function fetch_symbiocard( $site, $seed = false )
    {
        
        $seed == true ? $dir = 'seeds/' : '';
        
        $url = $site . '/symbiocard.csv';
        
        $key = symbiostock_website_to_key( $site );
        
        $newfile = symbiostock_NETDIR . $dir . $key . '.csv';
                
        $ch = curl_init( $url );
                    
        curl_setopt( $ch, CURLOPT_HEADER, 0 );
        curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, false);
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt( $ch, CURLOPT_BINARYTRANSFER, 1 );
        $raw = curl_exec( $ch );

        $info = curl_getinfo($ch);
            if ($info['http_code'] == 200) {
                $this->messages = '&mdash;<strong>Success<br /></strong>';
            } else {            
                $this->messages = '&mdash;<strong>' . $info['http_code'] . " @$url</strong> " . ' Aborting site...<br />';
                return false;
            }
                    
        curl_close( $ch );
        if ( file_exists( $newfile ) ) {
            unlink( $newfile );
        } //file_exists( $newfile )
        $fp = fopen( $newfile, 'x' );
        fwrite( $fp, $raw );
        fclose( $fp );
        
        $required_fields = array(
             'symbiostock_site',
            'admin_email',
            'symbiostock_version' 
        );
        
        //convert our info
        $converted              = $this->csv_to_array( $newfile, ',' );
        $network_associate_info = $converted[ 0 ];
        
        //validate
        
        foreach ( $required_fields as $must_have ) {
            
            if ( !isset( $converted[0][$must_have] ) || empty( $converted[0][$must_have] ) ) {
                
                unlink( symbiostock_NETDIR . $dir . $key . '.csv' );
                $this->messages = 'Invalid Symbiocard. Missing: <strong>' . $must_have . '</strong>. Deleted!<br />';
                return;
                
            } //!isset( $must_have ) || empty( $must_have )
            
        } //$required_fields as $must_have
    }
    
    public function write_network_csv( $uploaded = false )
    {
        
        $count = 0;
        $sites = array( );
        $exists = array( );
        while ( $count < 9 ) {
            
            if ( isset( $_POST[ 'symbiostock_network_site_' . $count ] ) && !empty( $_POST[ 'symbiostock_network_site_' . $count ] ) ) {
                
                $site = symbiostock_website_to_key( $_POST[ 'symbiostock_network_site_' . $count ] ) . '.csv';
                $sites[ 'symbiostock_network_site_' . $count ] = $site;
                
                if(!file_exists(symbiostock_NETDIR .$site . '.csv' )){
                	array_push($exists, $site );
                } else {
                    echo 'file ' . symbiostock_NETDIR .$site . ' missing...<br /><br />';
                }
            } //isset( $_POST[ 'symbiostock_network_site_' . $count ] )
            $count++;
        } //$count < 9
        
        if($uploaded == true){
            
            $files = $this->network_directory_file_list();
            
            foreach($files as $file){
            
            if(!in_array($file, $exists)){
                
                $sites[ 'symbiostock_network_site_' . $count ] = $file;
                
                }
            }
        }
        $name = ABSPATH . '/symbiostock_network.csv';
        
        $fp = fopen( $name, 'w' );
        
        fputcsv( $fp, array_keys( $sites ) );
        fputcsv( $fp, $sites );
        
        fclose( $fp );
        
    }  
    
    
    public function update_connected_networks( )
    {		
        if ( isset( $_POST[ 'save_form_info' ] ) ) {
            
			$this->write_network_csv();
            
            $networks = $this->get_connected_networks_csv( );
            
        } //isset( $_POST[ 'save_form_info' ] )
        
        $current_entries = $this->get_connected_networks();
        $last_count      = 0;
        
        //start at -1 so count intially increments to 0;
        $count   = -1;
        $skipped = false;
   
        while ( $count++ <= 9 ) {
            
            if ( isset( $_POST[ 'save_form_info' ] ) ) {
                
                $network_associate = get_option( 'symbiostock_network_site_' . $count, '' );
                
                $key = symbiostock_website_to_key( $network_associate[ 'address' ] );
                
                if(!in_array($key, $networks)){
                   
                    delete_option('symbiostock_network_site_' . $count);
                    
                }                  
                
            } //isset( $_POST[ 'save_form_info' ] ) && ( !isset( $_POST[ 'symbiostock_network_site_' . $count ] ) || empty( $_POST[ 'symbiostock_network_site_' . $count ] ) )
            
            if ( isset( $_POST[ 'symbiostock_network_site_' . $count ] ) ) {
                
                $success_count = $count;
                
                //accounting for skipped values due to user edits, we simply make an alternate count which reflect "successful" entries.
                if ( $skipped == true ) {
                    $success_count--;
                    $skipped = false;
                } //$skipped == true
                if ( symbiostock_validate_url( $_POST[ 'symbiostock_network_site_' . $success_count ] ) ) {
                    
                    $address = trim( $_POST[ 'symbiostock_network_site_' . $success_count ] );
                    
                } //symbiostock_validate_url( $_POST[ 'symbiostock_network_site_' . $count ] )
                elseif ( !empty( $_POST[ 'symbiostock_network_site_' . $success_count ] ) ) {
                    
                    echo '<p>' . $_POST[ 'symbiostock_network_site_' . $success_count ] . ' is not a valid URL. Try again.</p>';
                    
                    continue;
                    
                } //!empty( $_POST[ 'symbiostock_network_site_' . $success_count ] )
                
                $key = symbiostock_website_to_key( $address );
                
                $network_info = array( );
                
                $network_info[ 'key' ]         = trim( $key );
                $network_info[ 'address' ]     = trim( rtrim( $address, '/' ) );
                $network_info[ 'description' ] = trim( $_POST[ 'symbiostock_network_description_' . $success_count ] );
                
                if ( isset( $current_entries[ 'symbiostock_network_site_' . $success_count ] ) ) {
                    
                    $exists = true;
                    
                    $network_info[ 'key' ] == $network_info[ 'symbiostock_network_site_' . $success_count ][ 'key' ] ? $exists = true : $exists = false;
                    $network_info[ 'address' ] == rtrim( $network_info[ 'symbiostock_network_site_' . $success_count ][ 'address' ], '/' ) ? $exists = true : $exists = false;
                    
                } //isset( $current_entries[ 'symbiostock_network_site_' . $success_count ] )
                else {
                    
                    $exists = false;
                    
                }
                
                if ( $exists == false ) {
                    
                    //all conditions are right, so we can update our network associate info;
                    update_option( 'symbiostock_network_site_' . $success_count, $network_info );
                    
                    //if we do not already have the Symbiocard, we should get that too
                    $key = symbiostock_website_to_key( $network_info[ 'address' ] );
                    if ( !file_exists( symbiostock_NETDIR . $key . '.csv' ) ) {
                        $success = $this->fetch_symbiocard( $network_info[ 'address' ] );
                        if($success != false){
                            $this->process_network_file( symbiostock_NETDIR . symbiostock_website_to_key( $network_info[ 'address' ] ) . '.csv' );
                        } else {
                            
                        }
                    } //!file_exists( symbiostock_NETDIR . $key . '.csv' )
                } //$exists == false
                
                $last_count++;
                
            } //isset( $_POST[ 'symbiostock_network_site_' . $success_count ] )
            
            
        } //$count++ <= 9
        $this->network_directory_cleanup();
    }
    public function update_csv_info_to_networks( $csv_data )
    {
        
        //we count existing networks so we can assign the proper number to this one:
        $networks = $this->get_connected_networks();
        
        $next = ( count( $networks ) );
        
        if ( !empty( $csv_data ) ) {
            
            $network_info = array( );
            
            $key = symbiostock_website_to_key( $csv_data[ 'symbiostock_site' ] );
            
            $network_info[ 'key' ]     = rtrim( $key, '/' );
            $network_info[ 'address' ] = $csv_data[ 'symbiostock_site' ];
            
            if ( isset( $csv_data[ 'symbiostock_display_name' ] ) ) {
                
                $description = $csv_data[ 'symbiostock_display_name' ];
                
            } //isset( $csv_data[ 'symbiostock_display_name' ] )
            else {
                
                $description = $csv_data[ 'admin_email' ];
                
            }
            
            $network_info[ 'description' ] = $description;
            
            $count  = 0;
            $exists = false;
            foreach ( $networks as $network ) {
                
                if ( rtrim( $network[ 'key' ], '/' ) == rtrim( $network_info[ 'key' ], '/' ) ) {
                    
                    update_option( 'symbiostock_network_site_' . $count, $network_info );
                    $exists = true;
                    
                } //rtrim( $network[ 'key' ], '/' ) == rtrim( $network_info[ 'key' ], '/' )
                $count++;
            } //$networks as $network
            if ( $exists == false ) {
                
                update_option( 'symbiostock_network_site_' . $next, $network_info );
                
                
                
            } //$exists == false
            
        } //!empty( $csv_data )
        
        symbiostock_save_network_info();
    }
    
     public function network_directory_file_list(  )
    {
        $files = array();
        if ( $handle = opendir( symbiostock_NETDIR ) ) {
            $count = 1;
            /* This is the correct way to loop over the directory. */
            while ( false !== ( $entry = readdir( $handle ) ) ) {
                
                if ( $entry != "." && $entry != ".." ) {
                    $file = $entry;
                    $filetype = explode( '.', $entry );
                    if ( $filetype[ 1 ] != 'csv' ) {
                        continue;
                    } //$filetype[ 1 ] != 'csv'
                    array_push($files, $file);  
                                      
                    $count++;
                } //$entry != "." && $entry != ".."
            } //false !== ( $entry = readdir( $handle ) )          
           
        } //$handle = opendir( symbiostock_NETDIR )
        
        return $files;        
    }    
    public function list_all_networks( $compact = false, $seeds = false )
    {
        $count = 1;       
        $sites = $this->get_connected_networks_csv( );
        
        $seeds == true ? $dir = 'seeds/' : '';
        
        //if user saved sites in any particular order, it would have saved a file to the top level
        //looping through this will maintain the order
        if ( isset( $sites ) && !empty( $sites ) && $seeds == false) {
            
            foreach ( $sites as $site ) {
                
                $file = symbiostock_NETDIR . $dir . $site;
                
                symbiostock_csv_symbiocard_box( $file, $compact, 'symbiostock_author_' . $count );
                
                echo '<hr />';
                ;
                
                $count++;
            } //$sites[ 0 ] as $site
        } 
        else {
            if ( $handle = opendir( symbiostock_NETDIR . $dir) ) {
                
                /* This is the correct way to loop over the directory. */
                
                while ( false !== ( $entry = readdir( $handle ) ) ) {
                    
                    if ( $entry != "." && $entry != ".." ) {
                        
                        $filetype = explode( '.', $entry );
                        if ( $filetype[ 1 ] != 'csv' ) {
                            continue;
                        } //$filetype[ 1 ] != 'csv'
                        
                        echo '<div class="author_container">';    
                                            
                        symbiostock_csv_symbiocard_box( symbiostock_NETDIR . $dir . $entry, $compact, 'symbiostock_author_' . $count );
                        
                        echo '</div>';
                        
                        $count++;
                    } //$entry != "." && $entry != ".."
                } //false !== ( $entry = readdir( $handle ) )
                
                closedir( $handle );
            } //$handle = opendir( symbiostock_NETDIR )
        }
    }
    
    //sends a notification email to new network member. $path is the path to their symbiocard in network directory.
    public function network_added_email( $path )
    {
        
        $myinfo = $this->csv_to_array( ABSPATH . '/symbiocard.csv', ',' );
        $myinfo = $myinfo[ 0 ];
        
        $theirinfo = $this->csv_to_array( $path, ',' );
        $theirinfo = $theirinfo[ 0 ];
        
        $headers[ ] = 'From: ' . $myinfo[ 'symbiostock_site' ] . ' <' . get_bloginfo( 'admin_email' ) . '>';
                
        echo '<br /><br />';
        
        $email = symbiostock_email_convert( $theirinfo[ 'admin_email' ], 'decode' );
        
        isset( $myinfo[ 'symbiostock_display_name' ] ) ? $name = $myinfo[ 'symbiostock_display_name' ] : $name = $myinfo[ 'symbiostock_site' ];
        
        $subject = '[symbiostock_network_addition] ' . $name . ' has added you to their site network.';
        
        $message = '<p>' . $name . ' has added you to their site network.<br /> If you have not yet added them to your site, here is their Symbiocard: <a title="Author Symbiocard" href="' . site_url() . '/symbiocard.csv">' . site_url() . '/symbiocard.csv</a>
        
        <br /><br />See their network info at the author page: <a title="Author Page" href="' . $myinfo[ 'symbiostock_author_page' ] . '">' . $myinfo[ 'symbiostock_author_page' ] . '</a><br /><br />
        <a title="About network emails" href="http://www.symbiostock.com/about-network-emails/"><em>About Network Emails...</a></p>';
        
        $mailed = wp_mail( $email, $subject, $message, $headers );
        
        if ( $mailed ) {
            echo ' Member added. Notification email sent to <strong>' . $email . '</strong>';
        } //$mailed
        else {
            echo ' Notification email not sent. Either their Symbiocard lacks an email address or something went wrong.';
        }
        
        wp_mail( get_bloginfo( 'admin_email' ), '[symbiostock_network_update] Network friend ('.$theirinfo[ 'symbiostock_display_name' ].') notified.', '<p>Your network friend '.$theirinfo[ 'symbiostock_display_name' ].' was notified that you added them, and recieved this message: <br /></p>'.$message );
    }
    
    public function installation_upgrade_email( )
    {
        
        $has_sent = get_option( 'symbiostock_upgrade_email' );
        
        $theme_data = wp_get_theme( 'symbiostock' );
        
        if ( $has_sent != $theme_data->Version ) {
            
            $sites = $this->get_connected_networks();
            
            $myinfo = $this->csv_to_array( ABSPATH . '/symbiocard.csv', ',' );
            $myinfo = $myinfo[ 0 ];
            
            $mailed_to = array();            
            
            foreach ( $sites as $site ) {
                
                $theirinfo = $this->csv_to_array( symbiostock_NETDIR . $site[ 'key' ] . '.csv', ',' );
                $theirinfo = $theirinfo[ 0 ];
                
                $headers[ ] = 'From: ' . $myinfo[ 'symbiostock_site' ] . ' <' . get_bloginfo( 'admin_email' ) . '>';
                               
                $this->messages = '<br /><br />';
                
                $email = symbiostock_email_convert( $theirinfo[ 'admin_email' ], 'decode' );
                array_push($mailed_to, $theirinfo[ 'symbiostock_display_name' ]);
                
                $subject = '[symbiostock_upgrade] ' . $myinfo[ 'symbiostock_site' ] . ' (' . $myinfo[ 'symbiostock_display_name' ] . ') has upgraded: ' . $theme_data->Version;
                
                $message = '<p>' . $subject . '<br /><br /><a title="About network emails" href="http://www.symbiostock.com/about-network-emails/"><em>About Network Emails...</a></p>';
                
                wp_mail( $email, $subject, $message, $headers );    
                
            } //$sites as $site
           
        } //$has_sent != $theme_data->Version
        
        update_option( 'symbiostock_upgrade_email', $theme_data->Version );
        
    }
    
    //uses a symbiocard (by key) to setup a folder with all of its images and supporting files
    public function setup_network_directory( $symbiocard )
    {
        
        $info = $this->csv_to_array( $symbiocard . '.csv' );
        
        $dir = symbiostock_NETDIR . $symbiocard . '/';
        
        if ( !file_exists( $dir ) ) {
            mkdir( $dir, 0755 );
        } //!file_exists( $dir )
        
    }
    //processes a network csv file and transforms it to active network associate
    public function process_network_file( $path )
    {
        
        $required_fields = array(
             'symbiostock_site',
            'admin_email',
            'symbiostock_version' 
        );
        
        //convert our info
        $converted              = $this->csv_to_array( $path, ',' );
        $network_associate_info = $converted[ 0 ];
        
        //validate
        
        foreach ( $required_fields as $must_have ) {
            
            if ( !isset( $must_have ) && empty( $must_have ) ) {
                
                return '<p>Could not create network associate. Missing required info: <strong>' . $must_have . '</strong></p>';
                
            } //!isset( $must_have ) && empty( $must_have )
            
        } //$required_fields as $must_have
        //make our key
        $key = symbiostock_website_to_key( $network_associate_info[ 'symbiostock_site' ] );
        
        $this->update_csv_info_to_networks( $network_associate_info );
        
        //make a properly named file via unique network name key
        if ( !copy( $path, ABSPATH . 'symbiostock_network/' . $key . '.csv' ) ) {
            echo '';
        } //!copy( $path, ABSPATH . 'symbiostock_network/' . $key . '.csv' )
        
        if ( $path != ABSPATH . 'symbiostock_network/' . $key . '.csv' ) {
            //delete old file
            unlink( $path );
        } //$path != ABSPATH . 'symbiostock_network/' . $key . '.csv'
        
        $this->setup_network_directory( $key );
        
        $this->write_network_csv(true);
        
        $this->network_added_email( symbiostock_NETDIR . $key . '.csv' );
        
        return true;
        
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
            } //( $row = fgetcsv( $handle, 1000000, $delimiter ) ) !== FALSE
            fclose( $handle );
        } //( $handle = fopen( $filename, 'r' ) ) !== FALSE
        
        return $data;
    }
    
    public function get_coords( $address )
    {
        if ( !empty( $address ) ) {
            $address = str_replace( " ", "+", $address ); // replcae all the white space with "+" sign to match with google search pattern
            
            $url = "http://maps.google.com/maps/api/geocode/json?sensor=false&address=$address";
            
            $response = file_get_contents( $url );
            
            $json = json_decode( $response, TRUE ); //generate array object from the response from the web            
            
            return serialize( $json );
        } //!empty( $address )
    }
    
    public function generate_network_info( )
    {
        
        $network_info = array( ); //our master array
        
        $theme_data = wp_get_theme( 'symbiostock' );
        
        $site_author = get_option( 'symbiostock_site_author', 1 );
        $user_info   = get_userdata( $site_author );
        
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
            'price_zip' 
        );
        
        
        
        $profile_info = get_option( 'symbiostock_social_credentials' );
        
        $network_info[ 'symbiostock_site' ] = site_url();
        
        $network_info[ 'symbiostock_csv_generated_time' ] = current_time( 'mysql' );
        
        $network_info[ 'symbiostock_num_images' ] = wp_count_posts( 'image' )->publish;
        
        $network_info[ 'symbiostock_version' ] = $theme_data->Version;
        
        $network_info[ 'symbiostock_URI' ] = $theme_data->get( 'ThemeURI' );
        
        $network_info[ 'symbiostock_network_page' ] = symbiostock_network( $text, true );
        
        $network_info[ 'symbiostock_author_page' ] = get_author_posts_url( $site_author );
        
        $network_info[ 'symbiostock_display_name' ] = $user_info->display_name;
        
        //we encrypt our emails to avoid them getting harvested by internet spammers. 
        $network_info[ 'admin_email' ]                      = symbiostock_email_convert( get_option( 'admin_email', '' ) );
        $network_info[ 'symbiostock_correspondence_email' ] = symbiostock_email_convert( get_option( 'symbiostock_correspondence_email', '' ) );
        $network_info[ 'symbiostock_paypal_email' ]         = symbiostock_email_convert( get_option( 'symbiostock_paypal_email', '' ) );
        $network_info[ 'symbiostock_portfolio' ]            = get_post_type_archive_link( 'image' );
        $network_info[ 'symbiostock_rss' ]                  = symbiostock_feed();
        
        //get address info and apply it
        $home_coords = $this->get_coords( $profile_info[ 'symbiostock_home_location' ] );
        update_option( 'symbiostock_home_location_coords', $home_coords );
        
        $temp_1_coords = $this->get_coords( $profile_info[ 'symbiostock_temporary_location_1' ] );
        update_option( 'symbiostock_temporary_location_1_info', $temp_1_coords );
        
        $temp_2_coords = $this->get_coords( $profile_info[ 'symbiostock_temporary_location_2' ] );
        update_option( 'symbiostock_temporary_location_2_info', $temp_2_coords );
        
        $network_info[ 'symbiostock_home_location_coords' ]      = $home_coords;
        $network_info[ 'symbiostock_temporary_location_1_info' ] = $temp_1_coords;
        $network_info[ 'symbiostock_temporary_location_2_info' ] = $temp_2_coords;
        
        //set up categories
        $categories = wp_list_categories( array(
             'taxonomy' => 'image-type',
            'orderby' => 'name',
            'show_count' => 1,
            'pad_counts' => 1,
            'hierarchical' => 1,
            'echo' => 0,
            'title_li' => '',
            'feed' => 'rss' 
        ) );
        
        $network_info[ 'symbiostock_author_categories' ] = serialize( $categories );
        
        //show networks 
        $network_info[ 'symbiostock_networked_sites' ] = serialize( $this->get_connected_networks() );
        
        foreach ( $network_options as $option ) {
            
            $network_info[ $option ] = get_option( $option, '' );
            
        } //$network_options as $option
        
        if ( $profile_info != false && !empty( $profile_info ) ) {
            
            foreach ( $profile_info as $key => $profile_entry ) {
                
                $network_info[ $key ] = $profile_entry;
                
            } //$profile_info as $key => $profile_entry
        } //$profile_info != false && !empty( $profile_info )
        
        $this->network_info = $network_info;
        
    }
    
    public function write_network_info( )
    {
        
        $name = ABSPATH . '/symbiocard.csv';
        
        $fp = fopen( $name, 'w' );
        
        fputcsv( $fp, array_keys( $this->network_info ) );
        
        fputcsv( $fp, $this->network_info );
        
        fclose( $fp );
        
    }
    
    //generates a list of images for network hubs and other promo sites that use the info
    public function generate_image_list_info( )
    {
        
        ini_set( "memory_limit", "1024M" );
        set_time_limit( 0 );
        
        $images_meta = array( );
        
        $image_vals = array(
             'id',
            'image_id', //included
            'url', //included
            'fullimage_url', //included ???
            'thumbnail_url', //included
            'photographer_full_name', //included
            'keyword', //included
            'concepts',
            'category',
            'description', //included
            'caption', //included
            'model_release', //included
            'property_release', //included
            'location',
            'geolocation',
            'license_type', //included
            'collection',
            'color',
            'width', //included
            'height' //included
        );
        
        $args = array(
             'post_type' => 'image',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'caller_get_posts' => 1 
        );
        
        $all_images = null;
        $all_images = new WP_Query( $args );
        
        if ( $all_images->have_posts() ) {
            
            $image_meta = array( );
            
            while ( $all_images->have_posts() ):
                $all_images->the_post();
                
                $id = get_the_ID();
                
                //generate image id   
                $image_meta[ 'image_id' ] = $id;
                
                //generate licence type   
                $image_meta[ 'license_type' ] = 'RF';
                
                //generate url of image page                                       
                $image_meta[ 'url' ] = get_permalink();
                
                //generate preview pic location
                $preview                       = get_post_meta( $id, 'symbiostock_preview' );
                $image_meta[ 'fullimage_url' ] = $preview[ 0 ];
                
                //generate thumbnail pic location
                $image_minipic                 = get_post_meta( $id, 'symbiostock_minipic' );
                $image_meta[ 'thumbnail_url' ] = $image_minipic[ 0 ];
                
                //generate model released
                $model_released = get_post_meta( $id, 'symbiostock_model_released' );
                if ( empty( $model_released ) || $model_released == false ) {
                    $model_released[ 0 ] = 'N/A';
                } //empty( $model_released ) || $model_released == false
                $image_meta[ 'model_release' ] = $model_released[ 0 ];
                
                //generate property released
                $property_released = get_post_meta( $id, 'symbiostock_property_released' );
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
            
        } //$all_images->have_posts()
        $this->images_meta = $images_meta;
    }
    public function write_image_list_info( )
    {
        
        $name = ABSPATH . '/symbiostock_image_info.csv';
        $fp   = fopen( $name, 'w' );
        
        fputcsv( $fp, array_keys( $this->images_meta[ 0 ] ) );
        
        foreach ( $this->images_meta as $vals ) {
            
            fputcsv( $fp, $vals );
            
        } //$this->images_meta as $vals
        
        fclose( $fp );
        
    }
    
    //local search, responsible for generating local search results, returns xml.
    public function local_search( )
    {
        
        //determine number of results to show -
        
        $results_per_page = 5;
        
        if ( is_search() ) {
            $image_tags = get_query_var( 's' );
            
        } //is_search()
        else {
            $image_tags = get_query_var( 'image-tags' );
        }
        
        //if this is a category page, then we set the value
        $category = get_query_var( 'image-type' );
        if ( isset( $category ) ) {
            $category = get_query_var( 'image-type' );
        } //isset( $category )
        else {
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
        } //is_tax( 'image-tags' )
        
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
        } //is_tax( 'image-type' )
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
            } //$search_terms as $search_term
            
        } //is_search()
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
            
        } //!is_post_type_archive( 'image' )
        elseif ( is_search() ) {
            
            $local_query = array(
                 'post_type' => 'image',
                'post_status' => 'publish',
                'tax_query' => $tax_query,
                'paged' => $paged,
                'posts_per_page' => 24 
            );
            
        } //is_search()
        else {
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
            
            $network_limit = 9;
            $site_count    = 0;            
                        
            while ( $site_count <= $network_limit ) {
                
                $this->network_site_count = $site_count;
                
                $network_site = get_option( 'symbiostock_network_site_' . $site_count );
                
                //different sites might have wordpress installed at different levels like www.mystockphotosite.com/wordpress/
                //so we have to disect our url to get it to function properly...see $query below
                $sub_level = parse_url( get_home_url() );
                
                if ( symbiostock_validate_url( $network_site[ 'address' ] ) ) {
                    
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
                    
                    $network_results   = $this->get_remote_xml( $network_site[ 'address' ] . $query, $network_site[ 'address' ]);                    
                    $this->xml_results = $network_results;
                    $this->display_results( true );
                    
                    
                } //symbiostock_validate_url( $network_site[ 'address' ] )
                
                $site_count++;
                
            } //$site_count <= $network_limit
        } //$symbiostock_use_network == 'true'
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
        } //!isset( $this->network_site_count )
        
        symbiostock_build_html_results( $results, $network_search, $this->network_site_count );
        
    }
    
public function make_cache_key_from_url( $url )
    {
      $pos_paged = strpos( $url, 'paged=1' );
      $pos_s = strpos( $url, '?s=' );
      $pos_search = strpos( $url, '/search-images/' );

      if ( $pos_s > 0 ) { // first form of url
        $key = substr( $url, 0, $pos_s );
        if ( $key[ strlen( $key ) - 1 ] != '/' ) $key .= '/';
        $key .= substr( $url, $pos_s+3, strpos( $url, '&' ) - $pos_s - 3 );
        if ( $pos_paged == 0 ) {
          $pos_page = strpos( $url, 'page=' );
          while ( $pos_page > 0 && $pos_page < strlen( $url ) && $url[ $pos_page ] != '&' )
            $key .= $url[ $pos_page++ ];
          $key = str_replace( 'page=', '/page/', $key );
        }
      }
      else if ($pos_search > 0 ) { // second url
        $key = substr( $url, 0, $pos_search + 1 );
        if ( $pos_paged == 0 )
          $key .= substr( $url, $pos_search+15, strpos( $url, '?symbio' ) - $pos_search - 16 );
        else {
          $pos_page = strpos( $url, '/page/' );
          if ( $pos_page > 0 )
            $key .= substr( $url, $pos_search+15, strpos( $url, '/page/' ) - $pos_search - 15 );
          else
            $key .= substr( $url, $pos_search+15, strpos( $url, '/?symbio' ) - $pos_search - 15 );
        }
      }
      else // unknown url found
        $key = $url;
      $key .= '/';
      $key = str_replace( '/page/1/', '/', $key );
      return $key;
    }


// default cache period is 7 days
// set it to 0 to disable cache

public function get_remote_xml( $url, $site = '' )
    {

      $days = get_option('symbiostock_cache_days', 7);

      $caching_time = $days * 24 * 3600;   // must be number of seconds

      // let's remove old files from time to time
      if ( time() % 500 == 0 ) {

        $files = glob( ABSPATH . 'symbiostock_xml_cache/*' );
        usort( $files, create_function( ' $a, $b ', ' return filemtime($a) - filemtime($b); ' ) );
        for( $i = 0; $i < 100 && $i < count( $files ) && filemtime( $files[ $i ] ) < time() - $caching_time; $i++ )
          unlink( $files[ $i ] );
      }


      $key = $this->make_cache_key_from_url( $url );

      $file = ABSPATH . 'symbiostock_xml_cache/' . md5( $key );

      if ( file_exists( $file ) && time() - $caching_time < filemtime( $file ) ) {

         $data = explode( "\n>>----<<\n", file_get_contents( $file ) );
         if ( $data[0] == $key )
           return $data[2];
         else {
           unlink ( $file );
           return $this->get_remote_xml( $url, $site );
         }
      }
      else {

        $ch = curl_init();
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
        //our first attempt requires a fast response, our next attempt will be via ajax which allows slower
        if(!isset($_POST['ajax_request'])){ $timeout = 1; } else { $timeout = 10; }

        curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, $timeout );
        curl_setopt( $ch, CURLOPT_TIMEOUT, $timeout );

        curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
        curl_setopt( $ch, CURLOPT_URL, $url ); // get the url contents

        $data = curl_exec( $ch ); // execute curl request
        $info = curl_getinfo($ch);
        if(curl_errno($ch)) return symbiostock_xml_generic_results($url, $site); //if this happens, its probably a time-out, and will be fetched ajax instead

        curl_close( $ch );

        libxml_use_internal_errors( true );
        if ( simplexml_load_string( $data ) ) {

            file_put_contents( $file, $key . "\n>>----<<\n" . $url . "\n>>----<<\n" . $data );

            //libxml_use_internal_errors( false );
            return $data;

        } //simplexml_load_string( $data )
        else {

            //libxml_use_internal_errors( false );
            return '<?xml version="1.0"?><noresults></noresults>';
        }

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
    
    //our much desired and finally available "symbiocard" spider function, for marketing artists automatic symbiostock routines.
    public function the_spider(){
        
        //this will que our addresses to spider symbiocards
        $collected_addresses = array( );
        
        //this tracks where we have been
        $visited_addresses = array();
                
        $symbiocards     = array( );
        //get our initial networks to start the process...
        $starting_points = $this->get_connected_networks_csv();
        
        //get their networks
        foreach ( $starting_points as $point ) {
            
            $csv = symbiostock_NETDIR . $point;
            
            if ( file_exists( $csv ) ) {
                
                $symbiocard = $this->csv_to_array( $csv );
                
                if ( !isset( $symbiocard[ 0 ][ 'symbiostock_networked_sites' ] ) ) {
                    continue;
                } //!isset( $symbiocard[ 0 ][ 'symbiostock_networked_sites' ] )
                
                $addresses = maybe_unserialize( $symbiocard[ 0 ][ 'symbiostock_networked_sites' ] );
                
                if ( empty( $addresses ) || !is_array( $addresses ) ) {
                    continue;
                } //empty( $addresses ) || !is_array( $addresses )
                
                foreach ( $addresses as $address ) {
                    
                    if ( !isset( $address[ 'address' ] ) || empty( $address[ 'address' ] ) ) {
                        continue;
                    } //!isset( $address[ 'address' ] ) || empty( $address[ 'address' ] )
                    
                    if ( !in_array( $address[ 'address' ], $collected_addresses ) ) {
                        
                        array_push( $collected_addresses, $address[ 'address' ] );
                        
                    } //!in_array( $address[ 'address' ], $collected_addresses )
                    
                } //$addresses as $address
                
            } //file_exists( $csv )
            
        } //$starting_points as $point
        
        //PRELIMINARY STUFF DONE - START CRAWLING -------------------------------
        
        $collected_addresses = array_unique($collected_addresses);
            
        foreach($collected_addresses as $site){
            
            echo 'Getting site: ' . $site . '...<br />';
            
            $this->fetch_symbiocard( $site, true );
            echo $this->messages;
            $this->massages = '';
            echo '<br />';
            }
        
        //log our travels...
        $visited_addresses = array_unique($collected_addresses);
        
        //reset $collected
        $collected_addresses = array();
        
        
        //NOTE --- --- Until Symbiostock is a bigger network, we will limit crawling to the network of your network (friends of friends)
        
        /*
        $symbiocards = $this->get_connected_networks_by_symbiocard( true );
                                
        foreach($symbiocards as $symbiocard){
        
        if ( !isset( $symbiocard[ 'symbiostock_networked_sites' ] ) ) {
                    
                    continue;
                } //!isset( $symbiocard[ 0 ][ 'symbiostock_networked_sites' ] )
                
                $addresses = maybe_unserialize( $symbiocard[ 'symbiostock_networked_sites' ] );
                
                if ( empty( $addresses ) || !is_array( $addresses ) ) {
                    continue;
                } //empty( $addresses ) || !is_array( $addresses )
                
                foreach ( $addresses as $address ) {
                    var_dump($address); echo '<br /><br />';                
                    if ( !isset( $address[ 'address' ] ) || empty( $address[ 'address' ] ) ) {
                        continue;
                    } //!isset( $address[ 'address' ] ) || empty( $address[ 'address' ] )
                    
                    if ( !in_array( $address[ 'address' ], $visited_addresses ) ) {
                        
                        array_push( $collected_addresses, $address[ 'address' ] );
                        
                    } //!in_array( $address[ 'address' ], $collected_addresses )
                    
                } //$addresses as $address
                $count = 1;
                
            }*/
    }
    
}
function symbiostock_save_network_info( )
{
    
    $network_info = new network_manager();
    
    $network_info->generate_network_info();
    
    $network_info->write_network_info();
    
}
function symbiostock_save_image_list_info( )
{
    
    $network_info = new network_manager();
    
    $network_info->generate_image_list_info();
    
    $network_info->write_image_list_info();
    
}

//set up hourly fetching of symbiocards

add_action( 'wp', 'symbiocards_activation' );
add_action( 'symbiocards_hourly_event', 'update_symbiocards' );

function symbiocards_activation() {
    if ( !wp_next_scheduled( 'symbiocards_hourly_event' ) ) {
        wp_schedule_event( time() + 3600, 'hourly', 'symbiocards_hourly_event' );
    }
}

function update_symbiocards() {
     
    $update = new network_manager();
    $sites  = $update->get_connected_networks();
    foreach ( $sites as $site ) {
        $update->fetch_symbiocard( $site[ 'address' ] );
    } //$sites as $site
    
    symbiostock_save_network_info();
    
    update_option('symbiocards_last_update', current_time( 'mysql' ));
    
    //wp_mail( get_bloginfo( 'admin_email' ), '[symbiostock_network_update] Network Symbiocards Updated - ' . current_time( 'mysql' ), 'Network Symbiocards Updated - ' . current_time( 'mysql' ) );
}

add_action( 'wp', 'symbiostock_site_data_activation' );
add_action( 'symbiostock_site_data_daily_event', 'update_symbiostock_site_data' );

function symbiostock_site_data_activation() {
    if ( !wp_next_scheduled( 'symbiostock_site_data_daily_event' ) ) {
        wp_schedule_event( time() + 3600, 'daily', 'symbiostock_site_data_daily_event' );
    }
}

function update_symbiostock_site_data() {
     
    $update = new network_manager();
    $sites  = $update->get_connected_networks();
    foreach ( $sites as $site ) {
        $update->fetch_symbiocard( $site[ 'address' ] );
    } 
       
    symbiostock_save_image_list_info( );
    
    $spider_network = new network_manager();
    $spider_network->the_spider();
    
    update_option('symbiostock_site_data_last_update', current_time( 'mysql' ));
    
    wp_mail( get_bloginfo( 'admin_email' ), '[symbiostock_network_update] Site has updated image and network - ' . current_time( 'mysql' ), 'Public image and tag info updated. Network has been scanned and directory updated. - ' . current_time( 'mysql' ) );
}
?>