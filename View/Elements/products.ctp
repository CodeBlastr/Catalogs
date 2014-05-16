<?php if (!empty($products)) : ?>
	<?php for ($i=0; $i < count($products); $i++) : ?>
	<div class="products list-group" id="product<?php echo $products[$i]['Product']['id']; ?>">
		<div class="list-group-item clearfix">
	    	<div class="col-md-4"> 
	    		<?php echo $this->Html->link($this->Media->display($products[$i]['Media'][0], array('alt' => $products[$i]['Product']['name'])), array('controller' => 'products', 'action' => 'view', $products[$i]["Product"]["id"]), array('escape' => false)); ?>
	        </div>
	        <div class="col-md-8"> 
	        	<h3><?php echo $this->Html->link($products[$i]['Product']['name'], array('controller' => 'products', 'action' => 'view', $products[$i]["Product"]["id"])); ?></h3>
	            <p><?php echo strip_tags($products[$i]['Product']['summary']); ?></p>
	            <p><?php echo !empty($products[$i]['ProductBrand']) ? $this->Html->link($products[$i]['ProductBrand']['name'], array('controller' => 'product_brands', 'action' => 'view', $products[$i]["ProductBrand"]["id"])) : null; ?></p>
	           	<?php echo $this->element('Products.cart_add', array('product' => $product)); ?>
	           	<?php echo !empty($products[$i]['Product']['price']) ? __('<span class="badge"> %s </span>', ZuhaInflector::pricify($products[$i]['Product']['price']), array('currency' => 'USD')) : null; ?>
			</div>
		</div>	        
    </div>
	<?php endfor; ?>
	<div class="row">
		<div class="col-md-12">
			<?php echo $this->element('paging'); ?>
		</div>
	</div>	   
<?php else : ?>
	<p>No products found. <?php echo $this->Html->link('Add one now?', array('plugin' => 'products', 'controller' => 'products', 'action' => 'add')); ?></p>
<?php endif; ?>