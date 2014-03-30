<div class="products" id="elementProducts">  
    <div class="indexContainer">
    <?php
    if (!empty($products)) {
        $i = 0;
        foreach ($products as $product) { ?>
            <div class="indexRow">
                <div class="indexCell galleryThumb imageCell" id="galleryThumb<?php echo $product['Product']['id']; ?>"> 
                    <?php echo $this->Media->display($product['Media'][0], array('alt' => $product['Product']['name'])); ?>
                </div>
                <div class="indexCell itemDescription productDescription metaCell" id="productDescription<?php echo $product["Product"]["id"]; ?>"> 
                    <ul class="metaData">
                        <li><?php echo strip_tags($product['Product']['summary']); ?></li>
                        <?php if (!empty($product['ProductBrand'])) { ?>
                        <li class="itemBrand productBrand" id="productBrand<?php echo $product["Product"]["id"]; ?>"> <?php echo $this->Html->link($product['ProductBrand']['name'] , array('controller' => 'product_brands' , 'action'=>'view' , $product["ProductBrand"]["id"])); ?> </li>
                    <?php } ?>
                    </ul>
                </div>
                <div class="indexCell indexData">
                    <div class="indexCell itemName productName titleCell" id="productName<?php echo $product["Product"]["id"]; ?>">
                        <div class="recorddat">
                            <h3><?php echo $this->Html->link($product['Product']['name'] , array('controller' => 'products' , 'action'=>'view' , $product["Product"]["id"])); ?></h3>
                        </div>
                    </div>
                    
                    <div class="indexCell itemPrice productPrice descriptionCell" id="productPrice<?php echo $product['Product']['id']; ?>"> 
                        <div class="recorddat">
                            <div class="truncate"><?php echo __('$'); ?><?php echo (!empty($product['ProductPrice'][0]['price']) ? $product['ProductPrice'][0]['price'] : $product['Product']['price']); ?></div>
                        </div>
                        
                        <?php echo $this->element('Products.cart_add', array('product' => $product)); ?>
                    </div>
                    
                    
                </div>
            </div>
        <?php
        }
    } else {
        echo __('<p>No products found. %s</p>', $this->Html->link('Add the first', array('plugin' => 'products', 'controller' => 'products', 'action' => 'add')));
    } ?>
    </div>
    <?php echo $this->Element('paging');?>
</div>