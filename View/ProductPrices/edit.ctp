<div class="productPrices form">
<?php echo $this->Form->create('ProductPrice');?>
	<fieldset>
 		<legend><?php echo __('Advanced Pricing for '.$product['Product']['name'].' : Default Price '.$product['Product']['price']);?></legend>
		<table>
			<tr>
				<th>User Roles</th>
			</tr>
			<?php 
			$i = 0; 
			foreach($userRoles as $ugID => $ug) { 
				echo '<tr><td>' . $ug . '</td><td>';
				echo $this->Form->hidden("ProductPrice.{$i}.id");
				echo $this->Form->input("ProductPrice.{$i}.price",	array('default' => 0, 'div' => false, 'label' => false));
				echo $this->Form->hidden("ProductPrice.{$i}.product_id", array('value' => $this->request->data['Product']['id'])); 
				echo $this->Form->hidden("ProductPrice.{$i}.user_role_id", array('default' => $ugID));
				echo '</td></tr>';
				$i = $i + 1;
			}?>
		</table>
	</fieldset>
<?php echo $this->Form->end('Submit');?>
</div>