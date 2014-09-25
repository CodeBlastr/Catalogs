<?php echo $this->Form->create('Product', array('type' => 'file'));	?>
<?php echo $this->Form->input('Product.id'); ?>
<?php echo $this->Form->input('Credit.id', array('type' => 'hidden')); ?>
	<div class="productAdd form">
	    <h2><?php echo __('Purchase Credits Product'); ?></h2>
	    <div class="row">
	    	<div class="col-sm-9">
	    		<?php echo $this->Form->input('Product.name', array('label' => 'Credits Name', 'placeholder' => '10 Job Postings', 'required' => 'required')); ?>
	    		<?php echo $this->Form->input('Product.summary', array('type' => 'text', 'label' => 'Short description')); ?>
	    		<?php echo $this->Form->input('Product.description', array('type' => 'richtext', 'label' => 'Product description')); ?>
	    	</div>
	    	<div class="col-sm-3">
	    		<?php echo $this->Form->input('Credit.amount', array('label' => 'Credits', 'after' => '<small>The number of credits received when this item is purchased?</small>', 'placeholder' => '1', 'type' => 'number', 'required' => 'required')); ?>
	    		<?php echo $this->Form->input('Product.price', array('label' => 'Price', 'placeholder' => '55.97', 'type' => 'number', 'required' => 'required')); ?>
	    		<label>Images and Video Display</label>
	    		<?php echo CakePlugin::loaded('Media') ? $this->Element('Media.selector', array('multiple' => true)) : null; ?>
	    	</div>
	    </div>
	</div>
<?php echo $this->Form->end('Edit Credit Product'); ?>

<?php
// set the contextual menu items
$this->set('context_menu', array('menus' => array(
	array(
		'heading' => 'Products',
		'items' => array(
			$this->Html->link(__('List'), array('controller' => 'products', 'action' => 'index')),
			$this->Html->link(__('Add'), array('controller' => 'products', 'action' => 'add')),
			)
		),
 	array(
		'heading' => 'Webpages',
		'items' => array(
			 $this->Html->link(__('List'), array('action' => 'index')),
			 )
		)
	)));