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
	'url' => '/products/products/index/',
	'inputs' => array( array(
			'name' => 'contains:name',
			'options' => array(
				'label' => '',
				'placeholder' => 'Product Search',
				'value' => !empty($this->request->params['named']['contains']) ? substr($this->request->params['named']['contains'], strpos($this->request->params['named']['contains'], ':') + 1) : null,
			)
		), )
));
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
echo $this->element('products');
