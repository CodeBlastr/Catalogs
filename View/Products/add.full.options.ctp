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
<div class="productAdd form">
	<?php
	echo $this->Form->create('Product', array('type' => 'file'));
	?>
    <h2><?php echo __('Add a Product'); ?></h2>
    <fieldset>
    	<?php
		echo $this->Form->input('Product.is_public', array('default' => 1, 'type' => 'hidden'));
		echo $this->Form->input('Product.name', array('label' => 'Display name'));
		echo $this->Form->input('Product.sku');
        echo $this->Form->input('Product.price', array('label' => 'Retail price <small><em>(ex. 0.00)</em></small>', 'type' => 'number', 'step' => '.01', 'min' => '0', 'max' => '99999999999'));
        echo CakePlugin::loaded('Media') ? $this->Element('Media.selector', array('multiple' => true)) : null;
		echo $this->Form->input('Product.summary', array('type' => 'text', 'label' => 'Promo Text <br /><small><em>Used to entice people to view more about this item.</em></small>'));
		echo $this->Form->input('Product.description', array('type' => 'richtext', 'label' => 'What is the sales copy for this item?')); ?>
    </fieldset>
    <fieldset>
        <legend class="toggleClick"><?php echo __('Optional product details'); ?></legend>
        <?php
		echo $this->Form->input('Product.product_brand_id', array('empty' => '-- Select --', 'label' => 'What is this item\'s brand name? ('.$this->Html->link('add', array('controller' => 'product_brands', 'action' => 'add')).' / '.$this->Html->link('edit', array('controller' => 'product_brands', 'action' => 'index')).' brands)'));
		echo $this->Form->input('Product.stock', array('label' => 'Would you like to track inventory?', 'after' => '<p>Enter your current item count or leave blank for unlimited</p>'));
        echo $this->Form->input('Product.cost', array('label' => 'What does the product cost you?'));
		echo $this->Form->input('Product.cart_min', array('label' => 'Minimun Cart Quantity? <br /><small><em>Enter the minimum cart quantity or leave blank for 1</em></small>'));
		echo $this->Form->input('Product.cart_max', array('label' => 'Maximum Cart Quantity? <br /><small><em>Enter the max cart quantity or leave blank for unlimited</em></small>')); ?>
    </fieldset>
	<fieldset>
 		<legend class="toggleClick"><?php echo __('Do you offer shipping for this item?');?></legend>
    	<?php
		$fedexSettings = defined('__ORDERS_FEDEX') ? unserialize(__ORDERS_FEDEX) : null;
		$radioOptions = array();
		if (!empty($fedexSettings)) : foreach($fedexSettings as $k => $val) :
			$radioOptions[$k] = $val ;
			echo $this->Form->input('Product.weight', array('label' => 'Weight (lbs)'));
			echo $this->Form->input('Product.height', array('label' => 'Height (8-70 inches)'));
			echo $this->Form->input('Product.width', array('label' => 'Width (50-119 inches)'));
			echo $this->Form->input('Product.length', array('label' => 'Length (50-119 inches)'));
		endforeach; endif;
		$radioOptions += array('FIXEDSHIPPING' => 'FIX SHIPPING', 'FREESHIPPING' => 'FREE SHIPPING') ;
		echo $this->Form->radio('Product.shipping_type', $radioOptions, array('class' => 'shipping_type' , 'default' => ''));
	 	?>
	 	<div id='ShippingPrice'>
	 		<?php echo $this->Form->input('Product.shipping_charge');?>
		</div>
    </fieldset>
	<fieldset>
 		<legend class="toggleClick"><?php echo __('Do you want to limit geographic availability of this item?');?></legend>
    	<?php
		echo $this->Form->input('Location.available', array('rows'=>1, 'cols' => 30,'label' => 'Zip Codes Available (comma separated)'));
		echo $this->Form->input('Location.restricted', array('rows'=>1, 'cols' => 30,'label' => 'Zip Codes Restricted (comma separated)'));
		echo $this->Form->hidden('Location.model', array('value' => Inflector::camelize(Inflector::singularize($this->name))));
		?>
    </fieldset>
	<fieldset>
 		<legend class="toggleClick"><?php echo __('Does this item have a schedule?');?></legend>
    	<?php
		echo $this->Form->input('Product.started', array('empty' => true));
		echo $this->Form->input('Product.ended', array('empty' => true));

		if (isset($this->request->data['ProductPrice'])) :
			foreach($this->request->data['ProductPrice'] as $index => $val) :
				echo $this->Form->hidden("ProductPrice.{$index}.id", array('value'=>$val['id']));
				echo $this->Form->hidden("ProductPrice.{$index}.price", array('value'=>$val['price']));
				echo $this->Form->hidden("ProductPrice.{$index}.product_id", array('value'=>$val['product_id']));
				echo $this->Form->hidden("ProductPrice.{$index}.user_role_id", array('value'=>$val['user_role_id']));
			endforeach;
		endif;
		$i = 0;
		if (!empty($this->request->data['Category'])) { foreach($this->request->data['Category'] as $value) {
			++$i;
			echo '<div id="divCategory'.$i.'">';
			echo $i . ' '. $categories[$value];
			echo $this->Html->link('Remove' , "javascript:rem('Category{$i}')", array(''));
			echo $this->Form->hidden('Category.'.$i, array('value' => $value));
			echo '</div>';
		}?>
		<h3>Options</h3>
		<?php
		if(isset($options)) {
			foreach($options as $key => $opt) {
				echo '<div style ="float:left; width: 200px; clear:none;">';
				echo '<fieldset>';
				echo '<legend>' . $opt['CategoryOption']['name'] . '</legend>';
				$sel = array();
				foreach($opt['children'] as $child) {
					$sel[$child['CategoryOption']['id']] = $child['CategoryOption']['name'];
				}
				if (!empty($sel))
					echo $this->Form->input('CategoryOption.'.$opt['CategoryOption']['id'],
						array('options'=>$sel, 'multiple'=>'checkbox', 'label'=> false, 'div'=>false,
								'type'=> $opt['CategoryOption']['type'] == 'Attribute Group' ? 'radio' : 'select'));
				echo '</fieldset>';
				echo '</div>';
			}
		} }
	?>
	</fieldset>

	<fieldset>
 		<legend class="toggleClick"><?php echo __('Does this item need to be categorized?');?></legend>
			<?php echo $this->Form->input('Category', array('multiple' => 'checkbox', 'label' => 'Which categories? ('.$this->Html->link('add', array('plugin' => 'categories', 'controller' => 'categories', 'action' => 'tree')).' / '.$this->Html->link('edit', array('plugin' => 'categories', 'controller' => 'categories', 'action' => 'tree')).' categoies)')); ?>
	</fieldset>
	<fieldset>
 		<legend class="toggleClick"><?php echo __('Is this a recurring billing item?');?></legend>
			<?php
				echo $this->Form->input('Product.arb_settings', array(
					'rows' => 1,
					'cols' => 30,
					'label' => 'Arb Settings (
									trialOccurrences (No Of Billing Cycles For Trial),
									totalOccurrences (Total Billing Cycles),
									interval_length (How Many Months Do You Want In A Billing Cycle),
									trialAmount (Amount If Any For Trial Period) )'
					)); ?>
	</fieldset>
	<?php
		if(!empty($paymentOptions)) { ?>
		<fieldset>
			<legend class="toggleClick"><?php echo __('Select Payment Types For The Item.');?></legend>
			<?php
				echo $this->Form->input('Product.payment_type', array('options' => $paymentOptions, 'multiple' => 'checkbox'));
			?>
		</fieldset>
	<?php
		} ?>
	<fieldset>
 		<legend class="toggleClick"><?php echo __('Do you want to create this item as virtual?');?></legend>
    	<?php
			echo $this->Form->input('Product.model', array('options' => array('Webpage' => 'Webpage'), 'empty' => true));
			echo $this->Form->input('Product.foreign_key', array('empty' => true));
			echo $this->Form->input('Product.is_virtual');
			echo $this->Form->input('Product.hours_expire', array('after' => 'Number of hours that this item will be available to the customer after purchase'));
		?>
    </fieldset>

	<?php
    echo $this->Form->end('Submit');
	?>

<?php
// set the contextual menu items
$this->set('context_menu', array('menus' => array(
	array(
		'heading' => 'Products',
		'items' => array(
			$this->Html->link(__('List', true), array('controller' => 'products', 'action' => 'index')),
			$this->Html->link(__('Add', true), array('controller' => 'products', 'action' => 'add')),
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


</div>