<br><br>
<table class="catalogs_admin">
  <tr>
    <td class="catalogs_admin">
    	<span class="admin_header">Orders</span>
    	<ul>
    		<!--li><?php echo $this->Html->link("View Complete Orders" , array('plugin'=>'orders', 'controller'=>'order_items' , 'action'=>'index', 'admin'=>true, 'successful'));?></li>
    		<li><?php echo $this->Html->link("View Pending Orders" , array('plugin'=>'orders', 'controller'=>'order_items' , 'action'=>'index' , 'admin'=>true, 'pending'));?></li-->
    		<li><?php echo $this->Html->link("View Orders In Users Cart" , array('plugin'=>'orders', 'controller'=>'order_items' , 'action'=>'index' , 'admin'=>true, 'incart'));?></li>
    		<li><?php echo $this->Html->link("View Sent Orders" , array('plugin'=>'orders', 'controller'=>'order_items' , 'action'=>'index' , 'admin'=>true, 'sent'));?></li>
    		<li><?php echo $this->Html->link("View Paid Orders" , array('plugin'=>'orders', 'controller'=>'order_items' , 'action'=>'index' , 'admin'=>true, 'paid'));?></li>
    		<!--li><?php echo $this->Html->link("View Frozen Orders" , array('plugin'=>'orders', 'controller'=>'order_items' , 'action'=>'index' , 'admin'=>true, 'frozen'));?></li>
    		<li><?php echo $this->Html->link("View Cancelled Orders" , array('plugin'=>'orders', 'controller'=>'order_items' , 'action'=>'index', 'admin'=>true, 'cancelled'));?></li-->
    	</ul>
    </td>
    <td class="catalogs_admin">
    	<span class="admin_header">Transactions</span>
    	<ul>
    		<li><?php echo $this->Html->link("View Paid transactions" , array('plugin'=>'orders', 'controller'=>'order_transactions' , 'action'=>'index', 'paid', 'admin'=>true));?></li>
    		<li><?php echo $this->Html->link("View Shipped Transactions" , array('plugin'=>'orders', 'controller'=>'order_transactions' , 'action'=>'index', 'shipped', 'admin'=>true));?></li>
    		<!--li><?php echo $this->Html->link("View Reports" , array('plugin'=>'orders', 'controller'=>'order_transactions' , 'action'=>'add' , 'admin'=>true));?></li-->
    	</ul>
    </td>
    <td class="catalogs_admin">
    	<span class="admin_header">Catalog Items</span>
    	<ul>
    		<li><?php echo $this->Html->link("View Catalog Items" , array('controller'=>'catalog_items' , 'action'=>'index', 'admin'=> false));?></li>
    		<li><?php echo $this->Html->link("Add A Catalog Item" , array('controller'=>'catalog_items' , 'action'=>'add' , 'admin'=>false));?></li>
    	</ul>
    </td>
   </tr>
   <tr>
    <td class="catalogs_admin">
    	<span class="admin_header">Brands</span>
    	<ul>
    		<li><?php echo $this->Html->link("Add Brand" , array('plugin'=>'catalogs', 'controller'=>'catalog_item_brands' , 'action'=>'add' , 'admin'=>true));?></li>
    		<li><?php echo $this->Html->link("View Brands" , array('plugin'=>'catalogs', 'controller'=>'catalog_item_brands' , 'action'=>'index' , 'admin'=>true));?></li>
    	</ul>
    </td>
    <td class="catalogs_admin">
    	<span class="admin_header">Access Control</span>
    	<ul>
    		<li><?php echo $this->Html->link("Edit Permissions" , array('plugin'=>'privileges', 'controller'=>'sections' , 'action'=>'index' , 'admin' => false));?></li>
    	</ul>
    </td>
    <td class="catalogs_admin">
    	<span class="admin_header">Stock</span>
    	<ul>
    		<li><?php echo $this->Html->link("View Out Of Stock items" , array('plugin'=>'catalogs', 'controller'=>'catalog_items' , 'action' => 'index', 'stock' => 0, 'admin' => false));?></li>
    	</ul>
    </td>
  </tr>
  <tr>
  	<td class="catalogs_admin">
    	<span class="admin_header">Categories</span>
    	<ul>
    		<li><?php echo $this->Html->link("Add A Category" , array('plugin'=>'categories', 'controller'=>'categories' , 'action'=>'add' , 'admin'=>true, 'model' => 'Catalog'));?></li>
    		<li><?php echo $this->Html->link("View Categories" , array('plugin'=>'categories', 'controller'=>'categories' , 'action'=>'tree' , 'admin'=>true));?></li>
    	</ul>
    </td>
    <td class="catalogs_admin">
    	<!--span class="admin_header">Customers</span>
    	<ul>
    		<li><?php echo $this->Html->link("View Out Of Stock items" , array('plugin'=>'catalogs', 'controller'=>'catalog_items' , 'action'=>'index' , 'admin'=>true));?></li>
    		<li><?php echo $this->Html->link("View In stock items" , array('plugin'=>'catalogs', 'controller'=>'catalog_items' , 'action'=>'add' , 'admin'=>true));?></li>
    	</ul-->
    </td>
    <td class="catalogs_admin"></td>
  </tr>
</table>
