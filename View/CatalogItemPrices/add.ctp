<div class="catalogItemPrices form">
<?php echo $this->Form->create('CatalogItemPrice');?>
	<fieldset>
 		<legend><?php __('Add Price');?></legend>
<?php
	echo $this->element('hidden_item', array('plugin' => 'catalogs') );
?>

<?php		echo 'Product Item: ' . $this->request->data['CatalogItem']['name'];
			echo $this->Form->input('Catalog.id.0',
				 array('value'=>$this->request->data['CatalogItem']['catalog_id']));
		
			echo $this->Form->input('CatalogItem.price', array('readonly'=>true, 'label'=>'Default Price'));
		
	?>
<table>
	<tr>
		<th>User Roles</th>
			<?php
				foreach($priceTypes as $ptID => $pt) {
					echo '<th>' . $pt . '</th>';
				}?>
	</tr>
	<?php $index = 0;?>
	<?php foreach($userRoles as $ugID => $ug) {?>
	<tr>
		<td><?php echo $ug;?></td>
		<?php foreach($priceTypes as $ptID => $pt) {
			echo '<td>';
			echo $this->Form->hidden("CatalogItemPrice.{$index}.id");
			echo $this->Form->input("CatalogItemPrice.{$index}.price",
				array('default'=>0, 'div'=>false, 'label'=>false));
			echo $this->Form->hidden("CatalogItemPrice.{$index}.catalog_item_id", array('value'=>$this->request->data['CatalogItem']['id'])); 
			echo $this->Form->hidden("CatalogItemPrice.{$index}.user_role_id", array('default'=>$ugID));
			echo $this->Form->hidden("CatalogItemPrice.{$index}.price_type_id", array('default'=>$ptID));
			echo '</td>';
			$index++;
		}?>
	</tr>
	<?php }?>
</table>
	</fieldset>
<?php echo $this->Form->end('Submit');?>
</div>
<div class="actions">
	<ul>
		<li><?php echo $this->Html->link(__('List Prices', true), array('action' => 'index'));?></li>
	</ul>
</div>
