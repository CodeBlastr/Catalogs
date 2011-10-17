<h1><?php echo $catalogItemBrand['CatalogItemBrand']['name']; ?></h1>
<div class="catalogItemBrand view">
	<?php echo $catalogItemBrand['CatalogItemBrand']['description']; ?>
</div>


<?php echo $this->element('products');  ?>

<?php 
$menu->setValue(array(
	array(
		'heading' => 'Manufacturers',
		'items' => array(
			$this->Html->link(__('List Brands', true), array('controller' => 'catalog_item_brands', 'action' => 'index')),
			)
		),
	));
?>