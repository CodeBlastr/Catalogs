<h1>Auctions</h1>
<div class="products" id="elementAuctionProducts">

	<div class="">
	<?php
    if (!empty($products)) {
        $i = 0;
        foreach ($products as $product) :
        	$highestBid = (isset($product['ProductBid'][0]['amount'])) ? '$'.ZuhaInflector::pricify($product['ProductBid'][0]['amount']) : 'no bids';
        ?>
        
			<div class="ad-row media row-fluid">
			
				<div class="span2">
					<?php
					echo $this->Html->link(
						$this->Media->display($product['Media'][0], array('width' => 181, 'height' => 121, 'alt' => $product['Product']['name'])),
						array('plugin' => 'products', 'controller' => 'products', 'action' => 'viewAuction', $product['Product']['id']), array('class' => 'pull-left', 'escape' => false)
					);
					?>
				</div>
			
				<div class="span7">
					<div class="media-body">
						<h4 class="media-heading"><?php echo $product['Product']['name'] ?></h4>
						<div class="metas">
							<a href="<?php echo $this->Html->url(array('plugin'=>'users', 'controller'=>'users', 'action'=>'view', $product['Creator']['id'])) ?>"><?php echo $product['Creator']['full_name'] ?></a>
							<div> 
								<i class="icon-time"></i> <b><time datetime="<?php echo date('c', strtotime($product['Product']['ended']))?>"><?php echo $this->Time->timeAgoInWords($product['Product']['ended'])?></time> left</b>
							</div>
						</div>					
						
				
						<?php echo $this->Text->truncate($product['Product']['description']) ?>
					</div>
				</div>
			
				<div class="span3">
					<div class="price-tag">
						<a href="<?php echo $this->Html->url(array('action'=>'viewAuction', $product['Product']['id'])) ?>">
							<?php echo $highestBid ?>
						</a>
					</div>
				</div>
				
			</div>
        
        <?php
        endforeach;
    } else {
        echo __('<p>No products found.</p>');
    }
    ?>
	</div>
    <?php echo $this->Element('paging');?>
</div>
