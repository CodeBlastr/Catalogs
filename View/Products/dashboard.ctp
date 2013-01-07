<?php echo $this->Html->script('http://code.highcharts.com/highcharts.js', array('inline' => false)); ?>
<?php echo $this->Html->script('http://code.highcharts.com/modules/exporting.js', array('inline' => false)); ?>
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
        <div id="myTabContent" class="tab-content">
            <div class="tab-pane fade" id="today">
                <div class="row-fluid">
                    <div class="alert alert-success clearfix">
                        <h3 class="span6 pull-left"> <?php echo $statsSalesToday['count']; ?> Orders Today </h3>
                        <h3 class="span6 pull-left"> $<?php echo $statsSalesToday['value']; ?> Total Value </h3>
                    </div>

                    <?php
                    // vars for chart
                    $hour = array_fill(0, 24, 0);
                    foreach ($statsSalesToday as $order) {
                        if ($order['Transaction']) {
                            $hourKey = (int) date('H', strtotime($order['Transaction']['created']));
                            $hour[$hourKey]++;
                        }
                    } ?>
                    <script type="text/javascript">
                    $(function () {
                        $('#myTab a:first').tab('show');
                    });
                    var chart;
                    $(document).ready(function() {
                        chart = new Highcharts.Chart({
                            chart: {
                                renderTo: 'ordersToday',
                                type: 'spline'
                            },
                            credits: false,
                            title: {
                                text: false
                            },
                            subtitle: {
                                text: false
                            },
                            xAxis: {
                                type: 'datetime',
                                dateTimeLabelFormats: { // don't display the dummy year
                                    month: '%e. %b',
                                    year: '%b'
                                }
                            },
                            yAxis: {
                                title: {
                                    text: false
                                },
                                min: 0
                            },
                            tooltip: {
                                formatter: function() {
                                        return '<b>'+ this.series.name +'</b><br/>'+
                                        Highcharts.dateFormat('%e. %b', this.x) +': '+ this.y +' m';
                                }
                            },
        
                            series: [{
                                name: 'Leads',
                                // Define the data points. All series have a dummy year
                                // of 1970/71 in order to be compared on the same x axis. Note
                                // that in JavaScript, months start at 0 for January, 1 for February etc.
                                data: [
                                <?php
                                $i = 0;
                                while ($i < 24) { ?>
                                    [<?php echo $i ?>,   <?php echo $hour[$i] ? $hour[$i] : 0; ?>],
                                <?php ++$i; } ?>
                                ]
                            }]
                        });
                    });
                    </script>
                    <div id="ordersToday" style="min-width: 300px; height: 300px;"></div>
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
    </div>
    
    
    <div class="masonryBox dashboardBox tagProducts span3 pull-left">
        <h3>Transactions</h3>
        <ul>
            <?php foreach ($transactionStatuses as $key => $status) { ?>
                <li><?php echo $this->Html->link('List ' . $status, array('plugin' => 'transactions', 'controller' => 'transactions', 'action' => 'index', 'filter' => 'status:' . $key, 'sort' => 'Transaction.created', 'direction' => 'desc')); ?></li>
            <?php } ?>
        </ul>
        <h5>Transaction Items</h5>
        <p>List of items that are or have been part of a transaction.</p>
        <ul>
            <?php /* foreach ($itemStatuses as $key => $status) { ?>
              <li><?php echo $this->Html->link($status . ' Items', array('plugin' => 'orders', 'controller' => 'order_items', 'action' => 'index', 'filter' => 'status:'.$key)); ?></li>
              <?php } */ ?>
            <li><?php echo $this->Html->link('List In Cart Items', array('plugin' => 'transactions', 'controller' => 'transactions', 'action' => 'index', 'filter' => 'status:open')); ?></li>
        </ul>
    </div>

</div>

<div class="products clear dashboardBox first pull-left row-fluid">
    <h3>Setup</h3>
    <div class="span3">
        <h5>Store</h5>
        <ul>
            <li><?php echo $this->Html->link('All Products', array('plugin' => 'products', 'controller' => 'products', 'action' => 'index')); ?></li>
            <li><?php echo $this->Html->link('Create a Product', array('plugin' => 'products', 'controller' => 'products', 'action' => 'add')); ?></li>
            <?php //<li> echo $this->Html->link('Create a Virtual Product', array('plugin' => 'products', 'controller' => 'products', 'action' => 'add_virtual')); </li>?>
            <li><?php echo $this->Html->link('Out Of Stock Products', array('plugin' => 'products', 'controller' => 'products', 'action' => 'index', 'filter' => 'stock:0')); ?></li>
        </ul>
    </div>
    <div class="span3">
        <h5>Brands</h5>
        <ul>
            <li><?php echo $this->Html->link('List All Brands', array('plugin' => 'products', 'controller' => 'product_brands', 'action' => 'index')); ?></li>
            <li><?php echo $this->Html->link('Add a Brand', array('plugin' => 'products', 'controller' => 'product_brands', 'action' => 'add')); ?></li>
        </ul>
    </div>
    <div class="span3">
        <h5>Attributes</h5>
        <ul>
            <li><?php echo $this->Html->link('Product Variations', array('plugin' => 'products', 'controller' => 'products', 'action' => 'categories')); ?></li>
        </ul>
        <h5>Categories</h5>
        <ul>
            <li><?php echo $this->Html->link('List All Categories', array('plugin' => 'products', 'controller' => 'products', 'action' => 'categories')); ?></li>
        </ul>
    </div>
    <div class="span2">
        <h5>Settings</h5>
        <ul>
            <li><?php echo $this->Html->link('List All', array('plugin' => null, 'controller' => 'settings', 'action' => 'index', 'start' => 'type:Orders')); ?></li>
            <li><?php echo $this->Html->link('Tax Rates', array('plugin' => 'transactions', 'controller' => 'transaction_taxes', 'action' => 'index')); ?></li>
            <li><?php echo $this->Html->link('Status Types', array('plugin' => null, 'controller' => 'enumerations', 'action' => 'index', 'filter' => 'type:TRANSACTIONS_ITEM_STATUS')); ?></li>
            <li><?php echo $this->Html->link('Item Status Types', array('plugin' => null, 'controller' => 'enumerations', 'action' => 'index', 'start' => 'type:TRANSACTIONS_STATUS')); ?></li>
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
			$this->Html->link(__('Dashboard'), array('controller' => 'products', 'action' => 'dashboard'), array('class' => 'active')),
			)
		),
        array(
            'heading' => 'Products',
            'items' => array(
                $this->Html->link(__('List Products'), array('controller' => 'products', 'action' => 'index')),
                $this->Html->link(__('List Transactions'), array('plugin' => 'transactions', 'controller' => 'transactions', 'action' => 'index')),
            )
        ),
        ))); ?>