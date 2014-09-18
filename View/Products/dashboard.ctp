<?php if (!empty($products)) : ?>
	<?php for ($i=0; $i < count($products); $i++) : ?>
	<div class="products list-group" id="product<?php echo $products[$i]['Product']['id']; ?>">
		<div class="list-group-item clearfix">
	    	<div class="col-md-1"> 
	    		<?php echo $this->Html->link($this->Media->display($products[$i]['Media'][0], array('alt' => $products[$i]['Product']['name'])), array('controller' => 'products', 'action' => 'view', $products[$i]["Product"]["id"]), array('escape' => false)); ?>
	        </div>
	        <div class="col-md-11"> 
	        	<h4>
	        		<?php echo $products[$i]['Product']['name']; ?></h4>
		            <?php echo $this->Html->link('View', array('admin' => false, 'controller' => 'products', 'action' => 'view', $products[$i]["Product"]["id"]), array('class' => 'btn btn-default btn-xs')); ?>
					<?php echo $this->Html->link('Edit', array('action' => 'edit', $products[$i]["Product"]["id"]), array('class' => 'btn btn-default btn-xs')); ?>
					<?php echo $this->Html->link('<i class="glyphicon glyphicon-circle-arrow-up"></i>', array('action' => 'moveup', $products[$i]["Product"]["id"], $this->Paginator->counter('{:count}')), array('title' => 'Move to Top', 'class' => 'btn btn-default btn-xs', 'escape' => false)); ?>
					<?php echo $this->Html->link('<i class="glyphicon glyphicon-chevron-up"></i>', array('action' => 'moveup', $products[$i]["Product"]["id"], 1), array('title' => 'Move Up One Place', 'class' => 'btn btn-default btn-xs', 'escape' => false)); ?>
					<?php echo $this->Html->link('<i class="glyphicon glyphicon-chevron-down"></i>', array('action' => 'movedown', $products[$i]["Product"]["id"], 1), array('title' => 'Move Down One Place', 'class' => 'btn btn-default btn-xs', 'escape' => false)); ?>
					<?php echo $this->Html->link('<i class="glyphicon glyphicon-circle-arrow-down"></i>', array('action' => 'movedown', $products[$i]["Product"]["id"], $this->Paginator->counter('{:count}')), array('title' => 'Move to Bottom', 'class' => 'btn btn-default btn-xs', 'escape' => false)); ?>
				</h4>
				<p><?php echo $this->Text->truncate(strip_tags($products[$i]['Product']['summary'])); ?></p>
	            <?php echo !empty($products[$i]['Product']['price']) ? __('<span class="badge"> %s </span>', ZuhaInflector::pricify($products[$i]['Product']['price']), array('currency' => 'USD')) : null; ?>
			</div>
		</div>	        
    </div>
	<?php endfor; ?>
	<div class="row">
		<div class="col-md-12">
			<?php echo $this->element('paging'); ?>
		</div>
	</div>	   
<?php else : ?>
	<p>No products found. <?php echo $this->Html->link('Add one now?', array('plugin' => 'products', 'controller' => 'products', 'action' => 'add')); ?></p>
<?php endif; ?>
			
<?php
// set the contextual sorting items
$this->set('forms_sort', array(
	'type' => 'select',
	'sorter' => array( array(
			'heading' => '',
			'items' => array(
				$this->Paginator->sort('price'),
				$this->Paginator->sort('name'),
			)
		)),
));
// set contextual search options
$this->set('forms_search', array(
	'url' => '/admin/products/products/dashboard/',
	'inputs' => array( array(
			'name' => 'contains:name',
			'options' => array(
				'label' => '',
				'placeholder' => 'Product Search',
				'value' => !empty($this->request->params['named']['contains']) ? substr($this->request->params['named']['contains'], strpos($this->request->params['named']['contains'], ':') + 1) : null,
			)
		), )
));
// set the contextual breadcrumb items
$this->set('context_crumbs', array('crumbs' => array(
	$this->Html->link(__('Admin Dashboard'), '/admin'),
	$this->Html->link(__('Ecommerce Dashboard'), array('plugin' => 'transactions', 'controller' => 'transactions', 'action' => 'dashboard')),
	'Products Dashboard',
)));
// set the contextual menu items
$this->set('context_menu', array('menus' => array( array(
			'heading' => 'Products',
			'items' => array(
				$this->Html->link(__('Dashboard'), array(
					'admin' => true,
					'controller' => 'products',
					'action' => 'dashboard'
				), array('class' => 'active')),
				$this->Html->link(__('List'), array(
					'controller' => 'products',
					'action' => 'index'
				)),
				$this->Html->link(__('Add'), array(
					'controller' => 'products',
					'action' => 'add'
				)),
			)
		))));
