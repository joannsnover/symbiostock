<?php
/**
 * Runs image processing user initiates after upload.
 *
 * This is the main symbiostock image processor. Typically its used 
 * directly after upload, but should also be utilized for edits of other 
 * sorts, watermark changes, and so forth. Carefull attention should be 
 * given to improving and expanding this class for all IPTC and Image 
 * editing.
 *
 * @package    SYMBIOSTOCK
 * @author     Leo Blanchette <leo@clipartillustration.com>
 * @license    http://www.gnu.org/licenses/gpl.html
 * @link       http://www.symbiostock.com
 */
//above doc root? $destination = $_SERVER["DOCUMENT_ROOT"] . "/../Uploads/" . $random;
class symbiostock_image_processor
{
    //image processor variables
    
    //stores upload directory path
    public $upload_dir = '';
    
    //holds our list of files to loop through
    public $files = array( );
    
	public $current_file = '';
	
    //what we are looking for as product formats
    public $expected_types = array( 'jpg', 'png', 'eps', 'zip' );
    
    //this holds status reports / errors
    public $report = '';
	
    
    //current user's email for status updates 
    
    public $user_info = '';
    
	//our class constructor
    function __construct( )
    {
        set_time_limit( 0 );
        
        ini_set( "memory_limit", "1024M" );
        
        $this->user_info = get_currentuserinfo();
        
        $this->upload_dir = get_template_directory() . '/inc/classes/plupload/uploads/';
        
        $this->build_file_list();
        
    }
	
    //main image watermarking function
    
    /**
     * symbiostock_process_image -
     * 
     * Main image processing function. Takes path to original uploaded image, does 
     * conversions: Watermarked preview, transparency preview, and minipic (thumbnail)
     * size images. Saves them to a tmp directory, where IPTC data is changed/added.
     * 
     * Takes path to original image adn the id of the just created post it will be 
     * applied to.
     * 
     * This function relies on the "wideimage" class for processing images.
     */
    public function symbiostock_process_image( $image_file, $posted_id ) //this function is referenced by "create_image_page" function
    {
        //find which watermark we are using
        $watermark_path = get_option( 'symbiostock_watermark_link' );
        
        
        if ( $watermark_path == false ) {
            $watermark_path = symbiostock_CLASSDIR . '/image-processor/symbiostock-watermark.png';
            
        } //$watermark_path == false
        else {
            $url_vars = parse_url( $watermark_path );
            
            $watermark_path = $_SERVER[ 'DOCUMENT_ROOT' ] . trim( $url_vars[ 'path' ] );
            ;
            
        }
        
        $checkerboard_path = symbiostock_CLASSDIR . '/image-processor/transparency.png';
        
        $sizes = get_post_meta( $posted_id, 'size_info' );
        
        $sizes = unserialize( $sizes[ 0 ] );
        
        $extensions = get_post_meta( $posted_id, 'extensions' );
        
        $extensions = unserialize( $extensions[ 0 ] );
        
        $preview_size = $sizes[ 'preview' ];
        
        $thumb_size = $sizes[ 'thumb' ];
        
        //perform our resizing operations using wideimage
        
        include_once( symbiostock_CLASSROOT . 'image-processor/wideimage/lib/WideImage.php' );
        //get the watermarks
        $watermark       = WideImage::load( $watermark_path );
        $watermark_small = WideImage::load( symbiostock_CLASSDIR . '/image-processor/symbiostock-watermark-small.png' );
        
        //get the transparency background
        $checkerboard_transpency = WideImage::load( $checkerboard_path );
        
        //create thumbnail and previews, save in uploads/tmp folder
        
        
        if ( in_array( 'png', $extensions ) ) {
            //make our typical preview
            
            $image = WideImage::load( $this->upload_dir . $image_file . '.png' );
            
            $resized = $image->resize( $preview_size[ 'width' ], $preview_size[ 'height' ] );
            
            // Create an empty white canvas with the original image sizes
            $img = WideImage::createTrueColorImage( $resized->getWidth(), $resized->getHeight() );
            
            $bg = $img->allocateColor( 255, 255, 255 );
            
            $img->fill( 0, 0, $bg );
            
            //lay image over white canvas background - 
            
            $flattened = $img->merge( $resized, 'center', 'center', 100 );
            
            //also make minipic size
            $mini_pic = $flattened->resize( $thumb_size[ 'width' ], $thumb_size[ 'height' ] );
            
            //lay watermark over preview size
            $watermarked = $flattened->merge( $watermark, 'center', 'center', 100 );
            
            //save preview
            $watermarked->saveToFile( $this->upload_dir . 'tmp/' . $posted_id . '_preview.jpg', 100 );
            
            //save minipic
            $mini_pic->saveToFile( $this->upload_dir . 'tmp/' . $posted_id . '_minipic.jpg', 100 );
            
            //make our transparency preview
            
            $image = WideImage::load( $this->upload_dir . $image_file . '.png' );
            
            $transparency_resized = $image->resize( 515, 515 );
            
            $transparency_preview = $checkerboard_transpency->merge( $transparency_resized, 'center', 'center', 100 );
            
            $watermarked = $transparency_preview->merge( $watermark, 'center', 'center', 100 );
            
            $watermarked->saveToFile( $this->upload_dir . 'tmp/' . $posted_id . '_transparency_preview.jpg', 100 );
            
        } //in_array( 'png', $extensions )
        
        //jpg from jpg
        if ( in_array( 'jpg', $extensions ) ) {
            //make our typical preview
            
            $image = WideImage::load( $this->upload_dir . $image_file . '.jpg' );
            
            $resized = $image->resize( $preview_size[ 'width' ], $preview_size[ 'height' ] );
            
            $watermarked = $resized->merge( $watermark, 'center', 'center', 100 );
            
            $watermarked->saveToFile( $this->upload_dir . 'tmp/' . $posted_id . '_preview.jpg', 100 );
            
            //make our thumbnail
            
            $image = WideImage::load( $this->upload_dir . $image_file . '.jpg' );
            
            $resized = $image->resize( $thumb_size[ 'width' ], $thumb_size[ 'height' ] );
            
            $resized->saveToFile( $this->upload_dir . 'tmp/' . $posted_id . '_minipic.jpg', 100 );
            
            //copy iptc data over
            $this->copy_iptc( $this->upload_dir . $image_file . '.jpg', $this->upload_dir . 'tmp/' . $posted_id . '_minipic.jpg' );
            
        } //in_array( 'jpg', $extensions )
        
        //copy files over to symbiostock_rf_content folder, root
        
        //first the minipic		
        $this->move_to_content_folder( $posted_id . '_minipic.jpg', 'minipic', $posted_id );
        
        
        //now our watermarked preview
        
        $this->move_to_content_folder( $posted_id . '_preview.jpg', 'preview', $posted_id );
        
        
        //and then transparency preview 
        
        $this->move_to_content_folder( $posted_id . '_transparency_preview.jpg', 'transparency', $posted_id );
        
    }
    
