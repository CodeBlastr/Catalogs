
<div class="product view media row-fluid" id="<?php echo __('product%s', $product['Product']['id']); ?>" itemscope itemtype="http://schema.org/Product">
            <h2 class="media-heading" itemprop="name"><?php echo $product['Product']['name']; echo !empty($product['ProductBrand']['name']) ? ' by ' . $this->Html->link($product['ProductBrand']['name'], array('controller' => 'product_brands', 'action' => 'view', $product['ProductBrand']['id'])) : ''; ?></h2>

    <div class="itemGallery productGallery pull-left media-object"> 
        <?php echo $this->Element('Galleries.gallery', array('model' => 'Product', 'foreignKey' => $product['Product']['id'])); ?>
    </div>

    <div class="itemDescription productDescription span5 pull-left media-body">
        <div class="itemSummary productSummary">
            <span itemprop="description"><?php echo $product['Product']['summary']; ?></span>
        </div>
        <?php 
        echo $product['Product']['description'];
        if($product['Product']['hours_expire'] !== NULL) { 
            echo __('<p class="productHoursExpire">This virtual product will be accessible for %s hours after purchase.</p>', $product['Product']['hours_expire']); 
        } ?>
        <div class="itemPrice productPrice" itemprop="offers" itemscope itemtype="http://schema.org/Offer"> 
            <?php echo __('Price: $'); ?><span id="itemPrice" itemprop="price"><?php echo (!empty($product['ProductPrice'][0]['price']) ? ZuhaInflector::pricify($product['ProductPrice'][0]['price']) : ZuhaInflector::pricify($product['Product']['price'])); ?></span> 
        </div>
    </div>

    <div class="last span4">
    	<div>
    		<table>
    			<tr><td>Time left:</td><td><b><time datetime="<?php echo date('c', strtotime($product['Product']['ended']))?>"><?php echo $this->Time->timeAgoInWords($product['Product']['ended'])?></time></b><br />(<?php echo $this->Time->nice($product['Product']['ended']) ?>)</td></tr>
    			<tr><td>Bid history:</td><td><?php echo count($product['ProductBid']) ?> bids</td></tr>
    		</table>
    	</div>
    	<div class="well well-large">
        	<?php echo $this->Element('auction_bid', array('product' => $product), array('plugin' => 'products')); ?>       	
        	<?php 
	        	echo '<div class="action itemAddCart productAddCart">';
				echo $this->Form->create('TransactionItem', array('url' => array('plugin' => 'transactions', 'controller'=>'transaction_items', 'action'=>'add'), 'class' => 'form-inline'));
				echo $this->Form->hidden('TransactionItem.name' , array('value' => $productName));
				echo $this->Form->hidden('TransactionItem.model' , array('value' => $productModel));
				echo $this->Form->hidden('TransactionItem.foreign_key' , array('value' => $productForeignKey));
				echo $this->Form->hidden('TransactionItem.price' , array('value' => $productPrice));
				echo $this->Form->hidden('TransactionItem.arb_settings' , array('value' => $productArb));
				echo $this->Form->hidden('TransactionItem.quanity' , array('value' => 1));
			
				echo $this->Form->submit('Buy Now', array('class' => 'btn btn-primary'));
				$this->Form->end();
			    echo '</div>'; 
			?>
        </div>
    </div>
</div>
