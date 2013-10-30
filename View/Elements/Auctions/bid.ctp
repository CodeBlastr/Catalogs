<?php
// this should be at the top of every element created with format __ELEMENT_PLUGIN_ELEMENTNAME_instanceNumber.
// it allows a database driven way of configuring elements, and having multiple instances of that configuration.
if (!empty($instance) && defined('__ELEMENT_PRODUCTS_AUCTION_BID_'.$instance)) {
	extract(unserialize(constant('__ELEMENT_PRODUCTS_AUCTION_BID_'.$instance)));
} else if (defined('__ELEMENT_PRODUCTS_AUCTION_BID')) {
	extract(unserialize(__ELEMENT_PRODUCTS_AUCTION_BID));
}

// set up defaults
$productId = !empty($productId) ? $productId : $product['Product']['id'];
$productPrice = !empty($productPrice) ? $productPrice : $product['Product']['price'];
$minQty = !empty($product['Product']['cart_min']) ? $product['Product']['cart_min'] : 1;
$maxQty = !empty($product['Product']['cart_max']) ? $product['Product']['cart_max'] : null;
$highestBid = (isset($product['ProductBid'][0]['amount'])) ? '$'.ZuhaInflector::pricify($product['ProductBid'][0]['amount']) : 'no bids';
?>

<div>
	<table>
		<tr><td>Current bid:</td><td><b><?php echo $highestBid ?></b></td></tr>
		<tr>
			<td>Your bid:</td>
			<td>
			<?php
			echo $this->Form->create('ProductBid', array('url' => array('controller' => 'product_bids', 'action' => 'add')));
			echo $this->Form->hidden('ProductBid.product_id', array('value' => $productId));
			echo $this->Form->input('ProductBid.amount', array(
					'label' => false,
					'class' => 'required input-small',
					'placeholder' => ZuhaInflector::pricify($product['ProductBid'][0]['amount'] + 1)));
			echo $this->Form->submit('Place bid', array('class' => 'btn-primary'));
			echo $this->Form->end();
			?>
			</td>
		</tr>
	</table>
</div>
