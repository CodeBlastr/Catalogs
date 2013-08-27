<?php 
//debug($this->requestAction('/products/products/category/521b7d14-e534-4fbf-9281-58810ad25527'));

$products = $this->requestAction('/products/products/category/521b7d14-e534-4fbf-9281-58810ad25527'); ?>

<div class="row-fluid">
<div class="span8">
<div id="myCarousel" class="carousel slide">
	<!-- Carousel items -->
	<div class="carousel-inner">
		<?php debug($products); ?>
        <?php for($i = 0; $i < count($products['GalleryImage']); $i++) { ?>
		<div class="<?php echo $i == 0 ? 'active' : null; ?> item">
			<?php 
			echo !empty($gallery['GalleryImage'][$i]['_embed']) ? 
				'<iframe height="' . $products['GallerySettings']['largeImageHeight'] . '" width="100%" src="' . $products['GalleryImage']['filename']['_embed'] . '" frameborder="0" allowfullscreen></iframe>' :
				$this->Html->image($products['GalleryImage'][$i]['dir'] . '/' . $products['GalleryImage'][$i]['filename']); ?>
		</div>
		<?php } ?>
	</div>
	<!-- Carousel nav --> 
	<a class="carousel-control left" href="#myCarousel" data-slide="prev">&lsaquo;</a>
	<a class="carousel-control right" href="#myCarousel" data-slide="next">&rsaquo;</a>
	<ul class="carousel-indicators row-fluid" style="position: static;">
        <?php for($i = 0; $i < count($products); $i++) { ?>
		<li data-target="#myCarousel" data-slide-to="<?php echo $i; ?>" class="<?php echo $i == 0 ? 'active' : null; ?> pull-left"></li>
		<?php } ?>
	</ul>
	
</div>
</div>
<div class="span4">
	<p class="slider-rgt-txt1">Featured Listing</p>

<p class="slider-rgt-txt2"><?php echo $products[0]['Product']['name']?></p>
&nbsp;

<p class="slider-rgt-txt-hr"><?php echo $products[0]['Product']['Meta']['street']?></p>

<hr />
<p class="slider-rgt-txt-hr">$<?php echo $products[0]['Product']['price']?></p>

<hr />
<p class="slider-rgt-txt-hr"><?php echo $products[0]['Product']['Meta']['bedroom']?> Bedroom</p>

<hr />
<p class="slider-rgt-txt-hr"><?php echo $products[0]['Product']['Meta']['bathroom']?> bathrooms</p>

<hr />
<p class="slider-rgt-txt-hr"><?php echo $products[0]['Product']['Meta']['footage']?></p>
<hr />
<p align="center" class="intrested">Intrested in the listing?</p>

<p align="center"><button class="btn btn-primary contact-btn" type="button">CONTACT US</button></p>
</div>
</div>
