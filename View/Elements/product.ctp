

<div class="product view media" id="<?php echo __('product%s', $product['Product']['id']); ?>">

    <div class="itemGallery productGallery pull-left media-object"> 
        <?php echo $this->Element('gallery', array('model' => 'Product', 'foreignKey' => $product['Gallery']['foreign_key']), array('plugin' => 'galleries')); ?>
    </div>

    <div class="itemDescription productDescription span5 pull-left media-body">
        <div class="itemSummary productSummary">
            <h2 class="media-heading"><?php echo $product['Product']['name']; echo !empty($product['ProductBrand']['name']) ? ' by ' . $this->Html->link($product['ProductBrand']['name'], array('controller' => 'product_brands', 'action' => 'view', $product['ProductBrand']['id'])) : ''; ?></h2>
            <?php echo $product['Product']['summary']; ?>
        </div>
        <?php 
        echo $product['Product']['description'];
        if($product['Product']['hours_expire'] !== NULL) { 
            echo __('<p class="productHoursExpire">This virtual product will be accessible for %s hours after purchase.</p>', $product['Product']['hours_expire']); 
        } ?>
        <div class="itemPrice productPrice"> 
            <?php echo __('Price: $'); ?><span id="itemPrice"><?php echo (!empty($product['ProductPrice'][0]['price']) ? ZuhaInflector::pricify($product['ProductPrice'][0]['price']) : ZuhaInflector::pricify($product['Product']['price'])); ?></span> 
        </div>
    </div>

    <div class="well well-large pull-right last span4">
        <?php echo $this->Element('cart_add', array('product' => $product), array('plugin' => 'products')); ?>
    </div>
</div>