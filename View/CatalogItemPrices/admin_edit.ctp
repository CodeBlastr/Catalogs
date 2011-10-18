<div class="catalogItemPrices form">
<?php echo $this->Form->create('CatalogItemPrice');?>
	<fieldset>
 		<legend><?php echo __('Edit Price');?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('user_role_id');
		echo $this->Form->input('catalog_item_id');
		echo $this->Form->input('price');
	?>
	</fieldset>
<?php echo $this->Form->end('Submit');?>
</div>
<div class="actions">
	<ul>
		<li><?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $this->Form->value('CatalogItemPrice.id')), null, sprintf(__('Are you sure you want to delete # %s?', true), $this->Form->value('CatalogItemPrice.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Prices', true), array('action' => 'index'));?></li>
	</ul>
</div>
