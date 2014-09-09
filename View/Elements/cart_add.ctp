<?php
// set up defaults
$productId = !empty($productId) ? $productId : $product['Product']['id'];
$productName = !empty($productName) ? $productName : $product['Product']['name'];
$productPrice = !empty($productPrice) ? $productPrice : $product['Product']['price'];
$productModel = !empty($productModel) ? $productModel : $product['Product']['model'];
$productForeignKey = !empty($productForeignKey) ? $productForeignKey : $product['Product']['foreign_key'];
$productForeignKey = empty($productForeignKey) ? $productId : $productForeignKey;
$productArb = !empty($productArb) ? $productArb : $product['Product']['arb_settings'];
$minQty = !empty($product['Product']['cart_min']) ? $product['Product']['cart_min'] : 1;
$maxQty = !empty($product['Product']['cart_max']) ? $product['Product']['cart_max'] : null; ?>

<?php if (!empty($product['Product']['is_buyable'])) : ?>
<div class="actions indexCell itemAction productAction" id="productAction<?php echo $product['Product']['id']; ?>">
	<div class="action itemCartText productCartText">
	<?php if($this->params->action == 'index' && (!empty($product['Product']['children']) || !empty($options))) :  // don't show add to cart button for items with options on the index page ?>
		<div class="action itemAddCart productAddCart itemAddCartHasOptions">
			<?php echo $this->Html->link('View', array('plugin' =>
 'products', 'action' => 'products', 'action' => 'view', $productId), 
array('class' => 'button btn btn-primary')); ?>
		</div>
	<?php else : ?>
    	<?php echo $this->Form->create('TransactionItem', array('url' => array('plugin' => 'transactions', 'controller'=>'transaction_items', 'action'=>'add'))); ?>
    	<?php echo $this->Form->hidden('TransactionItem.name' , array('value' => $productName)); ?>
		<?php echo $this->Form->hidden('TransactionItem.model' , array('value' => $productModel)); ?>
		<?php echo $this->Form->hidden('TransactionItem.foreign_key' , array('value' => $productForeignKey)); ?>
		<?php echo $this->Form->hidden('TransactionItem.price' , array('value' => $productPrice)); ?>
		<?php echo $this->Form->hidden('TransactionItem.arb_settings' , array('value' => $productArb)); ?>
		<?php echo $this->Form->hidden('TransactionItem.cart_max' , array('value' => $maxQty)); ?>
		<?php echo $this->Form->hidden('TransactionItem.cart_min' , array('value' => $minQty)); ?>
		<div class="action itemAddCart productAddCart">
			<?php echo $this->Element('Options/select', array('product' => $product), array('plugin' => 'products')); ?>
			<div class="row"> 
				<div class="col-xs-12 add-to-cart"><div class="add-to-cart-box"> <p class="quantity">Quantity</p>
								<?php echo (int)$maxQty === 1 ? $this->Form->hidden('TransactionItem.quantity' , array('label' => false, 'value' => 1, 'min' => $minQty, 'max' => $maxQty)) : $this->Form->input('TransactionItem.quantity' , array('div' => false, 'label' => false, 'value' => $minQty, 'min' => $minQty, 'max' => $maxQty)); // if the max allowable quantity of this item is only one, hide the TransactionItem.quantity input ?>
				</div></div>
				<div class="col-xs-12 add-to-cart-btn">
					<?php echo $this->element('Products.payment_type'); ?>
				</div>
			</div>
        </div>
		<?php echo $this->Form->end(); ?>
	<?php endif; ?>
    </div>
</div>
<?php endif; ?>
