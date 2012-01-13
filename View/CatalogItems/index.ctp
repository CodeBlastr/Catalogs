<?php 
// set the contextual sorting items
$this->set('context_sort', array(
	'type' => 'select',
	'sorter' => array(array(
		'heading' => '',
		'items' => array(
			$this->Paginator->sort('price'),
			$this->Paginator->sort('name'),
			)
		)),
	)); 

echo $this->element('context_sort');

if (!empty($catalogItems[0]['Category'][0])) {  ?>

<div id="catalog<?php echo $catalogItems[0]['Category'][0]['id']; ?>" class="category view">
  <div id="viewname<?php echo $catalogItems[0]['Category'][0]['id']; ?>" class="viewRow name  altrow">
    <div id="viewNamename" class="viewCell name altrow"></div>
    <h2 id="viewContentname" class="viewCell content  altrow"> <?php echo $catalogItems[0]['Category'][0]['name']; ?> </h2>
  </div>
  <div id="viewdescription<?php echo $catalogItems[0]['Category'][0]['id']; ?>" class="viewRow description ">
    <div id="viewNamedescription" class="viewCell name "></div>
    <div id="viewContentdescription" class="viewCell content "> <?php echo $catalogItems[0]['Category'][0]['description']; ?> </div>
  </div>
</div>


<?php
}  // end category check

echo $this->element('products'); 

// set the contextual menu items
$this->set('context_menu', array('menus' => array(
	array(
		'heading' => 'Catalog Items',
		'items' => array(
			$this->Html->link(__('Add', true), array('controller' => 'catalog_items', 'action' => 'add')),
			)
		),
	))); 