    //called from above function - move to content folder and update post meta
    
    private function move_to_content_folder( $file, $preview_type, $posted_id )
    {
        //we will use some post elements, for SEO sake, and other features.
        $post_stuff       = get_post( $posted_id, ARRAY_A );
        $parent_permalink = get_permalink( $posted_id );
        
        //depending on preview image type, we assign different names/file names.
        switch ( $preview_type ) {
            
            case 'minipic':
                
                $name = $posted_id . '-minipic.jpg';
                
                break;
            
            case 'preview':
                
                $title = $post_stuff[ 'post_title' ];
                
                //make it file-name ready...
                $title = sanitize_title( $title, 'royalty-free-content.jpg' );
                
                $name = $posted_id . '-' . $title . '.jpg';
                
                break;
            
            case 'transparency':
                
                $name = $posted_id . 'transparency_preview.jpg';
                
                break;
                
        }
        
        $upload_dir = wp_upload_dir();
        
        $tmp_folder = $this->upload_dir . 'tmp/';
        
        $content_folder = $upload_dir[ 'basedir' ] . '/symbiostock_rf_content/';
        
        if ( file_exists( $tmp_folder . $file ) ) {
            if ( !copy( $tmp_folder . $file, $content_folder . $name ) ) {
                echo "failed to copy $file...\n";
                
            }
            
            $image_url = $upload_dir[ 'baseurl' ] . '/symbiostock_rf_content/';
            
            //updates post meta to easily get the paths for preview images
            
            update_post_meta( $posted_id, 'symbiostock_' . $preview_type, $image_url . $name );
            
            //Register our media and give it some info, directing people to product page.
            
            $wp_filetype = wp_check_filetype( basename( $content_folder . $name ), null );
            
            switch ( $preview_type ) {
                
                case 'preview':
                    
                    $attachment = array(
                        
                         'post_mime_type' => $wp_filetype[ 'type' ],
                        
                        'post_title' => 'Royalty Free Content: ' . ucwords( $post_stuff[ 'post_title' ] ),
                        
                        'post_content' => '<p>Preview image for royalty free content <a title="Royalty Free Image" href="' . $parent_permalink . '">' . $post_stuff[ 'post_title' ] . '</a></p>',
                        
                        'post_status' => 'inherit' 
                        
                    );
                    
                    $attach_id = wp_insert_attachment( $attachment, $content_folder . $name, $posted_id );
                    
					update_post_meta($attach_id, 'symbiostock_preview', 'preview');
					
					update_post_meta($posted_id , 'symbiostock_preview_id', $attach_id);
					
                    break;
                
                case 'transparency':
                    
                    $attachment = array(
                        
                         'post_mime_type' => $wp_filetype[ 'type' ],
                        
                        'post_title' => 'Transparency Preview For ' . ucwords( $post_stuff[ 'post_title' ] ),
                        
                        'post_content' => '<p>PNG transparency preview for stock image: <a title="Royalty Free Image" href="' . $parent_permalink . '">' . $post_stuff[ 'post_title' ] . '</a></p>',
                        
                        'post_status' => 'inherit' 
                        
                    );
                    
                    $attach_id = wp_insert_attachment( $attachment, $content_folder . $name, $posted_id );
                    
					update_post_meta($attach_id, 'symbiostock_preview', 'transparency');
					
					update_post_meta($posted_id , 'symbiostock_transparency_id', $attach_id);
					
                    break;
                
                
                case 'minipic':
                    
                    //set minipic as featured image
                    
                    $attachment = array(
                        
                         'post_mime_type' => $wp_filetype[ 'type' ],
                        
                        'post_title' => preg_replace( '/\.[^.]+$/', '', basename( $content_folder . $name ) ),
                        
                        'post_content' => '',
                        
                        'post_status' => 'inherit' 
                        
                    );
                    
                    $attach_id = wp_insert_attachment( $attachment, $content_folder . $name, $posted_id );
                    
					update_post_meta($attach_id, 'symbiostock_preview', 'minipic');
					
					update_post_meta($posted_id , 'symbiostock_minipic_id', $attach_id);
					
                    // you must first include the image.php file
                    
                    // for the function wp_generate_attachment_metadata() to work
                    
                    require_once( ABSPATH . "wp-admin" . '/includes/image.php' );
                    
                    $attach_data = wp_generate_attachment_metadata( $attach_id, ABSPATH . $upload_dir[ 'basedir' ] . '/' . $content_folder . $name );
                    
                    // if ( wp_update_attachment_metadata( $attach_id, $attach_data ) ) {
                    // set as featured image
                    
                    update_post_meta( $posted_id, '_thumbnail_id', $attach_id );
                    
										
                    //  } //wp_update_attachment_metadata( $attach_id, $attach_data )
                    
                    break;
                    
            }
            
        }
        
    }
    
