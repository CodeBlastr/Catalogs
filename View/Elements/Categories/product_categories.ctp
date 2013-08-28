<?php
$categories = $this->requestAction('/products/products/categoryList/'.$parentId);
if ( !empty($categories) ) {
echo '<ul class="unstyled">';
	foreach ( $categories as $category ) {
		echo '<li>' . $this->Html->link($category['Category']['name'], array('plugin'=>'products','controller'=>'products','action'=>'category',$category['Category']['id'])) . '</li>';
	}
	echo '</ul>';
}
