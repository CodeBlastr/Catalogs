<div class="catalogItems dashboard masonry">
    <div class="catalogItems dashboardBox">
    	<h3>Transactions</h3>
    	<ul>
        <?php foreach ($transactionStatuses as $key => $status) { ?>
			<li><?php echo $this->Html->link('List ' . $status, array('plugin' => 'orders', 'controller' => 'order_transactions', 'action' => 'index', 'filter' => 'status:'.$key)); ?></li>
		<?php } ?>
    	</ul>
    </div>
    
    <div class="catalogItems dashboardBox">
    	<h3>Order Items</h3>
    	<ul>
        <?php /* foreach ($itemStatuses as $key => $status) { ?>
			<li><?php echo $this->Html->link($status . ' Items', array('plugin' => 'orders', 'controller' => 'order_items', 'action' => 'index', 'filter' => 'status:'.$key)); ?></li>
		<?php } */?>
        	<li><?php echo $this->Html->link('List In Cart Items', array('plugin' => 'orders', 'controller' => 'order_items', 'action' => 'index', 'filter' => 'status:incart')); ?></li>
    	</ul>
	</div>
    
    <div class="catalogItems dashboardBox">
    	<h3>Catalog Items</h3>
    	<ul>
    		<li><?php echo $this->Html->link('Add', array('plugin' => 'catalogs', 'controller' => 'catalog_items' , 'action'=>'add'));?></li>
    		<li><?php echo $this->Html->link('List All', array('plugin' => 'catalogs', 'controller' => 'catalog_items' , 'action' => 'index'));?></li>
    		<li><?php echo $this->Html->link('List Out Of Stock', array('plugin' => 'catalogs', 'controller' => 'catalog_items', 'action' => 'index', 'filter' => 'stockItem:0'));?></li>
    	</ul>
    </div>
    
    <div class="catalogItems dashboardBox">
    	<h3>Brands</h3>
    	<ul>
    		<li><?php echo $this->Html->link('Add', array('plugin' => 'catalogs', 'controller' => 'catalog_item_brands', 'action' => 'add'));?></li>
    		<li><?php echo $this->Html->link('List All', array('plugin' => 'catalogs', 'controller' => 'catalog_item_brands', 'action' => 'index'));?></li>
    	</ul>
	</div>
    
    <div class="catalogItems dashboardBox">
    	<h3>Privileges</h3>
    	<ul>
    		<li><?php echo $this->Html->link('Edit Privileges', array('plugin' => 'privileges', 'controller' => 'sections', 'action' => 'index'));?></li>
    	</ul>
	</div>
    
    <div class="catalogItems dashboardBox">
    	<h3>Categories</h3>
    	<ul>
    		<li><?php echo $this->Html->link('Add', array('plugin' => 'categories', 'controller' => 'categories', 'action' => 'add', 'model' => 'Catalog'));?></li>
    		<li><?php echo $this->Html->link('List All', array('plugin' => 'categories', 'controller' => 'categories', 'action' => 'tree'));?></li>
    	</ul>
	</div>
    
    <!--
    Make an action in the catalogs controller to show people who have made an order 
    div class="catalogItems dashboardBox">
    	<h3>Customers</h3>
    	<ul>
    		<li><?php# echo $this->Html->link('List Customers', array('plugin' => 'catalogs', 'controller' => 'catalogs' , 'action' => 'people'));?></li>
    	</ul>
	</div-->
    
    <div class="catalogItems dashboardBox">
    	<h3>Attributes</h3>
    	<ul>
    		<li><?php echo $this->Html->link('Product Attributes', array('plugin' => 'categories', 'controller' => 'category_options' , 'action' => 'index'));?></li>
    	</ul>
	</div>
    
    <div class="catalogItems dashboardBox">
    	<h3>Settings</h3>
    	<ul>
    		<li><?php echo $this->Html->link('List All', array('plugin' => null, 'controller' => 'settings', 'action' => 'index', 'start' => 'type:Orders'));?></li>
    		<li><?php echo $this->Html->link('Transaction Status Types', array('plugin' => null, 'controller' => 'enumerations', 'action' => 'index', 'filter' => 'type:ORDER_ITEM_STATUS'));?></li>
            <li><?php echo $this->Html->link('Item Status Types', array('plugin' => null, 'controller' => 'enumerations', 'action' => 'index', 'start' => 'type:ORDER_TRANSACTION_STATUS')); ?></li>
    	</ul>
	</div>
    
</div>
