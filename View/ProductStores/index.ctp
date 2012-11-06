<div class="productStores index">
  <table cellpadding="0" cellspacing="0">
    <tr>
      <th><?php echo $this->Paginator->sort('id');?></th>
      <th><?php echo $this->Paginator->sort('name');?></th>
      <th class="actions"><?php echo __('Actions');?></th>
    </tr>
    <?php
$i = 0;
foreach ($productStores as $productStore):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
    <tr<?php echo $class;?>>
      <td><?php echo $productStore['ProductStore']['id']; ?></td>
      <td><?php echo $productStore['ProductStore']['name']; ?></td>
      <td class="actions"><?php echo $this->Html->link(__('View', true), array('action' => 'view', $productStore['ProductStore']['id'])); ?> <?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $productStore['ProductStore']['id'])); ?> <?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $productStore['ProductStore']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $productStore['ProductStore']['id'])); ?></td>
    </tr>
    <?php endforeach; ?>
  </table>
</div>
<?php echo $this->Element('paging'); ?>
<?php
// set the contextual menu items
$this->set('context_menu', array('menus' => array(
	array(
		'heading' => 'Store',
		'items' => array(
			$this->Html->link(__('New'), array('action' => 'add')),
			$this->Html->link(__('Assign Categories'), 
				array('plugin' => 'categories', 'controller' => 'categories', 'action' => 'categorized','type' => 'ProductStore')),
			),
		),
	))); ?>
