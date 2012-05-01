<p>image source from: <?php echo $this->Html->link('http://www.flickr.com/photos/77805782@N05/', 'http://www.flickr.com/photos/77805782@N05/', array('target' => '_blank'));?>
</p>

<div id="gallery">
	<?php foreach($photos as $photo):?>
		<?php echo $this->Html->link(
                $this->Html->image($photo['url'] , array('alt' => $photo['title'])),
                $photo['big_url'],
                array('escape' => false, 'class' => 'full-size', 'rel' => 'photo_group')
            );
        ?>
	<?php endforeach;?>
</div>
<p class="paging"><?php echo implode(' ', $pagination);?><span class="total">photos <?php echo $total;?> found. Page <?php echo $page;?> of <?php echo $pages;?></span></p>