    private function transfer_original( $created_page, $image_file, $image_meta_array ){
		
		$files_transferred = array();
		
		$extensions = unserialize($image_meta_array['extensions']);
		
		foreach ($extensions as $type){
			
			$old_file = $this->upload_dir . $image_file . '.' . $type;
			  if ( file_exists( $old_file ) ) {
				if ( !copy( $old_file, symbiostock_STOCKDIR . $created_page . '.' . $type ) ) {
					echo "failed to copy $file...\n";
					
					}
				
			//build transferred list - 
			
			array_push($files_transferred, $old_file);	
				
				}
			
			}
		return $files_transferred;
		}
	
	
    
    /**
     * copy_iptc -, iptc_make_tag -
     * 
     * Simply transfers certain IPTC data from old to new file, since
     * IPTC does not transfer during image resizing. This function 
     * should be expanded to do as much with IPTC to author's benefit.
     * 
     */
    
    // iptc_make_tag() function by Thies C. Arntzen
    private function iptc_make_tag( $rec, $data, $value )
    {
        $length = strlen( $value );
        $retval = chr( 0x1C ) . chr( $rec ) . chr( $data );
        
        if ( $length < 0x8000 ) {
            $retval .= chr( $length >> 8 ) . chr( $length & 0xFF );
        } //$length < 0x8000
        else {
            $retval .= chr( 0x80 ) . chr( 0x04 ) . chr( ( $length >> 24 ) & 0xFF ) . chr( ( $length >> 16 ) & 0xFF ) . chr( ( $length >> 8 ) & 0xFF ) . chr( $length & 0xFF );
        }
        
        return $retval . $value;
    }
    private function copy_iptc( $old_file, $new_file )
    {
        // Path to jpeg file
        $path = $new_file;
        
        // We need to check if theres any IPTC data in the jpeg image. If there is then 
        // bail out because we cannot embed any image that already has some IPTC data!
        $image = getimagesize( $path, $info );
        
        if ( isset( $info[ 'APP13' ] ) ) {
            die( 'Error: IPTC data found in source image, cannot continue' );
        } //isset( $info[ 'APP13' ] )
        
		if( isset($info[ '2#025' ] ) && is_array($info[ '2#025' ]) && !empty($info[ '2#025' ] )){
			
			$keywords = implode( ",\n ", $info[ '2#025' ] );
			
			} else {$keywords = "graphic,\n image,\n ";}
		
        // Set the IPTC tags
        $size = getimagesize( $old_file, $info );
        $info = iptcparse( $info[ 'APP13' ] );
        $iptc = array(
            //TITLE
             '2#005' => $info[ '2#005' ][ 0 ],
            //DESC
            '2#120' => $info[ '2#120' ][ 0 ],
            //AUTHOR
            '2#080' => $info[ '2#080' ][ 0 ],
            //COPYRIGHT
            '2#116' => $info[ '2#116' ][ 0 ] . ' ' . get_bloginfo( 'wpurl' ),
            //DATE
            '2#116' => $info[ '2#055' ][ 0 ],
            //KEYWORDS
            '2#025' => $keywords 
            
        );
        
        // Convert the IPTC tags into binary code
        $data = '';
        
        foreach ( $iptc as $tag => $string ) {
            $tag = substr( $tag, 2 );
            $data .= $this->iptc_make_tag( 2, $tag, $string );
        } //$iptc as $tag => $string
        
        // Embed the IPTC data
        $content = iptcembed( $data, $new_file );
        
        // Write the new image data out to the file.
        $fp = fopen( $new_file, "wb" );
        fwrite( $fp, $content );
        fclose( $fp );
        
    }
    /**
     * read_IPTC
     * 
     * Fetches IPTC from image which will be used in initial post creation.
     * 
     */
    private function read_IPTC( $file_name )
    {
        $dir = $this->upload_dir;
        
        $data = array( );
        
        $info = $this->files[ $file_name ];
        
        if ( in_array( 'jpg', $info[ 'extensions' ] ) ) {
            $size = GetImageSize( $dir . $file_name . '.jpg', &$info );
            
            $iptc = iptcparse( $info[ "APP13" ] );
            
            
            if ( !isset( $iptc[ '2#025' ] ) || empty( $iptc[ '2#025' ] ) ) {
                $keywords = array( ); //avoids errors by creating array with empty elements
                
            } //!isset( $iptc[ '2#025' ] ) || empty( $iptc[ '2#025' ] )
            
            else {
                $keywords = $iptc[ '2#025' ];
                
            }
            
            $data[ $file_name ] = array(
                
                 'title' => $iptc[ '2#005' ][ 0 ],
                
                'desc' => $iptc[ '2#120' ][ 0 ],
                
                'keywords' => implode( ',', $keywords ) 
                
            );
            
        } //in_array( 'jpg', $info[ 'extensions' ] )
        else {
            $this->report .= '<p class="alert">No jpeg file found for file: ' . $name . '</p>';
            return false;
        }
        
        return $data;
        
    }
	
