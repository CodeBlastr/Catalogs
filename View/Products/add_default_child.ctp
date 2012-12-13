<div class="productAdd form">
    <?php echo $this->Form->create('Product', array('type' => 'file')); ?>
    <fieldset>
    	<?php
		echo $this->Form->input('Product.parent_id', array('type' => 'hidden', 'value' => $this->request->data['Product']['id']));
		echo $this->Form->input('Product.name', array('label' => 'Display Name'));
        $i = 0; 
        foreach ($this->request->data['Option'] as $select) {
            foreach ($select['Children'] as $option) {
                $options[$option['id']] = $option['name'];
            }
            echo $this->Form->input('Option.Option.' . $i, array('label' => __('%s <small>(%s)</small>', $select['name'], $this->Html->link('add new', '#', array('class' => 'newOption', 'data-target' => '#Option'.$i.'Name'))), 'type' => 'select', 'options' => $options));
            
            echo $this->Form->input('Option.'. $i .'.parent_id', array('type' => 'hidden', 'empty' => '-- Optional --', 'value' => $select['id']));
            echo $this->Form->input('Option.'. $i .'.name', array('label' => __('%s <small>(%s)</small>', $select['name'], $this->Html->link('cancel', '#', array('class' => 'cancelOption', 'data-target' => '#OptionOption'.$i))), 'value' => false));
            
            unset($options); 
            $i++;
        }
        echo $this->Form->input('Product.price', array('label' => 'Retail Price <small><em>(ex. 0000.00)</em></small>', 'type' => 'number', 'step' => '0.01', 'min' => '0', 'max' => '99999999999'));
        echo $this->Form->input('GalleryImage.filename', array('type' => 'file', 'label' => 'Gallery Image  <br /><small><em>You can add additional images after you save.</em></small>'));
		echo $this->Form->input('Product.description', array('type' => 'richtext', 'label' => 'What is the sales copy for this item?')); ?>
    </fieldset>

    <fieldset>
        <legend class="toggleClick"><?php echo __('Optional product details'); ?></legend>
        <?php
        echo $this->Form->input('Product.sku', array('label' => 'SKU'));
        echo $this->Form->input('Product.summary', array('type' => 'text', 'label' => 'Promo Text <br /><small><em>Used to entice people to view more about this item.</em></small>'));
		echo $this->Form->input('Product.product_brand_id', array('empty' => '-- Select --', 'label' => 'What is the brand name for this product? ('.$this->Html->link('add', array('controller' => 'product_brands', 'action' => 'add')).' / '.$this->Html->link('edit', array('controller' => 'product_brands', 'action' => 'index')).' brands)'));
		echo $this->Form->input('Product.stock', array('label' => 'Would you like to track inventory?'));
        echo $this->Form->input('Product.cost', array('label' => 'What does the product cost you? <br /><small><em>Used if you get profit reports</em></small>'));
		echo $this->Form->input('Product.weight', array('label' => 'Weight (lbs)'));
		echo $this->Form->input('Product.height', array('label' => 'Height (8-70 inches)'));
		echo $this->Form->input('Product.width', array('label' => 'Width (50-119 inches)'));
		echo $this->Form->input('Product.length', array('label' => 'Length (50-119 inches)')); ?>
    </fieldset>
    <?php echo $this->Form->end('Submit'); ?>

    <script type="text/javascript">
        $('.cancelOption').parent().parent().parent().hide();
        $('.newOption, .cancelOption').click(function(e){
            e.preventDefault();
            $(this).parent().parent().parent().hide();
            $($(this).attr('data-target')).parent().show();
        });
    </script>
</div>

<?php
// set the contextual menu items
$this->set('context_menu', array('menus' => array(
    array(
		'heading' => 'Products',
		'items' => array(
			$this->Html->link(__('Dashboard'), array('controller' => 'products', 'action' => 'dashboard')),
			$this->Html->link(__('List'), array('controller' => 'products', 'action' => 'index')),
    		$this->Html->link($this->request->data['Product']['name'], array('controller' => 'products', 'action' => 'view', $this->request->data['Product']['id'])),
			)
		),
	))); ?>