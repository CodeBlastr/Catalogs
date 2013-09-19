<div class="products" id="elementAuctionProducts">

	<div class="row-fluid">
	<?php
    if (!empty($products)) {
        $i = 0;
        foreach ($products as $product) : ?>
        
        <div class="span11">
        	<div class="row">
        		<div class="span3">
					<?php echo $this->Element('thumb', array('model' => 'Product', 'foreignKey' => $product['Product']['id'], 'thumbSize' => 'medium', 'thumbLink' => '/products/products/viewAuction/'.$product['Product']['id']), array('plugin' => 'galleries')); ?>
				</div>
				<div class="span9">
					<?php echo $this->Html->link($product['Product']['name'] , array('controller' => 'products' , 'action'=>'viewAuction' , $product["Product"]["id"])); ?>
				</div>
			</div>
        </div>
        <div class="span1">
        	<div>$<?php echo (!empty($product['ProductPrice'][0]['price']) ? $product['ProductPrice'][0]['price'] : $product['Product']['price']); ?></div>
        </div>
        
        <?php
        endforeach;
    } else {
        echo __('<p>No products found.</p>');
    }
    ?>
	</div>
    <?php echo $this->Element('paging');?>
</div>