    /**
     * cleanup - 
     * 
     * Cleans up files after transferrs and processing, tmp folder and upload folder
     * 
     */
	
		private function cleanup($processed){
		
		foreach ($processed as $delete_me){
			
			unlink($delete_me);
			
			}
			
		$this->delete_all_files( $this->upload_dir . '/tmp/' );
		
		}
    /**
     * delete_image - 
     * 
     * Deletes specified image file.
     * 
     */
    public function delete_image( $image_file )
    {
        $file_list_info = $this->files;
        
        $dir = $this->upload_dir;
        
        foreach ( $file_list_info[ $image_file ][ 'extensions' ] as $filetype ) {
            file_exists( $dir . $image_file . '.' . $filetype ) ? unlink( $dir . $image_file . '.' . $filetype ) : '';
            
        } //$file_list_info[ $image_file ][ 'extensions' ] as $filetype
        
        unset( $this->files[ $image_file ] );
        
    }
    
    /**
     * delete_images -
     * 
     * Deletes multiple selected images
     * 
     */
    
    public function delete_images( )
    {
        $dir = $this->upload_dir;
        
        foreach ( $_POST[ 'process-image' ] as $delete_me ) {
            $types = $this->expected_types;
            
            foreach ( $types as $filetype ) {
                file_exists( $dir . $delete_me . '.' . $filetype ) ? unlink( $dir . $delete_me . '.' . $filetype ) : '';
                
                unset( $this->files[ $delete_me ] );
                
            } //$types as $filetype
            
        } //$_POST[ 'process-image' ] as $delete_me
        
    }
    
    /**
     * delete_all_files -
     * 
     * Clears entire directory. This is useful if the user uploads something
     * the processor doesn't recognize and will not interact with. 
     * 
     */
    public function delete_all_files( $dir )
    {        
        
		if($dir == 'uploads'){ $dir = $this->upload_dir; $clear_files_array = true;}
		
        if ( $handle = opendir( $dir  ) ) {
            while ( false !== ( $entry = readdir( $handle ) ) ) {
                if ( $entry != "." && $entry != ".." && $entry != "upload.php" && $entry != "tmp" && $entry != ".htaccess" ) {
                    //delete 
                    file_exists( $dir . '/' . $entry ) ? unlink( $dir . '/' . $entry ) : '';
                    
                } //$entry != "." && $entry != ".." && $entry != "upload.php"
                
            } //false !== ( $entry = readdir( $handle ) )
            
        } //$handle = opendir( $this->upload_dir )
        if($clear_files_array == true){ 
        $this->files = array( );
		}
    }
	
    
    /**
     * check_image_criteria -
     * 
     * This function checks images to make sure it all all necessary IPTC data,
     * and will alert the user accordingly.
     * 
     */
    
	
    private function check_image_criteria( $file_name )
    {
        $good_icon = '<img alt="ok" src="' . symbiostock_IMGDIR . '/filetypes/ok.png" />';
        
        $bad_icon = '<img alt="alert" src="' . symbiostock_IMGDIR . '/filetypes/alert.png" />';
        
        $alert_icon = '<img alt="alert" src="' . symbiostock_IMGDIR . '/filetypes/semi-alert.png" />';
        
        $reports = '';
        
        $attributes = $this->read_IPTC( $file_name );
        
        $available_extensions = $this->files[ $file_name ][ 'extensions' ];
        
        //make sure we have at least one image file.
        
        if ( !in_array( 'jpg', $available_extensions ) && !in_array( 'png', $available_extensions ) ) {
            $reports .= '<li>' . $bad_icon . '<span class="negative">No matching image files found. You may have named them incorrectly. Fix problem and re-upload. <strong>This file has been removed</strong>. </span><strong>Example:</strong> "<em><strong>my_stock_art</strong>.eps</em>" or "<em><strong>my_stock_art</strong>.zip</em>" must have accompanying ""<em><strong>my_stock_art</strong>.jpg</em>" to be considered a valid set/file.</li>';
            
            $this->delete_image( $file_name );
            
        } //!in_array( 'jpg', $available_extensions ) && !in_array( 'png', $available_extensions )
		
        
        if ( !in_array( 'jpg', $available_extensions ) && in_array( 'png', $available_extensions ) ) {
            $reports .= '<li class="neutral">' . $good_icon . '<strong>.png</strong> image only. Make sure it contains transparency.</li>';
            
        } //!in_array( 'jpg', $available_extensions ) && in_array( 'png', $available_extensions )
        if ( in_array( 'jpg', $available_extensions ) && in_array( 'eps', $available_extensions ) ) {
            $reports .= '<li class="neutral">' . $good_icon . 'This is a vector image.</li>';
            
        } //in_array( 'jpg', $available_extensions ) && in_array( 'eps', $available_extensions )
        
        //if we have a jpeg, see if it contains all IPTC data.	
        if ( in_array( 'jpg', $available_extensions ) ) {
            if ( !empty( $attributes[ $file_name ][ 'title' ] ) ) {
                $reports .= '<li class="positive">' . $good_icon . 'Title found.</li>';
            } //!empty( $attributes[ $file_name ][ 'title' ] )
            else {
                $reports .= '<li class="negative">' . $bad_icon . 'Title not found.</li>';
            }
            
            if ( !empty( $attributes[ $file_name ][ 'desc' ] ) ) {
                $reports .= '<li class="positive">' . $good_icon . 'Description found.</li>';
            } //!empty( $attributes[ $file_name ][ 'desc' ] )
            else {
                $reports .= '<li class="negative">' . $bad_icon . 'Description not found.</li>';
            }
            
            if ( !empty( $attributes[ $file_name ][ 'keywords' ] ) ) {
                $reports .= '<li class="positive">' . $good_icon . 'Keywords found.</li>';
            } //!empty( $attributes[ $file_name ][ 'keywords' ] )
            else {
                $reports .= '<li class="negative">' . $bad_icon . 'Keywords not found.</li>';
            }
            
            if ( empty( $attributes[ $file_name ][ 'title' ] ) && empty( $attributes[ $file_name ][ 'desc' ] ) ) {
                $reports .= '<li class="negative">' . $bad_icon . 'Title and description empty. <strong>Save as draft and edit to fix</strong>.</li>';
            } //empty( $attributes[ $file_name ][ 'title' ] ) && empty( $attributes[ $file_name ][ 'desc' ] )
            
        } //in_array( 'jpg', $available_extensions )
        
        return '<ul class="check_info">' . $reports . '<ul>';
        
    }
    
