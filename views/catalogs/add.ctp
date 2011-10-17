<div class="catalogs form">
<?php echo $this->Form->create('Catalog');?>
	<fieldset>
 		<legend><?php __('Add Catalog');?></legend>
	<?php
		echo $this->Form->input('name');
		#echo $this->Form->input('alias_id');
		echo $this->Form->input('summary');
		echo $this->Form->input('introduction');
		echo $this->Form->input('description');
		echo $this->Form->input('additional');
		#echo $this->Form->input('start_date');
		#echo $this->Form->input('end_date');
		echo $this->Form->input('published');
		echo $this->Form->hidden('creator_id', array('value' => $this->Session->read('Auth.User.id')));
		echo $this->Form->hidden('modifier_id', array('value' => $this->Session->read('Auth.User.id')));
	?>
	</fieldset>
<?php echo $this->Form->end('Submit');?>
</div>
<div class="actions">
	<ul>
		<li><?php echo $this->Html->link(__('List Catalogs', true), array('action' => 'index'));?></li>
	</ul>
</div>
