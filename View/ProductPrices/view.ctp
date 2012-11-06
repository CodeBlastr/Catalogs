<div class="productPrices view">
<h2><?php  __('ProductPrice');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __('Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $price['ProductPrice']['id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __('User Role Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $price['ProductPrice']['user_role_id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __('Product Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $price['ProductPrice']['product_id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __('ProductPrice'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $price['ProductPrice']['price']; ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<ul>
		<li><?php echo $this->Html->link(__('Edit Price', true), array('action' => 'edit', $price['ProductPrice']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('Delete Price', true), array('action' => 'delete', $price['ProductPrice']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $price['ProductPrice']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Prices', true), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Price', true), array('action' => 'add')); ?> </li>
	</ul>
</div>