    /**
     * get_file_type_icons - 
     * 
     * Simply fetches icons for each file type which is used in interface.
     * 
     */
    
    public function get_file_type_icons( $ext, $echo = false )
    {
        $src = symbiostock_IMGDIR . '/filetypes/';
        
        $url = '';
        
        $title = '';
        
        switch ( $ext ) {
            
            case 'png':
                
                $url = $src . 'image-x-generic-32.png';
                
                $title = 'png file';
                
                break;
            
            case 'eps':
                
                $url = $src . 'image-x-eps-32.png';
                
                $title = 'encapsulated postscript, vector graphic';
                
                break;
            
            case 'zip':
                
                $url = $src . 'application-zip-32.png';
                
                $title = 'zip file';
                
                break;
            
            case 'jpg':
                
                $url = $src . 'image-x-generic-jpg-32.png';
                
                $title = 'jpeg';
                
                break;
            
            case 'name':
                
                $url = $src . 'name.png';
                
                $title = 'File Name';
                
                break;
            
            case 'iptc':
                
                $url = $src . 'iptc.png';
                
                $title = 'iptc';
                
                break;
            
            case 'yes':
                
                $url = $src . 'supplied.png';
                
                $title = 'supplied';
                
                break;
            
            case 'no':
                
                $url = $src . 'none.png';
                
                $title = 'not supplied';
                
                break;
                
        } //$ext
        
        if ( $echo == true ) {
            echo '<img src="' . $url . '" alt="' . $title . '" title="' . $title . '" />';
        } //$echo == true
        else {
            return array(
                 'url' => $url,
                'title' => $title,
                'supplied' => $supplied 
            );
        }
    }
    
	
    /**
     * build_file_list -
     * 
     * Creates the file list info which the class uses.
     * 
     */
    
