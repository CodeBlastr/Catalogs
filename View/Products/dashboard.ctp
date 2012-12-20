<?php echo $this->Html->script('https://www.google.com/jsapi', array('inline' => false)); ?>
<?php //echo $this->Html->script('plugins/jquery.masonry.min', array('inline' => false));  ?>

<div class="masonry products dashboard">
    <div class="masonryBox dashboardBox span8 pull-left">
        <h3 class="title">Stats</h3>

        <ul class="nav nav-tabs" id="myTab">
            <li><a href="#today" data-toggle="tab">Today</a></li>
            <li><a href="#thisWeek" data-toggle="tab">This Week</a></li>
            <li><a href="#thisMonth" data-toggle="tab">This Month</a></li>
            <li><a href="#thisYear" data-toggle="tab">This Year</a></li>
            <li><a href="#allTime" data-toggle="tab">All Time</a></li>
        </ul>
        <div id="myTabContent" class="tab-content" style="overflow: visible;">
            <div class="tab-pane fade" id="today">
                <div>
                    <?php
                    echo
                    '<div class="alert alert-success">'
                    . '<h1>' . $statsSalesToday['count'] . '</h1><b>Orders Today</b>'
                    . '<h1>$' . $statsSalesToday['value'] . '</h1><b>Total Value</b>'
                    . '</div>';
                    ?>
                    <script type="text/javascript">
                        google.load("visualization", "1", {packages:["corechart"]});
                        google.setOnLoadCallback(drawOrdersTodayChart);
			 
                        function drawOrdersTodayChart() {
                            // Create and populate the data table.
                            var data = google.visualization.arrayToDataTable([
                                ['x', 'Orders'],
                                        <?php
                                        $hour = array_fill(0, 24, 0);
                                        foreach ($statsSalesToday as $order) {
                                            if ($order['Transaction']) {
                                                $hourKey = date('H', strtotime($order['Transaction']['created']));
                                                $hour[$hourKey]++;
                                            }
                                        }
                                        $i = 0;
                                        while ($i < 24) {
                                            ?>
                                ['<?php echo $i ?>', <?php echo $hour[$i] ? $hour[$i] : 0 ?>],
                                            <?php
                                            ++$i;
                                        }
                                        ?>
                                    ]);
					
                                    // Create and draw the visualization.
                                    new google.visualization.LineChart(document.getElementById('orders_today')).
                                        draw(data, {
                                        curveType: "none",
                                        width: '95%', 
                                        height: 300,
                                        legend: {position: 'none'},
                                        chartArea: {width: '90%', height: '80%'}
                                    }
                                );
                                    $(".masonry").masonry("reload"); // reload the layout
                                }
                    </script>
                    <div id="orders_today"></div>
                </div>
            </div>
            <div class="tab-pane fade" id="thisWeek">
                <div>
                    <?php
                    echo
                    '<div class="alert alert-success">'
                    . '<h1>' . $statsSalesThisWeek['count'] . '</h1><b>Orders This Week</b>'
                    . '<h1>$' . $statsSalesThisWeek['value'] . '</h1><b>Total Value</b>'
                    . '</div>';
                    ?>
                </div>
            </div>
            <div class="tab-pane fade" id="thisMonth">
                <div>
                    <?php
                    echo
                    '<div class="alert alert-success">'
                    . '<h1>' . $statsSalesThisMonth['count'] . '</h1><b>Orders This Month</b>'
                    . '<h1>$' . $statsSalesThisMonth['value'] . '</h1><b>Total Value</b>'
                    . '</div>';
                    ?>
                </div>
            </div>
            <div class="tab-pane fade" id="thisYear">
                <div>
                    <?php
                    echo
                    '<div class="alert alert-success">'
                    . '<h1>' . $statsSalesThisYear['count'] . '</h1><b>Orders This Year</b>'
                    . '<h1>$' . $statsSalesThisYear['value'] . '</h1><b>Total Value</b>'
                    . '</div>';
                    ?>
                </div>
            </div>
            <div class="tab-pane fade" id="allTime">
                <div>
                    <?php
                    echo
                    '<div class="alert alert-success">'
                    . '<h1>' . $statsSalesAllTime['count'] . '</h1><b>Orders All Time</b>'
                    . '<h1>$' . $statsSalesAllTime['value'] . '</h1><b>Total Value</b>'
                    . '</div>';
                    ?>
                </div>
            </div>
        </div>
        <script type="text/javascript">
            $().ready(function(){
                $('#myTab a:first').tab('show');
                //$(".masonry").masonry("reload"); // reload the layout  
            });
        </script>
    </div>
    
    
    <div class="masonryBox dashboardBox tagProducts span3 pull-left">
        <h3>Transactions</h3>
        <ul>
            <?php foreach ($transactionStatuses as $key => $status) { ?>
                <li><?php echo $this->Html->link('List ' . $status, array('plugin' => 'transactions', 'controller' => 'transactions', 'action' => 'index', 'filter' => 'status:' . $key)); ?></li>
            <?php } ?>
        </ul>
        <h5>Transaction Items</h5>
        <p>List of items that are or have been part of a transaction.</p>
        <ul>
            <?php /* foreach ($itemStatuses as $key => $status) { ?>
              <li><?php echo $this->Html->link($status . ' Items', array('plugin' => 'orders', 'controller' => 'order_items', 'action' => 'index', 'filter' => 'status:'.$key)); ?></li>
              <?php } */ ?>
            <li><?php echo $this->Html->link('List In Cart Items', array('plugin' => 'transactions', 'controller' => 'transaction_items', 'action' => 'index', 'filter' => 'status:incart')); ?></li>
        </ul>
    </div>

