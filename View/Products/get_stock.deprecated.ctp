<?php 
echo $this->Form->create('Product', array(
  'url' => array(
    'plugin' => 'products',
    'controller' => 'products',
    'action' => 'get_stock',
	'category_id' => $this->request->params['named']['category_id'] 
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
<?php if(isset($products)) { ?>	
<h2><?php echo __('Products');?></h2>
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
	foreach ($products as $product): ?>
	
	<?php 	if($product['Product']['stock'] != 0) {
	
	?>	
	<tr>
		<td><?php echo $product['Product']['id']; ?>&nbsp;</td>
		<td><?php echo $product['Product']['name']; ?>&nbsp;</td>
		<td><?php echo $product['Product']['description']; ?>&nbsp;</td>
		<td><?php echo $product['Product']['price']; ?>&nbsp;</td>
		<td><?php echo $product['Product']['stock']; ?>&nbsp;</td>
		<td><?php echo $product['Product']['created']; ?>&nbsp;</td>
		<td><?php echo $product['Product']['modified']; ?>&nbsp;</td>
	</tr>
	<?php } ?>
<?php endforeach; ?>
<?php } ?>