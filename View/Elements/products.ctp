<div class="products" id="elementProducts">
  <h2><?php echo !empty($elementTitle) ? $elementTitle : 'Products';?></h2>
  <div class="indexContainer">
    <div class="indexRow" id="headingRow">
      <div class="indexCell columnHeading"></div>
    </div>
    <?php
$i = 0;
foreach ($products as $product):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
    <div class="indexRow">
      <div class="indexCell galleryThumb" id="galleryThumb<?php echo $product['Product']['id']; ?>"> <?php echo $this->Element('thumb', array('model' => 'Product', 'foreignKey' => $product['Product']['id'], 'thumbSize' => 'large', 'thumbLink' => '/products/products/view/'.$product['Product']['id']), array('plugin' => 'galleries'));  ?> </div>
      <div class="indexCell itemName productName" id="productName<?php echo $product["Product"]["id"]; ?>"> <?php echo $this->Html->link($product['Product']['name'] , array('controller' => 'products' , 'action'=>'view' , $product["Product"]["id"])); ?> </div>
      <?php if (!empty($product['ProductBrand'])) { ?>
      <div class="indexCell itemBrand productBrand" id="productBrand<?php echo $product["Product"]["id"]; ?>"> <?php echo $this->Html->link($product['ProductBrand']['name'] , array('controller' => 'product_brands' , 'action'=>'view' , $product["ProductBrand"]["id"])); ?> </div>
      <?php } ?>
      <div class="indexCell itemDescription productDescription" id="productDescription<?php echo $product["Product"]["id"]; ?>"> <?php echo strip_tags($product['Product']['summary']); ?> </div>
      <div class="indexCell itemPrice productPrice" id="productPrice<?php echo $product['Product']['id']; ?>"> <?php echo __('$'); ?><?php echo (!empty($product['ProductPrice'][0]['price']) ? $product['ProductPrice'][0]['price'] : $product['Product']['price']); ?> </div>
      <div class="indexCell itemAction productAction" id="productAction<?php echo $product['Product']['id']; ?>"> <?php echo $this->Element('cart_add', array('product' => $product), array('plugin' => 'products')); ?> </div>
    </div>
    <?php endforeach; ?>
  </div>
  <?php echo $this->Element('paging');?> </div>
