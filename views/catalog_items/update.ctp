<div class="catalogItems form update">

	<h2>Existing Attributes</h3>
	<table>
		<tr>
	    	<th>Option</th>
	        <th>Quantity</th>
	        <th>Action</th>
	    <tr>
	<?php 
	foreach($catalogItem['CatalogItemChildren'] as $child) : ?>
		<tr>
		<?php
		$optionName = '';
		foreach($child['CategoryOption'] as $option) :
			$optionName .= $option['name'] . ', ';
		endforeach;?>		
			<td><?php echo $optionName; ?></td>
			<td><?php echo $child['stock_item']; ?></td>
			<td><?php echo $this->Html->link('Edit', array('action' => 'edit', $child['id'])); ?> <?php echo $this->Html->link('Delete', array('action' => 'delete', $child['id'])); ?></td>
		</tr>
	<?php
    endforeach; ?>
	</table>
    
    
    <fieldset>
    	<legend class="toggleClick"> Create a new attribute item for <?php echo $catalogItem['CatalogItem']['name']; ?> </legend>
<?php 
	echo $this->Form->create('CatalogItem', array('action' => 'update', 'type' => 'file'));
	#echo $this->Form->hidden('parent_id', array('options' => $catalogItemParentIds, 'empty' => true, 'value' => $parentId)); 
	echo $this->Form->hidden('parent_id', array('value' => $catalogItem['CatalogItem']['id'])); 
	echo $this->Form->input('GalleryImage.filename', array('type' => 'file', 'label' => 'Thumbnail', 'after' => 'Add more images after save.'));
    echo $this->Form->input('GalleryImage.dir', array('type' => 'hidden'));
    echo $this->Form->input('GalleryImage.mimetype', array('type' => 'hidden'));
    echo $this->Form->input('GalleryImage.filesize', array('type' => 'hidden'));
    echo $this->Form->input('price', array('after' => '(Leave blank to use parent price)'));
?>
		<fieldset>
		 	<legend class="toggleClick"><?php __('Add ARB settings for option?');?></legend>
				<?php
					echo $this->Form->input('CatalogItem.arb_settings', array('rows'=>1, 'cols' => 30,'label' => 'Arb Settings'));	 
				?>
		</fieldset>
		<div id="result"></div>
	</fieldset>
	
	<?php echo $this->Form->end('Submit');?>
</div>

<script>
$(document).ready(function() {
	var id = $('#CatalogItemParentId').val();
	getCatalogItem(id);
	
	$('#CatalogItemParentId').change(function(e){
		var id = $(this).val();	
		getCatalogItem(id);
	});
	
	function getCatalogItem(id) {
		$.ajax({
	        type: "POST",
	        //data: $('#BannerBuyForm').serialize(),
			url: "/catalogs/catalog_items/get_catalog_item/" + id ,
	        dataType: "html",						 
	        success:function(data){
	        	$('#result').html(data);
	        }
	    });
	}
});
</script>
