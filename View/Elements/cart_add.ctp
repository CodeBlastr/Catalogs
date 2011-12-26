<?php
# this should be at the top of every element created with format __ELEMENT_PLUGIN_ELEMENTNAME_instanceNumber.
# it allows a database driven way of configuring elements, and having multiple instances of that configuration.
if(!empty($instance) && defined('__ELEMENT_CATALOGS_CART_ADD_'.$instance)) {
	extract(unserialize(constant('__ELEMENT_CATALOGS_CART_ADD_'.$instance)));
} else if (defined('__ELEMENT_CATALOGS_CART_ADD')) {
	extract(unserialize(__ELEMENT_CATALOGS_CART_ADD));
}
	
# set up defaults
$catalogItemId = !empty($catalogItemId) ? $catalogItemId : $catalogItem['CatalogItem']['id'];
$catalogItemPrice = !empty($catalogItemPrice) ? $catalogItemPrice : $catalogItem['CatalogItem']['price'];
$catalogItemPaymentType = !empty($catalogItemPaymentType) ? $catalogItemPaymentType : $catalogItem['CatalogItem']['payment_type']; ?>
 
<div class="actions">
	<div class="action itemCartText catalogItemCartText">
<?php
	# don't show add to cart button for items with options on the index page
    if($this->params->action == 'index') { ?>
		<div class="action itemAddCart catalogItemAddCart itemAddCartHasOptions"> <?php echo $this->Html->link('View', array('plugin' => 'catalogs', 'action' => 'catalog_items', 'action' => 'view', $catalogItemId), array('class' => 'button')); ?> </div>
<?php
	# show items that have stock else don't
	} else if(!$no_stock) { ?>
    	<div class="action itemAddCart catalogItemAddCart">
<?php 
		if(isset($options) && !empty($options) && $this->params->action == 'index') { ?>
			<div class="action itemAddCart catalogItemAddCart itemAddCartHasOptions"> <?php echo $this->Html->link('View', array('plugin' => 'catalogs', 'action' => 'catalog_items', 'action' => 'view', $catalogItemId)); ?> </div>
<?php
		} else {
			echo $this->Form->create('OrderItem', array('url' => array('plugin' => 'orders', 'controller'=>'order_items', 'action'=>'add')));
			echo $this->Form->input('OrderItem.quantity' , array('label' => 'Add (Quantity)', 'value' => 1));
			echo $this->Form->hidden('OrderItem.parent_id' , array('value' => $catalogItemId));
			echo $this->Form->hidden('OrderItem.catalog_item_id' , array('value' => $catalogItemId));
			echo $this->Form->hidden('OrderItem.price' , array('value' => $catalogItemPrice));
			echo $this->Form->hidden('OrderItem.payment_type' , array('value' => $catalogItemPaymentType));
				
			echo $this->Element('item_options', array(), array('plugin' => 'catalogs')); 
			echo $this->Element('payment_type', array(), array('plugin' => 'catalogs')); 
			
			echo $this->Form->end(); ?>
    	  </div><!-- end action itemAddCart catalogItemAddCart -->
<?php 
		} // end options
	} else { ?>
    	<div class="action itemAddCart catalogItemAddCart itemAddCartNoStock">
      		<p>The item is out of stock. Please come back later</p> </div>
<?php
	} // end no_stock check ?>
    </div>
</div>