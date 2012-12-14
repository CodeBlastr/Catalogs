<div class="product view" id="<?php echo __('product%s', $product['Product']['id']); ?>">
  <h2><?php echo $product['Product']['name']; echo !empty($product['ProductBrand']['name']) ? ' by ' . $this->Html->link($product['ProductBrand']['name'], array('controller' => 'product_brands', 'action' => 'view', $product['ProductBrand']['id'])) : ''; ?></h2>
  <div class="itemGallery productGallery"> 
      <?php echo $this->Element('gallery', array('model' => 'Product', 'foreignKey' => $product['Product']['id']), array('plugin' => 'galleries')); ?>
  </div>

  <div class="itemSummary productSummary">
      <?php echo $product['Product']['summary']; ?>
  </div>
  
  <div class="itemDescription productDescription">
      <?php echo $product['Product']['description']; ?>
      <?php if($product['Product']['hours_expire'] !== NULL) { ?>
          <p class="productHoursExpire">This virtual product will be accessible for <?php echo $product['Product']['hours_expire'] ?> hours after purchase.</p>
      <?php } ?>
  </div>

  <div class="itemPrice productPrice"> 
      <?php echo __('Price: $'); ?><span id="itemPrice"><?php echo (!empty($product['ProductPrice'][0]['price']) ? ZuhaInflector::pricify($product['ProductPrice'][0]['price']) : ZuhaInflector::pricify($product['Product']['price'])); ?></span> 
  </div>
  <?php echo $this->Element('cart_add', array('product' => $product), array('plugin' => 'products')); ?>
</div>