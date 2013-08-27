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
<div class="productAdd form row-fluid clearfix">
	<div class="span8 pull-left">
		<?php echo $this->Form->create('Product', array('type' => 'file')); ?>
	    <fieldset>
	    	<?php
			echo $this->Form->input('Product.name', array('label' => 'Property Name'));
	        echo $this->Form->input('Product.price', array('label' => 'Price <small><em>(ex. 100000.00)</em></small>', 'type' => 'number', 'step' => '0.01', 'min' => '0', 'max' => '99999999999')); 
	        echo $this->Form->input('GalleryImage.filename', array('type' => 'file', 'label' => 'Property Image  <br /><small><em>You can add additional images after you save.</em></small>'));
			echo $this->Form->input('Product.description', array('type' => 'richtext', 'label' => 'Property Description')); 
			echo $this->Form->input('Product.Meta.street');
			echo $this->Form->input('Product.Meta.bedroom');
			echo $this->Form->input('Product.Meta.bathroom');
			echo $this->Form->input('Product.Meta.footage');
			echo $this->Form->input('Product.Meta.acreage');?>
	    </fieldset>
	</div>
	<div class="span4 pull-right">
	    <fieldset>
	        <legend class="toggleClick"><?php echo __('Optional property details'); ?></legend>
	        <?php
	        echo $this->Form->input('Product.sku', array('label' => 'MLS Number'));
	        echo $this->Form->input('Product.summary', array('type' => 'text', 'label' => 'Promo Text <br /><small><em>Short blurb of text to entice people to view more about this property.</em></small>'));
			//echo $this->Form->input('Product.product_brand_id', array('empty' => '-- Select --', 'label' => 'What is the brand name for this product? ('.$this->Html->link('add', array('controller' => 'product_brands', 'action' => 'add')).' / '.$this->Html->link('edit', array('controller' => 'product_brands', 'action' => 'index')).' brands)'));
			//echo $this->Form->input('Product.stock', array('label' => 'Would you like to track inventory?'));
	        //echo $this->Form->input('Product.cost', array('label' => 'What does the product cost you? <br /><small><em>Used if you get profit reports</em></small>'));
			//echo $this->Form->input('Product.cart_min', array('label' => 'Minimun Cart Quantity? <br /><small><em>Enter the minimum cart quantity or leave blank for 1</em></small>'));
			//echo $this->Form->input('Product.cart_max', array('label' => 'Maximum Cart Quantity? <br /><small><em>Enter the max cart quantity or leave blank for unlimited</em></small>'));
	        echo $this->Form->input('Product.is_public', array('default' => 1, 'label' => 'Published'));
	        echo $this->Form->input('Product.is_buyable', array('default' => 0, 'label' => 'Can you buy this property online <small><em>(not typical)</em></small>')); ?>
	    </fieldset>
	
		<fieldset>
	 		<legend class="toggleClick"><?php echo __('Property categories');?></legend>
				<?php echo $this->Form->input('Category', array('multiple' => 'checkbox', 'label' => __('Choose categories (%s)', $this->Html->link('manage categories', array('admin' => 1, 'plugin' => 'products', 'controller' => 'products', 'action' => 'categories'))))); ?>
		</fieldset>
		
		<?php if(!empty($paymentOptions)) { ?>
	    <fieldset>
	        <legend class="toggleClick"><?php echo __('Select Payment Types For The Item.');?></legend>
	        <?php
	            echo $this->Form->input('Product.payment_type', array('options' => $paymentOptions, 'multiple' => 'checkbox'));
	        ?>
	    </fieldset>
	    <?php } ?>
	</div>
</div>
<div class="row-fluid">
    <?php echo $this->Form->end('Save Property'); ?>
</div>

<?php
// set the contextual menu items
$this->set('context_menu', array('menus' => array(
	array(
		'heading' => 'Products',
		'items' => array(
			$this->Html->link(__('Dashboard'), array('admin' => true, 'controller' => 'products', 'action' => 'dashboard')),
			$this->Html->link(__('List'), array('controller' => 'products', 'action' => 'index')),
			)
		),
	)));
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
		$('#ShippingPrice').show();
	} else {
		$('#ShippingPrice').hide();
	}
});

</script>