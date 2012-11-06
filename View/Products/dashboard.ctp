<?php echo $this->Html->script('plugins/jquery.masonry.min', array('inline' => false)); ?>

<div class="masonry products dashboard">
    <div class="masonryBox dashboardBox tagProducts">
    	<h3>Transactions</h3>
    	<ul>
        <?php foreach ($transactionStatuses as $key => $status) { ?>
			<li><?php echo $this->Html->link('List ' . $status, array('plugin' => 'transactions', 'controller' => 'transactions', 'action' => 'index', 'filter' => 'status:'.$key)); ?></li>
		<?php } ?>
    	</ul>
		<h5>Transaction Items</h5>
		<p>List of items that are or have been part of a transaction.</p>
    	<ul>
        <?php /* foreach ($itemStatuses as $key => $status) { ?>
			<li><?php echo $this->Html->link($status . ' Items', array('plugin' => 'orders', 'controller' => 'order_items', 'action' => 'index', 'filter' => 'status:'.$key)); ?></li>
		<?php } */?>
        	<li><?php echo $this->Html->link('List In Cart Items', array('plugin' => 'transactions', 'controller' => 'transaction_items', 'action' => 'index', 'filter' => 'status:incart')); ?></li>
    	</ul>
    </div>

</div>

    <div class="products row dashboardBox">
    	<h3>Setup</h3>
		<div class="span3">
			<h5>Store</h5>
			<ul>
				<li><?php echo $this->Html->link('Add an Item', array('plugin' => 'products', 'controller' => 'products' , 'action'=>'add'));?></li>
				<li><?php echo $this->Html->link('Add a Virtual Webpage Item', array('plugin' => 'products', 'controller' => 'products' , 'action'=>'add_virtual'));?></li>
				<li><?php echo $this->Html->link('List All Items', array('plugin' => 'products', 'controller' => 'products' , 'action' => 'index'));?></li>
				<li><?php echo $this->Html->link('List Out Of Stock Items', array('plugin' => 'products', 'controller' => 'products', 'action' => 'index', 'filter' => 'stockItem:0'));?></li>
			</ul>
		</div>
		<div class="span3">
			<h5>Brands</h5>
			<ul>
				<li><?php echo $this->Html->link('Add a Brand', array('plugin' => 'products', 'controller' => 'product_brands', 'action' => 'add'));?></li>
				<li><?php echo $this->Html->link('List All Brands', array('plugin' => 'products', 'controller' => 'product_brands', 'action' => 'index'));?></li>
			</ul>
		</div>
		<div class="span3">
			<h5>Attributes</h5>
			<ul>
				<li><?php echo $this->Html->link('Product Attributes', array('plugin' => 'categories', 'controller' => 'category_options' , 'action' => 'index'));?></li>
			</ul>
			<h5>Categories</h5>
			<ul>
				<li><?php echo $this->Html->link('Add a Category', array('plugin' => 'categories', 'controller' => 'categories', 'action' => 'add', 'model' => 'ProductStore'));?></li>
				<li><?php echo $this->Html->link('List All Categories', array('plugin' => 'categories', 'controller' => 'categories', 'action' => 'tree'));?></li>
			</ul>
		</div>
		<div class="span2">
			<h5>Settings</h5>
			<ul>
				<li><?php echo $this->Html->link('List All', array('plugin' => null, 'controller' => 'settings', 'action' => 'index', 'start' => 'type:Orders'));?></li>
				<li><?php echo $this->Html->link('Transaction Status Types', array('plugin' => null, 'controller' => 'enumerations', 'action' => 'index', 'filter' => 'type:ORDER_ITEM_STATUS'));?></li>
				<li><?php echo $this->Html->link('Item Status Types', array('plugin' => null, 'controller' => 'enumerations', 'action' => 'index', 'start' => 'type:ORDER_TRANSACTION_STATUS')); ?></li>
			</ul>
		</div>
    </div>
