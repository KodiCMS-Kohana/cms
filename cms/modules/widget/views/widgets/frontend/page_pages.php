<?php if (!empty($header)): ?>
<h3><?php echo $header; ?></h3>
<?php endif; ?>
	
<?php foreach ($pages as $p): ?>
<div class="media">
    <a class="pull-left" href="<?php echo $p->url(); ?>">
        <img class="media-object" data-src="holder.js/64x64">
    </a>
    <div class="media-body">
        <h4 class="media-heading"><?php echo $p->link(); ?></h4>
        <p class="info">Posted by <?php echo $p->author(); ?> on <?php echo $p->date(); ?> 
        </p>
    </div>
</div>
<hr />
<?php endforeach; ?>