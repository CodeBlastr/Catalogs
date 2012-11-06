<div class="productPrices form">
<?php echo $this->Form->create('ProductPrice');?>
	<fieldset>
 		<legend><?php echo __('Add Price');?></legend>
		<?php
		echo $this->element('hidden_item', array('plugin' => 'products') );
		echo 'Product Item: ' . $this->request->data['Product']['name'];
		echo $this->Form->input('ProductStore.id.0', array('value' => $this->request->data['Product']['store_id']));
		echo $this->Form->input('Product.price', array('readonly' => true, 'label' => 'Default Price')); ?>
		<table>
			<tr>
				<th>User Roles</th>
			</tr>
			<?php 
			$i = 0; 
			foreach($userRoles as $ugID => $ug) { 
				echo '<tr><td>' . $ug . '</td><td>';
				echo $this->Form->hidden("ProductPrice.{$i}.id");
				echo $this->Form->input("ProductPrice.{$i}.price",	array('default'=>0, 'div'=>false, 'label'=>false));
				echo $this->Form->hidden("ProductPrice.{$i}.product_id", array('value'=>$this->request->data['Product']['id'])); 
				echo $this->Form->hidden("ProductPrice.{$i}.user_role_id", array('default'=>$ugID));
				echo '</td></tr>';
				$i = $i + 1;
			}?>
		</table>
	</fieldset>
<?php echo $this->Form->end('Submit');?>
</div>
