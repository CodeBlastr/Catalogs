<?php
if (!empty($options)) :
    foreach ($options as $key => $value) :
        $variants[$key] = implode(', ', $options[$key]); 
	endforeach;
    echo $this->Form->input('Product.id', array('type' => 'hidden', 'value' => $product['Product']['id']));
    echo $this->Form->input('Product.id', array('id' => __('ProductSelectId%s', $product['Product']['id']), 'class' => 'ProductSelectId', 'label' => 'Please select an option', 'type' => 'select', 'options' => $variants, 'disabled' => 'disabled', 'default' => $product['Product']['id']));
endif;
