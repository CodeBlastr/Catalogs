<div class="accordion">
  <ul>
    <li> <a href="#"><span>Sales</span></a></li>
  </ul>
  <ul>
    <li><?php echo $this->Html->link('Catalog Items', array('plugin' => 'catalogs', 'controller' => 'catalogs_items', 'action' => 'index')); ?></li>
    <li><?php echo $this->Html->link('Categories', array('plugin' => 'categories', 'controller' => 'categories', 'action' => 'index', 'type' => 'Catalog')); ?></li>
    <li><?php echo $this->Html->link('Orders', array('plugin' => 'orders', 'controller' => 'order_transactions', 'action' => 'index')); ?></li>
  </ul>
</div>