<?php
// set the contextual sorting items
$this->set('contextSort', array(
    'type' => 'select',
    'sorter' => array(array(
            'heading' => '',
            'items' => array(
                $this->Paginator->sort('price'),
                $this->Paginator->sort('name'),
            )
    )),
));

echo $this->element('products');

// set the contextual menu items
$this->set('context_menu', array('menus' => array(
        array(
            'heading' => 'Products',
            'items' => array(
                $this->Html->link(__('Add', true), array('controller' => 'products', 'action' => 'add')),
            )
        ),
        )));
