<div class="catalogItems" id="elementProducts">
  <h2><?php echo !empty($elementTitle) ? $elementTitle : 'Products';?></h2>
  <div class="indexContainer">
    <div class="indexRow" id="headingRow">
      <div class="indexCell columnHeading"></div>
    </div>
    <?php
$i = 0;
foreach ($catalogItems as $catalogItem):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
    <div class="indexRow">
      <div class="indexCell galleryThumb" id="galleryThumb<?php echo $catalogItem['CatalogItem']['id']; ?>"> <?php echo $this->Element('thumb', array('model' => 'CatalogItem', 'foreignKey' => $catalogItem['CatalogItem']['id'], 'thumbSize' => 'medium', 'thumbLink' => '/catalogs/catalog_items/view/'.$catalogItem['CatalogItem']['id']), array('plugin' => 'galleries'));  ?> </div>
      <div class="indexCell itemName catalogItemName" id="catalogItemName<?php echo $catalogItem["CatalogItem"]["id"]; ?>"> <?php echo $this->Html->link($catalogItem['CatalogItem']['name'] , array('controller' => 'catalog_items' , 'action'=>'view' , $catalogItem["CatalogItem"]["id"])); ?> </div>
      <?php if (!empty($catalogItem['CatalogItemBrand'])) { ?>
      <div class="indexCell itemBrand catalogItemBrand" id="catalogItemBrand<?php echo $catalogItem["CatalogItem"]["id"]; ?>"> <?php echo $this->Html->link($catalogItem['CatalogItemBrand']['name'] , array('controller' => 'catalog_item_brands' , 'action'=>'view' , $catalogItem["CatalogItemBrand"]["id"])); ?> </div>
      <?php } ?>
      <div class="indexCell itemDescription catalogItemDescription" id="catalogItemDescription<?php echo $catalogItem["CatalogItem"]["id"]; ?>"> <?php echo strip_tags($catalogItem['CatalogItem']['summary']); ?> </div>
      <div class="indexCell itemPrice catalogItemPrice" id="catalogItemPrice<?php echo $catalogItem['CatalogItem']['id']; ?>"> <?php echo __('$'); ?><?php echo (!empty($catalogItem['CatalogItemPrice'][0]['price']) ? $catalogItem['CatalogItemPrice'][0]['price'] : $catalogItem['CatalogItem']['price']); ?> </div>
      <div class="indexCell itemAction catalogItemAction" id="catalogItemAction<?php echo $catalogItem['CatalogItem']['id']; ?>"> <?php echo $this->Element('cart_add', array('catalogItem' => $catalogItem), array('plugin' => 'catalogs')); ?> </div>
    </div>
    <?php endforeach; ?>
  </div>
  <?php echo $this->Element('paging');?> </div>
