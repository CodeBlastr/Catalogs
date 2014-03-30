
<div class="product view media row" id="<?php echo __('product%s', $product['Product']['id']); ?>" itemscope itemtype="http://schema.org/Product">
    <div class="itemGallery productGallery col-md-4"> 
        <?php echo $this->Media->display($product['Media'][0], array('alt' => $product['Product']['name'])); ?>
    </div>

    <div class="itemDescription productDescription span5 col-md-8 media-body">
		<div class="itemSummary productSummary">
            <h2 class="media-heading" itemprop="name"><?php echo $product['Product']['name']; echo !empty($product['ProductBrand']['name']) ? ' by ' . $this->Html->link($product['ProductBrand']['name'], array('controller' => 'product_brands', 'action' => 'view', $product['ProductBrand']['id'])) : ''; ?></h2>
            <span itemprop="description"><?php echo $product['Product']['summary']; ?></span>
        </div>
        <?php echo $product['Product']['description']; ?>
        <?php if($product['Product']['hours_expire'] !== NULL) : ?>
            <p class="productHoursExpire">This virtual product will be accessible for <?php echo $product['Product']['hours_expire']; ?> hours after purchase.</p> 
        <?php endif; ?>
        
        <?php if (!empty($product['Product']['price'])) : ?>
        <div class="itemPrice productPrice" itemprop="offers" itemscope itemtype="http://schema.org/Offer"> 
        	Price : <span id="itemPrice" itemprop="price"><?php echo ZuhaInflector::pricify($product['Product']['price'], array('currency' => 'USD')); ?></span> 
        </div>
       	<?php endif; ?>
       	
        <?php echo $this->element('cart_add', array('product' => $product), array('plugin' => 'products')); ?>
    </div>
</div>