<div class="media">
    <a class="pull-left" href="#">
    	<img class="media-object" data-src="holder.js/220x100">
  	</a>
    <div class="media-body">
        <h3 class="media-heading"><?php echo $pages->link(); ?></h3>
        <?php echo $pages->content(); ?>
        <?php if ($pages->has_content('extended')) echo $pages->link('Continue Reading…'); ?>
        <p class="info">Posted by <?php echo $pages->author(); ?> on <?php echo $pages->date(); ?></p>
    </div>
</div>
<hr />