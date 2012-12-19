<?php
if (!empty($product['Children'])) {
    echo __('<div class="children">');
    foreach ($product['Children'] as $child) {
        echo $this->Element('product', array('product' => array('Product' => $child, 'Gallery' => $child['Gallery'])), array('plugin' => 'products'));
    }
    echo __('</div>');
} else {
    echo $this->Element('product', array('product' => $product), array('plugin' => 'products'));
}
?>

<script type="text/javascript">
    $(function() {
        $('.children .product.view').hide();
        $('.children .product.view:first-child').show();
        $('.ProductSelectId').removeAttr('disabled');
        $('.ProductSelectId').change(function() {
            $('.children .product.view').hide();
            $('#ProductSelectId' + $(this).val()).val($(this).val());
            $('#product' + $(this).val()).show();
        });
    });
</script>

<?php
// set the contextual menu items
$this->set('context_menu', array('menus' => array(
    array(
		'heading' => 'Products',
		'items' => array(
			$this->Html->link(__('Dashboard'), array('controller' => 'products', 'action' => 'dashboard')),
			$this->Html->link(__('Cart'), array('plugin' => 'transactions', 'controller' => 'transactions', 'action' => 'cart')),
			)
		),
	array(
		'heading' => 'Product',
		'items' => array(
			$this->Html->link(__d('products', 'List'), array('action' => 'index')),
			$this->Html->link(__d('products', 'Edit'), array('action' => 'edit', $product['Product']['id'])),
			$this->Html->link(__d('products', 'Delete'), array('action' => 'delete', $product['Product']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $product['Product']['id'])),
			),
		),
	))); ?>
