<div class="catalogItemPrices view">
<h2><?php  __('CatalogItemPrice');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __('Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $price['CatalogItemPrice']['id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __('User Role Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $price['CatalogItemPrice']['user_role_id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __('Catalog Item Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $price['CatalogItemPrice']['catalog_item_id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __('CatalogItemPrice'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $price['CatalogItemPrice']['price']; ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<ul>
		<li><?php echo $this->Html->link(__('Edit Price', true), array('action' => 'edit', $price['CatalogItemPrice']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('Delete Price', true), array('action' => 'delete', $price['CatalogItemPrice']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $price['CatalogItemPrice']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Prices', true), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Price', true), array('action' => 'add')); ?> </li>
	</ul>
</div>
