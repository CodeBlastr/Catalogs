<?php echo $this->Form->create('CatalogItem', array('type' => 'file', 'action' => 'add'));?>
 		<legend><?php __('Add Product');?></legend>
	<?php
		echo $this->Form->hidden('id');
		echo $this->Form->input('CatalogItem.sku', array('default'=>'0'));
		echo $this->Form->hidden('user_role_id', array('default'=>'0'));
		echo $this->Form->input('CatalogItem.stock_item', array('label' => 'Default Inventory Count'));
		// might need to update this default thing later to an actual default group
		echo $this->Form->input('CatalogItem.catalog_item_brand_id', array('label' => 'Brand'));
		echo $this->Form->input('CatalogItem.name', array('label' => 'Product Name'));
		echo $this->Form->input('CatalogItem.price', array('label' => 'Default Product Price'));
		if (!empty($this->data['CatalogItem']['id']))
			echo $this->Html->link('Advanced Price Matrix', '#',array('id' => 'priceID'));
		if (isset($this->data['CatalogItemPrice'])) {
			foreach($this->data['CatalogItemPrice'] as $index => $val) {
				echo $this->Form->hidden("CatalogItemPrice.{$index}.id", array('value'=>$val['id']));
				echo $this->Form->hidden("CatalogItemPrice.{$index}.price", array('value'=>$val['price']));
				echo $this->Form->hidden("CatalogItemPrice.{$index}.catalog_item_id", array('value'=>$val['catalog_item_id'])); 
				echo $this->Form->hidden("CatalogItemPrice.{$index}.user_role_id", array('value'=>$val['user_role_id']));
				echo $this->Form->hidden("CatalogItemPrice.{$index}.price_type_id", array('value'=>$val['price_type_id']));
			}
		}
			
			
			
		echo $this->Form->input('GalleryImage.filename', array('type' => 'file', 'label' => 'Thumbnail', 'after' => 'Add more images after save.'));
	    echo $this->Form->input('GalleryImage.dir', array('type' => 'hidden'));
	    echo $this->Form->input('GalleryImage.mimetype', array('type' => 'hidden'));
	    echo $this->Form->input('GalleryImage.filesize', array('type' => 'hidden'));
		
		echo $this->Form->input('CatalogItem.description', array('type' => 'richtext', 'ckeSettings' => array('buttons' => array('Bold','Italic','Underline','FontSize','TextColor','BGColor','-','NumberedList','BulletedList','Blockquote','JustifyLeft','JustifyCenter','JustifyRight','-','Link','Unlink','-', 'Image'))));
		echo $this->Form->hidden('published', array('default' => 1, 'checked' => 'checked'));
		echo $this->Form->hidden('catalog_id', array( 'value' => $this->data['Catalog']['id'][0]));
		echo '<b>Categories selected: </b>';
		$i = 0;
		foreach($this->data['Category'] as $value) {
			++$i;
			echo '<div id="divCategory'.$i.'">';
			echo $i . ' '. $categories[$value];
			echo $this->Html->link('Remove' , "javascript:rem('Category{$i}')", array('')); 
			echo $this->Form->hidden('Category.'.$i, array('value' => $value));
			echo '<br />';
			echo '</div>';
		}?>
		<br />
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
	?>
	<br />
	<fieldset>
		<legend>Location</legend>
		<?php
		echo $this->Form->input('Location.available', array('rows'=>1, 'cols' => 30, 'label' => 'Zip Codes Available (comma separated)'));
		echo $this->Form->input('Location.restricted', array('rows'=>1, 'cols' => 30, 'label' => 'Zip Codes Restricted (comma separated)'));?>
		</fieldset>
	<?php  echo $this->Html->link('Add another Category', '#', array('id'=>'addCat'));?>
			
    
<?php echo $this->Form->end('Submit');?>

<script>

$('#addCat').click(function(e){
	e.preventDefault();
	action = '<?php echo $this->Html->url(array('plugin'=>'categories',
			 'controller'=>'categories', 'action'=>'choose_category', $this->data['CatalogItem']['catalog_id'],'admin'=>true))?>';
	$("#CatalogItemAddForm").attr("action" , action);
	$("#CatalogItemAddForm").submit(); 
});

$('#priceID').click(function(e){
	e.preventDefault();
	action = '<?php echo $this->Html->url(array('plugin'=>'catalogs',
					'controller'=>'catalog_item_prices', 'action'=>'add', 'admin'=>true))?>';
	$("#CatalogItemAddForm").attr("action" , action);
	$("#CatalogItemAddForm").submit(); 
});
function rem($id) {
	$('#div'+$id).remove();
}
</script>