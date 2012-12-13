<?php
/**
 * Products Admin Add View
 *
 * PHP versions 5
 *
 * Zuha(tm) : Business Management Applications (http://zuha.com)
 * Copyright 2009-2012, Zuha Foundation Inc. (http://zuhafoundation.org)
 *
 * Licensed under GPL v3 License
 * Must retain the above copyright notice and release modifications publicly.
 *
 * @copyright     Copyright 2009-2012, Zuha Foundation Inc. (http://zuha.com)
 * @link          http://zuha.com Zuha� Project
 * @package       zuha
 * @subpackage    zuha.app.plugins.products.views
 * @since         Zuha(tm) v 0.0.1
 * @license       GPL v3 License (http://www.gnu.org/licenses/gpl.html) and Future Versions
 */
 ?>

<div class="hero-unit pull-right span3">
    <?php
    echo $this->Element('gallery', array('model' => 'Product', 'foreignKey' => $this->request->data['Product']['id']), array('plugin' => 'galleries'));
    echo $this->Html->link('Edit Gallery', array('plugin' => 'galleries', 'controller' => 'galleries', 'action' => 'edit', 'Product', $this->request->data['Product']['id'])); 
    // list of variants (think about moving to the controller, or an element for reuse)
    if (!empty($this->request->data['Children'])) {
        echo __('<hr /><h5>Variants</h5>');
        echo __('<ul>');
        foreach ($this->request->data['Children'] as $child) {
            if (!empty($child['Option'])) {
                foreach ($child['Option'] as $variant) {
                    $variants[$child['id']][] = $variant['name'];  
                    unset($variant);
                } 
                echo __('<li>%s</li>', $this->Html->link(implode(', ', $variants[key($variants)]), array('action' => 'edit', key($variants), '1')));
                unset($variants);
            }
        }
        echo __('</ul>');
    }
    echo !empty($existingOptions) ? __('<hr /><h5>Available Variant Types %s</h5><p>%s</p>', $this->Html->link(__('Add Variant'), array('controller' => 'products', 'action' => 'add', 'default', $this->request->data['Product']['id']), array('class' => 'btn btn-mini')), implode(', ', $existingOptions)) : null;
    echo !empty($options) ? __('<hr /><h5>Add Variant Type</h5>%s %s %s %s', $this->Form->create('Product'), $this->Form->input('Product.id'), $this->Form->input('Option.Option.0', array('label' => false, 'type' => 'select', 'options' => $options)), $this->Form->end('Add Available Variant Type')) : null; ?>
</div>