</div>

<div class="products clear dashboardBox first pull-left">
    <h3>Setup</h3>
    <div class="span3">
        <h5>Store</h5>
        <ul>
            <li><?php echo $this->Html->link('Create a Product', array('plugin' => 'products', 'controller' => 'products', 'action' => 'add')); ?></li>
            <li><?php echo $this->Html->link('Create a Virtual Product', array('plugin' => 'products', 'controller' => 'products', 'action' => 'add_virtual')); ?></li>
            <li><?php echo $this->Html->link('All Products', array('plugin' => 'products', 'controller' => 'products', 'action' => 'index')); ?></li>
            <li><?php echo $this->Html->link('Out Of Stock Products', array('plugin' => 'products', 'controller' => 'products', 'action' => 'index', 'filter' => 'stockItem:0')); ?></li>
        </ul>
    </div>
    <div class="span3">
        <h5>Brands</h5>
        <ul>
            <li><?php echo $this->Html->link('Add a Brand', array('plugin' => 'products', 'controller' => 'product_brands', 'action' => 'add')); ?></li>
            <li><?php echo $this->Html->link('List All Brands', array('plugin' => 'products', 'controller' => 'product_brands', 'action' => 'index')); ?></li>
        </ul>
    </div>
    <div class="span3">
        <h5>Attributes</h5>
        <ul>
            <li><?php echo $this->Html->link('Product Attributes', array('plugin' => 'categories', 'controller' => 'category_options', 'action' => 'index')); ?></li>
        </ul>
        <h5>Categories</h5>
        <ul>
            <li><?php echo $this->Html->link('Add a Category', array('plugin' => 'categories', 'controller' => 'categories', 'action' => 'add', 'model' => 'ProductStore')); ?></li>
            <li><?php echo $this->Html->link('List All Categories', array('plugin' => 'categories', 'controller' => 'categories', 'action' => 'tree')); ?></li>
        </ul>
    </div>
    <div class="span2">
        <h5>Settings</h5>
        <ul>
            <li><?php echo $this->Html->link('List All', array('plugin' => null, 'controller' => 'settings', 'action' => 'index', 'start' => 'type:Orders')); ?></li>
            <li><?php echo $this->Html->link('Transaction Status Types', array('plugin' => null, 'controller' => 'enumerations', 'action' => 'index', 'filter' => 'type:ORDER_ITEM_STATUS')); ?></li>
            <li><?php echo $this->Html->link('Item Status Types', array('plugin' => null, 'controller' => 'enumerations', 'action' => 'index', 'start' => 'type:ORDER_TRANSACTION_STATUS')); ?></li>
        </ul>
    </div>
</div>

<?php
// set contextual search options
$this->set('forms_search', array(
    'url' => '/products/products/index/', 
	'inputs' => array(
		array(
			'name' => 'contains:name', 
			'options' => array(
				'label' => '', 
				'placeholder' => 'Product Search',
				'value' => !empty($this->request->params['named']['contains']) ? substr($this->request->params['named']['contains'], strpos($this->request->params['named']['contains'], ':') + 1) : null,
				)
			),
		)
	));
// set the contextual menu items
$this->set('context_menu', array('menus' => array(
        array(
            'heading' => 'Products',
            'items' => array(
                $this->Html->link(__('Add', true), array('controller' => 'products', 'action' => 'add')),
            )
        ),
        ))); ?>