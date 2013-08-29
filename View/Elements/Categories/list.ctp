<?php
// NOT USED IN ANY SITE, FEEL FREE TO REWRITE
$products = $this->requestAction('/products/products/category/521918b7-2b30-435c-a803-59030ad25527/limit:3'); ?>

<div id="productCategory521918b7-2b30-435c-a803-59030ad25527" class="productCategory">
	<!-- Carousel items -->
	<ul class="productCategoryList">
        <?php for($i = 0; $i < count($products); $i++) : ?>
		<li class="media">
			<i class="icon-play pull-left"></i>
			<div class="media-body">
				<h6><?php echo $this->Html->link($products[$i]['Product']['name'], array('plugin' => 'products', 'controller' => 'products', 'action' => 'view_property', $products[$i]['Product']['id']));?></h6>
				<?php echo $products[$i]['Product']['description']; ?>
			</div>
		</li>
		<?php endfor; ?>
	</ul>
</div>