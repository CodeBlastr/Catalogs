<div class="catalogItemPrices form">
<?php echo $form->create('CatalogItemPrice');?>
	<fieldset>
 		<legend><?php __('Add Price');?></legend>
<?php
	echo $this->element('hidden_item', array('plugin' => 'catalogs') );
?>

<?php		echo 'Product Item: ' . $this->data['CatalogItem']['name'];
			echo $form->input('Catalog.id.0',
				 array('value'=>$this->data['CatalogItem']['catalog_id']));
		
			echo $form->input('CatalogItem.price', array('readonly'=>true, 'label'=>'Default Price'));
		
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
			echo $form->hidden("CatalogItemPrice.{$index}.id");
			echo $form->input("CatalogItemPrice.{$index}.price",
				array('default'=>0, 'div'=>false, 'label'=>false));
			echo $form->hidden("CatalogItemPrice.{$index}.catalog_item_id", array('value'=>$this->data['CatalogItem']['id'])); 
			echo $form->hidden("CatalogItemPrice.{$index}.user_role_id", array('default'=>$ugID));
			echo $form->hidden("CatalogItemPrice.{$index}.price_type_id", array('default'=>$ptID));
			echo '</td>';
			$index++;
		}?>
	</tr>
	<?php }?>
</table>
	</fieldset>
<?php echo $form->end('Submit');?>
</div>
<div class="actions">
	<ul>
		<li><?php echo $this->Html->link(__('List Prices', true), array('action' => 'index'));?></li>
	</ul>
</div>