    public function build_file_list( )
    {
        //this function builds the uploads list and adds to files array
        
        $dir = $this->upload_dir;
        
        $files = array( );
        
        if ( $handle = opendir( $this->upload_dir ) ) {
            while ( false !== ( $entry = readdir( $handle ) ) ) {
                if ( $entry != "." && $entry != ".." && $entry != "upload.php" && $entry != "tmp" && $entry != ".htaccess"  ) {
                    $entry = explode( '.', $entry );
               
                    if ( !in_array( $files[ $entry[ 0 ] ], $files ) ) {
                        $files[ $entry[ 0 ] ][ 'extensions' ] = array(
                             $entry[ 1 ] 
                        );
                          
                    } //!in_array( $files[ $entry[ 0 ] ], $files )
                    else { 
                        array_push( $files[ $entry[ 0 ] ][ 'extensions' ], $entry[ 1 ] );
                        
                                           
                    }
                         //get size of file if jpeg or png
                       
                        if ( $entry[ 1 ] == 'jpg' || $entry[ 1 ] == 'png' ) {
                            list( $width, $height, $type, $attr ) = getimagesize( $dir . $entry[ 0 ] . '.' . $entry[ 1 ] );
                            
                            $files[ $entry[ 0 ] ][ 'size_' . $entry[ 1 ] ] = array(
                                 $width,
                                $height 
                            );
                            
                        } //$entry[ 1 ] == 'jpg' || $entry[ 1 ] == 'png'
                } //$entry != "." && $entry != ".."
                
            } //false !== ( $entry = readdir( $handle ) )
            
        } //$handle = opendir( dirname( __FILE__ ) . '/admin/' )
		
        $this->files = $files;
        
    }
    /**
     * list_images
     * 
     * Builds a viewable table from the list build_file_list() provides.
     * 
     */
    public function list_images( )
    {
        $listings = $this->files;
        
?> <table class="symbiostock-image-processor wp-list-table widefat"> 
        
                <thead>
                    <tr>
                        <th class="manage-column column-cb check-column" id="cb" scope="col"> <input type="checkbox" id="cb-select-all-1">
                        </th>
                        <th class="manage-column jpg" scope="col">#</th>
                        <th class="manage-column jpg" scope="col">File Name</th>
                        <th class="manage-column jpg" scope="col">Info</th>
                        <th class="manage-column jpg" scope="col">jpeg</th>
                        <th class="manage-column png" scope="col">png</th>
                        <th class="manage-column eps" scope="col">eps</th>
                        <th class="manage-column zip" scope="col">zip</th>
                    </tr>
                </thead>
                <tr class="key">
                    <th>&nbsp;</th>
                    <th>&nbsp;</th>
                    <th class="manage-column jpg" scope="col"><?php
        $this->get_file_type_icons( 'name', true );
?></th>
                    <th class="manage-column jpg" scope="col"><?php
        $this->get_file_type_icons( 'iptc', true );
?></th>
                    <th class="manage-column jpg" scope="col"><?php
        $this->get_file_type_icons( 'jpg', true );
?></th>
                    <th class="manage-column png" scope="col"><?php
        $this->get_file_type_icons( 'png', true );
?></th>
                    <th class="manage-column eps" scope="col"><?php
        $this->get_file_type_icons( 'eps', true );
?></th>
                    <th class="manage-column zip" scope="col"><?php
        $this->get_file_type_icons( 'zip', true );
?></th>
                </tr>
                <tbody>
        <?php
        
        $count = 1;
        
        foreach ( $listings as $key => $listing ) {
            $count % 2 ? $odd_even = 'alternate' : $odd_even = '';
            
            $extns = array( );
            
            foreach ( $listing[ 'extensions' ] as $ext ) {
                array_push( $extns, $ext );
                
                
            } //$listing[ 'extensions' ] as $ext
            
            $search = $this->expected_types;
            
            $file_types = '';
            
            
            
            foreach ( $search as $is_supplied ) {
                in_array( $is_supplied, $extns ) ? $filetypes_supplied = $this->get_file_type_icons( 'yes' ) : $filetypes_supplied = $this->get_file_type_icons( 'no' );
                
                $file_types .= '<td><img alt="' . $filetypes_supplied[ 'title' ] . '" title="' . $filetypes_supplied[ 'title' ] . '" src="' . $filetypes_supplied[ 'url' ] . '" /></td>';
            } //$search as $is_supplied
            
?> <tr class="<?php
            echo $odd_even;
?>"> 
            
            <th class="check-column" scope="row">
               
                <input type="checkbox" value="<?php
            echo $key;
?>" name="process-image[]" id="image-<?php
            echo $count;
?>">
            
            </th>
            
            <?php
         
            echo '<td>' . $count . '</td><td>' . str_replace( '_', ' ', ucwords( $key ) ) . '</td><td> ' . $this->check_image_criteria( $key ) . ' </td>' . $file_types;
            
?> </tr> <?php
            $count++;
            
        } //$listings as $key => $listing
?> 
        </tbody>
        <tfoot>
            <tr>
                <th class="manage-column column-cb check-column" id="cb" scope="col"> <input type="checkbox" id="cb-select-all-2">
                </th>
                <th class="manage-column jpg" scope="col">#</th>
                <th class="manage-column jpg" scope="col">File Name</th>
                <th class="manage-column jpg" scope="col">Info</th>
                <th class="manage-column jpg" scope="col">jpeg</th>
                <th class="manage-column png" scope="col">png</th>
                <th class="manage-column eps" scope="col">eps</th>
                <th class="manage-column zip" scope="col">zip</th>
            </tr>
        </tfoot>
        
        </table> 
                
        <?php
        
    }
    
    /**
     * create_image_page - 
     * 
     * This takes all the populated data and creates a wordpress custom post (image)
     * page. Very important, should be improved and expounded to benefit post creation
     * more, in areas of SEO and content.
     * 
     */
    
    private function create_image_page( $image_meta_array, $image_file )
    {
        $_POST[ 'action' ] == 'process_publish' ? $status = 'publish' : $status = 'draft';
        
        $image_tags = explode( ',', $image_meta_array[ 'keywords' ] );
        
        $post = array(
             'comment_status' => 'open', // 'closed' means no comments.
            
            'ping_status' => 'open', // 'closed' means pingbacks or trackbacks turned off
            
            'post_author' => get_current_user_id(), //The user ID number of the author.
            
            'post_content' => $image_meta_array[ 'description' ], //The full text of the post.
            
            'post_excerpt' => $image_meta_array[ 'description' ], //For all your post excerpt needs.
            
            'post_name' => $image_meta_array[ 'title' ], // The name (slug) for your post
            
            'post_status' => $status, //[ 'draft' | 'publish' | 'pending'| 'future' | 'private' | custom registered status ] 
            
            'post_title' => $image_meta_array[ 'title' ], //The title of your post.
            
            'post_type' => 'image', //You may want to insert a regular post, page, link, a menu item or some custom post type
            
            'tags_input' => $image_meta_array[ 'keywords' ], //For tags.
            
            'tax_input' => array(
                 'image-tags' => $image_tags 
            ) // support for custom taxonomies. 
        );
        
        $posted_id = wp_insert_post( $post, $wp_error );
        
        //update post meta, excluding certain values
        
        $exclude = array(
             'title',
            'description',
            'keywords' 
        );
        
        foreach ( $image_meta_array as $post_meta => $value ) {
            if ( !in_array( $post_meta, $exclude ) ) {
                update_post_meta( $posted_id, $post_meta, $value );
                
            } //!in_array( $post_meta, $exclude )
            
        } //$image_meta_array as $post_meta => $value
        
        //IMPORTANT this processes the image and attributes it to the post.
        
        $this->symbiostock_process_image( $image_file, $posted_id );
        
        return $posted_id;
        
    }
	
