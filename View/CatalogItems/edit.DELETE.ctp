<fieldset>
<?php echo $this->Form->create('CatalogItem', array('type' => 'file', 'action' => 'edit_save', 'id'=>'CatalogItemAddForm'));?>
 		<legend><?php echo __('Add Product');?></legend>
        <fieldset>
	<?php 
		echo $this->Form->hidden('id');
		echo $this->Form->input('CatalogItem.sku', array('default'=>'0'));
		echo $this->Form->hidden('user_role_id', array('default'=>'0'));
		
		// might need to update this default thing later to an actual default group
		echo $this->Form->input('catalog_item_brand_id', array('label' => 'Manufacturer', 
				'options' => $catalogItemBrands));
		echo $this->Form->input('CatalogItem.name', array('label' => 'Product Name'));
		echo $this->Form->input('CatalogItem.price', array('label' => 'Default Product Price'));
		echo $this->Form->input('CatalogItem.stock_item', array('label' => 'Inventory (empty = unlimited).'));
	?>
    	</fieldset>
        <fieldset>
        	<legend> Additional Details </legend>
            <ul>
<?php 
		if (!empty($this->request->data['CatalogItem']['id']))
		
			echo '<li>'.$this->Html->link('Add Product Images', array('plugin' => 'galleries', 'controller' => 'galleries', 'action' => 'edit', 'CatalogItem', $this->request->data['CatalogItem']['id'])).'</li>';
			echo '<li>'.$this->Html->link('Advanced Price Matrix', '#',array('id' => 'priceID')).'</li>';
			echo '<li>'.$this->Html->link('Advanced Attributes', array('plugin' => 'catalogs', 'controller' => 'catalog_items', 'action' => 'update', $this->request->data['CatalogItem']['id'])).'</li>';
/*
 * original code for hidden element when price matrix was on separate screen
 * 		if (isset($this->request->data['CatalogItemPrice'])) {
			foreach($this->request->data['CatalogItemPrice'] as $index => $val) {
				echo $this->Form->hidden("CatalogItemPrice.{$index}.id", array('value'=>$val['id']));
				echo $this->Form->hidden("CatalogItemPrice.{$index}.price", array('value'=>$val['price']));
				echo $this->Form->hidden("CatalogItemPrice.{$index}.catalog_item_id", array('value'=>$val['catalog_item_id'])); 
				echo $this->Form->hidden("CatalogItemPrice.{$index}.user_role_id", array('value'=>$val['user_role_id']));
				echo $this->Form->hidden("CatalogItemPrice.{$index}.price_type_id", array('value'=>$val['price_type_id']));
			}
		}
*/			?>
			</ul>
		</fieldset>
<div id="advance-id" style="display:none">
	<br></br>
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
					array('default'=>0, 'div'=>false, 'label'=>false, 'cols'=>'8', 'rows'=>1));
				echo $this->Form->input('CatalogItem.stock_item', array('label' => 'Default Inventory Count'));	
				echo $this->Form->hidden("CatalogItemPrice.{$index}.catalog_item_id", array('value'=>$this->request->data['CatalogItem']['id'])); 
				echo $this->Form->hidden("CatalogItemPrice.{$index}.user_role_id", array('default'=>$ugID));
				echo $this->Form->hidden("CatalogItemPrice.{$index}.price_type_id", array('default'=>$ptID));
				echo '</td>';
				$index++;
			}?>
		</tr>
		<?php }?>
	</table>	
</div>			
<?php 
		
		echo $this->Form->input('CatalogItem.summary', array('type' => 'richtext'));
		echo $this->Form->input('CatalogItem.description', array('type' => 'richtext'));
		echo $this->Form->hidden('published', array('default' => 1, 'checked' => 'checked'));
		echo $this->Form->hidden('catalog_id', array( 'value' => $this->request->data['Catalog']['id'][0]));
		/*echo '<b>Categories selected: </b>';
		$i = 0;
		foreach($this->request->data['Category'] as $value) {
			++$i;
			echo '<div id="divCategory'.$i.'">';
			echo $i . ' '. $categories[$value];
			echo $this->Html->link('Remove' , "javascript:rem('Category{$i}')", array('')); 
			echo $this->Form->hidden('Category.'.$i, array('value' => $value));
			echo '<br />';
			echo '</div>';
		}?>
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
		}*/
		?>
		<fieldset>
		<legend class="toggleClick">Location</legend>
		<?php
		echo $this->Form->input('Location.available', array('label' => 'Zip Codes Available (comma separated)'));
		echo $this->Form->input('Location.restricted', array('label' => 'Zip Codes Restricted (comma separated)'));?>
		</fieldset>
    
<?php echo $this->Form->end('Submit');?>
</fieldset>

<script><!--

$('#addCat').click(function(e){
	e.preventDefault();
	action = '<?php echo $this->Html->url(array('plugin'=>'categories',
			 'controller'=>'categories', 'action'=>'choose_category', $this->request->data['CatalogItem']['catalog_id']))?>';
	$("#CatalogItemAddForm").attr("action" , action);
	$("#CatalogItemAddForm").submit(); 
});

$('#priceID').click(function(e){
	e.preventDefault();
	$('#advance-id').toggle();

/*
 * original code for hidden element when price matrix was on separate screen
 */
//	action = '<?php echo $this->Html->url(array('plugin'=>'catalogs',	'controller'=>'catalog_item_prices', 'action'=>'add'))?>';
//	$("#CatalogItemAddForm").attr("action" , action);
//	$("#CatalogItemAddForm").submit(); 
});

function rem($id) {
	$('#div'+$id).remove();
}
-->
</script>