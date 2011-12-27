<?php 
# @todo Add the behavior dynamically, and show these links if the behavior is loaded for this view.
# echo $this->Favorites->toggleFavorite('favorite', $catalogItem['CatalogItem']['id']); 
# echo $this->Favorites->toggleFavorite('watch', $catalogItem['CatalogItem']['id']); . 
?>

<div class="catalogItem view">
  <h2><?php  echo $catalogItem['CatalogItem']['name']; echo !empty($catalogItem['CatalogItemBrand']['name']) ? ' by ' . $this->Html->link($catalogItem['CatalogItemBrand']['name'], array('controller' => 'catalog_item_brands', 'action' => 'view', $catalogItem['CatalogItemBrand']['id'])) : ''; ?></h2>
  <div class="itemGallery catalogItemGallery"> <?php echo $this->element($gallery['Gallery']['type'], array('id' => $gallery['Gallery']['id']), array('plugin' => 'galleries')); ?> </div>
  
  <!-- Start child images -->
  <?php if (!empty($catalogItem['CatalogItemChildren'][0])) : foreach ($catalogItem['CatalogItemChildren'] as $child) : ?><div class="childrenGalleries hide" id="childGallery<?php echo $child['id']; ?>"><?php echo $this->Element($child['Gallery']['type'], array('id' => $child['Gallery']['id']), array('plugin' => 'galleries')); ?></div><?php endforeach; endif; ?>
  <!-- End child images -->
  
  <div class="itemDescription catalogItemDescription"> <?php echo $catalogItem['CatalogItem']['description']; ?> </div>
  
  <div class="itemPrice catalogItemPrice"> <?php echo __('Price: $'); ?><span id="itemPrice"><?php echo (!empty($catalogItem['CatalogItemPrice'][0]['price']) ? $catalogItem['CatalogItemPrice'][0]['price'] : $catalogItem['CatalogItem']['price']); ?></span> </div>
  
  <?php echo $this->Element('cart_add', array('catalogItem' => $catalogItem), array('plugin' => 'catalogs')); ?>   
    
</div>



<?php 
// set the contextual menu items
$this->set('context_menu', array('menus' => array(
	array(
		'heading' => 'Catalog Item',
		'items' => array(
			$this->Html->link(__d('catalogs', 'Edit'), array('action' => 'edit', $catalogItem['CatalogItem']['id'])),
			$this->Html->link(__d('catalogs', 'Delete'), array('action' => 'delete', $catalogItem['CatalogItem']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $catalogItem['CatalogItem']['id'])),
			),
		),
	)));
?>
