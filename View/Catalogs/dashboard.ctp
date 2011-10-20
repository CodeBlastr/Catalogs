<div class="catalogs dashboard">
	Put all the cool stuff here
</div>

<div class="actions">
	<ul>
		<li><?php echo $this->Html->link(__('New Catalog', true), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__d('Categories', 'Assign Category Catalog', true), 
				array('plugin'=>'categories', 'controller'=>'categories', 'action'=>'categorized','type'=>'Catalog' , 'admin'=>true)); ?> </li>
	</ul>
</div>