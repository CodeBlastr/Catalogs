<?php echo $this->Element('scaffolds/index', array('data' => $productBrands)); ?>

<?php
// set the contextual menu items
$this->set('context_menu', array('menus' => array(
    array(
		'heading' => 'Products',
		'items' => array(
			$this->Html->link(__('Dashboard'), array('controller' => 'products', 'action' => 'dashboard')),
			)
		),
	array(
		'heading' => 'Product',
		'items' => array(
			$this->Html->link(__d('products', 'Add'), array('action' => 'add')),
			),
		),
	))); ?>
