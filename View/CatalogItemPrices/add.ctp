<div class="catalogItemPrices form">
<?php echo $this->Form->create('CatalogItemPrice');?>
	<fieldset>
 		<legend><?php echo __('Add Price');?></legend>
		<?php
		echo $this->element('hidden_item', array('plugin' => 'catalogs') );
		echo 'Product Item: ' . $this->request->data['CatalogItem']['name'];
		echo $this->Form->input('Catalog.id.0', array('value' => $this->request->data['CatalogItem']['catalog_id']));
		echo $this->Form->input('CatalogItem.price', array('readonly' => true, 'label' => 'Default Price')); ?>
		<table>
			<tr>
				<th>User Roles</th>
			</tr>
			<?php 
			$i = 0; 
			foreach($userRoles as $ugID => $ug) { 
				echo '<tr><td>' . $ug . '</td><td>';
				echo $this->Form->hidden("CatalogItemPrice.{$i}.id");
				echo $this->Form->input("CatalogItemPrice.{$i}.price",	array('default'=>0, 'div'=>false, 'label'=>false));
				echo $this->Form->hidden("CatalogItemPrice.{$i}.catalog_item_id", array('value'=>$this->request->data['CatalogItem']['id'])); 
				echo $this->Form->hidden("CatalogItemPrice.{$i}.user_role_id", array('default'=>$ugID));
				echo '</td></tr>';
				$i = $i + 1;
			}?>
		</table>
	</fieldset>
<?php echo $this->Form->end('Submit');?>
</div>