<div class="productAdd form span7 pull-left">
	<?php echo $this->Form->create('Product', array('type' => 'file')); ?>
    <fieldset>
    	<?php
		echo $this->Form->input('Product.id');
		echo $this->Form->input('Product.name', array('label' => 'Display Name'));
        echo $this->Form->input('Product.price', array('label' => 'Retail Price <small><em>(ex. 0000.00)</em></small>', 'step' => '0.01', 'min' => '0', 'max' => '99999999999'));
		echo $this->Form->input('Product.description', array('type' => 'richtext', 'label' => 'What is the sales copy for this item?')); ?>
    </fieldset>
    <fieldset>
        <legend class="toggleClick"><?php echo __d('products', 'Optional product details'); ?></legend>
        <?php
    	echo $this->Form->input('Product.sku', array('label' => 'SKU'));
        echo $this->Form->input('Product.summary', array('type' => 'text', 'label' => 'Promo Text'));
		echo $this->Form->input('Product.product_brand_id', array('empty' => '-- Select --', 'label' => 'What is the brand name for this product? ('.$this->Html->link('add', array('controller' => 'product_brands', 'action' => 'add')).' / '.$this->Html->link('edit', array('controller' => 'product_brands', 'action' => 'index')).' brands)'));
		echo $this->Form->input('Product.stock', array('label' => 'Would you like to track inventory?'));
        echo $this->Form->input('Product.cost', array('label' => 'What does the product cost you? <br /><small><em>Used if you get profit reports</em></small>', 'between'=>'<span class="add-on">$</span>', 'div'=>array('class'=>'input-prepend')));
		echo $this->Form->input('Product.cart_min', array('label' => 'Minimun Cart Quantity? <br /><small><em>Enter the minimum cart quantity or leave blank for 1</em></small>'));
		echo $this->Form->input('Product.cart_max', array('label' => 'Maximum Cart Quantity? <br /><small><em>Enter the max cart quantity or leave blank for unlimited</em></small>'));
        echo $this->Form->input('Product.is_public', array('default' => 1, 'label' => 'Published'));
        echo $this->Form->input('Product.is_buyable', array('default' => 1, 'label' => 'Buyable')); ?>
    </fieldset>
	<fieldset>
 		<legend class="toggleClick"><?php echo __d('products', 'Do you offer shipping for this product?');?></legend>
    	<?php
		$fedexSettings = defined('__ORDERS_FEDEX') ? unserialize(__ORDERS_FEDEX) : null;
		$radioOptions = array();
		if (!empty($fedexSettings)) {
            foreach($fedexSettings as $k => $val) {
    			$radioOptions[$k] = $val ;
    			echo $this->Form->input('Product.weight', array('label' => 'Weight (lbs)'));
    			echo $this->Form->input('Product.height', array('label' => 'Height (8-70 inches)'));
    			echo $this->Form->input('Product.width', array('label' => 'Width (50-119 inches)'));
    			echo $this->Form->input('Product.length', array('label' => 'Length (50-119 inches)'));
		    }
        }
		$radioOptions += array('FIXEDSHIPPING' => 'FIX SHIPPING', 'FREESHIPPING' => 'FREE SHIPPING') ;
		echo $this->Form->radio('Product.shipping_type', $radioOptions, array('class' => 'shipping_type' , 'default' => ''));
	 	echo $this->Form->input('Product.shipping_charge'); ?>
    </fieldset>
    
    <?php if (in_array('Categories', CakePlugin::loaded())) { ?>
	<fieldset>
 		<legend class="toggleClick"><?php echo __d('products', 'Does this product belong to a category?');?></legend>
		<?php echo $this->Form->input('Category', array('multiple' => 'checkbox', 'label' => __('Choose categories (%s)', $this->Html->link('edit', array('action' => 'categories'))))); ?>
	</fieldset>
    <?php } ?>
	
	<?php if(!empty($paymentOptions)) { ?>
    <fieldset>
        <legend class="toggleClick"><?php echo __d('products', 'Select Payment Types For The Item.');?></legend>
        <?php
            echo $this->Form->input('Product.payment_type', array('options' => $paymentOptions, 'multiple' => 'checkbox'));
        ?>
    </fieldset>
	<?php } ?>

	
	<?php
    echo $this->Form->end('Submit');
	?>

    <script type="text/javascript">
        $('#addCat').click(function(e){
        	e.preventDefault();
        	$('#anotherCategory').show();
        });
        
        $('#priceID').click(function(e){
        	e.preventDefault();
        	action = '<?php echo $this->Html->url(array('plugin' => 'products',
        					'controller'=>'product_prices', 'action'=>'add', 'admin'=>true))?>';
        	$("#ProductAddForm").attr("action" , action);
        	$("#ProductAddForm").submit();
        });
        function rem($id) {
        	$('#div'+$id).remove();
        }
        
        $(document).ready( function(){
        	if($('input.shipping_type:checked').val() == 'FIXEDSHIPPING') {
        		$('#ShippingPrice').show();
        	} else {
        		$('#ShippingPrice').hide();
        	}
        });
        
        var shipTypeValue = null;
        $('input.shipping_type').click(function(e){
        	shipTypeValue = ($('input.shipping_type:checked').val());
        	if(shipTypeValue == 'FIXEDSHIPPING') {
        		$('#ProductShippingCharge').parent().show();
        	} else {
        		$('#ProductShippingCharge').parent().hide();
        	}
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
			)
		),
    array(
    	'heading' => 'Products',
		'items' => array(
			$this->Html->link(__('List'), array('controller' => 'products', 'action' => 'index')),
			$this->Html->link(__('Delete'), array('controller' => 'products', 'action' => 'delete', $this->request->data['Product']['id']), array(), 'Are you sure? (cannot be undone)'),
    		)
		),
	))); ?>