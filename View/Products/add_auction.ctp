<div class="product add form">
	<?php echo $this->Form->create('Product', array('type' => 'file')); ?>
	<?php echo $this->Form->hidden('Product.type', array('value' => 'auction')); ?>
	<?php echo $this->Form->hidden('Product.model', array('value' => 'Product')); ?>
	<?php echo $this->Form->hidden('Product.seller_id', array('value' => $this->Session->read('Auth.User.id'))); ?>
	<fieldset>
		<?php echo $this->Form->input('Product.name', array('label' => 'Auction Name')); ?>
		<?php echo $this->Form->input('Product.price', array('label' => 'Buy it Now Price', 'type' => 'number', 'step' => '0.01', 'min' => '0', 'max' => '99999999999')); ?>
		<?php echo $this->Form->input('GalleryImage.filename', array('type' => 'file', 'label' => 'Primary Image  <br /><small><em>You can add additional images after you save.</em></small>')); ?>
		<?php echo $this->Form->input('Product.started', array('type' => 'datetimepicker', 'label' => 'Date & Time to Start Auction')); ?>
		<?php echo $this->Form->input('Product.ended', array('type' => 'datetimepicker', 'label' => 'Date & Time to End Auction')); ?>
		<?php echo $this->Form->hidden('Activity.function', array('value' => 'expire')); ?>
		<?php echo $this->Form->input('Product.description', array('type' => 'richtext', 'label' => 'What is the sales copy for this item?')); ?>
	</fieldset>

	<fieldset>
		<legend class="toggleClick"><?php echo __('Optional product details'); ?></legend>
		<?php echo $this->Form->input('Product.summary', array('type' => 'text', 'label' => 'Promo Text <br /><small><em>Used to entice people to view more about this item.</em></small>')); ?>
		<?php // echo $this->Form->input('Product.product_brand_id', array('empty' => '-- Select --', 'label' => 'What is the brand name for this product? (' . $this->Html->link('add', array('controller' => 'product_brands', 'action' => 'add')) . ' / ' . $this->Html->link('edit', array('controller' => 'product_brands', 'action' => 'index')) . ' brands)')); ?>
		<?php echo $this->Form->input('Product.is_public', array('default' => 1, 'label' => 'Published')); ?>
		<?php echo $this->Form->input('Product.is_buyable', array('default' => 1,'label' => 'Buyable (uncheck to disable buy it now)')); ?>
	</fieldset>

	<!--fieldset>
		<legend class="toggleClick">
		<?php echo __('Does this product belong to a category?'); ?></legend>
		<?php echo $this->Form->input('Category', array('multiple' => 'checkbox', 'label' => __('Which categories? (%s)', $this->Html->link('edit categories', array('admin' => 1, 'plugin' => 'products', 'controller' => 'products', 'action' => 'categories'))))); ?>
	</fieldset-->
		
	<?php echo $this->Form->end('Submit'); ?>
</div>

<?php
// set the contextual menu items
$this->set('context_menu', array(
	'menus' => array(
		array(
			'heading' => 'Products',
			'items' => array(
				$this->Html->link(__('Dashboard'), array('admin' => true, 'controller' => 'products', 'action' => 'dashboard')),
				$this->Html->link(__('List'), array('controller' => 'products', 'action' => 'index'))
			)
		)
	)
));
