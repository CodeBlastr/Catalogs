<div class="products categories row">
    <div class="span6 pull-left">
        <h3>Categories</h3>
        <?php 
        echo $this->Tree->generate($categories, array(
            'model' => 'Category', 
    		'alias' => 'item_text', 
			'class' => 'categoriesList', 
			'id' => 'categoriesList', 
			'element' => 'Categories/item', 
			'elementPlugin' => 'products'));

        echo $this->Form->create('Category');
        echo __('<fieldset><legend>Create Category</legend>');
        echo $this->Form->input('Category.parent_id', array('empty' => '-- Optional --', 'options' => $parentCategories));
        echo $this->Form->input('Category.name');
        echo $this->Form->input('Category.model', array('type' => 'hidden', 'value' => 'Product'));
        echo __('</fieldset>');
        echo $this->Form->end('Submit'); ?>
    </div>
    <div class="span6 pull-left">
        <h3>Options / Variations</h3>
        <?php
        echo $this->Tree->generate($options, array(
            'model' => 'Option',
			'alias' => 'item_text',
			'class' => 'optionsList', 
			'id' => 'optionsList',
			'element' => 'Options/item',
			'elementPlugin' => 'products'));

        echo $this->Form->create('Options');
        echo __('<fieldset><legend>Create Option</legend>');
        echo $this->Form->input('Option.parent_id', array('empty' => '-- Optional --', 'options' => $parentOptions));
        echo $this->Form->input('Option.name');
        echo __('</fieldset>');
        echo $this->Form->end('Submit'); ?>
    </div>
</div>