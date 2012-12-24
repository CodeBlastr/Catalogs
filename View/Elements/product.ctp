

<div class="product view media row-fluid" id="<?php echo __('product%s', $product['Product']['id']); ?>" itemscope itemtype="http://schema.org/Product">

    <div class="itemGallery productGallery pull-left media-object"> 
        <?php echo $this->Element('gallery', array('model' => 'Product', 'foreignKey' => $product['Gallery']['foreign_key']), array('plugin' => 'galleries')); ?>
    </div>

    <div class="itemDescription productDescription span5 pull-left media-body">
        <div class="itemSummary productSummary">
            <h2 class="media-heading" itemprop="name"><?php echo $product['Product']['name']; echo !empty($product['ProductBrand']['name']) ? ' by ' . $this->Html->link($product['ProductBrand']['name'], array('controller' => 'product_brands', 'action' => 'view', $product['ProductBrand']['id'])) : ''; ?></h2>
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

    <div class="well well-large last span4">
        <?php echo $this->Element('cart_add', array('product' => $product), array('plugin' => 'products')); ?>
    </div>
</div>