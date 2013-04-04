<?php
if (!extension_loaded('imagick')){
   		echo '<h3>Imagick is not installed on this server. :(</h3>';
	}else {		
		echo '<h3>Imagick is installed on this server!!! Good times ahead!</h3>';	
	}


?>
<hr />
<div class="">
<p>This is a temporary page while Symbiostock is in its early testing / development phase.</p>
<br />
<?php
phpinfo();
?>
</div>