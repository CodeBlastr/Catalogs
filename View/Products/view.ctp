<?php
// @todo Add the behavior dynamically, and show these links if the behavior is loaded for this view.
//echo $this->Favorites->toggleFavorite('favorite', $product['Product']['id']);
// echo $this->Favorites->toggleFavorite('watch', $product['Product']['id']); ?>

<div class="product view">
  <h2><?php  echo $product['Product']['name']; echo !empty($product['ProductBrand']['name']) ? ' by ' . $this->Html->link($product['ProductBrand']['name'], array('controller' => 'product_brands', 'action' => 'view', $product['ProductBrand']['id'])) : ''; ?></h2>
  <div class="itemGallery productGallery"> <?php echo $this->Element('gallery', array('model' => 'Product', 'foreignKey' => $product['Product']['id']), array('plugin' => 'galleries')); ?> </div>

  <!-- Start child images -->
  <?php if (!empty($product['ProductChild'][0])) : foreach ($product['ProductChild'] as $child) : ?><div class="childrenGalleries hide" id="childGallery<?php echo $child['id']; ?>"><?php echo $this->Element('gallery', array('model' => 'Product', 'foreignKey' => $child['id']), array('plugin' => 'galleries')); ?></div><?php endforeach; endif; ?>
  <!-- End child images -->

  <div class="itemSummary productSummary">
      <?php echo $product['Product']['summary']; ?>
  </div>
  <div class="itemDescription productDescription">
      <?php echo $product['Product']['description']; ?>
      <?php if($product['Product']['hours_expire'] !== NULL) { ?>
          <p class="productHoursExpire">This virtual product will be accessible for <?php echo $product['Product']['hours_expire'] ?> hours after purchase.</p>
      <?php } ?>
  </div>

  <div class="itemPrice productPrice"> <?php echo __('Price: $'); ?><span id="itemPrice"><?php echo (!empty($product['ProductPrice'][0]['price']) ? ZuhaInflector::pricify($product['ProductPrice'][0]['price']) : ZuhaInflector::pricify($product['Product']['price'])); ?></span> </div>

  <?php echo $this->Element('cart_add', array('product' => $product), array('plugin' => 'products')); ?>
</div>

<?php
// set the contextual menu items
$this->set('context_menu', array('menus' => array(
	array(
		'heading' => 'Product',
		'items' => array(
			$this->Html->link(__d('products', 'Edit'), array('action' => 'edit', $product['Product']['id'])),
			$this->Html->link(__d('products', 'Delete'), array('action' => 'delete', $product['Product']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $product['Product']['id'])),
			),
		),
	))); ?>
