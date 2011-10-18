<div class="catalogs index">
<table cellpadding="0" cellspacing="0">
<tr>
	<th><?php echo $this->Paginator->sort('id');?></th>
	<th><?php echo $this->Paginator->sort('name');?></th>
	<th class="actions"><?php echo __('Actions');?></th>
</tr>
<?php
$i = 0;
foreach ($catalogs as $catalog):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<tr<?php echo $class;?>>
		<td>
			<?php echo $catalog['Catalog']['id']; ?>
		</td>
		<td>
			<?php echo $catalog['Catalog']['name']; ?>
		</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View', true), array('action' => 'view', $catalog['Catalog']['id'])); ?>
			<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $catalog['Catalog']['id'])); ?>
			<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $catalog['Catalog']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $catalog['Catalog']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
</table>
</div>
<?php echo $this->Element('paging'); ?>

<div class="actions">
	<ul>
		<li><?php echo $this->Html->link(__('New Catalog', true), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__d('Categories', 'Assign Category Catalog', true), 
				array('plugin'=>'categories', 'controller'=>'categories', 'action'=>'categorized','type'=>'Catalog' , 'admin'=>true)); ?> </li>
	</ul>
</div>