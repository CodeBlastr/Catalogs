<?php
// NOT USED IN ANY SITE, FEEL FREE TO REWRITE
echo $this->Element('forms/search', array(
	'formsSearch' => array(
		'url' => '/products/products/index/'
		), 
	'inputs' => array(
		array(
			'name' => 'contains:search_tags', 
			'options' => array(
				'class' => 'products search', 
				'placeholder' => 'Search by address or zip code', // joujou - create joujou custom version if changed
				'after' => '<button id="search-button" type="submit"></button>'
				)
			)
		)
	)); ?>