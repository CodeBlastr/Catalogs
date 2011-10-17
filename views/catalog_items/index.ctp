<?php
echo $this->element('products'); 

// set the contextual menu items
$this->Menu->setValue(array(
	array(
		'heading' => 'Catalog Items',
		'items' => array(
			$this->Html->link(__('Add', true), array('controller' => 'catalog_items', 'action' => 'add')),
			)
		),
	)
); 
?>