	//formats filesize to be humanly readable
	
	function formatSize( $bytes )
	{
			$types = array( 'B', 'KB', 'MB', 'GB', 'TB', 'PB' );
			for( $i = 0; $bytes >= 1024 && $i < ( count( $types ) -1 ); $bytes /= 1024, $i++ );
					return( round( $bytes, 2 ) . " " . $types[$i] );
	}
	  
	function get_file_size($data, $type = ''){
		
		$current_file = $this->current_file;
				
		$file_name = $this->upload_dir . $current_file . '.' . $type;
		
		$extensions = $this->files[ $current_file  ][ 'extensions' ];
									
		switch($type){
			
			case 'eps':
			
			if(in_array('eps', $extensions)){
				
				$file_size = filesize($file_name);
				
				$file_size = $this->formatSize($file_size);
				
				return $file_size;
				
				} else { return 'N/A'; }
			
			break;
			
			case 'zip':
			
			if(in_array('zip', $extensions)){
				
				$file_size = filesize($file_name);
				
				$file_size = $this->formatSize($file_size);
				
				return $file_size;				
				
				} else { return 'N/A'; }
						
			break;
						
			}
		
		}	
    /**
     * create_size_specs - 
     * 
     * Simply decides what image sizes will be ahead of time. We do not pre-create
     * every image size, since those are dynamically provided for customer at download.
     * Also we wish to create a "sizes available" option from both user panel and 
     * image page, without having to regenerate verything and take up extra file space.
     * 
     */
    function create_size_specs( $resize_to, $width, $height, $preview = '' )
    {
        if ( $resize_to != 0 ) {
            $w = 0;
            $h = 0;
            
            if ( $width > $height ) {
                $w = $width / $height;
            } //$width > $height
            else {
                $w = 1;
            }
            if ( $height > $width ) {
                $h = $height / $width;
            } //$height > $width
            else {
                $h = 1;
            }
            
            $width_scaled = $w > $h ? $width_scaled = ( $w / $w ) * $resize_to : $width_scaled = ( 1 / $h ) * $resize_to;
            
            $height_scaled = $h > $w ? $height_scaled = ( $h / $h ) * $resize_to : $height_scaled = ( 1 / $w ) * $resize_to;
        } //$resize_to != 0
        else {
            $width_scaled = $width;
            
            $height_scaled = $height;
            
        }
        
        $width_scaled = round( $width_scaled );
        
        $height_scaled = round( $height_scaled );
        
        //make other specs based on values which will appear on page 
        
        $width_scaled > 1500 || $height_scaled > 1500 ? $dpi = 300 : $dpi = 72;
        
        $inches_dpi_statement = round( $width_scaled / $dpi, 2 ) . '" &times; ' . round( $height_scaled / $dpi, 2 ) . '" @ ' . $dpi . ' dpi';
        
        $pixel_statement = $width_scaled . ' &times; ' . $height_scaled . 'px';
        
        return array(
            
             'width' => $width_scaled,
            
            'height' => $height_scaled,
            
            'dpi' => $inches_dpi_statement,
            
            'pixels' => $pixel_statement 
            
        );
        
    }
	
	
    function establish_image_sizes( $image_file )
    {
        //we consider png superior to jpg due to transparency. 
        //if png is included, we will use that as sell-able item.
        
        $image_file = $this->files[ $image_file ];
        
		if(empty($image_file)){ return;}
		
        if ( in_array( 'png', $image_file[ 'extensions' ] ) ) {
            //get png size
            
            $width = $image_file[ 'size_png' ][ 0 ];
            
            $height = $image_file[ 'size_png' ][ 1 ];
            
        } //in_array( 'png', $image_file[ 'extensions' ] )
        
        elseif ( in_array( 'jpg', $image_file[ 'extensions' ] ) ) {
            $width = $image_file[ 'size_jpg' ][ 0 ];
            
            $height = $image_file[ 'size_jpg' ][ 1 ];
            
            //get jpg size	
            
        } //in_array( 'jpg', $image_file[ 'extensions' ] )
        else {
            return;
        }
        
        $medium_size = get_option('symbiostock_medium_size', 1000);
		$small_size = get_option('symbiostock_small_size', 500);
		$blogge_size = get_option('symbiostock_bloggee_size', 250);
		
		
        $sizes = array(
            
             'large' => $this->create_size_specs( 0, $width, $height ),
            
            'medium' => $this->create_size_specs( $medium_size, $width, $height ),
            
            'small' => $this->create_size_specs( $small_size, $width, $height ),
            
            'bloggee' => $this->create_size_specs( $blogge_size, $width, $height ),
            
            'preview' => $this->create_size_specs( 590, $width, $height, 'preview' ),
            
            'thumb' => $this->create_size_specs( 150, $width, $height ) 
            
        );
        
        return serialize( $sizes );
        
    }
    
	
    /**
     * image_vals
     * 
     * Uses all collected data relevant to image and prepares post meta 
     * variables, plus other things.
     * 
     */
    private function image_vals( $data, $image_file )
    {
        global $current_user;
        
        get_currentuserinfo();
        
        $image_meta = array(
            
             'title' => $data[ $image_file ][ 'title' ],
            
            'description' => $data[ $image_file ][ 'desc' ],
            
            'keywords' => $data[ $image_file ][ 'keywords' ],
            
            'extensions' => serialize( $this->files[ $image_file ][ 'extensions' ] ),
            
            'collections' => '',
            
            'collection_img' => false,
            
            'related_images' => '',
            
            'author' => $current_user->user_login,
            
            'live' => get_option( 'symbiostock_live', 'live' ),
            
            'size_info' => $this->establish_image_sizes( $image_file ),
            
            'price_bloggee' => get_option( 'price_bloggee', '2.50' ),
            
            'price_small' => get_option( 'price_small', '5.00' ),
            
            'price_medium' => get_option( 'price_medium', '10.00' ),
            
            'price_large' => get_option( 'price_large', '20.00' ),
            
            'price_vector' => get_option( 'price_vector', '25.00' ),
            
            'price_zip' => get_option( 'price_zip', '25.00' ),
			
			'symbiostock_bloggee_available' => get_option( 'symbiostock_bloggee_available', 'yes'),
			
			'symbiostock_small_available'   => get_option( 'symbiostock_small_available', 'yes'),
			
			'symbiostock_medium_available'  => get_option( 'symbiostock_medium_available', 'yes'),
			
			'symbiostock_large_available'   => get_option( 'symbiostock_large_available', 'yes'),
			
			'symbiostock_vector_available'  => get_option( 'symbiostock_vector_available', 'yes'),
			
			'symbiostock_zip_available'     => get_option( 'symbiostock_zip_available', 'yes'),
            
			'size_eps' => $this->get_file_size($data, 'eps'),
			
			'size_zip' => $this->get_file_size($data, 'zip'),
			
            'locked' => 'not_locked',
            
            'discount_percent' => 0,
            
            'exclusive' => get_option( 'exclusive', 'not_exclusive' ),
			
			'symbiostock_referral_label_1' => get_option( 'symbiostock_referral_label_1', '' ),
			'symbiostock_referral_label_2' => get_option( 'symbiostock_referral_label_2', '' ),
			'symbiostock_referral_label_3' => get_option( 'symbiostock_referral_label_3', '' ),
			'symbiostock_referral_label_4' => get_option( 'symbiostock_referral_label_4', '' ),
			'symbiostock_referral_label_5' => get_option( 'symbiostock_referral_label_5', '' ),			
			
			'symbiostock_referral_link_1' => get_option( 'symbiostock_referral_link_1', '' ),
			'symbiostock_referral_link_2' => get_option( 'symbiostock_referral_link_2', '' ),
			'symbiostock_referral_link_3' => get_option( 'symbiostock_referral_link_3', '' ),
			'symbiostock_referral_link_4' => get_option( 'symbiostock_referral_link_4', '' ),
			'symbiostock_referral_link_5' => get_option( 'symbiostock_referral_link_5', '' ),
            
        );
        return $image_meta;
    }
    
