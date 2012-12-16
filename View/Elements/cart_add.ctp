<?php
// this should be at the top of every element created with format __ELEMENT_PLUGIN_ELEMENTNAME_instanceNumber.
// it allows a database driven way of configuring elements, and having multiple instances of that configuration.
if(!empty($instance) && defined('__ELEMENT_PRODUCTS_CART_ADD_'.$instance)) {
    extract(unserialize(constant('__ELEMENT_PRODUCTS_CART_ADD_'.$instance)));
} else if (defined('__ELEMENT_PRODUCTS_CART_ADD')) {
    extract(unserialize(__ELEMENT_PRODUCTS_CART_ADD));
}

// set up defaults
$productId = !empty($productId) ? $productId : $product['Product']['id'];
$productPrice = !empty($productPrice) ? $productPrice : $product['Product']['price'];
$minQty = !empty($product['Product']['cart_min']) ? $product['Product']['cart_min'] : 1;
$maxQty = !empty($product['Product']['cart_max']) ? $product['Product']['cart_max'] : null; ?>

<div class="actions">
	<div class="action itemCartText productCartText">
	<?php
    if($this->params->action == 'index' && (!empty($product['Product']['children']) || !empty($options))) {
        // don't show add to cart button for items with options on the index page
		echo __('<div class="action itemAddCart productAddCart itemAddCartHasOptions">%s</div>',  $this->Html->link('View', array('plugin' => 'products', 'action' => 'products', 'action' => 'view', $productId), array('class' => 'button')));
	} else {
    	echo '<div class="action itemAddCart productAddCart">';
		echo $this->Form->create('TransactionItem', array('url' => array('plugin' => 'transactions', 'controller'=>'transaction_items', 'action'=>'add')));
		// if the max allowable quantity of this item is only one, hide the TransactionItem.quantity input
		echo $this->Element('Options/select', array('product' => $product), array('plugin' => 'products'));
		echo (int)$maxQty === 1 ? $this->Form->hidden('TransactionItem.quantity' , array('value' => 1, 'min' => $minQty, 'max' => $maxQty)) : $this->Form->input('TransactionItem.quantity' , array('label' => ' Quantity ', 'value' => $minQty, 'min' => $minQty, 'max' => $maxQty));
		echo $this->Form->hidden('TransactionItem.model' , array('value' => 'Product'));
		echo $this->Form->hidden('TransactionItem.foreign_key' , array('value' => $productId));
		echo $this->Form->hidden('TransactionItem.price' , array('value' => $productPrice));

		echo $this->Element('payment_type', array(), array('plugin' => 'products'));
        
		echo $this->Form->end();
	    echo '</div>'; 
	} ?>
    </div>
</div>