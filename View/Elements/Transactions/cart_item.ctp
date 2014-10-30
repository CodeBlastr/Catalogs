<?php $product = $this->requestAction('/products/products/view/' . $transactionItem['foreign_key'] . '/1'); ?>
<?php $minQty = !empty($product['Product']['cart_min']) ? $product['Product']['cart_min'] : 0; ?>
<?php $maxQty = !empty($product['Product']['cart_max']) ? $product['Product']['cart_max'] : null; ?>

<h5><?php echo $this->Html->link($transactionItem['name'], '/products/products/view/'.$transactionItem['foreign_key'], null, __('Are you sure you want to leave this page?')); ?></h5>
<table class="table table-hover">
	<tr>
		<th>&nbsp;</th>
		<th>&nbsp;</th>
		<th class="text-right">single</th>
		<th class="text-right">sub total</th>
	</tr>
	<tr>
		<td class="span1 col-xs-2">
			<?php echo $this->Media->display($product['Media'][0], array('alt' => $product['Product']['name'])); ?>
		</td>
		<td class="col-xs-4">
			<?php echo $this->Form->input("TransactionItem.{$i}.quantity", array(
				    'label' => false,
					'class' => 'TransactionItemCartQty span5 input-small',
				    'div' => false,
				    'value' => $transactionItem['quantity'],
				    'min' => $minQty, 'max' => $maxQty,
				    'size' => 1,
				    'after' => __(' %s', $this->Html->link('<i class="icon-trash glyphicon glyphicon-trash"></i>', array('plugin' => 'transactions', 'controller' => 'transaction_items', 'action' => 'delete', $transactionItem['id']), array('title' => 'Remove from cart', 'escape' => false)))
				    )); ?>
			<?php $transactionItemCartPrice = $transactionItem['price'] * $transactionItem['quantity']; ?>
		</td>
		<td class="col-xs-3 text-right TransactionItemCartPrice">
			<span class="priceOfOne"><?php echo number_format($transactionItem['price'], 2);  // pricify causes js error ?></span>
		</td>
		<td class="col-xs-3 text-right TransactionItemCartPrice">
			<span class="floatPrice"><?php echo number_format($transactionItemCartPrice, 2); // pricify causes js error ?></span>
		</td>
	</tr>
</table>
