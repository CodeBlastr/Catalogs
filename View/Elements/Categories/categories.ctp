<?php
$CategoryHelper = $this->Helpers->load('Categories.Category', $___dataForView);

$categories = $CategoryHelper->loadData();
//debug($categories); exit;
print'<div class="category-list">';
 if ( !empty($categories) ) {
 echo '<ul class="unstyled">';
	 foreach ($categories as $category) {
		 echo '<li>' . $this->Html->link($category['Category']['name'], array('plugin' => 'products','controller'=>'products','action'=>'category',$category['Category']['id'])) . '</li>';
	 }
	 echo '</ul>';
 }
print '</div>';

// $categories = $this->requestAction('/products/products/categories/'.$parentId);
 // if ( !empty($categories) ) {
 // echo '<ul class="unstyled">';
	 // foreach ($categories as $category) {
		 // echo '<li>' . $this->Html->link($category['Category']['name'], array('plugin' => 'products','controller'=>'products','action'=>'category',$category['Category']['id'])) . '</li>';
	 // }
	 // echo '</ul>';
 // }
 ?>