<div class="catalogs form">
<?php echo $form->create('Catalog');?>
	<fieldset>
 		<legend><?php __('Edit Catalog');?></legend>
	<?php
		echo $form->input('id');
		// echo $form->input('parent_id');
		echo $form->input('name');
		echo $form->input('alias_id');
		echo $form->input('summary');
		echo $form->input('introduction');
		echo $form->input('description');
		echo $form->input('additional');
		//echo $form->input('start_date');
		//echo $form->input('end_date');
		echo $form->input('published');
	?>
	</fieldset>
<?php echo $form->end('Submit');?>
</div>
<div class="actions">
	<ul>
		<li><?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $form->value('Catalog.id')), null, sprintf(__('Are you sure you want to delete # %s?', true), $form->value('Catalog.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Catalogs', true), array('action' => 'index'));?></li>
	</ul>
</div>