    /**
     * process -
     * 
     * This is the main processing loop which puts it all together.
     * 
     */
    
    public function process( )
    {
        $file_list_info = $this->files;
        
        //Did user select the files to process?
        if ( empty( $_POST[ 'process-image' ] ) ) {
            echo '<p><em>Please select files to process.</em></p>';
            
            return;
            
        } //empty( $_POST[ 'process-image' ] )
        
				
        //get posted files and process them
        foreach ( $_POST[ 'process-image' ] as $image_file ) {
            
			//update our current file for the class
			
			$this->current_file = $image_file;
			
			//class construction supplied us with available file types per file name...get these...
            $available_extensions = $file_list_info[ $image_file ][ 'extensions' ];
            
			//sometimes a file is deleted due to a problem (like mismatched filenames  resulting in eps files with no jpeg.
			//if something like that happens then we detect it and restart loop
			if(empty( $available_extensions )){ continue; }
			
            //if jpeg, then we get meta data
            if ( in_array( 'jpg', $available_extensions ) ) {
                $data = $this->read_IPTC( $image_file );
                
            } //in_array( 'jpg', $available_extensions )
            else {
				
			//update our current file for the class
			
			$this->current_file = $image_file;
				
                //if not jpeg, we give generic data for draft
                $data = array( );
                
                $data[ $image_file ] = array(
                    
                     'title' => 'Royalty Free Stock Image',
                    
                    'desc' => 'Royalty Free Stock Image',
                    
                    'keywords' => 'Stock Image, Royalty Free, Graphic, Download' 
                    
                );
                
            }
            
            $image_meta_array = $this->image_vals( $data, $image_file );
            
            $created_page = $this->create_image_page( $image_meta_array, $image_file );
            
			$processed = $this->transfer_original($created_page, $image_file, $image_meta_array );
			
			$this->cleanup($processed);
			
			unset( $this->files[ $this->current_file ] );
            
        } //$_POST[ 'process-image' ] as $image_file
        
    }
    
}
?>