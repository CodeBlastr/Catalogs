<?php 
echo $this->Form->create('CatalogItem', array(
  'url' => array(
    'plugin' => 'catalogs',
    'controller' => 'catalog_items',
    'action' => 'get_stock',
	'category_id' => $this->params['named']['category_id'] 
  )
  ));
	?>
		<h3>Options</h3>
		<?php 
		if(isset($options)) {
			foreach($options as $key => $opt) {
				echo '<div style ="float:left; width: 200px; clear:none;">';
				echo '<fieldset>';
				echo '<legend>' . $opt['CategoryOption']['name'] . '</legend>';
				$sel = array();
				foreach($opt['children'] as $child) {
					$sel[$child['CategoryOption']['id']] = $child['CategoryOption']['name'];
				}
				if (!empty($sel))
					echo $this->Form->input('CategoryOption.'.$opt['CategoryOption']['id'], 
						array('options'=>$sel, 'multiple'=>'checkbox', 'label'=> false, 'div'=>false,
								'type'=> $opt['CategoryOption']['type'] == 'Attribute Group' ? 'radio' : 'select'));
				echo '</fieldset>';
				echo '</div';
			}
		} 
		
	 echo $this->Form->end('Submit');	
	?>
<?php if(isset($catalogitems)) { ?>	
<h2><?php __('CatalogItems');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th>Id</th>
			<th>Name</th>
			<th>Description</th>
			<th>Price</th>
			<th>Stock Quantity</th>
			<th>Created</th>
			<th>Modified</th>
	</tr>
	<?php
	foreach ($catalogitems as $catalogitem): ?>
	
	<?php 	if($catalogitem['CatalogItem']['stock_item'] != 0) {
	
	?>	
	<tr>
		<td><?php echo $catalogitem['CatalogItem']['id']; ?>&nbsp;</td>
		<td><?php echo $catalogitem['CatalogItem']['name']; ?>&nbsp;</td>
		<td><?php echo $catalogitem['CatalogItem']['description']; ?>&nbsp;</td>
		<td><?php echo $catalogitem['CatalogItem']['price']; ?>&nbsp;</td>
		<td><?php echo $catalogitem['CatalogItem']['stock_item']; ?>&nbsp;</td>
		<td><?php echo $catalogitem['CatalogItem']['created']; ?>&nbsp;</td>
		<td><?php echo $catalogitem['CatalogItem']['modified']; ?>&nbsp;</td>
	</tr>
	<?php } ?>
<?php endforeach; ?>
<?php } ?>