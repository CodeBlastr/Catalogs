<div class="catalogItemPrices index">
<h2><?php __('CatalogItemPrices');?></h2>
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
	<th><?php echo $this->Paginator->sort('catalog_item_id');?></th>
	<th><?php echo $this->Paginator->sort('price');?></th>
	<th class="actions"><?php __('Actions');?></th>
</tr>
<?php
$i = 0;
foreach ($catalogItemPrices as $price):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<tr<?php echo $class;?>>
		<td>
			<?php echo $price['CatalogItemPrice']['id']; ?>
		</td>
		<td>
			<?php echo $price['CatalogItemPrice']['user_role_id']; ?>
		</td>
		<td>
			<?php echo $price['CatalogItemPrice']['catalog_item_id']; ?>
		</td>
		<td>
			<?php echo $price['CatalogItemPrice']['price']; ?>
		</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View', true), array('action' => 'view', $price['CatalogItemPrice']['id'])); ?>
			<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $price['CatalogItemPrice']['id'])); ?>
			<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $price['CatalogItemPrice']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $price['CatalogItemPrice']['id'])); ?>
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
