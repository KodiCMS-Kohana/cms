<div class="panel-heading">
	<span class="panel-title" data-icon="tags"><?php echo __('Tags (separator: ":sep")', array(':sep' => Model_Tag::SEPARATOR)); ?></span>
</div>
<div class="panel-body">
	<?php echo Form::textarea('page_tags', implode(Model_Tag::SEPARATOR, $tags), array(
		'class' => 'tags', 'id' => 'page_tags'
	)); ?>
</div>