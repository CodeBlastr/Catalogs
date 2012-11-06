<div class="productPrices index">
<h2><?php echo __('ProductPrices');?></h2>
<p>
<?php
echo $this->Paginator->counter(array(
'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true)
));
?></p>
<table cellpadding="0" cellspacing="0">
<tr>
	<th><?php echo $this->Paginator->sort('id');?></th>
	<th><?php echo $this->Paginator->sort('user_role_id');?></th>
	<th><?php echo $this->Paginator->sort('product_id');?></th>
	<th><?php echo $this->Paginator->sort('price');?></th>
	<th class="actions"><?php echo __('Actions');?></th>
</tr>
<?php
$i = 0;
foreach ($productPrices as $price):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<tr<?php echo $class;?>>
		<td>
			<?php echo $price['ProductPrice']['id']; ?>
		</td>
		<td>
			<?php echo $price['ProductPrice']['user_role_id']; ?>
		</td>
		<td>
			<?php echo $price['ProductPrice']['product_id']; ?>
		</td>
		<td>
			<?php echo $price['ProductPrice']['price']; ?>
		</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View', true), array('action' => 'view', $price['ProductPrice']['id'])); ?>
			<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $price['ProductPrice']['id'])); ?>
			<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $price['ProductPrice']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $price['ProductPrice']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
</table>
</div>
<?php echo $this->element('paging'); ?>
<div class="actions">
	<ul>
		<li><?php echo $this->Html->link(__('New Price', true), array('action' => 'add')); ?></li>
	</ul>
</div>
