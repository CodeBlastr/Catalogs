<div class="catalogItems dashboard masonry">
    
    <div class="catalogItems dashboardBox">
    	<h3>Transactions</h3>
    	<ul>
    		<li><?php echo $this->Html->link('Paid Transactions', array('plugin' => 'orders', 'controller' => 'order_transactions', 'action' => 'index', 'filter' => 'status:paid'));?></li>
    		<li><?php echo $this->Html->link('Shipped Transactions', array('plugin' => 'orders', 'controller' => 'order_transactions', 'action' => 'index', 'filter' => 'status:shipped'));?></li>
    	</ul>
    </div>
    
    <div class="catalogItems dashboardBox">
    	<h3>Order Items</h3>
    	<ul>
    		<li><?php echo $this->Html->link('Completed Items', array('plugin' => 'orders', 'controller' => 'order_items' , 'action' => 'index', 'filter' => 'status:successful'));?></li>
    		<li><?php echo $this->Html->link('Pending Items', array('plugin' => 'orders', 'controller' => 'order_items', 'action' => 'index', 'filter' => 'status:pending'));?></li>
    		<li><?php echo $this->Html->link('In Cart Items', array('plugin' => 'orders', 'controller' => 'order_items', 'action' => 'index', 'filter' => 'status:incart'));?></li>
    		<li><?php echo $this->Html->link('Sent Items', array('plugin' => 'orders', 'controller' => 'order_items', 'action' => 'index', 'filter' => 'status:sent'));?></li>
    		<li><?php echo $this->Html->link('Paid Items', array('plugin' => 'orders', 'controller' => 'order_items', 'action' => 'index', 'filter' => 'status:paid'));?></li>
    		<li><?php echo $this->Html->link('Frozen Items', array('plugin' => 'orders', 'controller' => 'order_items', 'action' => 'index', 'filter' => 'status:frozen'));?></li>
    		<li><?php echo $this->Html->link('Cancelled Items', array('plugin'=>'orders', 'controller' => 'order_items', 'action' => 'index', 'filter' => 'status:cancelled'));?></li>
    	</ul>
	</div>
    
    <div class="catalogItems dashboardBox">
    	<h3>Catalog Items</h3>
    	<ul>
    		<li><?php echo $this->Html->link('List Catalog Items', array('plugin' => 'catalogs', 'controller' => 'catalog_items' , 'action' => 'index'));?></li>
    		<li><?php echo $this->Html->link('Add a Catalog Item', array('plugin' => 'catalogs', 'controller' => 'catalog_items' , 'action'=>'add'));?></li>
    		<li><?php echo $this->Html->link('Out Of Stock items', array('plugin' => 'catalogs', 'controller' => 'catalog_items', 'action' => 'index', 'filter' => 'stockItem:0'));?></li>
    	</ul>
    </div>
    
    <div class="catalogItems dashboardBox">
    	<h3>Brands</h3>
    	<ul>
    		<li><?php echo $this->Html->link('List Brands', array('plugin' => 'catalogs', 'controller' => 'catalog_item_brands', 'action' => 'index'));?></li>
    		<li><?php echo $this->Html->link('Add Brand', array('plugin' => 'catalogs', 'controller' => 'catalog_item_brands', 'action' => 'add'));?></li>
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
    		<li><?php echo $this->Html->link('Add Category', array('plugin' => 'categories', 'controller' => 'categories', 'action' => 'add', 'model' => 'Catalog'));?></li>
    		<li><?php echo $this->Html->link('List Categories', array('plugin' => 'categories', 'controller' => 'categories', 'action' => 'tree'));?></li>
    	</ul>
	</div>
    
    <div class="catalogItems dashboardBox">
    	<h3>Customers</h3>
    	<ul>
    		<li><?php echo $this->Html->link('List Customers', array('plugin' => 'contacts', 'controller' => 'contacts' , 'action' => 'people'));?></li>
    	</ul>
	</div>
    
    <div class="catalogItems dashboardBox">
    	<h3>Attributes</h3>
    	<ul>
    		<li><?php echo $this->Html->link('Product Attributes', array('plugin' => 'categories', 'controller' => 'category_options' , 'action' => 'index'));?></li>
    	</ul>
	</div>
    
</div>
