<?php
//this generates any number of sliders needed for widgets, pages, etc, which fetch latest/featured images of different sizes

function symbiostock_image_slider( $images = array(), $id = 'sscarousel', $size='preview', $action = 'latest' ){
	var_dump($images);
	?>
<div id="<?php echo $id ?>" class="carousel slide">
    <ol class="carousel-indicators">
        <li data-target="#<?php echo $id ?>" data-slide-to="0" class="active"></li>
        <li data-target="#<?php echo $id ?>" data-slide-to="1"></li>
        <li data-target="#<?php echo $id ?>" data-slide-to="2"></li>
    </ol>
    <!-- Carousel items -->
    <div class="carousel-inner">
        <div class="active item">…</div>
        <div class="item">…</div>
        <div class="item">…</div>
    </div>
    <!-- Carousel nav --> 
    <a class="carousel-control left" href="#<?php echo $id ?>" data-slide="prev">&lsaquo;</a> <a class="carousel-control right" href="#<?php echo $id ?>" data-slide="next">&rsaquo;</a> 
</div>
<?php	
}
?>