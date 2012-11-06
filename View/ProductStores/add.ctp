<div class="productStores form">
<?php echo $this->Form->create('ProductStore');?>
	<fieldset>
 		<legend><?php echo __('Add Store');?></legend>
	<?php
		echo $this->Form->input('name');
		echo $this->Form->input('summary');
		echo $this->Form->input('introduction');
		echo $this->Form->input('description');
		echo $this->Form->input('additional');
		#echo $this->Form->input('started');
		#echo $this->Form->input('ended');
		echo $this->Form->input('is_public');
	?>
	</fieldset>
<?php echo $this->Form->end('Submit');?>
</div>
<div class="actions">
	<ul>
		<li><?php echo $this->Html->link(__('List', true), array('action' => 'index'));?></li>
	</ul>
</div>
