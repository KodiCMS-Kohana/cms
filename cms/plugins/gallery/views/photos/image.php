<div class="span1" data-id="<?php echo $photo->id; ?>">
	<?php if($category->id > 0 AND $category->image != $photo->filename): ?>
	<?php echo UI::icon('picture option'); ?>
	<?php endif; ?>
	<?php echo UI::icon('trash option'); ?>
	<div class="thumbnail <?php if($category->image == $photo->filename): ?>category-image<?php endif; ?>">
		<?php echo HTML::anchor($photo->src('full'), HTML::image($photo->src()), array('class' => 'fancybox-image', 'rel' => 'gallery')); ?>
	</div>
</div>