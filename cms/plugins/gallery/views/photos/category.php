<div class="span1 ui-droppable <?php if($category->id > 0): ?>ui-sort<?php endif; ?>" data-id="<?php echo $category->id > 0 ? $category->id : $category->parent_id; ?>" ?>
	<?php if($category->loaded()): ?>
	<?php echo UI::icon('trash option'); ?>
	<?php endif; ?>
	<div class="thumbnail">
		<?php echo HTML::anchor('photos/category/' . ($category->id > 0 ? $category->id : $category->parent_id), $category->title); ?>
	</div>
</div>