<div class="products categories">
    <div class="row-fluid">
		<div class="span6">
			<?php echo $this->Form->create('Category'); ?>
	        <?php echo $this->Form->hidden('Category.model', array('value' => 'Product')); ?>
	        <fieldset>
	        	<legend class="toggleClick">Add New Category</legend>
		        <?php echo $this->Form->input('Category.parent_id', array('empty' => '-- Optional --', 'options' => $parentCategories)); ?>
		        <?php echo $this->Form->input('Category.name'); ?>
		        <?php echo $this->Form->end('Submit'); ?>
		    </fieldset>
	    </div>
	    <div class="span6">
	        <?php echo $this->Form->create('Options'); ?>
	        <fieldset>
	        	<legend class="toggleClick">Add New Option</legend>
		        <?php echo $this->Form->input('Option.parent_id', array('empty' => '-- Optional --', 'options' => $parentOptions)); ?>
		        <?php echo $this->Form->input('Option.name'); ?>
		        <?php echo $this->Form->end('Submit'); ?>
	        </fieldset>
	    </div>
	</div>
    <div class="row-fluid">
    	<div class="span6">
	    	<?php if (!empty($categories)) : ?>
	    		<h3>Current Categories</h3>
		        <?php echo $this->Tree->generate($categories, array('model' => 'Category', 'alias' => 'item_text', 'class' => 'categoriesList', 'id' => 'categoriesList', 'element' => 'Categories/item', 'elementPlugin' => 'products')); ?>
			<?php endif; ?>
		</div>
    	
    	<div class="span6">
    		<?php if (!empty($options)) : ?>
		        <h3>Current Options / Variations</h3>
		        <?php echo $this->Tree->generate($options, array('model' => 'Option', 'alias' => 'item_text', 'class' => 'optionsList',  'id' => 'optionsList', 'element' => 'Options/item', 'elementPlugin' => 'products')); ?>
			<?php endif; ?>
		</div>
	</div>
</div>
<?php
// set the contextual menu items
$this->set('context_menu', array('menus' => array(
    array(
		'heading' => 'Products',
		'items' => array(
			$this->Html->link(__('Dashboard'), array('admin' => true, 'controller' => 'products', 'action' => 'dashboard')),
			)
		),
	))); ?>