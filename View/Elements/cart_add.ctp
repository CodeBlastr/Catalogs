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
$catalogItemPaymentType = !empty($catalogItemPaymentType) ? $catalogItemPaymentType : $catalogItem['CatalogItem']['payment_type'];

$minQty = !empty($catalogItem['CatalogItem']['cart_min']) ? $catalogItem['CatalogItem']['cart_min'] : 1;
$maxQty = !empty($catalogItem['CatalogItem']['cart_max']) ? $catalogItem['CatalogItem']['cart_max'] : null;
?>

<div class="actions">
	<div class="action itemCartText catalogItemCartText">
	<?php
	# don't show add to cart button for items with options on the index page
    if($this->params->action == 'index' && ($catalogItem['CatalogItem']['children'] === null || $catalogItem['CatalogItem']['children'] > 0)) { ?>
		<div class="action itemAddCart catalogItemAddCart itemAddCartHasOptions"> <?php echo $this->Html->link('View', array('plugin' => 'catalogs', 'action' => 'catalog_items', 'action' => 'view', $catalogItemId), array('class' => 'button')); ?> </div>
<?php
	# show items that have stock else don't
	# NOTE : This children check is temporary.  The assumption is that if it has children the stock is probably not zero, but instead we need to make an afterSave function or some other callback, which updates the parent stock so that it is equal to the sum of all the children stocks.
	} else if(
            ( $catalogItem['CatalogItem']['stock'] > 0 || $catalogItem['CatalogItem']['stock'] === NULL )
            || !empty($catalogItem['CatalogItemChildren'][0])
            ) {
      ?>
    	<div class="action itemAddCart catalogItemAddCart">
		<?php
		if(isset($options) && !empty($options) && $this->params->action == 'index') { ?>
			<div class="action itemAddCart catalogItemAddCart itemAddCartHasOptions"> <?php echo $this->Html->link('View', array('plugin' => 'catalogs', 'action' => 'catalog_items', 'action' => 'view', $catalogItemId)); ?> </div>
		<?php
		} else {
			echo $this->Form->create('TransactionItem', array('url' => array('plugin' => 'transactions', 'controller'=>'transaction_items', 'action'=>'add')));
			echo $this->Form->input('TransactionItem.quantity' , array('label' => ' Quantity ', 'value' => $minQty, 'min' => $minQty, 'max' => $maxQty));
			echo $this->Form->hidden('TransactionItem.parent_id' , array('value' => $catalogItemId));
			echo $this->Form->hidden('TransactionItem.catalog_item_id' , array('value' => $catalogItemId));
			echo $this->Form->hidden('TransactionItem.price' , array('value' => $catalogItemPrice));
			echo $this->Form->hidden('TransactionItem.payment_type' , array('value' => $catalogItemPaymentType));

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
	} ?>
    </div>
</div>