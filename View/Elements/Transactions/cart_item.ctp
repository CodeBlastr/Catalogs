<div>
<?php
echo $this->Html->link($transactionItem['name'],
	'/catalogs/catalog_items/view/'.$transactionItem['foreign_key'],
	array('style' => 'text-decoration: underline;'),
	'Are you sure you want to leave this page?'
	);
?>
</div>
<?php
echo $this->element('thumb', array(
	    'model' => 'CatalogItem',
	    'foreignKey' => $transactionItem['foreign_key'],
	    'thumbSize' => 'small',
	    'thumbWidth' => 75,
	    'thumbHeight' => 75,
	    'thumbLink' => '/catalogs/catalog_items/view/'.$transactionItem['foreign_key']
	    ),
	array('plugin' => 'galleries')
	);

$minQty = !empty($catalogItem['CatalogItem']['cart_min']) ? $catalogItem['CatalogItem']['cart_min'] : 1;
$maxQty = !empty($catalogItem['CatalogItem']['cart_max']) ? $catalogItem['CatalogItem']['cart_max'] : null;

echo $this->Form->input("TransactionItem.{$i}.quantity", array(
    'label' => 'Qty.',
    'div' => array('style' => 'display:inline-block'),
    'value' => $transactionItem['quantity'],
    'min' => $minQty, 'max' => $maxQty,
    'size' => 1
    ));
?>

<div style="display: inline-block; float: right; font-weight: bold; padding: 22px 0;" id="">
    $<?php echo number_format($transactionItem['price'], 2); ?>
</div>
