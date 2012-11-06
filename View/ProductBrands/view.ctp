<h1><?php echo $productBrand['ProductBrand']['name']; ?></h1>
<div class="productBrand view">
	<?php echo $productBrand['ProductBrand']['description']; ?>
</div>


<?php echo $this->element('products');  ?>

<?php 
$this->set('context_menu', array('menus' => array(
	array(
		'heading' => 'Manufacturers',
		'items' => array(
			$this->Html->link(__('List Brands', true), array('controller' => 'product_brands', 'action' => 'index')),
			)
		),
	))); ?>