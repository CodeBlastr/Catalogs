<div class="catalogs form">
<?php echo $this->Form->create('Catalog');?>
	<fieldset>
 		<legend><?php echo __('Edit Catalog');?></legend>
	<?php
		echo $this->Form->input('id');
		// echo $this->Form->input('parent_id');
		echo $this->Form->input('name');
		echo $this->Form->input('summary');
		echo $this->Form->input('introduction');
		echo $this->Form->input('description');
		echo $this->Form->input('additional');
		//echo $this->Form->input('started');
		//echo $this->Form->input('ended');
		echo $this->Form->input('is_public');
	?>
	</fieldset>
<?php echo $this->Form->end('Submit');?>
</div>
<div class="actions">
	<ul>
		<li><?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $this->Form->value('Catalog.id')), null, sprintf(__('Are you sure you want to delete # %s?', true), $this->Form->value('Catalog.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Catalogs', true), array('action' => 'index'));?></li>
	</ul>
</div>
