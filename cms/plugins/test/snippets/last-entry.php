<?php foreach($pages as $page): ?>
<div class="media">
    <a class="pull-left" href="#">
    	<img class="media-object" data-src="holder.js/220x100">
  	</a>
    <div class="media-body">
        <h3 class="media-heading"><?php echo $page->link(); ?></h3>
        <?php echo Part::content($page); ?>
        <?php if (Part::exists($page, 'extended')) echo $page->link('Continue Reading…'); ?>
        <p class="info">Posted by <?php echo $page->author(); ?> on <?php echo $page->date(); ?></p>
    </div>
</div>
<hr />
<?php endforeach; ?>