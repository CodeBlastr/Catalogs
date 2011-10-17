<div class="catalogItemPrices form">
<?php echo $form->create('CatalogItemPrice');?>
	<fieldset>
 		<legend><?php __('Edit Price');?></legend>
	<?php
		echo $form->input('id');
		echo $form->input('user_role_id');
		echo $form->input('catalog_item_id');
		echo $form->input('price');
	?>
	</fieldset>
<?php echo $form->end('Submit');?>
</div>
<div class="actions">
	<ul>
		<li><?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $form->value('CatalogItemPrice.id')), null, sprintf(__('Are you sure you want to delete # %s?', true), $form->value('CatalogItemPrice.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Prices', true), array('action' => 'index'));?></li>
	</ul>
</div>
