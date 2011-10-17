<div class="catalogs form">
<?php echo $form->create('Catalog');?>
	<fieldset>
 		<legend><?php __('Add Catalog');?></legend>
	<?php
		echo $form->input('name');
		#echo $form->input('alias_id');
		echo $form->input('summary');
		echo $form->input('introduction');
		echo $form->input('description');
		echo $form->input('additional');
		#echo $form->input('start_date');
		#echo $form->input('end_date');
		echo $form->input('published');
		echo $form->hidden('creator_id', array('value' => $this->Session->read('Auth.User.id')));
		echo $form->hidden('modifier_id', array('value' => $this->Session->read('Auth.User.id')));
	?>
	</fieldset>
<?php echo $form->end('Submit');?>
</div>
<div class="actions">
	<ul>
		<li><?php echo $this->Html->link(__('List Catalogs', true), array('action' => 'index'));?></li>
	</ul>
</div>
