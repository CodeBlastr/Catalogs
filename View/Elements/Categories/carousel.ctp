<?php 
// NOT USED IN ANY SITE, FEEL FREE TO REWRITE
$products = $this->requestAction('/products/products/category/521b7d14-e534-4fbf-9281-58810ad25527'); ?>

<div class="row-fluid">
	<div class="span12">
		<div id="myCarousel" class="carousel slide">
			<!-- Carousel items -->
			<div class="carousel-inner">
		        <?php for($i = 0; $i < count($products); $i++) { ?>
				<div class="<?php echo $i == 0 ? 'active' : null; ?> item">
					<?php echo $this->Media->display($products[$i]['Media'][0], array('alt' => $products[$i]['Product']['name'])); ?>
					<div class="carousel-caption">
						<h4><small>Featured Listing</small></h4>
						<h1><?php echo $this->Html->link($products[$i]['Product']['name'], array('plugin' => 'products', 'controller' => 'products', 'action' => 'view_property', $products[$i]['Product']['id'])); ?></h1>
						<p>
							<?php echo $products[$i]['Product']['Meta']['street']?>
						</p>
						<hr />
						<p>
							$<?php echo $products[$i]['Product']['price']?>
						</p>
						<hr />
						<p>
							<?php echo $products[$i]['Product']['Meta']['bedroom']?> Bedroom
						</p>
						<hr />
						<p>
							<?php echo $products[$i]['Product']['Meta']['bathroom']?> bathrooms
						</p>
						<hr />
						<p>
							<?php echo $products[$i]['Product']['Meta']['footage']?>
						</p>
						<hr />
						<p>
							<?php echo $products[$i]['Product']['Meta']['acreage']?>
						</p>
						<hr />
						<p align="center" class="intrested">
							Intrested in the listing?
						</p>
						<p align="center">
							<button class="btn btn-primary contact-btn" type="button">CONTACT US</button>
						</p>
					</div>
				</div>
				<?php } ?>
			</div>
			<!-- Carousel nav --> 
			<a class="carousel-control left" href="#myCarousel" data-slide="prev">&lsaquo;</a>
			<a class="carousel-control right" href="#myCarousel" data-slide="next">&rsaquo;</a>
			<ul class="carousel-indicators row-fluid" style="position: static;">
		        <?php for($i = 0; $i < count($products); $i++) : ?>
		        	<li data-target="#myCarousel" data-slide-to="<?php echo $i; ?>" class="<?php echo $i == 0 ? 'active' : null; ?>"></li>
		        <?php endfor; ?>
			</ul>
		</div>
	</div>
</div>
