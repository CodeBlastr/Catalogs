<?php
# this should be at the top of every element created with format __ELEMENT_PLUGIN_ELEMENTNAME_instanceNumber.
# it allows a database driven way of configuring elements, and having multiple instances of that configuration.
if(!empty($instance) && defined('__ELEMENT_PRODUCTS_CART_ADD_'.$instance)) {
	extract(unserialize(constant('__ELEMENT_PRODUCTS_CART_ADD_'.$instance)));
} else if (defined('__ELEMENT_PRODUCTS_CART_ADD')) {
	extract(unserialize(__ELEMENT_PRODUCTS_CART_ADD));
}

# set up defaults
$productId = !empty($productId) ? $productId : $product['Product']['id'];
$productPrice = !empty($productPrice) ? $productPrice : $product['Product']['price'];
$productPaymentType = !empty($productPaymentType) ? $productPaymentType : $product['Product']['payment_type'];

$minQty = !empty($product['Product']['cart_min']) ? $product['Product']['cart_min'] : 1;
$maxQty = !empty($product['Product']['cart_max']) ? $product['Product']['cart_max'] : null;
?>

<div class="actions">
	<div class="action itemCartText productCartText">
	<?php
	# don't show add to cart button for items with options on the index page
    if($this->params->action == 'index' && ($product['Product']['children'] === null || $product['Product']['children'] > 0)) { ?>
		<div class="action itemAddCart productAddCart itemAddCartHasOptions"> <?php echo $this->Html->link('View', array('plugin' => 'products', 'action' => 'products', 'action' => 'view', $productId), array('class' => 'button')); ?> </div>
<?php
	# show items that have stock else don't
	# NOTE : This children check is temporary.  The assumption is that if it has children the stock is probably not zero, but instead we need to make an afterSave function or some other callback, which updates the parent stock so that it is equal to the sum of all the children stocks.
	} else if(
            ( $product['Product']['stock'] > 0 || $product['Product']['stock'] === NULL )
            || !empty($product['ProductChild'][0])
            ) {
      ?>
    	<div class="action itemAddCart productAddCart">
		<?php
		if(isset($options) && !empty($options) && $this->params->action == 'index') { ?>
			<div class="action itemAddCart productAddCart itemAddCartHasOptions"> <?php echo $this->Html->link('View', array('plugin' => 'products', 'action' => 'products', 'action' => 'view', $productId)); ?> </div>
		<?php
		} else {
			echo $this->Form->create('TransactionItem', array('url' => array('plugin' => 'transactions', 'controller'=>'transaction_items', 'action'=>'add')));
			echo $this->Form->input('TransactionItem.quantity' , array('label' => ' Quantity ', 'value' => $minQty, 'min' => $minQty, 'max' => $maxQty));
			//echo $this->Form->hidden('TransactionItem.parent_id' , array('value' => $productId));
			echo $this->Form->hidden('TransactionItem.model' , array('value' => 'Product'));
			echo $this->Form->hidden('TransactionItem.foreign_key' , array('value' => $productId));
			echo $this->Form->hidden('TransactionItem.price' , array('value' => $productPrice));
			echo $this->Form->hidden('TransactionItem.payment_type' , array('value' => $productPaymentType));

			echo $this->Element('item_options', array(), array('plugin' => 'products'));
			echo $this->Element('payment_type', array(), array('plugin' => 'products'));

			echo $this->Form->end(); ?>
    	  </div><!-- end action itemAddCart productAddCart -->
		<?php
		} // end options
	} else { ?>
    	<div class="action itemAddCart productAddCart itemAddCartNoStock">
      		<p>The item is out of stock. Please come back later</p> </div>
<?php
	} ?>
    </div>
</div>