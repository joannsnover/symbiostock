<?php
function symbiostock_admin_tabs( $current = 'homepage' ) {
    
	//Change add tabs to array corresponding with admin folder's php files. ('general' => 'General', should refer to admin/general.php)
	
	$tabs = array( 'homepage' => 'symbiostock', 'general' => 'General', 'footer' => 'Footer' );
	
    echo '<div id="icon-themes" class="icon32"><br></div>';
    
	echo '<h2 class="nav-tab-wrapper">';
    
	//decide which tab to show
	
	if ( isset ($_POST)) {
		
		$current = $_POST['tab'];
		
	} else if (isset($_GET)) {
		
		$current = $_GET['tab'];
		
	} else { $current = 'homepage'; }
	
	//generate the tabs, and choose active tab
	
	foreach( $tabs as $tab => $name ){
    
	    $class = ( $tab == $current ) ? ' nav-tab-active' : '';
    
	    echo "<a id='$tab' class='nav-tab$class' href='?page=symbiostock-control-options&tab=$tab'>$name</a>";
    }
	echo '</h2>';
}
?>